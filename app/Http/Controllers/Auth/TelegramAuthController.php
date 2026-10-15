<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class TelegramAuthController extends Controller
{
    /**
     * Redirect to Telegram authentication widget/provider.
     */
    public function redirect()
    {
        return Socialite::driver('telegram')->redirect();
    }

    /**
     * Handle callback from Telegram.
     */
    public function callback(Request $request)
    {
        try {
            $telegram = Socialite::driver('telegram')->user();

            $telegramId = (string) $telegram->getId();
            $user = User::where('telegram_id', $telegramId)->first();

            if (!$user) {
                // Check if an existing approved user has matching username or email
                $name = $telegram->getName() ?: ($telegram->getNickname() ?: 'Telegram User');
                $email = 'telegram_' . $telegramId . '@telegram.user';

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'employee',
                    'status' => 'approved',
                    'telegram_id' => $telegramId,
                ]);

                // Create associated employee profile
                Employee::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'email' => $user->email,
                    'hire_date' => now()->toDateString(),
                    'profile_image' => $telegram->getAvatar() ?: Employee::DEFAULT_AVATAR,
                    'is_active' => true,
                    'additional_leave_days' => 0,
                ]);
            }

            // Check status
            if ($user->isPending()) {
                return redirect()->route('auth.pending')->with('warning', __('Your account is awaiting administrator approval.'));
            }

            if ($user->isRejected() || !$user->isApproved()) {
                return redirect()->route('login')->withErrors(['email' => __('Your account is not approved to access the dashboard.')]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            // Push notification to Telegram bot
            TelegramNotificationService::sendLoginNotification($user, $request);

            if ($user->isAdmin()) {
                return redirect()->intended(route('dashboard'))->with('success', __('Welcome back, :name!', ['name' => $user->name]));
            }

            return redirect()->intended(route('dashboard.leaves'))->with('success', __('Welcome back, :name!', ['name' => $user->name]));

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Telegram login failed: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['email' => __('Telegram authentication failed. Please try again.')]);
        }
    }
}

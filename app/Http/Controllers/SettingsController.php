<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\ShiftPattern;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Display the Settings page.
     */
    public function index()
    {
        $today = Carbon::today();
        $user = Auth::user();

        $stats = [
            'total_employees' => Employee::withoutAdmins()->count(),
            'active_employees' => Employee::withoutAdmins()->where('is_active', true)->count(),
            'today_clocked_in' => AttendanceLog::whereDate('event_timestamp', $today)
                ->where('event_type', 'Clock-In')
                ->distinct('employee_id')
                ->count('employee_id'),
            'pending_leaves' => LeaveRequest::where('status', 'Pending')->count(),
            'total_departments' => Department::count(),
            'total_shifts' => ShiftPattern::count(),
        ];

        $employee = $user ? $user->getAssociatedEmployee() : null;

        $telegramConfig = [
            'bot' => config('services.telegram.bot', 'AttendanceLogSystemBot'),
            'token' => \App\Services\TelegramNotificationService::getToken(),
            'chat_id' => \App\Services\TelegramNotificationService::getChatId(),
        ];

        return view('dashboard.partials.settings', [
            'activeTab' => 'settings',
            'stats' => $stats,
            'currentTheme' => session('theme', 'light'),
            'currentLocale' => app()->getLocale(),
            'currentUser' => $user,
            'currentEmployee' => $employee,
            'telegramConfig' => $telegramConfig,
        ]);
    }

    /**
     * Update user theme preference (light, dark, system).
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,system',
        ]);

        session(['theme' => $validated['theme']]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'theme' => $validated['theme'],
                'message' => 'Theme updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Theme preference updated!');
    }

    /**
     * Update authenticated user's profile information.
     * Prevents altering role, approval status, or other users' profiles.
     * Allows uploading profile image (accepting only form files).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'profile_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'profile_image.file' => 'Profile image must be an uploaded form file.',
            'profile_image.image' => 'The uploaded file must be a valid image file.',
            'profile_image.mimes' => 'Only JPEG, PNG, JPG, GIF, or WEBP image files are allowed.',
            'profile_image.max' => 'The profile image size may not exceed 5MB.',
        ]);

        // Explicitly update permitted field 'name'
        $user->name = trim($validated['name']);
        $user->save();

        // Get or create associated employee record
        $emp = $user->getAssociatedEmployee();
        if (!$emp) {
            $emp = Employee::firstOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'is_active' => true,
                    'hire_date' => now(),
                ]
            );
            if (!$emp->user_id) {
                $emp->update(['user_id' => $user->id]);
            }
        }

        // Handle profile image file upload from form
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            if ($file->isValid()) {
                // Delete previous uploaded local file if exists
                if ($emp && $emp->getRawOriginal('profile_image') && !str_starts_with($emp->getRawOriginal('profile_image'), 'http')) {
                    $oldPath = str_replace(['/storage/', 'storage/'], '', $emp->getRawOriginal('profile_image'));
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }

                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile_images', $filename, 'public');
                
                if ($emp) {
                    $emp->profile_image = 'storage/' . $path;
                }
            }
        }

        // Save employee profile
        if ($emp) {
            $emp->full_name = $user->name;
            $emp->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update authenticated user's account password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'current_password' => 'required|string|current_password',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update Telegram Bot Chat ID in .env and verify connection.
     */
    public function updateTelegram(Request $request)
    {
        $validated = $request->validate([
            'telegram_chat_id' => 'required|string|max:50',
        ]);

        $chatId = trim($validated['telegram_chat_id']);
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);
            if (preg_match('/^TELEGRAM_CHAT_ID=.*/m', $env)) {
                $env = preg_replace('/^TELEGRAM_CHAT_ID=.*/m', "TELEGRAM_CHAT_ID={$chatId}", $env);
            } else {
                $env .= "\nTELEGRAM_CHAT_ID={$chatId}\n";
            }
            file_put_contents($envPath, $env);
        }

        // Test sending alert
        $sent = \App\Services\TelegramNotificationService::sendMessage(
            $chatId,
            "🎉 <b>[AttendX] Telegram Integration Connected!</b>\n\n"
            . "✅ <b>Chat ID:</b> <code>{$chatId}</code> is successfully verified.\n"
            . "🤖 <b>Bot:</b> @" . config('services.telegram.bot', 'AttendanceLogSystemBot') . "\n"
            . "🕒 <b>Time:</b> " . now()->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i:s T') . "\n\n"
            . "<i>Whenever an employee or administrator logs in, a security push alert will be delivered here automatically.</i>"
        );

        if ($sent) {
            return redirect()->back()->with('success', __('Telegram Chat ID saved and test alert delivered successfully!'));
        }

        return redirect()->back()->with('warning', __('Telegram Chat ID saved to .env, but could not deliver message. Please verify your Chat ID and make sure you clicked START in @AttendanceLogSystemBot.'));
    }
}


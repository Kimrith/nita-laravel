<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('dashboard');
            } elseif ($user->isApproved()) {
                return redirect()->route('dashboard.leaves');
            } else {
                Auth::logout();
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

        $user = Auth::user();

        // Check account approval status
        if ($user->isPending()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('auth.pending')->with('warning', __('Your account is waiting for administrator approval before you can log in to the dashboard.'));
        }

        if ($user->isRejected()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('Your account registration request was rejected by an administrator. Please contact support.'),
            ]);
        }

        if (!$user->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('Your account is not approved to access the dashboard.'),
            ]);
        }

        $request->session()->regenerate();

        // Push login notification to Telegram (BotFather)
        TelegramNotificationService::sendLoginNotification($user, $request);

        // Redirect according to user role
        if ($user->isAdmin()) {
            return redirect()->intended(route('dashboard'))->with('success', __('Welcome back, :name!', ['name' => $user->name]));
        }

        return redirect()->intended(route('dashboard.leaves'))->with('success', __('Welcome back, :name!', ['name' => $user->name]));
    }

    /**
     * Show the user registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle user self-registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users,email', 'unique:employees,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.unique' => __('This email address is already registered in the system.'),
            'password.confirmed' => __('Password confirmation does not match.'),
            'password.min' => __('Password must be at least 6 characters.'),
        ]);

        // 1. Create user account with default role 'employee' and status 'pending'
        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
            'status' => 'pending',
        ]);

        // 2. Create matching inactive employee profile pending approval
        Employee::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'hire_date' => now()->toDateString(),
            'profile_image' => Employee::DEFAULT_AVATAR,
            'is_active' => false,
            'additional_leave_days' => 0,
        ]);

        // Notify Telegram of new registration awaiting approval
        TelegramNotificationService::sendRegistrationNotification($user, $request);

        return redirect()->route('auth.pending')->with('success', __('Your registration was submitted successfully! Your account is currently pending administrator approval.'));
    }

    /**
     * Display the pending approval notification page.
     */
    public function pendingApproval()
    {
        return view('auth.pending');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}

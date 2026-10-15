@extends('layouts.auth')

@section('title', __('Registration Pending Approval — AttendX'))

@section('content')
<div class="w-full max-w-lg text-center">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none transition-colors">
        
        <!-- Animated Icon Container -->
        <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-lg shadow-amber-500/10 mb-6">
            <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Heading -->
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            {{ __('Account Pending Approval') }}
        </h1>

        <!-- Subtitle -->
        <p class="text-sm text-slate-600 dark:text-slate-300 mt-3 leading-relaxed">
            {{ __('Thank you for registering with AttendX! Your account registration has been submitted and is currently') }}
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-700 mx-1">
                {{ __('Pending Approval') }}
            </span>
            {{ __('by an administrator.') }}
        </p>

        <!-- Informational Steps Box -->
        <div class="mt-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 text-left space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-900/60 text-brand-600 dark:text-brand-300 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                    1
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ __('Admin Verification') }}</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('An administrator will review your submitted credentials.') }}</div>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-900/60 text-brand-600 dark:text-brand-300 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                    2
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ __('Dashboard Activation') }}</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Once approved, you will gain full access to your employee leave dashboard.') }}</div>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-900/60 text-brand-600 dark:text-brand-300 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                    3
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ __('Sign In') }}</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Return to the login screen and sign in with your email and password.') }}</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-sm hover:shadow-brand-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                <span>{{ __('Return to Sign In') }}</span>
            </a>
            <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold py-2.5 px-5 rounded-xl text-sm transition">
                <span>{{ __('Check Status Again') }}</span>
            </a>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Cambodia Labor Law — Leave & Permission Rules')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Header (Minimal UI) -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-5 transition hover:border-slate-300 dark:hover:border-slate-700">
        <div class="space-y-1.5 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 text-xs font-semibold border border-teal-200/60 dark:border-teal-800/40">
                <svg class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>{{ __('Kingdom of Cambodia • Labor Law Compliance') }}</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                {{ __('Employee Leave & Permission Rules') }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ __('Statutory leave entitlements, seniority accruals, special leaves, maternity protections, and labor permission regulations under the Cambodian Labor Law (1997) and MLVT guidelines.') }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
            <a href="{{ route('dashboard.leaves') }}" class="inline-flex items-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-3.5 py-2 rounded-xl text-xs transition shadow-sm hover:shadow-teal-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ __('Manage Employee Leaves') }}</span>
            </a>
            <a href="#calculator-section" class="inline-flex items-center gap-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 font-medium px-3.5 py-2 rounded-xl text-xs transition border border-slate-200 dark:border-slate-700">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>{{ __('Accrual Calculator') }}</span>
            </a>
        </div>
    </div>

    <!-- Statutory Leave Breakdown Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        
        <!-- 1. Annual Leave Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition">
                        🌴
                    </span>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-teal-100/70 text-teal-800">
                        {{ __('18 Days / Year') }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span>{{ __('Paid Annual Leave') }}</span>
                    <span class="text-xs font-normal text-slate-400">({{ __('ច្បាប់ឈប់សម្រាកប្រចាំឆ្នាំ') }})</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ __('Employees are entitled to 18 working days of paid annual leave per year after 1 year of continuous service, accruing at 1.5 days per month of service.') }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 text-xs border border-slate-100">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-teal-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Seniority Bonus') }}:</span>
                            <span class="text-slate-600">{{ __('Increases by 1 extra day for every 3 years of continuous service.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-teal-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Accrual Rule') }}:</span>
                            <span class="text-slate-600">{{ __('1.5 days per completed month for employees in their first working year.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ __('Labor Code: Articles 166-170') }}</span>
                <span class="font-semibold text-teal-600">{{ __('100% Paid') }}</span>
            </div>
        </div>

        <!-- 2. Special Leave Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition">
                        💍
                    </span>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-100/70 text-amber-800">
                        {{ __('Up to 7 Days / Year') }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span>{{ __('Special Family Leave') }}</span>
                    <span class="text-xs font-normal text-slate-400">({{ __('ច្បាប់ពិសេស') }})</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ __('Leave granted for immediate personal or family life milestones including marriage of worker, childbirth, wedding of children, or illness/death of family.') }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 text-xs border border-slate-100">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-amber-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Marriage / Birth') }}:</span>
                            <span class="text-slate-600">{{ __('Official allowance for wedding or childbirth of child.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-amber-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Deduction') }}:</span>
                            <span class="text-slate-600">{{ __('May be deducted from annual leave balance or compensated with makeup work.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ __('Labor Code: Article 171') }}</span>
                <span class="font-semibold text-amber-600">{{ __('Paid / Offset') }}</span>
            </div>
        </div>

        <!-- 3. Maternity Leave Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition">
                        👶
                    </span>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-pink-100/70 text-pink-800">
                        {{ __('90 Calendar Days') }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span>{{ __('Maternity Leave') }}</span>
                    <span class="text-xs font-normal text-slate-400">({{ __('ច្បាប់លំហែមាតុភាព') }})</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ __('Female employees are entitled to 90 consecutive days (approx. 3 months) of maternity leave. Paid at 50% regular wage if employed for at least 1 year.') }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 text-xs border border-slate-100">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-pink-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Compensation') }}:</span>
                            <span class="text-slate-600">{{ __('50% of regular wages & benefits paid by employer for staff >= 1 yr.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-pink-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Nursing Breaks') }}:</span>
                            <span class="text-slate-600">{{ __('1 hour/day nursing break allowed for 1 year after birth.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ __('Labor Code: Articles 182-183') }}</span>
                <span class="font-semibold text-pink-600">{{ __('50% Pay & Benefits') }}</span>
            </div>
        </div>

        <!-- 4. Sick Leave Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition">
                        🩺
                    </span>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-100/70 text-blue-800">
                        {{ __('Up to 6 Months') }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span>{{ __('Certified Sick Leave') }}</span>
                    <span class="text-xs font-normal text-slate-400">({{ __('ច្បាប់ឈឺ') }})</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ __('Cambodian labor law protects prolonged absence due to medical illness up to 6 months before employment termination rules apply, with valid medical doctor certificate.') }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 text-xs border border-slate-100">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-blue-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Medical Certificate') }}:</span>
                            <span class="text-slate-600">{{ __('Must be issued by recognized doctor or hospital.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-blue-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Customary Tiered Pay') }}:</span>
                            <span class="text-slate-600">{{ __('M1: 100%, M2-M3: 60%, M4-M6: Unpaid job protection.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ __('Labor Code: Article 71') }}</span>
                <span class="font-semibold text-blue-600">{{ __('Job Protected') }}</span>
            </div>
        </div>

        <!-- 5. Public Holidays Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition">
                        🇰🇭
                    </span>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-purple-100/70 text-purple-800">
                        {{ __('21 - 22 Days / Year') }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span>{{ __('Statutory Public Holidays') }}</span>
                    <span class="text-xs font-normal text-slate-400">({{ __('បុណ្យជាតិ') }})</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ __('Announced annually via Royal Government Sub-Decree (Prakas). Paid days off for all workers across the Kingdom of Cambodia.') }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 text-xs border border-slate-100">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-purple-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Major Festivities') }}:</span>
                            <span class="text-slate-600">{{ __('Khmer New Year (4 days), Pchum Ben (3 days), Water Festival (3 days).') }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-purple-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Holiday Premium') }}:</span>
                            <span class="text-slate-600">{{ __('Work performed on public holidays receives double wage (200%).') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ __('Annual Royal Decree') }}</span>
                <span class="font-semibold text-purple-600">{{ __('100% Paid Off') }}</span>
            </div>
        </div>

        <!-- 6. Foreign Work Permits & Visas Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition">
                        🌐
                    </span>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100/70 text-emerald-800">
                        {{ __('Work Permit & Visas') }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <span>{{ __('Foreign Work Permits (FWP)') }}</span>
                    <span class="text-xs font-normal text-slate-400">({{ __('លិខិតអនុញ្ញាត') }})</span>
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    {{ __('Foreign employees working in Cambodia require a valid E-type business visa, health check, and MLVT Foreign Work Permit & Employment Card.') }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 text-xs border border-slate-100">
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('10% Foreign Quota') }}:</span>
                            <span class="text-slate-600">{{ __('Employers can hire up to 10% foreign nationals of their local staff count.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <div>
                            <span class="font-semibold">{{ __('Annual Renewal') }}:</span>
                            <span class="text-slate-600">{{ __('FWP valid for 1 calendar year; renewal window Jan 1 - Mar 31.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ __('MLVT Prakas 196') }}</span>
                <span class="font-semibold text-emerald-600">{{ __('Annual Compliance') }}</span>
            </div>
        </div>

    </div>

    <!-- Interactive Cambodia Statutory Accrual Calculator Section -->
    <div id="calculator-section" class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-semibold mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ __('Simulation Tool') }}</span>
                </span>
                <h2 class="text-lg font-bold text-slate-900">{{ __('Cambodia Statutory Leave Entitlement Calculator') }}</h2>
                <p class="text-xs text-slate-500">{{ __('Calculate legal base leave and seniority increments based on service length (18 days base + 1 day per 3 continuous years).') }}</p>
            </div>

            <!-- Employee Preset Picker -->
            @if(isset($employees) && $employees->count() > 0)
                <div class="w-full md:w-72">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('Load Existing Employee') }}:</label>
                    <select id="employeeCalcSelect" onchange="loadEmployeeCalc(this)" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-teal-500 transition py-2 px-3">
                        <option value="">-- {{ __('Select Employee') }} --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}" 
                                    data-hire="{{ $emp->hire_date ? $emp->hire_date->format('Y-m-d') : '' }}"
                                    data-years="{{ $emp->years_of_service }}"
                                    data-months="{{ $emp->months_of_service }}"
                                    data-base="{{ $emp->base_annual_leave_days }}"
                                    data-bonus="{{ $emp->seniority_bonus_days }}"
                                    data-additional="{{ $emp->additional_leave_days ?? 0 }}"
                                    data-total="{{ $emp->total_annual_leave_entitlement }}"
                                    data-used="{{ $emp->used_annual_leave_days }}"
                                    data-rem="{{ $emp->remaining_annual_leave_days }}">
                                {{ $emp->full_name }} ({{ $emp->hire_date ? $emp->hire_date->format('M Y') : 'N/A' }} • {{ $emp->years_of_service }}y {{ $emp->months_of_service % 12 }}m)
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Controls / Slider Form -->
            <div class="lg:col-span-6 space-y-5">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="yearsServiceSlider" class="text-xs font-bold text-slate-700">{{ __('Years of Continuous Service') }}</label>
                        <span id="yearsServiceDisplay" class="text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800">1 {{ __('Year') }}</span>
                    </div>
                    <input type="range" id="yearsServiceSlider" min="0" max="30" value="1" step="1" oninput="updateCalculator()" class="w-full accent-teal-600 cursor-pointer h-2 bg-slate-100 rounded-lg">
                    <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                        <span>{{ __('0 yr (New hire)') }}</span>
                        <span>{{ __('5 yrs') }}</span>
                        <span>{{ __('10 yrs') }}</span>
                        <span>{{ __('15 yrs') }}</span>
                        <span>{{ __('20+ yrs') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Additional Months') }}</label>
                        <select id="extraMonthsSelect" onchange="updateCalculator()" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-teal-500 py-2 px-3">
                            @for($m = 0; $m < 12; $m++)
                                <option value="{{ $m }}">{{ $m }} {{ __('Month(s)') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Admin Bonus Days') }}</label>
                        <input type="number" id="calcAdminBonus" value="0" min="0" max="50" oninput="updateCalculator()" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-teal-500 py-2 px-3">
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-900 text-xs flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <strong class="font-semibold">{{ __('Legal Seniority Formula') }}:</strong>
                        <span>{{ __('Base 18 days + floor(Service Years / 3) extra days. In service under 1 year, 1.5 days accrued per month.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Output Metric Cards -->
            <div class="lg:col-span-6 bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
                    
                    <div class="bg-white rounded-xl p-3.5 shadow-sm border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ __('Base Annual') }}</div>
                        <div id="calcBaseDays" class="text-2xl font-black text-slate-900">18.0</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">{{ __('days / year') }}</div>
                    </div>

                    <div class="bg-white rounded-xl p-3.5 shadow-sm border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ __('Seniority Bonus') }}</div>
                        <div id="calcSeniorityDays" class="text-2xl font-black text-teal-600">+0</div>
                        <div class="text-[10px] text-teal-600/75 mt-0.5">{{ __('+1d every 3 yrs') }}</div>
                    </div>

                    <div class="bg-white rounded-xl p-3.5 shadow-sm border border-slate-100 col-span-2 sm:col-span-1">
                        <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ __('Admin Granted') }}</div>
                        <div id="calcAdminDays" class="text-2xl font-black text-indigo-600">+0</div>
                        <div class="text-[10px] text-indigo-500/75 mt-0.5">{{ __('company bonus') }}</div>
                    </div>

                </div>

                <!-- Total Highlight Banner -->
                <div class="mt-4 p-4 rounded-xl bg-teal-600 text-white flex items-center justify-between shadow-md shadow-teal-600/20">
                    <div>
                        <div class="text-xs font-medium text-teal-100">{{ __('Total Annual Statutory Entitlement') }}:</div>
                        <div class="text-[11px] text-teal-200 mt-0.5">{{ __('Legally compliant under Cambodian Labor Code') }}</div>
                    </div>
                    <div class="text-right">
                        <div id="calcTotalEntitlement" class="text-3xl font-black">18.0</div>
                        <div class="text-[10px] font-semibold text-teal-100">{{ __('working days') }}</div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-white/80 rounded-lg p-2 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-semibold">{{ __('Special Leave') }}</div>
                        <div class="font-bold text-slate-800">7 {{ __('Days') }}</div>
                    </div>
                    <div class="bg-white/80 rounded-lg p-2 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-semibold">{{ __('Maternity') }}</div>
                        <div class="font-bold text-slate-800">90 {{ __('Days (50%)') }}</div>
                    </div>
                    <div class="bg-white/80 rounded-lg p-2 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-semibold">{{ __('Public Holidays') }}</div>
                        <div class="font-bold text-slate-800">22 {{ __('Days') }}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<script>
function openModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove('hidden');
        el.classList.add('flex');
    }
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add('hidden');
        el.classList.remove('flex');
    }
}

function updateCalculator() {
    const years = parseInt(document.getElementById('yearsServiceSlider').value) || 0;
    const months = parseInt(document.getElementById('extraMonthsSelect').value) || 0;
    const adminBonus = parseFloat(document.getElementById('calcAdminBonus').value) || 0;

    document.getElementById('yearsServiceDisplay').innerText = years + (years === 1 ? ' {{ __('Year') }}' : ' {{ __('Years') }}');

    const totalMonths = (years * 12) + months;

    let base = 18.0;
    if (totalMonths < 12) {
        base = Math.min(18.0, Math.round(totalMonths * 1.5 * 10) / 10);
    }

    const seniority = Math.floor(years / 3);
    const total = base + seniority + adminBonus;

    document.getElementById('calcBaseDays').innerText = base.toFixed(1) + 'd';
    document.getElementById('calcSeniorityDays').innerText = '+' + seniority + 'd';
    document.getElementById('calcAdminDays').innerText = '+' + adminBonus + 'd';
    document.getElementById('calcTotalEntitlement').innerText = total.toFixed(1);
}

function loadEmployeeCalc(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt || !opt.value) return;

    const years = parseInt(opt.getAttribute('data-years')) || 0;
    const months = parseInt(opt.getAttribute('data-months')) || 0;
    const additional = parseFloat(opt.getAttribute('data-additional')) || 0;

    document.getElementById('yearsServiceSlider').value = years;
    document.getElementById('extraMonthsSelect').value = months % 12;
    document.getElementById('calcAdminBonus').value = additional;

    updateCalculator();
}

// Initial calculation on load
document.addEventListener('DOMContentLoaded', () => {
    updateCalculator();
});
</script>
@endsection

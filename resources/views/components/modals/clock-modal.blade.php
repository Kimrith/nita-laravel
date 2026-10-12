<div id="clockModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-md overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                    ⏱️
                </div>
                <h3 class="text-base font-bold text-slate-800">Record Attendance</h3>
            </div>
            <button type="button" onclick="closeModal('clockModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none">
                ✕
            </button>
        </div>

        <form action="{{ route('attendance.record') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Select Employee</label>
                <select name="employee_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @if(isset($employees))
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->full_name }} ({{ $emp->email }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Event Type</label>
                <select name="event_type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="Clock-In">Clock-In (Shift Start)</option>
                    <option value="Clock-Out">Clock-Out (Shift End)</option>
                    <option value="Break-Start">Break-Start</option>
                    <option value="Break-End">Break-End</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Source Device</label>
                <select name="source_device" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="Web">Web Dashboard</option>
                    <option value="Mobile">Mobile Application</option>
                    <option value="Kiosk">On-Premises Kiosk</option>
                </select>
            </div>

            <input type="hidden" name="latitude" id="geoLat">
            <input type="hidden" name="longitude" id="geoLng">

            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm hover:shadow-brand-500/25">
                    Submit Punch
                </button>
            </div>
        </form>
    </div>
</div>

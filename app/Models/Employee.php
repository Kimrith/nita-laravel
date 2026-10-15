<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    public const DEFAULT_AVATAR = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaC_2saS2_XH6OGHmktJs0U5Es2-6jVi_KY3h8RDjVncK0Jrcf3X8MsUw&s=10';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'profile_image',
        'department_id',
        'role_id',
        'shift_pattern_id',
        'hire_date',
        'additional_leave_days',
        'permission_notes',
        'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'additional_leave_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include regular workforce employees (excluding system administrators).
     */
    public function scopeWithoutAdmins($query)
    {
        return $query->where('email', '!=', 'admin@example.com')
            ->whereDoesntHave('user', function ($q) {
                $q->where('role', 'admin');
            });
    }

    /**
     * Get employee profile image with fallback to default.
     */
    public function getProfileImageAttribute($value): string
    {
        if (empty($value)) {
            return self::DEFAULT_AVATAR;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset($value);
    }

    /**
     * Years of continuous service based on hire_date.
     */
    public function getYearsOfServiceAttribute(): int
    {
        if (!$this->hire_date) return 0;
        return max(0, (int) $this->hire_date->diffInYears(now()));
    }

    /**
     * Months of continuous service based on hire_date.
     */
    public function getMonthsOfServiceAttribute(): int
    {
        if (!$this->hire_date) return 0;
        return max(0, (int) $this->hire_date->diffInMonths(now()));
    }

    /**
     * Seniority bonus days under Cambodia Labor Law:
     * Increases by 1 day for every 3 years of continuous service.
     */
    public function getSeniorityBonusDaysAttribute(): int
    {
        return (int) floor($this->years_of_service / 3);
    }

    /**
     * Base Annual Leave under Cambodia Labor Law:
     * 18 working days per year (accruing at 1.5 days per month of service after completing one year of work).
     * For employees under 1 year of service, accrues pro-rata at 1.5 days per month.
     */
    public function getBaseAnnualLeaveDaysAttribute(): float
    {
        if ($this->months_of_service < 12) {
            return min(18.0, round($this->months_of_service * 1.5, 1));
        }
        return 18.0;
    }

    /**
     * Total Annual Leave Entitlement (Base + Seniority + Admin Increased Days).
     */
    public function getTotalAnnualLeaveEntitlementAttribute(): float
    {
        return (float) ($this->base_annual_leave_days + $this->seniority_bonus_days + ($this->additional_leave_days ?? 0));
    }

    /**
     * Total approved annual leave days used in current year.
     */
    public function getUsedAnnualLeaveDaysAttribute(): float
    {
        return (float) $this->leaveRequests()
            ->where('status', 'Approved')
            ->whereHas('leaveType', function ($q) {
                $q->where('type_name', 'like', '%Annual%')
                  ->orWhere('type_name', 'like', '%Vacation%')
                  ->orWhere('type_name', 'like', '%Personal%');
            })
            ->whereYear('start_date', now()->year)
            ->get()
            ->sum(function ($req) {
                return max(1, \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1);
            });
    }

    /**
     * Remaining annual leave balance.
     */
    public function getRemainingAnnualLeaveDaysAttribute(): float
    {
        return max(0, round($this->total_annual_leave_entitlement - $this->used_annual_leave_days, 1));
    }

    /**
     * Special Leave: Up to 7 days per year for personal/family events (wedding, childbirth, funeral).
     */
    public function getSpecialLeaveEntitlementAttribute(): int
    {
        return 7;
    }

    /**
     * Maternity Leave: 90 days (approx. 3 months) paid at 50% regular wages.
     */
    public function getMaternityLeaveEntitlementAttribute(): int
    {
        return 90;
    }

    /**
     * Public Holidays: Typically 21 to 22 days per year decreed by Royal Government of Cambodia.
     */
    public function getPublicHolidaysCountAttribute(): int
    {
        return 22;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function shiftPattern(): BelongsTo
    {
        return $this->belongsTo(ShiftPattern::class, 'shift_pattern_id', 'shift_pattern_id');
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id', 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'employee_id');
    }

    public function approvedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'approver_id', 'employee_id');
    }

    public function managedDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'manager_id', 'employee_id');
    }

    public function auditTrails(): HasMany
    {
        return $this->hasMany(AuditTrail::class, 'changed_by', 'employee_id');
    }
}

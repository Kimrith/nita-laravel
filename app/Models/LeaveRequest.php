<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'employee_id',
        'start_date',
        'end_date',
        'leave_type_id',
        'status',
        'approver_id',
        'last_edited_by',
        'last_edited_at',
        'edit_reason',
        'submitted_date',
        'location_data',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_date' => 'datetime',
        'last_edited_at' => 'datetime',
        'location_data' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approver_id', 'employee_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'last_edited_by', 'employee_id');
    }
}

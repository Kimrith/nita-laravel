<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an approved employee.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if user is pending admin approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user registration was rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Relationship to linked Employee record.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    /**
     * Relationship to leave requests created by this user.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'user_id', 'id');
    }

    /**
     * Resolve associated Employee record by user_id or matching email.
     */
    public function getAssociatedEmployee(): ?Employee
    {
        if ($this->employee) {
            return $this->employee;
        }

        $emp = Employee::where('email', $this->email)->first();
        if ($emp && !$emp->user_id) {
            $emp->update(['user_id' => $this->id]);
        }
        return $emp;
    }

    /**
     * Get user profile avatar URL with fallback.
     */
    public function getProfileImageAttribute(): string
    {
        $emp = $this->getAssociatedEmployee();
        return $emp ? $emp->profile_image : Employee::DEFAULT_AVATAR;
    }
}

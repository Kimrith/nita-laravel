<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShiftPattern extends Model
{
    use HasFactory;

    protected $primaryKey = 'shift_pattern_id';

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'is_flexible',
        'grace_period_mins',
    ];

    protected $casts = [
        'is_flexible' => 'boolean',
        'grace_period_mins' => 'integer',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'shift_pattern_id', 'shift_pattern_id');
    }
}

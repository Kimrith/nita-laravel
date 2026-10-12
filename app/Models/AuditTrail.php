<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    use HasFactory;

    protected $table = 'audit_trail';

    protected $primaryKey = 'audit_id';

    protected $fillable = [
        'table_name',
        'record_id',
        'changed_by',
        'change_timestamp',
        'change_description',
    ];

    protected $casts = [
        'record_id' => 'integer',
        'change_timestamp' => 'datetime',
    ];

    public function changedByEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'changed_by', 'employee_id');
    }
}

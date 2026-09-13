<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierSession extends Model
{
    use HasFactory;

    protected $table = 'cashier_sessions';

    protected $fillable = [
        'school_id',
        'user_id',
        'opened_at',
        'opening_cash',
        'closed_at',
        'expected_cash',
        'actual_cash',
        'difference',
        'status',
        'closing_notes',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'user_id' => 'integer',
        'opened_at' => 'datetime',
        'opening_cash' => 'decimal:2',
        'closed_at' => 'datetime',
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
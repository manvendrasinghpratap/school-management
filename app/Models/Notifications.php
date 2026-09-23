<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['user_id', 'title', 'body', 'type', 'read_at', 'data'];
    protected $casts = ['read_at' => 'datetime', 'data' => 'array'];
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function scopeUnread($query) { return $query->whereNull('read_at'); }
}

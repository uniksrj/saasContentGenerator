<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';
    public const STATUS_REMOVED = 'removed';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'status',
        'is_active',
        'settings',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'is_active' => true,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query
            ->where('status', self::STATUS_ACTIVE)
            ->where('is_active', true);
    }

    public function scopeNotRemoved($query)
    {
        return $query->where('status', '!=', self::STATUS_REMOVED);
    }

    public function isAccessible(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->is_active;
    }
}

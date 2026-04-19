<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'source_type',
        'source_url',
        'engagement_score',
        'score',
        'score_breakdown',
        'published_at',
        'status',
        'raw_payload',
    ];

    protected $casts = [
        'engagement_score' => 'integer',
        'score' => 'integer',
        'score_breakdown' => 'array',
        'raw_payload' => 'array',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}

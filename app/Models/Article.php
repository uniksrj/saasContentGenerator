<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'topic_id',
        'legacy_post_id',
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'content',
        'category',
        'source_type',
        'source_url',
        'status',
        'is_published',
        'is_featured',
        'tags',
        'table_of_contents',
        'word_count',
        'reading_time',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
        'table_of_contents' => 'array',
        'word_count' => 'integer',
        'reading_time' => 'integer',
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

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function legacyPost(): BelongsTo
    {
        return $this->belongsTo(newpost_details::class, 'legacy_post_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class newpost_details extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'project_id',
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'is_published',
        'status',
        'description',
        'views_count',
        'comments_count',
        'category',
        'source_url',
        'source_type',
        'file_path',
        'active',
        'tags',
        'table_of_contents',
        'reading_time',
        'word_count',
        'created_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'active' => 'boolean',
        'created_date' => 'datetime',
        'table_of_contents' => 'array',
    ];

    protected $attributes = [
        'name' => 'Suraj',
        'active' => 1,
        'status' => 'published',
    ];

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Get all views for the post.
     */
    public function views()
    {
        return $this->hasMany(PostView::class, 'post_id');
    }

    /**
     * Get only published posts (active = 1).
     */
    public function scopePublished($query)
    {
        $query->where('active', 1)
            ->whereNotNull('created_date');

        if (Schema::hasColumn($this->getTable(), 'status')) {
            $query->where('status', 'published');
        }

        if (Schema::hasColumn($this->getTable(), 'is_published')) {
            $query->where('is_published', 1);
        }

        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1)
            ->published()
            ->orderBy('created_date', 'desc')
            ->limit(6);
    }

    public function scopeLatest($query)
    {
        return $query->published()
            ->orderBy('created_date', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->published()
            ->orderBy('views_count', 'desc');
        //  ->limit(5);
    }

    public function scopeByCategory($query, $category)
    {
        if (empty($category)) {
            return $query;
        }
        return $query->published()
            ->where('category', $category);
    }

    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->description));
        $minutes = ceil($wordCount / 200);
        return $minutes . ' min read';
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getEstimatedReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->description));
        return ceil($wordCount / 200);
    }

    public function getViewsAttribute()
    {
        return $this->views_count;
    }

    public function getThumbnailAttribute()
    {
        return $this->file_path ?: 'images/default-thumbnail.jpg';
    }

    public function getTagsAttribute()
    {
        if (!empty($this->meta_keywords)) {
            return explode(',', $this->meta_keywords);
        }
        return [];
    }

    /**
     * Get only draft posts (active = 0).
     */
    public function scopeDraft($query)
    {
        if (Schema::hasColumn($this->getTable(), 'status')) {
            return $query->where('status', 'draft');
        }

        if (Schema::hasColumn($this->getTable(), 'is_published')) {
            return $query->where('is_published', 0);
        }

        return $query->where('active', 0);
    }

    /**
     * Get recent posts.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_date', 'desc');
    }

    /**
     * Get reading time attribute.
     */
    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $wordsPerMinute = 200;
                $words = str_word_count(strip_tags($this->description));
                $time = ceil($words / $wordsPerMinute);
                return max(1, $time);
            }
        );
    }

    /**
     * Get excerpt attribute (first 150 chars).
     */
    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit(strip_tags($this->description), 150)
        );
    }

    /**
     * Get formatted created date.
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_date
                ? $this->created_date->format('F j, Y')
                : $this->created_at->format('F j, Y')
        );
    }

    /**
     * Get time ago.
     */
    protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_date
                ? $this->created_date->diffForHumans()
                : $this->created_at->diffForHumans()
        );
    }

    public function getPublishedAtAttribute()
    {
        return $this->created_date;
    }

    /**
     * Parse file_path to array of images.
     */
    public function getImagesAttribute()
    {
        if (empty($this->file_path)) {
            return ['uploads/no_image.jpg'];
        }

        try {
            $images = json_decode($this->file_path, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($images)) {
                return $images;
            }
            return [$this->file_path];
        } catch (\Exception $e) {
            return [$this->file_path];
        }
    }

    /**
     * Get featured image (first image).
     */
    public function getFeaturedImageAttribute()
    {
        $images = $this->images;
        return asset('storage/' . $images[0]);
    }

    /**
     * Get all image URLs.
     */
    public function getImageUrlsAttribute()
    {
        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    /**
     * Check if post has multiple images.
     */
    public function getHasMultipleImagesAttribute()
    {
        return count($this->images) > 1;
    }

    /**
     * Increment views count (if you add this column later).
     */
    public function incrementViews($count = 1)
    {
        // If you add a views_count column later:
        // $this->increment('views_count', $count);
        return $this;
    }

    /**
     * Get the author name (uses 'name' field).
     */
    public function getAuthorAttribute()
    {
        return $this->name;
    }

    public static function checkSlugExists($slug, $id = null)
    {
        $query = self::where('slug', $slug);
        if ($id !== null) {
            $query->where('id', '!=', $id);
        }
        return $query->exists();
    }
}

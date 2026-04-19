<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'name',
        'email',
        'content',
        'is_approved',
        'user_ip',
        'user_agent'
    ];

    protected $casts = [
        'is_approved' => 'boolean'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function post()
    {
        return $this->belongsTo(newpost_details::class, 'post_id');
    }

    /**
     * Get formatted date.
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->format('F j, Y, g:i a')
        );
    }

    /**
     * Get time ago.
     */
    protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans()
        );
    }

    /**
     * Get initials for avatar.
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $words = explode(' ', $this->name);
                $initials = '';
                foreach ($words as $word) {
                    $initials .= strtoupper(substr($word, 0, 1));
                }
                return substr($initials, 0, 2);
            }
        );
    }

    /**
     * Scope a query to only include approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Approve the comment.
     */
    public function approve()
    {
        $this->is_approved = true;
        $this->save();
        return $this;
    }
}

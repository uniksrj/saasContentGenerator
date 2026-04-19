<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    use HasFactory;
     protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'session_id'
    ];

    protected $table = 'post_views';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the post that owns the view.
     */
    public function post()
    {
        return $this->belongsTo(newpost_details::class, 'post_id');
    }

    /**
     * Check if IP has already viewed today.
     */
    public static function hasViewedToday($postId, $ipAddress)
    {
        return self::where('post_id', $postId ?? 0)
            ->where('ip_address', $ipAddress ?? '')
            ->whereDate('created_at', today())
            ->exists();
    }

    /**
     * Get today's views for a post.
     */
    public static function todayViews($postId)
    {
        return self::where('post_id', $postId)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Get total views for a post.
     */
    public static function totalViews($postId)
    {
        return self::where('post_id', $postId)->count();
    }

    /**
     * Get unique views (by session) for a post.
     */
    public static function uniqueViews($postId)
    {
        return self::where('post_id', $postId)
            ->distinct('session_id')
            ->count('session_id');
    }

    public static function totalViewsByMonth($month, $year)
    {
        return self::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
    }
}

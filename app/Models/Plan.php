<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_article_limit',
        'monthly_token_limit',
        'price_cents',
        'currency',
        'stripe_price_id',
        'billing_interval',
        'is_active',
        'features',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'monthly_article_limit' => 'integer',
        'monthly_token_limit' => 'integer',
        'price_cents' => 'integer',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function formattedPrice(): string
    {
        return strtoupper($this->currency) . ' ' . number_format($this->price_cents / 100, 2);
    }
}

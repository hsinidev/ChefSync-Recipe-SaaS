<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class Recipe extends Model implements HasMedia
{
    use HasTenant, InteractsWithMedia;

    protected $fillable = [
        'tenant_uuid',
        'author_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'cover_image',
        'video_url',
        'description_html',
        'prep_time_minutes',
        'cook_time_minutes',
        'servings',
        'status',
    ];

    protected $casts = [
        'prep_time_minutes' => 'integer',
        'cook_time_minutes' => 'integer',
        'servings' => 'integer',
    ];

    /**
     * Get the author that created the recipe.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category of the recipe.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the ingredients of the recipe.
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class)->orderBy('sort_order');
    }

    /**
     * Get the instructions steps of the recipe.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    /**
     * Register Spatie MediaLibrary conversions.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(150)
            ->height(150)
            ->sharpen(10);

        $this->addMediaConversion('card')
            ->width(600)
            ->height(400);

        $this->addMediaConversion('hero')
            ->width(1200)
            ->height(800);
    }
}

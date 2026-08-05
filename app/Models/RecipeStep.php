<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RecipeStep extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'step_number',
        'instruction',
        'media_url',
    ];

    protected $casts = [
        'step_number' => 'integer',
    ];

    /**
     * Get the recipe that owns the step.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}

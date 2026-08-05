<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Ingredient extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'sort_order',
        'amount',
        'unit',
        'name',
        'state',
    ];

    protected $casts = [
        'amount' => 'float',
        'sort_order' => 'integer',
    ];

    /**
     * Get the recipe that owns the ingredient.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}

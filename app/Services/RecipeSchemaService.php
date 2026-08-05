<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Facades\Cache;

final readonly class RecipeSchemaService
{
    /**
     * Generate Schema.org compliant JSON-LD representation for Google Rich Snippets.
     */
    public function generate(Recipe $recipe): string
    {
        return Cache::remember("recipe:schema:{$recipe->id}", 86400, function () use ($recipe) {
            $ingredientsData = $recipe->ingredients->map(
                fn($ing) => "{$ing->amount} {$ing->unit} " . ($ing->state ? "{$ing->state} " : "") . $ing->name
            )->toArray();

            $stepsData = $recipe->steps->map(fn($step) => [
                '@type' => 'HowToStep',
                'text' => $step->instruction
            ])->toArray();

            $totalMinutes = $recipe->prep_time_minutes + $recipe->cook_time_minutes;

            $payload = [
                '@context' => 'https://schema.org',
                '@type' => 'Recipe',
                'name' => $recipe->title,
                'image' => $recipe->getFirstMediaUrl('covers', 'hero') ?: null,
                'author' => [
                    '@type' => 'Person',
                    'name' => $recipe->author->name ?? 'Head Chef'
                ],
                'datePublished' => $recipe->created_at?->toIso8601String(),
                'description' => $recipe->excerpt,
                'prepTime' => "PT{$recipe->prep_time_minutes}M",
                'cookTime' => "PT{$recipe->cook_time_minutes}M",
                'totalTime' => "PT{$totalMinutes}M",
                'recipeYield' => "{$recipe->servings} servings",
                'recipeIngredient' => $ingredientsData,
                'recipeInstructions' => $stepsData
            ];

            return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        });
    }
}

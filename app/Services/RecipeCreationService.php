<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use App\Jobs\FetchUnsplashCoverJob;

/**
 * Handles atomic transactional recipe creation and hooks background image fetching pipelines.
 */
final readonly class RecipeCreationService
{
    /**
     * Create base recipe model and all polymorphic/associated children safely.
     * 
     * @param array{
     *   tenant_uuid: string,
     *   author_id: int,
     *   title: string,
     *   slug: string,
     *   excerpt: ?string,
     *   prep_time_minutes: int,
     *   cook_time_minutes: int,
     *   servings: int,
     *   status: string,
     *   cover_image_url: ?string,
     *   seo_keywords: ?string
     * } $recipeData
     * @param array<int, array{amount: float, unit: string, name: string, state: ?string}> $ingredients
     * @param array<int, array{step_number: int, instruction: string}> $steps
     */
    public function createWithRelations(array $recipeData, array $ingredients, array $steps): Recipe
    {
        return DB::transaction(function () use ($recipeData, $ingredients, $steps) {
            // 1. Create Base Recipe Model
            $recipe = Recipe::create($recipeData);

            // 2. Perform bulk insertion of related ingredients mapping proper sorted indices
            $recipe->ingredients()->createMany(
                array_map(fn ($ing, $index) => [
                    'sort_order' => $index,
                    'amount' => $ing['amount'],
                    'unit' => $ing['unit'],
                    'name' => $ing['name'],
                    'state' => $ing['state'] ?? null
                ], $ingredients, array_keys($ingredients))
            );

            // 3. Perform bulk insertion of instructions
            $recipe->steps()->createMany(
                array_map(fn ($step) => [
                    'step_number' => $step['step_number'],
                    'instruction' => $step['instruction']
                ], $steps)
            );

            // 4. Asynchronously fetch Unsplash cover image if missing but keywords exist
            if (empty($recipeData['cover_image_url']) && !empty($recipeData['seo_keywords'])) {
                $this->dispatchUnsplashCoverFetch($recipe);
            }

            return $recipe;
        });
    }

    private function dispatchUnsplashCoverFetch(Recipe $recipe): void
    {
        // Dispatch media-processing job directly to Horizon dedicated queue
        FetchUnsplashCoverJob::dispatch($recipe)->onQueue('media');
    }
}

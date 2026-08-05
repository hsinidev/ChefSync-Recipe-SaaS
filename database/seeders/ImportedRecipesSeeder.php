<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ImportedRecipesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get default tenant and author
        $tenant = Tenant::first();
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Default Tenant',
            ]);
        }
        $tenantUuid = $tenant->uuid;

        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Head Chef',
                'email' => 'chef@recipes.hsini.dev',
                'password' => bcrypt('password123'),
            ]);
        }

        // 2. Load recipes.json file
        $jsonPath = database_path('data/recipes.json');
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("recipes.json file not found at: {$jsonPath}");
        }

        $jsonData = file_get_contents($jsonPath);
        $recipesData = json_decode($jsonData, true);

        if (!is_array($recipesData)) {
            throw new \RuntimeException("Failed to parse recipes.json or file is empty.");
        }

        $this->command->info('Seeding ' . count($recipesData) . ' imported recipes...');

        // 3. Process and create each recipe
        foreach ($recipesData as $item) {
            // Category
            $categoryName = $item['category'] ?? 'General';
            $categorySlug = Str::slug($categoryName);
            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                ['name' => $categoryName]
            );

            // Slug uniqueness check
            $title = $item['name'];
            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $counter = 1;
            while (Recipe::withoutGlobalScopes()->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Excerpt
            $excerpt = $item['description'] ?? '';

            // Description HTML
            $instructionsHtml = '';
            if (isset($item['instructions']) && is_array($item['instructions'])) {
                $instructionsHtml = '<h3>Instructions</h3><ol>';
                foreach ($item['instructions'] as $step) {
                    $instructionsHtml .= '<li>' . e($step) . '</li>';
                }
                $instructionsHtml .= '</ol>';
            }
            $descriptionHtml = '<p>' . e($excerpt) . '</p>' . $instructionsHtml;

            // Create Recipe
            $recipe = Recipe::create([
                'tenant_uuid'       => $tenantUuid,
                'author_id'         => $user->id,
                'category_id'       => $category->id,
                'title'             => $title,
                'slug'              => $slug,
                'excerpt'           => Str::limit($excerpt, 200),
                'cover_image'       => $item['image'] ?? null,
                'prep_time_minutes' => $item['prepTime'] ?? 0,
                'cook_time_minutes' => $item['cookTime'] ?? 0,
                'servings'          => $item['servings'] ?? 4,
                'status'            => 'published',
                'description_html'  => $descriptionHtml,
            ]);

            // Create Ingredients
            if (isset($item['ingredients']) && is_array($item['ingredients'])) {
                $ingredientsData = [];
                foreach ($item['ingredients'] as $idx => $ingredientName) {
                    $ingredientsData[] = [
                        'name'       => $ingredientName,
                        'amount'     => '1',
                        'unit'       => 'unit',
                        'sort_order' => $idx,
                    ];
                }
                $recipe->ingredients()->createMany($ingredientsData);
            }

            // Create Steps
            if (isset($item['instructions']) && is_array($item['instructions'])) {
                $stepsData = [];
                foreach ($item['instructions'] as $idx => $stepText) {
                    $stepsData[] = [
                        'step_number' => $idx + 1,
                        'instruction' => $stepText,
                    ];
                }
                $recipe->steps()->createMany($stepsData);
            }
        }

        $this->command->info('Seeding completed successfully!');
    }
}

# ChefSync: Technical Design & Recipe Engine Blueprint

This technical blueprint outlines the enterprise-grade schema definitions, transactional service layers, asynchronous queues, SEO markup generators, and reactive frontend components designed to power ChefSync: Multi-Tenant Recipes Sharing & Directory SaaS.

## 1. Relational Database Schema (MySQL 8.4 / InnoDB)

To support high-throughput, structured operations across multiple tenant environments, the primary database utilizes strict datatype mapping, compound indexed constraints, and InnoDB table page compression.

```
[Tenants] 1 ── * [Recipes] 1 ── * [Ingredients]
                             1 ── * [Recipe Steps]
```

### Schema Definitions

```sql
-- Enable page compression for text-heavy tables
SET GLOBAL innodb_file_format='Barracuda';

CREATE TABLE tenants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid BINARY(16) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    domain VARCHAR(255) NOT NULL UNIQUE,
    uses_isolated_db TINYINT(1) DEFAULT 0,
    db_config TEXT NULL, -- Encrypted JSON: host, database, username, password
    billing_plan VARCHAR(50) DEFAULT 'free',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_domain (domain),
    INDEX idx_uuid (uuid)
) ENGINE=InnoDB ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8;

CREATE TABLE recipes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_uuid BINARY(16) NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    excerpt TEXT NULL,
    prep_time_minutes SMALLINT UNSIGNED DEFAULT 0,
    cook_time_minutes SMALLINT UNSIGNED DEFAULT 0,
    servings SMALLINT UNSIGNED DEFAULT 4,
    status ENUM('draft', 'review', 'published') DEFAULT 'draft',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY idx_tenant_slug (tenant_uuid, slug),
    INDEX idx_tenant_status_created (tenant_uuid, status, created_at DESC)
) ENGINE=InnoDB ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8;

CREATE TABLE ingredients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    sort_order SMALLINT UNSIGNED DEFAULT 0,
    amount DECIMAL(8, 2) UNSIGNED NOT NULL,
    unit VARCHAR(50) NOT NULL, -- e.g., 'grams', 'ml', 'cups', 'pcs', 'tbsp'
    name VARCHAR(255) NOT NULL,
    state VARCHAR(100) NULL,   -- e.g., 'chopped', 'sifted', 'minced'
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    INDEX idx_recipe_ingredients (recipe_id, sort_order)
) ENGINE=InnoDB;

CREATE TABLE recipe_steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    step_number SMALLINT UNSIGNED NOT NULL,
    instruction TEXT NOT NULL,
    media_url VARCHAR(500) NULL,
    UNIQUE KEY idx_recipe_step (recipe_id, step_number),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
) ENGINE=InnoDB ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8;
```

## 2. Atomic Recipe Creation Service (PHP 8.4 & Laravel 12)

Admin-controlled recipe submission triggers a database transaction encapsulating base attributes, structured bulk insert operations for steps and ingredients, and background-dispatched media requests.

```php
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
```

## 3. SEO Schema.org JSON-LD Generator

ChefSync automatically structures recipe pages with search engine-friendly metadata. The generated schema is structured via specialized service layers and cached in Redis.

```php
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
```

Inside your master Blade layout header, simply render the payload within HTML script components:

```html
<script type="application/ld+json">
    {!! app(App\Services\RecipeSchemaService::class)->generate($recipe) !!}
</script>
```

## 4. Front-End Dynamic Serving Scaler (Tailwind v4 & Alpine.js)

To prevent server CPU exhaustion caused by constant calculation requests, dynamic serving updates are handled directly on the client. Alpine.js reactive proxy states calculate and render scaling ratios instantly, transforming raw fractions smoothly into culinary-standard readable formats.

```html
<div x-data="{
    servings: 4,
    baseServings: 4,
    ingredients: [
        { name: 'Unbleached Bread Flour', qty: 500, unit: 'g' },
        { name: 'Active Dry Yeast', qty: 7, unit: 'g' },
        { name: 'Fine Sea Salt', qty: 10, unit: 'g' },
        { name: 'Lukewarm Water', qty: 350, unit: 'ml' },
        { name: 'Extra Virgin Olive Oil', qty: 1.5, unit: 'tbsp' }
    ],
    formatFraction(val) {
        if (val % 1 === 0) return val.toString();
        const tolerance = 0.05;
        const decimal = val % 1;
        const integer = Math.floor(val);
        let fraction = '';
        
        if (Math.abs(decimal - 0.25) < tolerance) fraction = '1/4';
        else if (Math.abs(decimal - 0.33) < tolerance || Math.abs(decimal - 0.3) < tolerance) fraction = '1/3';
        else if (Math.abs(decimal - 0.5) < tolerance) fraction = '1/2';
        else if (Math.abs(decimal - 0.66) < tolerance || Math.abs(decimal - 0.7) < tolerance) fraction = '2/3';
        else if (Math.abs(decimal - 0.75) < tolerance) fraction = '3/4';
        
        if (fraction) {
            return integer > 0 ? `${integer} ${fraction}` : fraction;
        }
        return val.toFixed(1).replace('.0', '');
    }
}" class="max-w-md mx-auto p-6 bg-white border border-slate-200 rounded-3xl shadow-sm">
    
    <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
        <div>
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Ingredients Scaler</h3>
            <p class="text-xs text-slate-500">Adjust serving count to recalculate portions</p>
        </div>
        
        <div class="flex items-center space-x-2 bg-slate-50 p-1.5 rounded-2xl border border-slate-100">
            <button 
                type="button"
                @click="if(servings > 1) servings--" 
                class="w-8 h-8 flex items-center justify-center text-slate-700 bg-white hover:bg-slate-100 rounded-xl shadow-xs transition-all active:scale-95">
                −
            </button>
            <span x-text="servings" class="text-base font-bold text-slate-900 w-8 text-center"></span>
            <button 
                type="button"
                @click="servings++" 
                class="w-8 h-8 flex items-center justify-center text-slate-700 bg-white hover:bg-slate-100 rounded-xl shadow-xs transition-all active:scale-95">
                +
            </button>
        </div>
    </div>

    <ul class="space-y-3.5">
        <template x-for="(ing, index) in ingredients" :key="index">
            <li class="flex items-center justify-between p-3 bg-slate-50/50 hover:bg-slate-50 border border-slate-100/50 rounded-2xl transition-colors">
                <span x-text="ing.name" class="text-sm font-medium text-slate-700"></span>
                <span class="text-sm font-semibold text-emerald-600 tracking-tight">
                    <span x-text="formatFraction((ing.qty * servings) / baseServings)"></span>
                    <span x-text="ing.unit" class="text-emerald-500/80 font-medium text-xs ml-0.5"></span>
                </span>
            </li>
        </template>
    </ul>
</div>
```

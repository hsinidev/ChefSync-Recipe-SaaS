<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeStep;
use App\Models\Category;
use App\Models\Setting;
use App\Services\AIService;
use App\Services\RecipeCreationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

final class RecipeController extends Controller
{
    public function __construct(private readonly RecipeCreationService $recipeService) {}

    public function index(Request $request): View
    {
        $query = Recipe::withoutGlobalScopes()->with(['author', 'category']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $recipes = $query->latest()->paginate(1000)->withQueryString();

        return view('admin.recipes.index', compact('recipes'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.recipes.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'excerpt'           => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'description_html'  => 'nullable|string',
            'prep_time_minutes' => 'required|integer|min:0',
            'cook_time_minutes' => 'required|integer|min:0',
            'servings'          => 'required|integer|min:1',
            'status'            => 'required|in:draft,review,published',
            'video_url'         => 'nullable|string|max:500',
            'cover_image'       => 'nullable|image|max:8192',
            'cover_image_url'   => 'nullable|string|max:500',
            'gallery_images'    => 'nullable|array',
            'gallery_images.*'  => 'image|max:8192',
            'ingredients'       => 'required|array|min:1',
            'ingredients.*.name'   => 'required|string|max:255',
            'ingredients.*.amount' => 'required|numeric|min:0',
            'ingredients.*.unit'   => 'required|string|max:50',
            'ingredients.*.state'  => 'nullable|string|max:100',
            'steps'             => 'required|array|min:1',
            'steps.*.instruction'  => 'required|string',
        ]);

        $author = \App\Models\User::first();

        $recipeData = [
            'title'             => $validated['title'],
            'slug'              => Str::slug($validated['title']),
            'excerpt'           => $validated['excerpt'] ?? null,
            'category_id'       => $validated['category_id'] ?? null,
            'description_html'  => $validated['description_html'] ?? null,
            'prep_time_minutes' => $validated['prep_time_minutes'],
            'cook_time_minutes' => $validated['cook_time_minutes'],
            'servings'          => $validated['servings'],
            'status'            => $validated['status'],
            'author_id'         => $author?->id ?? 1,
            'tenant_uuid'       => \App\Models\Scopes\TenantScope::getTenantId()
                                    ?? (\App\Models\Tenant::first()?->uuid),
            'video_url'         => $validated['video_url'] ?? null,
            'cover_image'       => $validated['cover_image_url'] ?? '/images/recipes/beef-bourguignon.png',
        ];

        $steps = array_map(
            fn($step, $i) => ['step_number' => $i + 1, 'instruction' => $step['instruction']],
            $validated['steps'],
            array_keys($validated['steps'])
        );

        \DB::transaction(function () use ($recipeData, $validated, $steps, $request) {
            $recipe = Recipe::create($recipeData);

            if ($request->hasFile('cover_image')) {
                $media = $recipe->addMediaFromRequest('cover_image')->toMediaCollection('cover');
                $recipe->update(['cover_image' => $media->getUrl()]);
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $recipe->addMedia($file)->toMediaCollection('gallery');
                }
            }

            $recipe->ingredients()->createMany(
                array_map(fn ($ing, $index) => [
                    'sort_order' => $index,
                    'amount' => $ing['amount'],
                    'unit' => $ing['unit'],
                    'name' => $ing['name'],
                    'state' => $ing['state'] ?? null
                ], $validated['ingredients'], array_keys($validated['ingredients']))
            );

            $recipe->steps()->createMany($steps);
        });

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recipe created successfully!');
    }

    public function edit(Recipe $recipe): View
    {
        $recipe->loadMissing(['ingredients', 'steps']);
        $categories = Category::all();
        return view('admin.recipes.edit', compact('recipe', 'categories'));
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'excerpt'           => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'description_html'  => 'nullable|string',
            'prep_time_minutes' => 'required|integer|min:0',
            'cook_time_minutes' => 'required|integer|min:0',
            'servings'          => 'required|integer|min:1',
            'status'            => 'required|in:draft,review,published',
            'video_url'         => 'nullable|string|max:500',
            'cover_image'       => 'nullable|image|max:8192',
            'cover_image_url'   => 'nullable|string|max:500',
            'gallery_images'    => 'nullable|array',
            'gallery_images.*'  => 'image|max:8192',
            'ingredients'       => 'required|array|min:1',
            'ingredients.*.name'   => 'required|string|max:255',
            'ingredients.*.amount' => 'required|numeric|min:0',
            'ingredients.*.unit'   => 'required|string|max:50',
            'ingredients.*.state'  => 'nullable|string|max:100',
            'steps'             => 'required|array|min:1',
            'steps.*.instruction'  => 'required|string',
        ]);

        \DB::transaction(function () use ($recipe, $validated, $request) {
            $recipe->update([
                'title'             => $validated['title'],
                'slug'              => Str::slug($validated['title']),
                'excerpt'           => $validated['excerpt'] ?? null,
                'category_id'       => $validated['category_id'] ?? null,
                'description_html'  => $validated['description_html'] ?? null,
                'prep_time_minutes' => $validated['prep_time_minutes'],
                'cook_time_minutes' => $validated['cook_time_minutes'],
                'servings'          => $validated['servings'],
                'status'            => $validated['status'],
                'video_url'         => $validated['video_url'] ?? null,
            ]);

            // Handle deleting existing gallery images
            if ($request->has('delete_media')) {
                foreach ($request->input('delete_media') as $mediaId) {
                    $mediaItem = $recipe->media()->find($mediaId);
                    if ($mediaItem) {
                        $mediaItem->delete();
                    }
                }
            }

            // Handle cover image file upload
            if ($request->hasFile('cover_image')) {
                $recipe->clearMediaCollection('cover');
                $media = $recipe->addMediaFromRequest('cover_image')->toMediaCollection('cover');
                $recipe->update(['cover_image' => $media->getUrl()]);
            } elseif (!empty($validated['cover_image_url'])) {
                $recipe->update(['cover_image' => $validated['cover_image_url']]);
            }

            // Handle gallery images upload
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $recipe->addMedia($file)->toMediaCollection('gallery');
                }
            }

            // Re-sync ingredients
            $recipe->ingredients()->delete();
            $recipe->ingredients()->createMany(
                array_map(fn ($ing, $index) => [
                    'sort_order' => $index,
                    'amount' => $ing['amount'],
                    'unit' => $ing['unit'],
                    'name' => $ing['name'],
                    'state' => $ing['state'] ?? null
                ], $validated['ingredients'], array_keys($validated['ingredients']))
            );

            // Re-sync steps
            $recipe->steps()->delete();
            $recipe->steps()->createMany(
                array_map(fn ($step, $i) => [
                    'step_number' => $i + 1,
                    'instruction' => $step['instruction']
                ], $validated['steps'], array_keys($validated['steps']))
            );
        });

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recipe updated successfully!');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->delete();

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recipe deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Recipe::withoutGlobalScopes()->whereIn('id', $ids)->delete();
            return redirect()->route('admin.recipes.index')
                ->with('success', 'Selected recipes deleted successfully.');
        }
        return redirect()->route('admin.recipes.index')
            ->with('error', 'No recipes selected.');
    }

    public function updateStatus(Request $request, Recipe $recipe): RedirectResponse
    {
        $request->validate(['status' => 'required|in:draft,review,published']);
        $recipe->update(['status' => $request->input('status')]);
        return back()->with('success', "Recipe marked as {$request->input('status')}.");
    }

    /**
     * AJAX endpoint to generate recipe from AI
     */
    public function generateAI(Request $request): JsonResponse
    {
        $request->validate(['title' => 'required|string|max:255']);
        
        $setting = Setting::firstOrCreate();
        
        $res = AIService::generateRecipe($request->input('title'), $setting->toArray());
        
        if (isset($res['error'])) {
            return response()->json(['error' => $res['error']], 400);
        }
        
        return response()->json($res['data']);
    }
}

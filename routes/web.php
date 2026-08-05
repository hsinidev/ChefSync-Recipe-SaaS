<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Recipe;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Public Recipe Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (\Illuminate\Http\Request $request) {
    $query = Recipe::with(['author', 'category'])->where('status', 'published');
    
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    if ($categorySlug = $request->input('category')) {
        $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    if ($time = $request->input('time')) {
        if ($time === 'quick') {
            $query->whereRaw('(prep_time_minutes + cook_time_minutes) <= 30');
        } elseif ($time === 'medium') {
            $query->whereRaw('(prep_time_minutes + cook_time_minutes) > 30 and (prep_time_minutes + cook_time_minutes) <= 60');
        } elseif ($time === 'slow') {
            $query->whereRaw('(prep_time_minutes + cook_time_minutes) > 60');
        }
    }

    $recipes = $query->latest()->paginate(20)->withQueryString();
    $categories = \App\Models\Category::withCount(['recipes' => function ($q) {
        $q->where('status', 'published');
    }])->get();
    $slides = \App\Models\HeroSlide::where('is_active', true)->orderBy('sort_order')->get();

    return view('recipes.index', compact('recipes', 'categories', 'slides'));
});

Route::get('/recipes/{slug}', function (string $slug) {
    $recipe = Recipe::with(['author', 'category', 'ingredients', 'steps'])
        ->where('slug', $slug)
        ->firstOrFail();

    return view('recipes.show', compact('recipe'));
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Recipe management
    Route::post('recipes/generate-ai', [AdminRecipeController::class, 'generateAI'])
        ->name('recipes.generate-ai');
    Route::post('recipes/bulk-destroy', [AdminRecipeController::class, 'bulkDestroy'])
        ->name('recipes.bulk-destroy');
    Route::resource('recipes', AdminRecipeController::class);
    Route::patch('recipes/{recipe}/status', [AdminRecipeController::class, 'updateStatus'])
        ->name('recipes.status');

    // Category management
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Hero Slides management
    Route::resource('slides', \App\Http\Controllers\Admin\HeroSlideController::class)->except(['show']);

    // Settings management
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Tenant management
    Route::resource('tenants', TenantController::class)->except(['show']);

    // User management
    Route::resource('users', UserController::class)->only(['index','create','store','destroy']);
});



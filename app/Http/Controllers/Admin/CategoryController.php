<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = empty($validated['slug']) ? Str::slug($validated['name']) : Str::slug($validated['slug']);

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$validated['name']}\" created.");
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = empty($validated['slug']) ? Str::slug($validated['name']) : Str::slug($validated['slug']);

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted.');
    }
}

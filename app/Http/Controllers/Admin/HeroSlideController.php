<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class HeroSlideController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::orderBy('sort_order')->paginate(15);
        return view('admin.slides.index', compact('slides'));
    }

    public function create(): View
    {
        return view('admin.slides.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'subtitle'     => 'nullable|string',
            'category_tag' => 'nullable|string|max:255',
            'image_url'    => 'nullable|string|max:500',
            'image_file'   => 'nullable|image|max:8192',
            'link_url'     => 'nullable|string|max:500',
            'sort_order'   => 'required|integer',
            'is_active'    => 'boolean',
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('slides', 'public');
            $imageUrl = '/storage/' . $path;
        }

        HeroSlide::create([
            'title'        => $validated['title'],
            'subtitle'     => $validated['subtitle'],
            'category_tag' => $validated['category_tag'],
            'image_url'    => $imageUrl,
            'link_url'     => $validated['link_url'],
            'sort_order'   => $validated['sort_order'],
            'is_active'    => $request->has('is_active'),
        ]);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $slide): View
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function update(Request $request, HeroSlide $slide): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'subtitle'     => 'nullable|string',
            'category_tag' => 'nullable|string|max:255',
            'image_url'    => 'nullable|string|max:500',
            'image_file'   => 'nullable|image|max:8192',
            'link_url'     => 'nullable|string|max:500',
            'sort_order'   => 'required|integer',
            'is_active'    => 'boolean',
        ]);

        $imageUrl = $validated['image_url'] ?? $slide->image_url;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('slides', 'public');
            $imageUrl = '/storage/' . $path;
        }

        $slide->update([
            'title'        => $validated['title'],
            'subtitle'     => $validated['subtitle'],
            'category_tag' => $validated['category_tag'],
            'image_url'    => $imageUrl,
            'link_url'     => $validated['link_url'],
            'sort_order'   => $validated['sort_order'],
            'is_active'    => $request->has('is_active'),
        ]);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $slide): RedirectResponse
    {
        $slide->delete();
        return redirect()->route('admin.slides.index')
            ->with('success', 'Hero slide deleted.');
    }
}

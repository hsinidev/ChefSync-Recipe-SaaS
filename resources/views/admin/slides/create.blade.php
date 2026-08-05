@extends('layouts.admin')
@section('title', 'Create Slide')
@section('page-title', 'Create Slide')

@section('content')
<div class="max-w-2xl">

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.slides.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition-all hover:text-blue-600"
           style="color: var(--slate-500); text-decoration: none;">
            <svg class="w-4 h-4" width="16" height="16" style="width:16px; height:16px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Slideshow list
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl" style="background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15);">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-xs" style="color: #dc2626;">• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="cs-card" style="padding: 24px;">
        <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Hero Slide Information</h2>
                    <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Create a new banner slide to display on your homepage.</p>
                </div>

                <div>
                    <label class="cs-label">Heading / Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Tahini & Rye Cookies" class="cs-input">
                </div>

                <div>
                    <label class="cs-label">Description / Subtitle</label>
                    <textarea name="subtitle" rows="3" placeholder="We are obsessed with tahini so when we heard of a cookie..." class="cs-input" style="resize:none;">{{ old('subtitle') }}</textarea>
                </div>

                <div>
                    <label class="cs-label">Category Tag (e.g., cuisine or type)</label>
                    <input type="text" name="category_tag" value="{{ old('category_tag') }}" placeholder="e.g. SWEET TREATS" class="cs-input">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label class="cs-label">Upload Slide Image</label>
                        <input type="file" name="image_file" accept="image/*" class="cs-input" style="font-size:12px; padding:8px;">
                    </div>
                    <div>
                        <label class="cs-label">Or Use Image URL</label>
                        <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="e.g. https://images.unsplash.com/..." class="cs-input">
                    </div>
                </div>

                <div>
                    <label class="cs-label">Destination Link URL</label>
                    <input type="text" name="link_url" value="{{ old('link_url') }}" placeholder="e.g. /recipes/classic-chocolate-chip-cookies" class="cs-input">
                    <p style="font-size:11px; color:var(--slate-400); margin-top:4px;">Where the visitor will land when clicking this slide.</p>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:center;">
                    <div>
                        <label class="cs-label">Sort Order *</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', \App\Models\HeroSlide::count() + 1) }}" required class="cs-input">
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; margin-top:20px;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--blue-600);">
                        <label for="is_active" class="cs-label" style="margin-bottom:0; cursor:pointer;">Active / Visible on Home</label>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; border-top: 1px solid var(--slate-200); padding-top: 20px; margin-top: 10px;">
                    <button type="submit" class="btn-primary">
                        Create Slide
                    </button>
                    <a href="{{ route('admin.slides.index') }}" class="btn-secondary" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@extends('layouts.admin')
@section('title', 'Hero Slideshow')
@section('page-title', 'Hero Slideshow')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:13px;color:#64748b;">Configure up to 10 rotating slides on your homepage in the style of Green Kitchen Stories.</p>
        <a href="{{ route('admin.slides.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Slide
        </a>
    </div>

    <div class="cs-card">
        <table class="cs-table">
            <thead>
                <tr>
                    <th>Slide Image</th>
                    <th>Cuisine / Tag</th>
                    <th>Heading</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($slides as $slide)
            <tr>
                <td>
                    @if($slide->image_url)
                        <img src="{{ $slide->image_url }}" alt="Preview" style="width:80px;height:45px;object-cover;border-radius:6px;border:1px solid var(--slate-200);">
                    @else
                        <div style="width:80px;height:45px;background:var(--slate-100);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--slate-400);font-size:10px;">
                            No Image
                        </div>
                    @endif
                </td>
                <td>
                    <span style="font-size:10.5px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:var(--blue-600);background:var(--blue-50);padding:2px 8px;border-radius:4px;">
                        {{ $slide->category_tag ?: 'General' }}
                    </span>
                </td>
                <td>
                    <div style="font-weight:700;color:var(--slate-900);">{{ $slide->title }}</div>
                    @if($slide->subtitle)
                        <div style="font-size:11.5px;color:var(--slate-400);margin-top:2px;max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $slide->subtitle }}
                        </div>
                    @endif
                </td>
                <td style="font-weight:600;color:var(--slate-600);">#{{ $slide->sort_order }}</td>
                <td>
                    @if($slide->is_active)
                        <span class="badge badge-published">Active</span>
                    @else
                        <span class="badge badge-draft">Inactive</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                        <a href="{{ route('admin.slides.edit', $slide) }}" class="icon-btn icon-btn-slate" title="Edit slide">
                            <svg style="width:13px;height:13px;" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}"
                              onsubmit="return confirm('Remove slide {{ addslashes($slide->title) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-red" title="Remove slide">
                                <svg style="width:13px;height:13px;" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#94a3b8;padding:40px 0;">No hero slides created yet. Add slides to activate the homepage slider.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
        @if($slides->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">{{ $slides->links() }}</div>
        @endif
    </div>
</div>
@endsection

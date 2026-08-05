@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:13px;color:#64748b;">Organize and filter recipes using categories.</p>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px;" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </a>
    </div>

    <div class="cs-card">
        <table class="cs-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Recipes Count</th>
                    <th>Created At</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($category->name,0,1)) }}
                        </div>
                        <div style="font-weight:600;color:#1e293b;">{{ $category->name }}</div>
                    </div>
                </td>
                <td style="color:#64748b;"><code>{{ $category->slug }}</code></td>
                <td>
                    <span style="font-size:12.5px;background:var(--blue-50);color:var(--blue-700);padding:2px 9px;border-radius:99px;font-weight:600;">
                        {{ $category->recipes()->count() }}
                    </span>
                </td>
                <td style="color:#94a3b8;font-size:12.5px;">{{ $category->created_at->format('d M Y') }}</td>
                <td style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="icon-btn icon-btn-slate" title="Edit category">
                            <svg style="width:13px;height:13px;" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Remove category {{ addslashes($category->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-red" title="Remove category">
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
                <td colspan="5" style="text-align:center;color:#94a3b8;padding:40px 0;">No categories found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
        @if($categories->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">{{ $categories->links() }}</div>
        @endif
    </div>
</div>
@endsection

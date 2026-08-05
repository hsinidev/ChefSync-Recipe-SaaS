@extends('layouts.admin')
@section('title', 'Create Category')
@section('page-title', 'Create Category')

@section('content')
<div class="max-w-2xl">

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition-all hover:text-blue-600"
           style="color: var(--slate-500); text-decoration: none;">
            <svg class="w-4 h-4" width="16" height="16" style="width:16px; height:16px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Categories
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
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Category Information</h2>
                    <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Create a new recipe organization category</p>
                </div>

                <div>
                    <label class="cs-label">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Desserts" class="cs-input">
                </div>

                <div>
                    <label class="cs-label">Custom Slug (optional)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="e.g. desserts-and-sweets" class="cs-input">
                    <p style="font-size: 11px; color: var(--slate-400); margin-top: 4px;">Leave empty to automatically generate from name.</p>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; border-top: 1px solid var(--slate-200); padding-top: 20px; margin-top: 10px;">
                    <button type="submit" class="btn-primary">
                        Create Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

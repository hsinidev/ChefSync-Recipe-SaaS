@extends('layouts.admin')
@section('title','Recipes')
@section('page-title','Recipes')

@section('content')
<div x-data="{ 
    selectedRecipes: [],
    allIds: {{ json_encode($recipes->pluck('id')->toArray()) }},
    toggleAll() {
        if (this.selectedRecipes.length === this.allIds.length) {
            this.selectedRecipes = [];
        } else {
            this.selectedRecipes = [...this.allIds];
        }
    },
    isAllSelected() {
        return this.selectedRecipes.length === this.allIds.length && this.allIds.length > 0;
    }
}" style="display:flex;flex-direction:column;gap:20px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:13px;color:#64748b;">{{ $recipes->total() }} recipes across all tenants</p>
        <a href="{{ route('admin.recipes.create') }}" class="btn-primary">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Recipe
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.recipes.index') }}"
          style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;">
        <svg style="width:14px;height:14px;color:#94a3b8;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search recipes…"
               style="flex:1;border:none;background:transparent;font-size:13px;color:#1e293b;outline:none;">
        <select name="status" class="cs-input" style="width:140px;font-size:12.5px;padding:6px 10px;">
            <option value="">All Statuses</option>
            <option value="draft"     {{ request('status')==='draft'     ?'selected':'' }}>Draft</option>
            <option value="review"    {{ request('status')==='review'    ?'selected':'' }}>In Review</option>
            <option value="published" {{ request('status')==='published' ?'selected':'' }}>Published</option>
        </select>
        <button type="submit" class="btn-primary" style="padding:7px 16px;font-size:12.5px;">Filter</button>
        @if(request()->anyFilled(['search','status']))
        <a href="{{ route('admin.recipes.index') }}" style="font-size:12px;color:#94a3b8;text-decoration:none;">Clear</a>
        @endif
    </form>

    {{-- Bulk Action Bar --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;font-size:13px;color:#64748b;">
        <div style="display:flex;align-items:center;gap:12px;">
            <label style="display:inline-flex;align-items:center;gap:8px;font-weight:600;color:#334155;cursor:pointer;">
                <input type="checkbox" :checked="isAllSelected()" @click="toggleAll()" style="width:16px;height:16px;border-radius:4px;border:1px solid #cbd5e1;cursor:pointer;">
                Select All on Page
            </label>
            <span x-show="selectedRecipes.length > 0" x-cloak style="color:#2563eb;font-weight:700;">
                <span x-text="selectedRecipes.length"></span> selected
            </span>
        </div>
        <div>
            <button type="submit" form="bulk-delete-form" class="btn-primary" style="background:#dc2626;border-color:#dc2626;padding:8px 16px;font-size:12.5px;display:flex;align-items:center;gap:6px;transition:opacity 0.2s;"
                    :disabled="selectedRecipes.length === 0"
                    :style="selectedRecipes.length === 0 ? 'opacity:0.5;cursor:not-allowed;' : 'cursor:pointer;'"
                    onmouseover="if(this.style.cursor!=='not-allowed')this.style.background='#b91c1c'"
                    onmouseout="if(this.style.cursor!=='not-allowed')this.style.background='#dc2626'">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Selected
            </button>
        </div>
    </div>

    {{-- Recipe Card Grid --}}
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.recipes.bulk-destroy') }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:18px;">
            @forelse($recipes as $recipe)
            <div class="cs-card" style="padding:0;overflow:hidden;transition:transform .18s,box-shadow .18s;position:relative;"
                 onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(37,99,235,0.12)'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">

                <!-- Selection Checkbox overlay -->
                <div style="position:absolute;top:10px;left:10px;z-index:30;background:rgba(255,255,255,0.9);padding:5px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;align-items:center;justify-content:center;border:1px solid #e2e8f0;">
                    <input type="checkbox" name="ids[]" value="{{ $recipe->id }}"
                           x-model="selectedRecipes"
                           style="width:16px;height:16px;cursor:pointer;accent-color:#2563eb;margin:0;">
                </div>

                {{-- Cover Image --}}
                <div style="position:relative;height:150px;overflow:hidden;background:#e0f2fe;">
                    @if($recipe->cover_image)
                    <img src="{{ $recipe->cover_image }}" alt="{{ $recipe->title }}"
                         style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#3b82f6,#6366f1);">
                        <span style="font-size:36px;font-weight:800;color:rgba(255,255,255,0.3);">
                            {{ strtoupper(substr($recipe->title,0,1)) }}
                        </span>
                    </div>
                    @endif

                    {{-- Status Badge (shifted to left:45px to not overlap with checkbox) --}}
                    <div style="position:absolute;top:10px;left:45px;">
                        <div x-data="{ open: false }" style="position:relative;">
                            <button @click="open = !open" type="button"
                                    class="badge badge-{{ $recipe->status }}"
                                    style="cursor:pointer;border:none;">
                                {{ ucfirst($recipe->status) }} ▾
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                 style="position:absolute;top:28px;left:0;z-index:20;border-radius:10px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.12);border:1px solid #e2e8f0;background:#fff;min-width:120px;">
                                @foreach(['draft','review','published'] as $s)
                                <button type="button"
                                        style="width:100%;text-align:left;padding:9px 14px;font-size:12px;font-weight:600;border:none;background:{{ $recipe->status===$s?'#eff6ff':'#fff' }};cursor:pointer;"
                                        onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='{{ $recipe->status===$s?'#eff6ff':'#fff' }}'"
                                        onclick="event.preventDefault(); 
                                                 document.getElementById('status-update-value').value = '{{ $s }}';
                                                 document.getElementById('status-update-form').action = '{{ route('admin.recipes.status', $recipe) }}';
                                                 document.getElementById('status-update-form').submit();">
                                    {{ ucfirst($s) }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Quick Action Buttons --}}
                    <div style="position:absolute;top:8px;right:8px;display:flex;gap:5px;">
                        <a href="{{ url('/recipes/'.$recipe->slug) }}" target="_blank"
                           class="icon-btn icon-btn-slate" title="View on site"
                           style="background:rgba(255,255,255,0.9);backdrop-filter:blur(4px);">
                            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Card Body --}}
                <div style="padding:14px 16px;">
                    <h3 style="font-size:13.5px;font-weight:700;color:#1e293b;margin-bottom:4px;line-height:1.3;">
                        {{ $recipe->title }}
                    </h3>
                    @if($recipe->excerpt)
                    <p style="font-size:11.5px;color:#94a3b8;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:10px;">
                        {{ $recipe->excerpt }}
                    </p>
                    @endif

                    <div style="display:flex;align-items:center;gap:12px;font-size:11.5px;color:#64748b;margin-bottom:12px;">
                        <span style="display:flex;align-items:center;gap:4px;">
                            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"/>
                            </svg>
                            {{ $recipe->prep_time_minutes + $recipe->cook_time_minutes }}min
                        </span>
                        <span style="display:flex;align-items:center;gap:4px;">
                            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $recipe->servings }} servings
                        </span>
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-size:11.5px;color:#94a3b8;">{{ $recipe->author?->name ?? '—' }}</span>
                        <div style="display:flex;gap:5px;">
                            <a href="{{ route('admin.recipes.edit', $recipe) }}" class="icon-btn icon-btn-blue" title="Edit">
                                <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button type="button" class="icon-btn icon-btn-red" title="Delete"
                                    onclick="if(confirm('Delete {{ addslashes($recipe->title) }}?')) { document.getElementById('delete-form-{{ $recipe->id }}').submit(); }">
                                <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#94a3b8;">
                <svg style="width:40px;height:40px;margin:0 auto 12px;opacity:0.3;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p style="font-size:14px;font-weight:600;margin-bottom:6px;">No recipes found</p>
                <a href="{{ route('admin.recipes.create') }}" style="color:#2563eb;font-size:13px;">Create your first recipe →</a>
            </div>
            @endforelse
        </div>
    </form>

    {{-- Individual Delete Forms --}}
    @foreach($recipes as $recipe)
    <form id="delete-form-{{ $recipe->id }}" method="POST" action="{{ route('admin.recipes.destroy', $recipe) }}" style="display:none;">
        @csrf @method('DELETE')
    </form>
    @endforeach

    {{-- Status Update Form --}}
    <form id="status-update-form" method="POST" action="" style="display:none;">
        @csrf @method('PATCH')
        <input type="hidden" name="status" id="status-update-value" value="">
    </form>

    {{-- Pagination --}}
    @if($recipes->hasPages())
    <div>{{ $recipes->links() }}</div>
    @endif

</div>
@endsection

@extends('layouts.app')

@section('title', $recipe->title . ' - ChefSync Recipes')

@section('seo_schema')
<script type="application/ld+json">
    {!! app(\App\Services\RecipeSchemaService::class)->generate($recipe) !!}
</script>
@endsection

@section('content')
@php
    $embedUrl = null;
    if (!empty($recipe->video_url)) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $recipe->video_url, $matches)) {
            $embedUrl = "https://www.youtube.com/embed/" . $matches[1];
        } else {
            $embedUrl = $recipe->video_url;
        }
    }
@endphp

<style>
    .recipe-article-content h1 {
        font-size: 1.875rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-left: 4px solid #10b981;
        padding-left: 0.75rem;
    }
    .recipe-article-content h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-left: 4px solid #10b981;
        padding-left: 0.75rem;
    }
    .recipe-article-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
    }
    .recipe-article-content p {
        margin-bottom: 1.25rem;
        line-height: 1.8;
        color: #475569;
    }
    .recipe-article-content ul {
        list-style-type: disc;
        margin-left: 1.5rem;
        margin-bottom: 1.25rem;
        color: #475569;
    }
    .recipe-article-content ol {
        list-style-type: decimal;
        margin-left: 1.5rem;
        margin-bottom: 1.25rem;
        color: #475569;
    }
    .recipe-article-content li {
        margin-bottom: 0.5rem;
    }
</style>

<div class="space-y-10">
    <!-- Breadcrumbs -->
    <nav class="flex items-center space-x-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
        <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Recipes</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        @if($recipe->category)
            <a href="{{ url('/') . '?category=' . $recipe->category->slug }}" class="hover:text-emerald-600 transition-colors">{{ $recipe->category->name }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        @endif
        <span class="text-slate-600 truncate">{{ $recipe->title }}</span>
    </nav>

    <!-- Header Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Title and Stats -->
        <div class="lg:col-span-7 space-y-6">
            <div class="space-y-3">
                @if($recipe->category)
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ $recipe->category->name }}
                    </span>
                @endif
                <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $recipe->title }}
                </h1>
            </div>
            
            <p class="text-base sm:text-lg text-slate-500 font-medium leading-relaxed">
                {{ $recipe->excerpt ?: 'Prepare this classic recipe at home. Fully detailed, with interactive portion control and step-by-step guides.' }}
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-5 bg-white border border-slate-100 rounded-3xl shadow-sm">
                <!-- Author -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-extrabold text-white shadow-md shadow-emerald-100 shrink-0">
                        {{ strtoupper(substr($recipe->author->name ?? 'HC', 0, 2)) }}
                    </div>
                    <div>
                        <span class="text-[10px] block text-slate-400 font-extrabold uppercase tracking-wider">Chef</span>
                        <span class="text-xs font-bold text-slate-900 block truncate">{{ $recipe->author->name ?? 'Head Chef' }}</span>
                    </div>
                </div>

                <!-- Prep Time -->
                <div class="border-l border-slate-100 pl-4">
                    <span class="text-[10px] block text-slate-400 font-extrabold uppercase tracking-wider">Prep Time</span>
                    <span class="text-sm font-extrabold text-slate-950">{{ $recipe->prep_time_minutes }} mins</span>
                </div>

                <!-- Cook Time -->
                <div class="border-l border-slate-100 pl-4">
                    <span class="text-[10px] block text-slate-400 font-extrabold uppercase tracking-wider">Cook Time</span>
                    <span class="text-sm font-extrabold text-slate-950">{{ $recipe->cook_time_minutes }} mins</span>
                </div>

                <!-- Servings -->
                <div class="border-l border-slate-100 pl-4">
                    <span class="text-[10px] block text-slate-400 font-extrabold uppercase tracking-wider">Servings</span>
                    <span class="text-sm font-extrabold text-slate-950">{{ $recipe->servings }} portions</span>
                </div>
            </div>
        </div>

        <!-- Cover Image -->
        <div class="lg:col-span-5 relative rounded-3xl overflow-hidden shadow-xl border border-slate-100 aspect-video lg:aspect-square bg-slate-100 w-full">
            <img 
                src="{{ $recipe->cover_image ?: 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=1200&auto=format&fit=crop' }}" 
                alt="{{ $recipe->title }}" 
                class="absolute inset-0 w-full h-full object-cover"
            />
        </div>
    </div>

    <!-- Video Integration -->
    @if($embedUrl)
        <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24">
                    <path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.508a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.871.508 9.388.508 9.388.508s7.517 0 9.388-.508a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                <span>Video Tutorial</span>
            </h3>
            
            <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-950 shadow-md">
                @if(str_contains($embedUrl, 'youtube.com/embed/'))
                    <iframe 
                        src="{{ $embedUrl }}" 
                        title="{{ $recipe->title }} Tutorial"
                        class="absolute inset-0 w-full h-full border-0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen
                    ></iframe>
                @elseif(str_ends_with($embedUrl, '.mp4') || str_ends_with($embedUrl, '.webm') || str_ends_with($embedUrl, '.ogg'))
                    <video 
                        src="{{ $embedUrl }}" 
                        controls 
                        class="absolute inset-0 w-full h-full"
                    ></video>
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 space-y-3 text-white">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                        </svg>
                        <div class="space-y-1">
                            <h4 class="font-bold text-sm">External Cooking Video Available</h4>
                            <p class="text-xs text-slate-400">Click below to watch the tutorial on the external platform.</p>
                        </div>
                        <a href="{{ $recipe->video_url }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-xs font-bold rounded-xl shadow-md transition-all">
                            Watch Video Tutorial
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Instructions and Scaler Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Steps -->
        <div class="lg:col-span-7 space-y-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Cooking Steps</span>
            </h2>

            <!-- Interactive Steps Checklist -->
            <div x-data="{ completedSteps: [] }" class="space-y-4">
                @forelse($recipe->steps as $step)
                    <div 
                        class="flex items-start space-x-4 p-5 bg-white border rounded-3xl transition-all duration-200"
                        :class="completedSteps.includes({{ $step->step_number }}) ? 'border-slate-100 bg-slate-50/50 opacity-60' : 'border-slate-100 shadow-sm'"
                    >
                        <button 
                            type="button"
                            @click="completedSteps.includes({{ $step->step_number }}) ? completedSteps = completedSteps.filter(s => s !== {{ $step->step_number }}) : completedSteps.push({{ $step->step_number }})"
                            class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 mt-0.5 transition-all duration-200"
                            :class="completedSteps.includes({{ $step->step_number }}) ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-emerald-500'"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" x-show="completedSteps.includes({{ $step->step_number }})">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        
                        <div class="space-y-3">
                            <span class="text-xs font-extrabold uppercase tracking-wider" :class="completedSteps.includes({{ $step->step_number }}) ? 'text-slate-400' : 'text-emerald-600'">
                                Step {{ $step->step_number }}
                            </span>
                            <p 
                                class="text-slate-700 text-sm sm:text-base font-medium leading-relaxed"
                                :class="completedSteps.includes({{ $step->step_number }}) ? 'line-through text-slate-400' : ''"
                            >
                                {{ $step->instruction }}
                            </p>
                            @if($step->media_url)
                                <img src="{{ $step->media_url }}" alt="Step image" class="rounded-xl max-h-48 object-cover border border-slate-100">
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm">No steps defined for this recipe.</p>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Interactive Portion Scaler -->
        <div class="lg:col-span-5 space-y-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Ingredients & Portions</span>
            </h2>

            <x-portion-scaler :recipe="$recipe" />
        </div>
    </div>

    <!-- Bottom HTML Article -->
    @if(!empty($recipe->description_html))
        <div class="bg-white border border-slate-100 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center space-x-2 border-b border-slate-100 pb-4">
                <span>📖 Culinary Journey & Secrets</span>
            </h2>
            <div class="recipe-article-content">
                {!! $recipe->description_html !!}
            </div>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'ChefSync - Discover & Scale Premium Recipes')

@section('content')
@php
    $currentCategory = request('category');
    $currentTime = request('time');
    $currentSearch = request('search');
@endphp

<div class="space-y-12">
    <!-- Premium Header Cover Hero Slideshow (100/30 aspect ratio style) -->
    <style>
        .hero-slider-container {
            position: relative !important;
            width: 100% !important;
            border-radius: 24px !important;
            overflow: hidden !important;
            background-color: #0f172a !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.1) !important;
            height: 320px !important; /* Mobile height */
        }
        @media (min-width: 768px) {
            .hero-slider-container {
                height: 380px !important; /* Elegant 100/30 height on desktop */
            }
        }
        .hero-slide {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }
        .hero-slide-bg {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            z-index: 1 !important;
        }
        .hero-slide-overlay {
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(to right, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.7) 45%, rgba(15, 23, 42, 0.1) 100%) !important;
            z-index: 2 !important;
        }
        @media (max-width: 767px) {
            .hero-slide-overlay {
                background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.75) 60%, rgba(15, 23, 42, 0.3) 100%) !important;
            }
        }
        .hero-slide-content {
            position: relative !important;
            z-index: 3 !important;
            max-width: 100% !important;
            padding: 32px !important;
            color: #ffffff !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            height: 100% !important;
            box-sizing: border-box !important;
        }
        @media (min-width: 768px) {
            .hero-slide-content {
                padding: 48px 64px !important;
                max-width: 60% !important;
            }
        }
        .hero-slide-content .tag {
            color: #34d399 !important; /* Emerald green accent */
            font-size: 11px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.15em !important;
            margin-bottom: 12px !important;
            display: inline-block !important;
        }
        .hero-slide-content .title {
            font-family: 'Playfair Display', Georgia, serif !important;
            font-size: 24px !important;
            font-weight: 700 !important;
            line-height: 1.25 !important;
            color: #ffffff !important;
            margin-bottom: 12px !important;
        }
        .hero-slide-content .title a {
            color: #ffffff !important;
            text-decoration: none !important;
            transition: color 0.2s !important;
        }
        .hero-slide-content .title a:hover {
            color: #34d399 !important;
        }
        @media (min-width: 768px) {
            .hero-slide-content .title {
                font-size: 36px !important;
            }
        }
        .hero-slide-content .subtitle {
            font-size: 13px !important;
            line-height: 1.6 !important;
            color: #cbd5e1 !important;
            margin-bottom: 24px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            font-weight: 400 !important;
            max-width: 520px !important;
        }
        .hero-slide-content .read-btn {
            background-color: #10b981 !important;
            color: #ffffff !important;
            text-decoration: none !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            padding: 10px 22px !important;
            border-radius: 12px !important;
            width: fit-content !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            transition: background-color 0.2s !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
        }
        .hero-slide-content .read-btn:hover {
            background-color: #059669 !important;
        }
        
        /* Overlaid Controls */
        .hero-slider-container .slider-controls {
            position: absolute !important;
            bottom: 24px !important;
            right: 24px !important;
            display: flex !important;
            align-items: center !important;
            gap: 16px !important;
            z-index: 10 !important;
        }
        @media (max-width: 767px) {
            .hero-slider-container .slider-controls {
                bottom: 16px !important;
                left: 32px !important;
                right: auto !important;
            }
        }
        .hero-slider-container .dot {
            width: 6px !important;
            height: 6px !important;
            border-radius: 50% !important;
            background-color: rgba(255, 255, 255, 0.4) !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
            padding: 0 !important;
        }
        .hero-slider-container .dot.active {
            background-color: #34d399 !important;
            width: 20px !important;
            border-radius: 10px !important;
        }
        .hero-slider-container .arrow {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            background-color: rgba(15, 23, 42, 0.6) !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
        }
        .hero-slider-container .arrow:hover {
            background-color: rgba(16, 185, 129, 0.9) !important;
            border-color: #10b981 !important;
        }
    </style>

    @if($slides->count() > 0)
    <div x-data="heroSlider" class="hero-slider-container">
        <!-- Slides Container -->
        <div class="relative w-full h-full">
            @foreach($slides as $index => $slide)
                <div 
                    x-show="activeSlide === {{ $index }}"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 scale-98"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-500 absolute inset-0"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-98"
                    data-slide-index="{{ $index }}"
                    class="hero-slide"
                >
                    <!-- Background Cover Image -->
                    <img 
                        src="{{ $slide->image_url ?: 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=1200&auto=format&fit=crop' }}" 
                        alt="{{ $slide->title }}" 
                        class="hero-slide-bg"
                    />
                    <div class="hero-slide-overlay"></div>

                    <!-- Centered/Left Overlaid Text Content -->
                    <div class="hero-slide-content">
                        <div>
                            <span class="tag">
                                {{ $slide->category_tag ?: 'FEATURED' }}
                            </span>
                        </div>

                        <h2 class="title">
                            @if($slide->link_url)
                                <a href="{{ $slide->link_url }}">
                                    {{ $slide->title }}
                                </a>
                            @else
                                {{ $slide->title }}
                            @endif
                        </h2>

                        @if($slide->subtitle)
                            <p class="subtitle">
                                {{ $slide->subtitle }}
                            </p>
                        @endif

                        @if($slide->link_url)
                            <a href="{{ $slide->link_url }}" class="read-btn">
                                <span>Read Post / Recipe</span>
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Overlaid Controls (Dots and Arrows) -->
        <div class="slider-controls">
            <!-- Dots -->
            <div class="flex items-center space-x-2">
                @foreach($slides as $index => $slide)
                    <button 
                        @click="goToSlide({{ $index }})" 
                        :class="activeSlide === {{ $index }} ? 'dot active' : 'dot'"
                        aria-label="Go to slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>

            <!-- Arrow buttons -->
            <div class="flex items-center space-x-2">
                <button 
                    @click="prevSlide()" 
                    class="arrow"
                    aria-label="Previous slide"
                >
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button 
                    @click="nextSlide()" 
                    class="arrow"
                    aria-label="Next slide"
                >
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Search & Welcome Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1.5 text-center md:text-left">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider">
                🔥 Join 5.2 Million Food Lovers
            </span>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                Looking for something delicious?
            </h2>
            <p class="text-slate-400 text-xs font-medium">
                Adjust portions dynamically, scale ingredients instantly, and cook like a professional.
            </p>
        </div>

        <!-- Search Form -->
        <form action="{{ url('/') }}" method="GET" class="w-full md:w-auto md:flex-grow max-w-xl">
            @if($currentCategory)
                <input type="hidden" name="category" value="{{ $currentCategory }}">
            @endif
            @if($currentTime)
                <input type="hidden" name="time" value="{{ $currentTime }}">
            @endif
            
            <div class="relative flex items-center bg-slate-50 rounded-2xl p-1.5 border border-slate-200/80 text-slate-800 focus-within:bg-white focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100 transition-all">
                <div class="pl-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $currentSearch }}"
                    placeholder="Search recipes, ingredients, cuisines..." 
                    class="w-full pl-3 pr-4 py-2.5 bg-transparent border-0 focus:ring-0 text-slate-900 placeholder-slate-400 font-medium focus:outline-none text-sm"
                />
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-all shadow-md cursor-pointer">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Active Filters & Category Navigation -->
    <div class="space-y-6">
        <!-- Horizontal Category Pills -->
        <div class="border-b border-slate-100 pb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Browse by Cuisine</h3>
                @if($currentCategory || $currentTime || $currentSearch)
                    <a href="{{ url('/') }}" class="text-xs font-bold text-red-500 hover:text-red-600 flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Clear All Filters</span>
                    </a>
                @endif
            </div>

            <!-- Categories Scroller -->
            <div class="flex items-center space-x-3 overflow-x-auto no-scrollbar py-2">
                <a 
                    href="{{ url('/') . '?' . http_build_query(array_filter(array_merge(request()->all(), ['category' => null]))) }}" 
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all shrink-0 {{ !$currentCategory ? 'bg-emerald-600 text-white shadow-md shadow-emerald-100' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}"
                >
                    🌍 All Cuisines
                </a>
                @foreach($categories as $category)
                    <a 
                        href="{{ url('/') . '?' . http_build_query(array_merge(request()->all(), ['category' => $category->slug])) }}" 
                        class="px-5 py-2.5 rounded-full text-sm font-bold transition-all shrink-0 flex items-center space-x-2 {{ $currentCategory === $category->slug ? 'bg-emerald-600 text-white shadow-md shadow-emerald-100' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}"
                    >
                        <span>
                            @if($category->slug === 'breakfast-brunch') 🍳
                            @elseif($category->slug === 'appetizers-salads') 🥗
                            @elseif($category->slug === 'main-courses') 🍝
                            @elseif($category->slug === 'side-dishes') 🍟
                            @elseif($category->slug === 'desserts') 🍰
                            @else 🍽️
                            @endif
                        </span>
                        <span>{{ $category->name }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded-full {{ $currentCategory === $category->slug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                            {{ $category->recipes_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Cooking Time Filter Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-100">
            <div class="flex items-center space-x-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Time Limit:</span>
                <div class="flex items-center space-x-1.5">
                    <a 
                        href="{{ url('/') . '?' . http_build_query(array_filter(array_merge(request()->all(), ['time' => null]))) }}"
                        class="px-3.5 py-1.5 text-xs font-bold rounded-lg border {{ !$currentTime ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-slate-300' }}"
                    >
                        Any Time
                    </a>
                    <a 
                        href="{{ url('/') . '?' . http_build_query(array_merge(request()->all(), ['time' => 'quick'])) }}"
                        class="px-3.5 py-1.5 text-xs font-bold rounded-lg border {{ $currentTime === 'quick' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-slate-300' }}"
                    >
                        ⚡ Under 30 Mins
                    </a>
                    <a 
                        href="{{ url('/') . '?' . http_build_query(array_merge(request()->all(), ['time' => 'medium'])) }}"
                        class="px-3.5 py-1.5 text-xs font-bold rounded-lg border {{ $currentTime === 'medium' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-slate-300' }}"
                    >
                        🕒 30 - 60 Mins
                    </a>
                    <a 
                        href="{{ url('/') . '?' . http_build_query(array_merge(request()->all(), ['time' => 'slow'])) }}"
                        class="px-3.5 py-1.5 text-xs font-bold rounded-lg border {{ $currentTime === 'slow' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-slate-300' }}"
                    >
                        🍳 Over 1 Hour
                    </a>
                </div>
            </div>

            <div class="text-xs font-semibold text-slate-400 sm:text-right">
                Showing {{ $recipes->firstItem() ?? 0 }} to {{ $recipes->lastItem() ?? 0 }} of {{ $recipes->total() }} delicious recipes
            </div>
        </div>
    </div>

    <!-- Recipe Feed Grid -->
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($recipes as $recipe)
                <div class="group bg-white border border-slate-100 hover:border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                    <!-- Cover image -->
                    <div class="relative h-56 bg-slate-100 overflow-hidden">
                        <img 
                            src="{{ $recipe->cover_image ?: 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=1200&auto=format&fit=crop' }}" 
                            alt="{{ $recipe->title }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        
                        <!-- Overlay gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-black/10 to-transparent"></div>
                        
                        <!-- Category Badge -->
                        @if($recipe->category)
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/95 text-slate-900 backdrop-blur-sm shadow-sm">
                                    {{ $recipe->category->name }}
                                </span>
                            </div>
                        @endif

                        <!-- Video Badge -->
                        @if($recipe->video_url)
                            <div class="absolute top-4 right-4">
                                <span class="w-8 h-8 rounded-full bg-emerald-500/90 text-white flex items-center justify-center backdrop-blur-sm shadow-md">
                                    <svg class="w-4 h-4 fill-current ml-0.5" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </span>
                            </div>
                        @endif

                        <!-- Title and Excerpt on Image for premium touch -->
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest block mb-0.5">
                                @if(($recipe->prep_time_minutes + $recipe->cook_time_minutes) <= 30)
                                    ⚡ QUICK RECIPE
                                @else
                                    🌟 POPULAR CHOICE
                                @endif
                            </span>
                            <h3 class="text-lg font-bold line-clamp-1 leading-tight group-hover:text-emerald-300 transition-colors">
                                {{ $recipe->title }}
                            </h3>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col flex-grow space-y-4">
                        <p class="text-slate-500 text-sm font-medium line-clamp-3 leading-relaxed">
                            {{ $recipe->excerpt ?: 'Indulge in a premium cooking experience with this chef-curated recipe, complete with scaled portion ingredients and step-by-step instructions.' }}
                        </p>

                        <!-- Stats and links -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs font-bold text-slate-600 mt-auto">
                            <div class="flex items-center space-x-3.5">
                                <span class="flex items-center space-x-1.5 text-slate-500">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $recipe->prep_time_minutes + $recipe->cook_time_minutes }} mins</span>
                                </span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                                <span class="flex items-center space-x-1.5 text-slate-500">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>{{ $recipe->servings }} portions</span>
                                </span>
                            </div>
                            
                            <a 
                                href="{{ url('/recipes/' . $recipe->slug) }}" 
                                class="inline-flex items-center space-x-1 text-emerald-600 hover:text-emerald-700 transition-colors group/link"
                            >
                                <span>Cook Now</span>
                                <svg class="w-4 h-4 transform group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-slate-100 rounded-3xl p-16 text-center space-y-4 shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto border border-slate-100">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="space-y-2 max-w-sm mx-auto">
                        <h3 class="text-xl font-bold text-slate-900">No matching recipes</h3>
                        <p class="text-slate-400 text-sm">
                            We couldn't find any recipes matching your current filter settings. Try adjusting your cuisine category or time limits.
                        </p>
                        <a href="{{ url('/') }}" class="inline-block mt-4 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                            View All Recipes
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($recipes->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('heroSlider', () => ({
            activeSlide: 0,
            slidesCount: 0,
            autoplayInterval: null,
            init() {
                this.slidesCount = this.$el.querySelectorAll('[data-slide-index]').length;
                this.startAutoplay();
            },
            startAutoplay() {
                this.stopAutoplay();
                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, 6000);
            },
            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                }
            },
            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
            },
            prevSlide() {
                this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
            },
            goToSlide(index) {
                this.activeSlide = index;
                this.startAutoplay();
            }
        }));
    });
</script>
@endsection

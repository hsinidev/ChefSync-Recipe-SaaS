@extends('layouts.admin')

@section('title', 'Create Recipe')
@section('page-title', 'Create Recipe')

@section('content')
@php
    $ingredientsData = old('ingredients');
    if (empty($ingredientsData)) {
        $ingredientsData = [['name' => '', 'amount' => '', 'unit' => 'g', 'state' => '']];
    }
    $ingredientsJson = json_encode($ingredientsData);

    $stepsData = old('steps');
    if (empty($stepsData)) {
        $stepsData = [['instruction' => '']];
    }
    $stepsJson = json_encode($stepsData);
@endphp

<div class="max-w-5xl" x-data="recipeForm">

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.recipes.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition-all hover:text-blue-600"
           style="color: var(--slate-505); text-decoration: none;">
            <svg class="w-4 h-4" width="16" height="16" style="width:16px; height:16px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Recipes
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

    {{-- Main Form --}}
    <form method="POST" action="{{ route('admin.recipes.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr; gap: 30px;" class="lg:grid-cols-3 lg:items-start">
            
            {{-- Left Side: Main Fields (2 columns wide on desktop) --}}
            <div style="display: flex; flex-direction: column; gap: 30px;" class="lg:col-span-2">
                
                {{-- Basic Information --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Basic Information</h2>
                            <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Core details of your culinary creation</p>
                        </div>
                        <button type="button" @click="generateFromAI()" id="ai-generate-btn" class="btn-primary" style="padding: 6px 14px; font-size: 12px; background: linear-gradient(135deg, var(--blue-600) 0%, #1e40af 100%);">
                            ✨ AI Generate
                        </button>
                    </div>

                    <div>
                        <label class="cs-label">Title *</label>
                        <input type="text" name="title" x-model="title" required placeholder="e.g. Classic Beef Bourguignon" class="cs-input">
                    </div>

                    <div>
                        <label class="cs-label">Excerpt</label>
                        <textarea name="excerpt" x-model="excerpt" rows="2" placeholder="Short description of the recipe..." class="cs-input" style="resize: none;"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr);" class="sm:grid-cols-5 gap-4">
                        <div>
                            <label class="cs-label">Prep Time (min) *</label>
                            <input type="number" name="prep_time_minutes" x-model="prep_time" min="0" required class="cs-input">
                        </div>
                        <div>
                            <label class="cs-label">Cook Time (min) *</label>
                            <input type="number" name="cook_time_minutes" x-model="cook_time" min="0" required class="cs-input">
                        </div>
                        <div>
                            <label class="cs-label">Servings *</label>
                            <input type="number" name="servings" x-model="servings" min="1" required class="cs-input">
                        </div>
                        <div>
                            <label class="cs-label">Category</label>
                            <select name="category_id" class="cs-input" style="-webkit-appearance: none; background-color: var(--slate-50);">
                                <option value="">No Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="cs-label">Status *</label>
                            <select name="status" required class="cs-input" style="-webkit-appearance: none; background-color: var(--slate-50);">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="review" {{ old('status') === 'review' ? 'selected' : '' }}>Review</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Detailed Recipe Article (HTML) --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Detailed Recipe Article (HTML)</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Rich article body at the bottom of the recipe detail page</p>
                    </div>
                    <div>
                        <textarea name="description_html" x-model="description_html" rows="10" placeholder="<h2>Introduction</h2><p>Describe the origin of this dish...</p>" class="cs-input" style="font-family: monospace; font-size: 13px;"></textarea>
                    </div>
                </div>

                {{-- Ingredients Dynamic Section --}}
                <div class="cs-card" style="padding: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Ingredients</h2>
                            <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Add ingredients required for the recipe</p>
                        </div>
                        <button type="button" @click="addIngredient()" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                            + Add Row
                        </button>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <template x-for="(ing, index) in ingredients" :key="index">
                            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: var(--slate-50); border-radius: 12px; border: 1px solid var(--slate-200);">
                                <div style="flex: 2;">
                                    <input type="text" :name="`ingredients[${index}][name]`" x-model="ing.name" required placeholder="Name (e.g. Flour)" class="cs-input" style="background:#fff; padding: 8px 12px;">
                                </div>
                                <div style="width: 80px;">
                                    <input type="number" step="any" :name="`ingredients[${index}][amount]`" x-model="ing.amount" required placeholder="Qty" class="cs-input" style="background:#fff; padding: 8px 12px;">
                                </div>
                                <div style="width: 100px;">
                                    <input type="text" :name="`ingredients[${index}][unit]`" x-model="ing.unit" required placeholder="Unit (e.g. g)" class="cs-input" style="background:#fff; padding: 8px 12px;">
                                </div>
                                <div style="flex: 1.2;">
                                    <input type="text" :name="`ingredients[${index}][state]`" x-model="ing.state" placeholder="State (e.g. sifted)" class="cs-input" style="background:#fff; padding: 8px 12px;">
                                </div>
                                <button type="button" @click="removeIngredient(index)" class="icon-btn icon-btn-red" style="flex-shrink:0;">
                                    <svg width="14" height="14" style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Instructions Dynamic Section --}}
                <div class="cs-card" style="padding: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Instructions / Steps</h2>
                            <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">List instructions in sequential order</p>
                        </div>
                        <button type="button" @click="addStep()" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                            + Add Step
                        </button>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <template x-for="(step, index) in steps" :key="index">
                            <div style="display: flex; gap: 12px; align-items: flex-start; padding: 12px; background: var(--slate-50); border-radius: 12px; border: 1px solid var(--slate-200);">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--blue-500); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; margin-top: 4px;" x-text="index + 1">
                                </div>
                                <div style="flex: 1;">
                                    <textarea :name="`steps[${index}][instruction]`" x-model="step.instruction" required rows="2" placeholder="Describe this cooking step..." class="cs-input" style="background:#fff;"></textarea>
                                </div>
                                <button type="button" @click="removeStep(index)" class="icon-btn icon-btn-red" style="flex-shrink:0; margin-top: 4px;">
                                    <svg width="14" height="14" style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- Right Side: Media, Video & Gallery (1 column wide on desktop) --}}
            <div style="display: flex; flex-direction: column; gap: 30px;">
                
                {{-- Hero / Cover Image --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Hero Image</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Main presentation image for catalogs</p>
                    </div>

                    <div>
                        <label class="cs-label">Upload Hero Image</label>
                        <input type="file" name="cover_image" accept="image/*" class="cs-input" style="font-size: 12px; padding: 8px;">
                    </div>

                    <div>
                        <label class="cs-label">Or Use Image URL</label>
                        <input type="text" name="cover_image_url" placeholder="https://unsplash.com/..." class="cs-input">
                    </div>
                </div>

                {{-- Video Integration --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Cooking Video</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">YouTube, Vimeo or direct video link</p>
                    </div>

                    <div>
                        <label class="cs-label">Video URL</label>
                        <input type="text" name="video_url" placeholder="e.g. https://www.youtube.com/watch?v=..." class="cs-input">
                    </div>
                </div>

                {{-- Gallery / Other Images --}}
                <div class="cs-card" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--slate-900);">Other Gallery Images</h2>
                        <p style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">Additional photos showing steps or results</p>
                    </div>

                    <div>
                        <label class="cs-label">Upload Gallery Images</label>
                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="cs-input" style="font-size: 12px; padding: 8px;">
                    </div>
                </div>

            </div>

        </div>

        {{-- Form Actions Footer --}}
        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--slate-200); padding-top: 24px; margin-top: 40px;">
            <div class="flex gap-3">
                <button type="submit" class="btn-primary" style="padding: 10px 24px;">
                    Create Recipe
                </button>
                <a href="{{ route('admin.recipes.index') }}" class="btn-secondary" style="padding: 10px 24px; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </div>
    </form>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('recipeForm', () => ({
        title: {!! json_encode(old('title', '')) !!},
        excerpt: {!! json_encode(old('excerpt', '')) !!},
        prep_time: {{ old('prep_time_minutes', 15) }},
        cook_time: {{ old('cook_time_minutes', 30) }},
        servings: {{ old('servings', 4) }},
        description_html: {!! json_encode(old('description_html', '')) !!},
        ingredients: {!! $ingredientsJson !!},
        steps: {!! $stepsJson !!},
        addIngredient() { this.ingredients.push({ name: '', amount: '', unit: 'g', state: '' }); },
        removeIngredient(index) { if (this.ingredients.length !== 1) { this.ingredients.splice(index, 1); } },
        addStep() { this.steps.push({ instruction: '' }); },
        removeStep(index) { if (this.steps.length !== 1) { this.steps.splice(index, 1); } },
        async generateFromAI() {
            if (!this.title.trim()) {
                alert('Please enter a recipe title first.');
                return;
            }
            const btn = document.getElementById('ai-generate-btn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '✨ Generating...';
            
            try {
                const response = await fetch('{{ route('admin.recipes.generate-ai') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ title: this.title })
                });
                
                const resData = await response.json();
                if (!response.ok) {
                    throw new Error(resData.error || 'Server error occurred.');
                }
                
                // Populate fields
                this.excerpt = resData.excerpt || '';
                this.prep_time = resData.prep_time_minutes || 15;
                this.cook_time = resData.cook_time_minutes || 30;
                this.servings = resData.servings || 4;
                this.description_html = resData.description_html || '';
                if (resData.ingredients && resData.ingredients.length) {
                    this.ingredients = resData.ingredients;
                }
                if (resData.steps && resData.steps.length) {
                    this.steps = resData.steps;
                }
                alert('Recipe generated successfully!');
            } catch (error) {
                alert('AI Generation Failed: ' + error.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    }));
});
</script>
@endsection

@props(['recipe'])

<div x-data="{
    servings: {{ $recipe->servings ?? 4 }},
    baseServings: {{ $recipe->servings ?? 4 }},
    checkedIngredients: [],
    ingredients: [
        @foreach($recipe->ingredients as $ing)
            { name: '{{ addslashes($ing->name) }}', qty: {{ $ing->amount }}, unit: '{{ addslashes($ing->unit) }}', state: '{{ addslashes($ing->state ?? '') }}' },
        @endforeach
    ],
    formatFraction(val) {
        if (val % 1 === 0) return val.toString();
        const tolerance = 0.05;
        const decimal = val % 1;
        const integer = Math.floor(val);
        let fraction = '';
        
        if (Math.abs(decimal - 0.25) < tolerance) fraction = '1/4';
        else if (Math.abs(decimal - 0.33) < tolerance || Math.abs(decimal - 0.3) < tolerance) fraction = '1/3';
        else if (Math.abs(decimal - 0.5) < tolerance) fraction = '1/2';
        else if (Math.abs(decimal - 0.66) < tolerance || Math.abs(decimal - 0.7) < tolerance) fraction = '2/3';
        else if (Math.abs(decimal - 0.75) < tolerance) fraction = '3/4';
        
        if (fraction) {
            return integer > 0 ? `${integer} ${fraction}` : fraction;
        }
        return val.toFixed(1).replace('.0', '');
    }
}" class="w-full p-6 bg-white border border-slate-200/80 rounded-3xl shadow-sm space-y-6">
    
    <!-- Title & Adjuster -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-sm font-extrabold text-slate-900 tracking-wider uppercase">Ingredients</h3>
            <p class="text-xs text-slate-400 font-medium">Click to cross off as you prep</p>
        </div>
        
        <div class="flex items-center space-x-2 bg-slate-50 p-1 rounded-xl border border-slate-200/50">
            <button 
                type="button"
                @click="if(servings > 1) { servings--; checkedIngredients = []; }" 
                class="w-7 h-7 flex items-center justify-center text-slate-700 bg-white hover:bg-slate-100 rounded-lg shadow-xs transition-all active:scale-95 text-sm font-bold border border-slate-200/30 cursor-pointer">
                −
            </button>
            <span x-text="servings" class="text-xs font-extrabold text-slate-900 w-8 text-center"></span>
            <button 
                type="button"
                @click="servings++; checkedIngredients = [];" 
                class="w-7 h-7 flex items-center justify-center text-slate-700 bg-white hover:bg-slate-100 rounded-lg shadow-xs transition-all active:scale-95 text-sm font-bold border border-slate-200/30 cursor-pointer">
                +
            </button>
        </div>
    </div>

    <!-- Modern Checkable Ingredients List -->
    <ul class="space-y-4">
        <template x-for="(ing, index) in ingredients" :key="index">
            <li 
                @click="checkedIngredients.includes(index) ? checkedIngredients = checkedIngredients.filter(i => i !== index) : checkedIngredients.push(index)"
                class="flex items-start space-x-3.5 group cursor-pointer select-none py-0.5"
            >
                <!-- Checkbox -->
                <div class="flex items-center justify-center mt-0.5 shrink-0">
                    <div 
                        class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                        :class="checkedIngredients.includes(index) ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 group-hover:border-emerald-500'"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24" x-show="checkedIngredients.includes(index)">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <!-- Ingredient Item Description -->
                <div 
                    class="text-sm leading-relaxed transition-all duration-200 flex-grow"
                    :class="checkedIngredients.includes(index) ? 'line-through text-slate-400/85' : 'text-slate-800'"
                >
                    <!-- Quantity Badge -->
                    <span 
                        class="font-extrabold mr-2 transition-colors inline-block"
                        :class="checkedIngredients.includes(index) ? 'text-slate-400 bg-slate-100 border border-slate-200/60 px-1.5 py-0.5 rounded-md' : 'text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-1.5 py-0.5 rounded-md'"
                    >
                        <span x-text="formatFraction((ing.qty * servings) / baseServings)"></span>
                        <span x-text="ing.unit" class="text-xs font-semibold ml-0.5"></span>
                    </span>
                    
                    <!-- Ingredient Name & State -->
                    <span class="font-semibold text-slate-800" :class="checkedIngredients.includes(index) ? 'text-slate-400' : 'text-slate-800'">
                        <span x-text="ing.name"></span>
                    </span>
                    <template x-if="ing.state">
                        <span class="text-xs text-slate-400 font-medium italic block sm:inline sm:ml-1">
                            — <span x-text="ing.state"></span>
                        </span>
                    </template>
                </div>
            </li>
        </template>
    </ul>
</div>

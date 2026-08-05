<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MoroccanRecipesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or Create Tenant
        $tenant = Tenant::where('domain', 'localhost')->first();
        if (!$tenant) {
            $tenantUuid = Str::uuid()->getBytes();
            $tenant = Tenant::create([
                'uuid'            => $tenantUuid,
                'name'            => 'Chef Hsin-I Culinary Blog',
                'domain'          => 'localhost',
                'uses_isolated_db'=> false,
                'billing_plan'    => 'enterprise',
            ]);
        }
        $tenantUuid = $tenant->uuid;

        // Set global tenant context for models
        TenantScope::setTenantId($tenantUuid);

        // 2. Get or Create Chef User
        $user = User::where('email', 'hsini@recipes.hsini.dev')->first();
        if (!$user) {
            $user = User::factory()->create([
                'name'  => 'Chef Hsin-I',
                'email' => 'hsini@recipes.hsini.dev',
            ]);
        }

        // 3. Resolve Categories
        $categories = Category::all()->keyBy('slug');

        // 20 Moroccan Recipes Data
        $recipes = [
            [
                'meta' => [
                    'title'            => 'Moroccan Lamb Tagine',
                    'slug'             => 'moroccan-lamb-tagine',
                    'excerpt'          => 'A classic sweet and savory slow-cooked tagine featuring tender lamb, caramelized prunes, toasted almonds, and fragrant spices.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1541518763669-27fef04b14ea?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 120,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1000,'unit'=>'g',  'name'=>'Lamb Shoulder',            'state'=>'cut into chunks'],
                    ['sort_order'=>1,'amount'=>2,   'unit'=>'pcs','name'=>'Yellow Onions',           'state'=>'finely chopped'],
                    ['sort_order'=>2,'amount'=>200, 'unit'=>'g',  'name'=>'Pitted Prunes',           'state'=>null],
                    ['sort_order'=>3,'amount'=>50,  'unit'=>'g',  'name'=>'Blanched Almonds',        'state'=>'toasted'],
                    ['sort_order'=>4,'amount'=>1,   'unit'=>'tsp','name'=>'Ground Ginger',           'state'=>null],
                    ['sort_order'=>5,'amount'=>1,   'unit'=>'tsp','name'=>'Ground Cinnamon',         'state'=>null],
                    ['sort_order'=>6,'amount'=>0.5, 'unit'=>'tsp','name'=>'Saffron Threads',         'state'=>'crushed'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Marinate the lamb in olive oil, ginger, saffron, salt, pepper, and a bit of water for 1 hour.'],
                    ['step_number'=>2,'instruction'=>'Sauté onions in a tagine or heavy pot, add the lamb, and brown on all sides.'],
                    ['step_number'=>3,'instruction'=>'Cover with water, bring to a simmer, cover, and cook on low heat for 1.5 hours until tender.'],
                    ['step_number'=>4,'instruction'=>'Simmer prunes separately in water with cinnamon and honey until caramelized.'],
                    ['step_number'=>5,'instruction'=>'Top tagine with the caramelized prunes and toasted almonds. Serve warm with crusty bread.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Moroccan Chicken Pastilla',
                    'slug'             => 'moroccan-chicken-pastilla',
                    'excerpt'          => 'A magnificent spiced chicken pie made of paper-thin pastry layers, sweet almonds, and a dusting of cinnamon and sugar.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1608039755401-742074f0548d?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 45,
                    'cook_time_minutes'=> 60,
                    'servings'         => 8,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1000,'unit'=>'g',  'name'=>'Chicken Pieces',          'state'=>'skinless'],
                    ['sort_order'=>1,'amount'=>12,  'unit'=>'sheets','name'=>'Warqa or Phyllo Pastry', 'state'=>null],
                    ['sort_order'=>2,'amount'=>4,   'unit'=>'pcs','name'=>'Eggs',                    'state'=>'beaten'],
                    ['sort_order'=>3,'amount'=>150, 'unit'=>'g',  'name'=>'Almonds',                 'state'=>'fried and coarsely ground'],
                    ['sort_order'=>4,'amount'=>2,   'unit'=>'tbsp','name'=>'Orange Blossom Water',    'state'=>null],
                    ['sort_order'=>5,'amount'=>2,   'unit'=>'tbsp','name'=>'Powdered Sugar & Cinnamon','state'=>'for dusting'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Cook chicken with onions, ginger, saffron, turmeric, butter, and parsley until tender. Shred chicken.'],
                    ['step_number'=>2,'instruction'=>'Reduce cooking liquid and slowly stir in beaten eggs to form a soft, spiced curd.'],
                    ['step_number'=>3,'instruction'=>'Mix ground fried almonds with sugar, cinnamon, and orange blossom water.'],
                    ['step_number'=>4,'instruction'=>'Layer phyllo sheets in a round pie dish, brush with melted butter. Fill with layers of chicken, egg mixture, and almond mix.'],
                    ['step_number'=>5,'instruction'=>'Fold pastry over, seal with butter, and bake at 190°C until golden and crisp. Dust with powdered sugar and cinnamon.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Seven Vegetable Couscous',
                    'slug'             => 'seven-vegetable-couscous',
                    'excerpt'          => 'The ultimate Moroccan comfort dish: steamed semolina topped with tender beef and seven slow-simmered vegetables.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1541518763669-27fef04b14ea?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 90,
                    'servings'         => 8,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',  'name'=>'Couscous Semolina',       'state'=>null],
                    ['sort_order'=>1,'amount'=>800,'unit'=>'g',  'name'=>'Beef Chuck',              'state'=>'cut in large pieces'],
                    ['sort_order'=>2,'amount'=>3,  'unit'=>'pcs','name'=>'Carrots & Zucchini',       'state'=>'halved lengthwise'],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'pcs','name'=>'Turnips & Potatoes',       'state'=>'peeled and quartered'],
                    ['sort_order'=>4,'amount'=>150,'unit'=>'g',  'name'=>'Cabbage & Pumpkin',       'state'=>'sliced thick'],
                    ['sort_order'=>5,'amount'=>1,  'unit'=>'cup','name'=>'Canned Chickpeas',         'state'=>'drained'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'In a couscoussier, brown beef with onions, ginger, turmeric, and pepper. Cover with water and simmer.'],
                    ['step_number'=>2,'instruction'=>'Prepare couscous grains with water and oil. Place in the top steamer basket over the boiling meat broth.'],
                    ['step_number'=>3,'instruction'=>'Add carrots, turnips, and cabbage to the broth. Steam couscous for 20 minutes, then remove and fluff with cold salted water.'],
                    ['step_number'=>4,'instruction'=>'Return couscous to steamer. Add zucchini, pumpkin, and chickpeas to broth. Steam another 20 minutes.'],
                    ['step_number'=>5,'instruction'=>'Pile fluffed couscous on a large platter, make a well, arrange beef and vegetables on top, and moisten generously with broth.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Classic Moroccan Harira',
                    'slug'             => 'classic-moroccan-harira',
                    'excerpt'          => 'A rich, fragrant tomato-based soup with lentils, chickpeas, beef, and fine vermicelli, traditionally served during Ramadan.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 25,
                    'cook_time_minutes'=> 60,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>200,'unit'=>'g',  'name'=>'Beef or Lamb',             'state'=>'diced very small'],
                    ['sort_order'=>1,'amount'=>100,'unit'=>'g',  'name'=>'Brown Lentils',            'state'=>'rinsed'],
                    ['sort_order'=>2,'amount'=>150,'unit'=>'g',  'name'=>'Canned Chickpeas',         'state'=>null],
                    ['sort_order'=>3,'amount'=>500,'unit'=>'g',  'name'=>'Crushed Tomatoes',        'state'=>null],
                    ['sort_order'=>4,'amount'=>50, 'unit'=>'g',  'name'=>'Vermicelli Pasta',         'state'=>null],
                    ['sort_order'=>5,'amount'=>1,  'unit'=>'bunch','name'=>'Fresh Cilantro & Celery', 'state'=>'finely chopped'],
                    ['sort_order'=>6,'amount'=>3,  'unit'=>'tbsp','name'=>'Flour',                   'state'=>'for thickening paste'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Sauté meat, onion, celery, cilantro, and parsley with ginger, turmeric, and cinnamon.'],
                    ['step_number'=>2,'instruction'=>'Add crushed tomatoes, lentils, and water. Cover and cook until lentils are soft (approx. 40 minutes).'],
                    ['step_number'=>3,'instruction'=>'Add chickpeas and vermicelli, cooking for another 10 minutes.'],
                    ['step_number'=>4,'instruction'=>'Mix flour with water to create a smooth paste (tedouira) and slowly pour into the bubbling soup to thicken.'],
                    ['step_number'=>5,'instruction'=>'Simmer for 5 more minutes, finish with a squeeze of fresh lemon juice, and serve.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Moroccan Mint Tea',
                    'slug'             => 'moroccan-mint-tea',
                    'excerpt'          => 'The absolute staple of hospitality: gunpowder green tea brewed with fresh spearmint leaves and sweet sugar.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 5,
                    'cook_time_minutes'=> 10,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>2,  'unit'=>'tbsp','name'=>'Gunpowder Green Tea',      'state'=>null],
                    ['sort_order'=>1,'amount'=>1,  'unit'=>'bunch','name'=>'Fresh Spearmint Leaves',  'state'=>'washed'],
                    ['sort_order'=>2,'amount'=>4,  'unit'=>'tbsp','name'=>'Sugar',                   'state'=>null],
                    ['sort_order'=>3,'amount'=>800,'unit'=>'ml',  'name'=>'Boiling Water',           'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Add tea leaves to a Moroccan teapot. Pour in a splash of boiling water, swirl, and pour out the water to wash the leaves.'],
                    ['step_number'=>2,'instruction'=>'Fill the teapot with boiling water and let the tea steep on a low flame for 2-3 minutes.'],
                    ['step_number'=>3,'instruction'=>'Add sugar and the fresh mint leaves, pushing them down into the liquid.'],
                    ['step_number'=>4,'instruction'=>'Pour a glass of tea and pour it back into the teapot. Repeat 2-3 times to dissolve and mix the sugar.'],
                    ['step_number'=>5,'instruction'=>'Pour into glasses from high above to create a frothy crown (regga) on top of each glass.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Smoky Zaalouk',
                    'slug'             => 'smoky-zaalouk',
                    'excerpt'          => 'A wonderful cooked dip made of eggplants and tomatoes, seasoned with olive oil, garlic, cumin, and sweet paprika.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1592417817098-8f3d6eb19675?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 25,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>2,  'unit'=>'pcs','name'=>'Large Eggplants',          'state'=>'peeled and cubed'],
                    ['sort_order'=>1,'amount'=>3,  'unit'=>'pcs','name'=>'Ripe Tomatoes',            'state'=>'peeled and chopped'],
                    ['sort_order'=>2,'amount'=>3,  'unit'=>'pcs','name'=>'Garlic Cloves',            'state'=>'minced'],
                    ['sort_order'=>3,'amount'=>60, 'unit'=>'ml', 'name'=>'Extra Virgin Olive Oil',   'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp','name'=>'Ground Cumin & Paprika',   'state'=>null],
                    ['sort_order'=>5,'amount'=>20, 'unit'=>'g',  'name'=>'Fresh Cilantro & Parsley', 'state'=>'chopped'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Boil or steam eggplant cubes until tender. Drain and press out excess water.'],
                    ['step_number'=>2,'instruction'=>'In a skillet, combine tomatoes, garlic, olive oil, spices, salt, and herbs. Cook until soft.'],
                    ['step_number'=>3,'instruction'=>'Add eggplant to the tomato mixture. Mash them together using the back of a fork.'],
                    ['step_number'=>4,'instruction'=>'Cook uncovered, stirring occasionally, until all liquids evaporate and the dip is thick.'],
                    ['step_number'=>5,'instruction'=>'Serve warm or cold, drizzled with olive oil, alongside pita or crusty bread.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Classic Taktouka',
                    'slug'             => 'classic-taktouka',
                    'excerpt'          => 'A refreshing and colorful Moroccan cooked salad consisting of bell peppers, ripe tomatoes, garlic, and fresh herbs.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 20,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>3,  'unit'=>'pcs','name'=>'Bell Peppers (Green & Red)', 'state'=>'roasted and peeled'],
                    ['sort_order'=>1,'amount'=>3,  'unit'=>'pcs','name'=>'Ripe Tomatoes',            'state'=>'peeled and chopped'],
                    ['sort_order'=>2,'amount'=>3,  'unit'=>'pcs','name'=>'Garlic Cloves',            'state'=>'minced'],
                    ['sort_order'=>3,'amount'=>3,  'unit'=>'tbsp','name'=>'Olive Oil',                'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp','name'=>'Paprika & Cumin',          'state'=>null],
                    ['sort_order'=>5,'amount'=>15, 'unit'=>'g',  'name'=>'Fresh Coriander',          'state'=>'chopped'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Dice the roasted and peeled bell peppers into small pieces.'],
                    ['step_number'=>2,'instruction'=>'Cook the tomatoes, garlic, spices, olive oil, and herbs in a pan until reduced to a sauce.'],
                    ['step_number'=>3,'instruction'=>'Add the bell peppers and simmer on medium heat for 10-15 minutes.'],
                    ['step_number'=>4,'instruction'=>'Sauté until the mixture is thick and dry. Serve warm or cold.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Moroccan Msemen',
                    'slug'             => 'moroccan-msemen',
                    'excerpt'          => 'Delectable, square laminated flatbreads with crispy golden edges and soft, chewy inner layers, perfect with honey.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 20,
                    'servings'         => 8,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>250,'unit'=>'g',  'name'=>'Fine Semolina Flour',      'state'=>null],
                    ['sort_order'=>1,'amount'=>150,'unit'=>'g',  'name'=>'All-Purpose Flour',        'state'=>null],
                    ['sort_order'=>2,'amount'=>250,'unit'=>'ml', 'name'=>'Warm Water',               'state'=>null],
                    ['sort_order'=>3,'amount'=>100,'unit'=>'g',  'name'=>'Melted Butter & Vegetable Oil','state'=>'for laminating'],
                    ['sort_order'=>4,'amount'=>0.5,'unit'=>'tsp','name'=>'Dry Yeast',                'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Knead semolina, flour, yeast, salt, and water until smooth and very elastic (about 10 minutes).'],
                    ['step_number'=>2,'instruction'=>'Divide dough into small balls, coat in oil, and rest for 15 minutes.'],
                    ['step_number'=>3,'instruction'=>'On a greased surface, flatten a ball into a paper-thin circle. Splash with butter and sprinkle semolina.'],
                    ['step_number'=>4,'instruction'=>'Fold into a square by folding top, bottom, left, and right sides into the center. Repeat with all balls.'],
                    ['step_number'=>5,'instruction'=>'Flatten each square slightly and cook on a hot dry griddle until golden brown on both sides.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Baghrir (Beghrir)',
                    'slug'             => 'moroccan-baghrir',
                    'excerpt'          => 'Moroccan "thousand holes" semolina pancakes that are incredibly light, cooked only on one side, and served with warm honey butter.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 15,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>250,'unit'=>'g',  'name'=>'Fine Semolina',            'state'=>null],
                    ['sort_order'=>1,'amount'=>50, 'unit'=>'g',  'name'=>'All-Purpose Flour',        'state'=>null],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'tbsp','name'=>'Active Dry Yeast',         'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'tsp','name'=>'Baking Powder',            'state'=>null],
                    ['sort_order'=>4,'amount'=>500,'unit'=>'ml', 'name'=>'Warm Water',               'state'=>null],
                    ['sort_order'=>5,'amount'=>2,  'unit'=>'tbsp','name'=>'Honey & Butter',           'state'=>'melted together for topping'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Blend semolina, flour, yeast, baking powder, salt, and warm water in a blender until smooth and frothy.'],
                    ['step_number'=>2,'instruction'=>'Cover batter and let rest for 15 minutes until slightly bubbly.'],
                    ['step_number'=>3,'instruction'=>'Heat a non-stick skillet over medium-low heat. Do not grease.'],
                    ['step_number'=>4,'instruction'=>'Pour a ladle of batter. Holes will form on top as it cooks. Do not flip. Remove when top is dry.'],
                    ['step_number'=>5,'instruction'=>'Serve warm drizzled with warm honey-butter sauce.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Kefta Tagine with Eggs',
                    'slug'             => 'kefta-tagine-eggs',
                    'excerpt'          => 'Flavorful kefta (spiced beef meatballs) simmered in a zesty, seasoned tomato sauce, topped with perfectly poached eggs.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 30,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',  'name'=>'Ground Beef or Lamb',      'state'=>null],
                    ['sort_order'=>1,'amount'=>600,'unit'=>'g',  'name'=>'Fresh Grated Tomatoes',    'state'=>null],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'pcs','name'=>'Onion',                    'state'=>'grated'],
                    ['sort_order'=>3,'amount'=>4,  'unit'=>'pcs','name'=>'Eggs',                    'state'=>null],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'tsp','name'=>'Paprika & Cumin',          'state'=>null],
                    ['sort_order'=>5,'amount'=>2,  'unit'=>'pcs','name'=>'Garlic Cloves',           'state'=>'minced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Mix ground beef, half the spices, grated onion, parsley, cilantro, and salt. Shape into small meatballs.'],
                    ['step_number'=>2,'instruction'=>'In a tagine or wide pan, simmer grated tomatoes, garlic, remaining spices, olive oil, and herbs for 10 minutes.'],
                    ['step_number'=>3,'instruction'=>'Add the meatballs to the tomato sauce, cover, and cook for 15 minutes.'],
                    ['step_number'=>4,'instruction'=>'Crack eggs directly onto the meatballs. Cover and cook for 5 minutes until egg whites are set but yolks are runny.'],
                    ['step_number'=>5,'instruction'=>'Garnish with fresh herbs and serve hot from the tagine.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Almond Briouats',
                    'slug'             => 'almond-briouats',
                    'excerpt'          => 'Crispy triangular pastries filled with sweet almond paste, orange blossom water, fried golden, and coated in pure honey.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 35,
                    'cook_time_minutes'=> 15,
                    'servings'         => 12,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>300,'unit'=>'g',  'name'=>'Blanched Almonds',        'state'=>null],
                    ['sort_order'=>1,'amount'=>100,'unit'=>'g',  'name'=>'Powdered Sugar',           'state'=>null],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'tbsp','name'=>'Orange Blossom Water',    'state'=>null],
                    ['sort_order'=>3,'amount'=>20, 'unit'=>'sheets','name'=>'Warqa or Phyllo Pastry', 'state'=>null],
                    ['sort_order'=>4,'amount'=>500,'unit'=>'ml', 'name'=>'Honey',                    'state'=>'for dipping'],
                    ['sort_order'=>5,'amount'=>1,  'unit'=>'pcs','name'=>'Egg Yolk',                'state'=>'for sealing'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Fry half the almonds until golden. Blend fried and raw almonds with powdered sugar, butter, and orange blossom water into a smooth paste.'],
                    ['step_number'=>2,'instruction'=>'Roll almond paste into small balls.'],
                    ['step_number'=>3,'instruction'=>'Cut pastry sheets into long strips. Place almond ball at one end, fold in triangles, and seal with egg yolk.'],
                    ['step_number'=>4,'instruction'=>'Deep fry briouats in hot oil until golden-brown, then plunge immediately into warm honey for 10 minutes.'],
                    ['step_number'=>5,'instruction'=>'Drain and garnish with sesame seeds or crushed almonds.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Cinnamon Orange Salad',
                    'slug'             => 'cinnamon-orange-salad',
                    'excerpt'          => 'A simple, light, and wonderfully refreshing Moroccan dessert of sliced oranges sprinkled with cinnamon and orange blossom water.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1511122847541-4cf54809b974?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 0,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>4,  'unit'=>'pcs','name'=>'Navel Oranges',            'state'=>'peeled and sliced thin'],
                    ['sort_order'=>1,'amount'=>1,  'unit'=>'tsp','name'=>'Ground Cinnamon',         'state'=>null],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'tbsp','name'=>'Orange Blossom Water',    'state'=>null],
                    ['sort_order'=>3,'amount'=>10, 'unit'=>'g',  'name'=>'Fresh Mint Leaves',       'state'=>'sliced'],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tbsp','name'=>'Powdered Sugar',          'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Arrange orange slices overlapping on a serving platter.'],
                    ['step_number'=>2,'instruction'=>'Drizzle orange blossom water evenly over the oranges.'],
                    ['step_number'=>3,'instruction'=>'Dust with powdered sugar and cinnamon.'],
                    ['step_number'=>4,'instruction'=>'Garnish with fresh mint. Chill in the refrigerator for 20 minutes before serving.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Ghriba Almond Cookies',
                    'slug'             => 'ghriba-almond-cookies',
                    'excerpt'          => 'Chewy Moroccan almond macaroons with a beautiful cracked surface and soft orange-scented crumb.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 15,
                    'servings'         => 18,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>250,'unit'=>'g',  'name'=>'Almond Flour',             'state'=>null],
                    ['sort_order'=>1,'amount'=>100,'unit'=>'g',  'name'=>'Sugar',                  'state'=>null],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'pcs','name'=>'Eggs',                    'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'tbsp','name'=>'Apricot Jam',             'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp','name'=>'Baking Powder',            'state'=>null],
                    ['sort_order'=>5,'amount'=>100,'unit'=>'g',  'name'=>'Powdered Sugar',          'state'=>'for coating'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Whisk eggs, sugar, jam, orange zest, and butter together.'],
                    ['step_number'=>2,'instruction'=>'Stir in almond flour, baking powder, and a pinch of salt until a soft dough forms.'],
                    ['step_number'=>3,'instruction'=>'Roll dough into balls. Roll balls generously in powdered sugar to coat completely.'],
                    ['step_number'=>4,'instruction'=>'Place on baking sheet, press down slightly. Bake at 170°C for 12-15 minutes until cracked and lightly golden.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Loubia (Moroccan Lentils)',
                    'slug'             => 'loubia-moroccan-lentils',
                    'excerpt'          => 'Hearty, spiced white beans or brown lentils simmered in a garlicky tomato sauce, served warm.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 35,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>300,'unit'=>'g',  'name'=>'Brown Lentils or White Beans','state'=>'soaked'],
                    ['sort_order'=>1,'amount'=>2,  'unit'=>'pcs','name'=>'Grated Tomatoes',          'state'=>null],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'pcs','name'=>'Onion',                    'state'=>'finely chopped'],
                    ['sort_order'=>3,'amount'=>4,  'unit'=>'pcs','name'=>'Garlic Cloves',           'state'=>'minced'],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp','name'=>'Cumin & Paprika & Ginger', 'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'In a pot, combine onion, garlic, tomatoes, lentils, olive oil, spices, and salt.'],
                    ['step_number'=>2,'instruction'=>'Cover with water (about 1 liter). Bring to a boil.'],
                    ['step_number'=>3,'instruction'=>'Simmer covered on medium-low heat for 35 minutes until lentils are creamy and tender.'],
                    ['step_number'=>4,'instruction'=>'Adjust seasoning and serve hot with crusty bread.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Chicken Rfissa',
                    'slug'             => 'chicken-rfissa',
                    'excerpt'          => 'A classic Moroccan specialty of shredded msemen bread topped with flavorful chicken, lentils, and a fenugreek broth.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 40,
                    'cook_time_minutes'=> 75,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1200,'unit'=>'g', 'name'=>'Whole Chicken',            'state'=>'cut into pieces'],
                    ['sort_order'=>1,'amount'=>6,   'unit'=>'pcs','name'=>'Msemen Flatbreads',       'state'=>'shredded into strips'],
                    ['sort_order'=>2,'amount'=>100, 'unit'=>'g', 'name'=>'Lentils',                  'state'=>null],
                    ['sort_order'=>3,'amount'=>2,   'unit'=>'tbsp','name'=>'Fenugreek Seeds',         'state'=>null],
                    ['sort_order'=>4,'amount'=>3,   'unit'=>'pcs','name'=>'Onions',                  'state'=>'sliced'],
                    ['sort_order'=>5,'amount'=>2,   'unit'=>'tsp','name'=>'Ras El Hanout Spice Mix',  'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Marinate chicken pieces in Ras El Hanout, ginger, saffron, garlic, and oil.'],
                    ['step_number'=>2,'instruction'=>'Cook chicken with onions and fenugreek seeds in a large pot, adding plenty of water for broth.'],
                    ['step_number'=>3,'instruction'=>'Add lentils after 20 minutes and simmer until everything is fully cooked.'],
                    ['step_number'=>4,'instruction'=>'Steam the shredded msemen to warm it up.'],
                    ['step_number'=>5,'instruction'=>'Lay warm shredded msemen on a plate, top with chicken, and ladle hot lentil broth over it.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Harcha (Semolina Bread)',
                    'slug'             => 'harcha-semolina-bread',
                    'excerpt'          => 'Moroccan pan-fried semolina griddle flatbreads, crispy on the outside, crumbly inside, served warm with butter and honey.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 15,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>250,'unit'=>'g',  'name'=>'Fine Semolina',            'state'=>null],
                    ['sort_order'=>1,'amount'=>100,'unit'=>'g',  'name'=>'Unsalted Butter',          'state'=>'melted'],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'tbsp','name'=>'Sugar',                   'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'tsp','name'=>'Baking Powder',            'state'=>null],
                    ['sort_order'=>4,'amount'=>120,'unit'=>'ml', 'name'=>'Milk',                     'state'=>'warm'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Combine semolina, sugar, baking powder, and salt. Rub melted butter into mixture with fingertips.'],
                    ['step_number'=>2,'instruction'=>'Add milk, stir gently. Do not overmix. Let dough rest for 5 minutes.'],
                    ['step_number'=>3,'instruction'=>'Form dough into balls, roll in extra coarse semolina, and flatten into discs.'],
                    ['step_number'=>4,'instruction'=>'Cook discs on a dry, non-stick griddle over medium heat until golden-brown on both sides.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Beef Tagine with Prunes',
                    'slug'             => 'beef-tagine-prunes',
                    'excerpt'          => 'A melt-in-your-mouth slow-cooked beef tagine layered with sweet caramelized prunes, toasted sesame seeds, and almonds.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1541518763669-27fef04b14ea?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 120,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>800,'unit'=>'g',  'name'=>'Beef Chuck',              'state'=>'cubed'],
                    ['sort_order'=>1,'amount'=>2,  'unit'=>'pcs','name'=>'Onions',                  'state'=>'sliced'],
                    ['sort_order'=>2,'amount'=>150,'unit'=>'g',  'name'=>'Prunes',                  'state'=>null],
                    ['sort_order'=>3,'amount'=>50, 'unit'=>'g',  'name'=>'Almonds',                 'state'=>'peeled and fried'],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tbsp','name'=>'Sesame Seeds',            'state'=>'toasted'],
                    ['sort_order'=>5,'amount'=>1,  'unit'=>'tsp','name'=>'Cinnamon & Ginger',        'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Cook beef with onions, garlic, ginger, turmeric, saffron, and oil in a heavy pot.'],
                    ['step_number'=>2,'instruction'=>'Add water, cover, and simmer for 1.5 - 2 hours until beef is tender.'],
                    ['step_number'=>3,'instruction'=>'Caramelize prunes in water, sugar, and cinnamon.'],
                    ['step_number'=>4,'instruction'=>'Arrange beef on a platter, top with caramelized prunes, syrup, almonds, and sesame seeds.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Spiced Carrot Salad',
                    'slug'             => 'spiced-carrot-salad',
                    'excerpt'          => 'Tender boiled carrots tossed in a zesty marinade of olive oil, lemon juice, garlic, cumin, and fresh cilantro.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 10,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',  'name'=>'Carrots',                 'state'=>'peeled and sliced'],
                    ['sort_order'=>1,'amount'=>3,  'unit'=>'tbsp','name'=>'Olive Oil',                'state'=>null],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'tbsp','name'=>'Lemon Juice',              'state'=>null],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'pcs','name'=>'Garlic Cloves',            'state'=>'minced'],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp','name'=>'Cumin & Paprika',          'state'=>null],
                    ['sort_order'=>5,'amount'=>20, 'unit'=>'g',  'name'=>'Fresh Cilantro',          'state'=>'chopped'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Boil carrots in salted water until tender but still firm. Drain and let cool.'],
                    ['step_number'=>2,'instruction'=>'Whisk olive oil, lemon juice, garlic, cumin, paprika, salt, and black pepper.'],
                    ['step_number'=>3,'instruction'=>'Toss carrots with the dressing and fresh cilantro.'],
                    ['step_number'=>4,'instruction'=>'Chill and serve cold.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Fish Tagine Chermoula',
                    'slug'             => 'fish-tagine-chermoula',
                    'excerpt'          => 'Firm white fish marinated in a garlic-cilantro chermoula sauce, slow-baked on a bed of bell peppers and tomatoes.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 25,
                    'cook_time_minutes'=> 40,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>600,'unit'=>'g',  'name'=>'White Fish Fillets (Cod/Sea Bass)','state'=>null],
                    ['sort_order'=>1,'amount'=>2,  'unit'=>'pcs','name'=>'Bell Peppers & Tomatoes',  'state'=>'sliced'],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'pcs','name'=>'Potatoes',                 'state'=>'peeled and sliced thin'],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'bunch','name'=>'Fresh Coriander & Garlic','state'=>'for chermoula'],
                    ['sort_order'=>4,'amount'=>50, 'unit'=>'ml', 'name'=>'Lemon Juice & Olive Oil',   'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Blend coriander, garlic, cumin, paprika, chili, lemon juice, and olive oil to make Chermoula.'],
                    ['step_number'=>2,'instruction'=>'Marinate fish fillets in half the chermoula for 30 minutes.'],
                    ['step_number'=>3,'instruction'=>'Arrange potato slices, bell peppers, and tomatoes in a tagine. Drizzle remaining chermoula.'],
                    ['step_number'=>4,'instruction'=>'Place fish on top of vegetables, cover, and cook on low heat (or bake at 180°C) for 40 minutes.'],
                ],
            ],
            [
                'meta' => [
                    'title'            => 'Moroccan Mechoui',
                    'slug'             => 'moroccan-mechoui',
                    'excerpt'          => 'Ultra-tender slow-roasted lamb shoulder, seasoned simply with salt, cumin, and butter, roasting until it falls off the bone.',
                    'cover_image'      => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 180,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1500,'unit'=>'g', 'name'=>'Lamb Shoulder on bone',    'state'=>null],
                    ['sort_order'=>1,'amount'=>100, 'unit'=>'g', 'name'=>'Softened Butter',          'state'=>null],
                    ['sort_order'=>2,'amount'=>2,   'unit'=>'tbsp','name'=>'Ground Cumin',            'state'=>null],
                    ['sort_order'=>3,'amount'=>2,   'unit'=>'tsp','name'=>'Sea Salt',                'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Make deep incisions all over the lamb shoulder. Rub with butter, cumin, and salt.'],
                    ['step_number'=>2,'instruction'=>'Wrap lamb tightly in foil. Bake at 150°C for 3 hours.'],
                    ['step_number'=>3,'instruction'=>'Unwrap lamb, increase oven heat to 200°C, and roast for another 20 minutes until crisp and golden.'],
                    ['step_number'=>4,'instruction'=>'Serve hot with bowls of salt and cumin for dipping.'],
                ],
            ],
        ];

        // Loop and Save Recipes
        foreach ($recipes as $index => $data) {
            $title = $data['meta']['title'];
            $catSlug = 'main-courses'; // default

            if (Str::contains($title, ['Tea', 'Briouats', 'Cookies', 'Salad'], true)) {
                if (Str::contains($title, ['Salad'], true)) {
                    $catSlug = 'appetizers-salads';
                } else {
                    $catSlug = 'desserts';
                }
            } elseif (Str::contains($title, ['Zaalouk', 'Taktouka', 'Soup', 'Lentils', 'Carrot'], true)) {
                $catSlug = 'appetizers-salads';
            } elseif (Str::contains($title, ['Msemen', 'Baghrir', 'Harcha'], true)) {
                $catSlug = 'breakfast-brunch';
            }

            $catId = isset($categories[$catSlug]) ? $categories[$catSlug]->id : null;

            $data['meta']['cover_image'] = "https://loremflickr.com/800/600/food," . urlencode(implode(',', explode(' ', strtolower($title)))) . "?lock=" . ($index + 200);

            // Create Recipe
            $recipe = Recipe::create(array_merge($data['meta'], [
                'tenant_uuid' => $tenantUuid,
                'author_id'   => $user->id,
                'category_id' => $catId,
                'description_html' => '<p>This is a delicious, authentic Moroccan recipe for ' . e($title) . '. Perfect for sharing with friends and family!</p>',
            ]));

            // Create Ingredients
            $recipe->ingredients()->createMany($data['ingredients']);

            // Create Steps
            $recipe->steps()->createMany($data['steps']);
        }
    }
}

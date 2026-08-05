<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Recipe;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Chef User
        $user = User::factory()->create([
            'name'  => 'Chef Hsin-I',
            'email' => 'hsini@recipes.hsini.dev',
        ]);

        // 2. Create Default Tenant for localhost
        $tenantUuid = Str::uuid()->getBytes();
        Tenant::create([
            'uuid'            => $tenantUuid,
            'name'            => 'Chef Hsin-I Culinary Blog',
            'domain'          => 'localhost',
            'uses_isolated_db'=> false,
            'billing_plan'    => 'enterprise',
        ]);

        TenantScope::setTenantId($tenantUuid);

        // Create default categories
        $categoriesData = [
            ['name' => 'Breakfast & Brunch', 'slug' => 'breakfast-brunch'],
            ['name' => 'Appetizers & Salads', 'slug' => 'appetizers-salads'],
            ['name' => 'Main Courses', 'slug' => 'main-courses'],
            ['name' => 'Side Dishes', 'slug' => 'side-dishes'],
            ['name' => 'Desserts', 'slug' => 'desserts'],
        ];
        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = \App\Models\Category::create([
                'name' => $cData['name'],
                'slug' => $cData['slug'],
            ]);
        }

        // Create default settings for this tenant
        \App\Models\Setting::create([
            'tenant_uuid' => $tenantUuid,
            'gemini_api_key' => null,
            'openai_api_key' => null,
            'preferred_ai_provider' => 'gemini',
            'openai_model' => 'gpt-4o-mini',
            'header_logo_text' => 'ChefSync',
            'header_subtitle' => 'Culinary Portal',
            'header_nav_links' => [
                ['text' => 'Recipes', 'url' => '/'],
                ['text' => 'Portions Scaler', 'url' => '#'],
                ['text' => 'Sign In', 'url' => '#'],
            ],
            'footer_newsletter_title' => 'Our best tips for eating thoughtfully and living joyfully, right in your inbox.',
            'footer_newsletter_placeholder' => 'ex: myname@email.com',
            'footer_newsletter_button' => 'SUBSCRIBE',
            'footer_copyright' => '© 2026 Food52, Inc. All Rights Reserved',
            'footer_columns_json' => [
                [
                    'title' => 'Company',
                    'links' => [
                        ['text' => 'About Us', 'url' => '#'],
                        ['text' => 'DEI Vision', 'url' => '#'],
                        ['text' => 'Press', 'url' => '#'],
                        ['text' => 'Jobs', 'url' => '#'],
                        ['text' => 'Affiliate Program', 'url' => '#'],
                    ]
                ],
                [
                    'title' => 'Get Help',
                    'links' => [
                        ['text' => 'Help Center', 'url' => '#'],
                        ['text' => 'Advertising Inquiries', 'url' => '#'],
                    ]
                ],
                [
                    'title' => 'Social',
                    'links' => [
                        ['text' => 'Facebook', 'url' => '#'],
                        ['text' => 'Instagram', 'url' => '#'],
                        ['text' => 'Pinterest', 'url' => '#'],
                        ['text' => 'TikTok', 'url' => '#'],
                        ['text' => 'YouTube', 'url' => '#'],
                    ]
                ]
            ]
        ]);

        // Create 10 default hero slides (Green Kitchen Stories style)
        $slidesData = [
            [
                'title' => 'Tahini & Rye Cookies',
                'subtitle' => 'We are obsessed with tahini so when we heard of a cookie that combined tahini with rye flour and chocolate, we knew it had to be good. And we were not wrong.',
                'category_tag' => 'SWEET TREATS',
                'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/classic-chocolate-chip-cookies',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Say hello to our vegan supplements!',
                'subtitle' => 'We\'re very excited to launch our first line of high-quality supplements for vegans and vegetarians in collaboration with Puori. Click here to order!',
                'category_tag' => 'PRODUCTS',
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/classic-caesar-salad',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Our Guide to Stockholm',
                'subtitle' => 'Stockholm is pretty awesome during the summer. You should come and visit! To convince you, we have created this very personal guide to our favorite places in the city.',
                'category_tag' => 'CITY GUIDE',
                'image_url' => 'https://images.unsplash.com/photo-1485081669829-bacb8c7bb1f3?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/artisan-sourdough-bread',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Creamy Vegan Pesto Pasta & Cauliflower',
                'subtitle' => 'We are fully aware that you hardly need yet another recipe for spaghetti al pesto. But there are a few twists that turn this simple Italian classic into a nutrient-packed meal.',
                'category_tag' => 'MAINS',
                'image_url' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/spaghetti-carbonara',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Our Guide to Barcelona',
                'subtitle' => 'We have gathered all our favorite green restaurants, coffee bars, gelato and kid-friendly places in one of our favorite European cities.',
                'category_tag' => 'CITY GUIDE',
                'image_url' => 'https://images.unsplash.com/photo-1511527661048-7fe73d85e9a4?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/street-beef-tacos',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Winter Holiday Saffron & Millet Salad',
                'subtitle' => 'This saffron and cinnamon studded grain salad with sweet pomegranate seeds, crunchy pistachios and fresh herbs is perfect for the festive table.',
                'category_tag' => 'SALADS',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/classic-caesar-salad',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Double Chocolate & Buckwheat Cookies',
                'subtitle' => 'A rich, dark and slightly salty chocolate cookie that is gluten-free and has a rich, fudgy center. They are quick to whip up and vanish instantly.',
                'category_tag' => 'SWEET TREATS',
                'image_url' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/chocolate-lava-cake',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Sourdough Pancakes with Wild Berries',
                'subtitle' => 'What to do with your discarded sourdough starter? Make the fluffiest, slightly tangy pancakes you will ever taste. Served with maple syrup.',
                'category_tag' => 'BREAKFAST',
                'image_url' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/fluffy-french-toast',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'Classic Shakshuka for Two',
                'subtitle' => 'A vibrant pan of gently poached eggs in a rich, spiced tomato and bell pepper sauce. Top with fresh feta and cilantro, and serve with crusty bread.',
                'category_tag' => 'BREAKFAST',
                'image_url' => 'https://images.unsplash.com/photo-1541832676-9b763b0239ab?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/shakshuka',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'Crispy Baked French Fries',
                'subtitle' => 'The secret to golden-brown, extra-crispy baked fries that taste like they were deep-fried. Serve with a sprinkle of sea salt and rosemary.',
                'category_tag' => 'SIDE DISHES',
                'image_url' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=1200&auto=format&fit=crop',
                'link_url' => '/recipes/golden-french-fries',
                'sort_order' => 10,
                'is_active' => true,
            ]
        ];

        foreach ($slidesData as $sData) {
            \App\Models\HeroSlide::create(array_merge($sData, [
                'tenant_uuid' => $tenantUuid,
            ]));
        }

        // ─────────────────────────────────────────────────────────────────────
        // 20 Most Popular Recipes in the World
        // ─────────────────────────────────────────────────────────────────────
        $recipes = [

            // ── 1 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Artisan Sourdough Bread',
                    'slug'             => 'artisan-sourdough-bread',
                    'excerpt'          => 'A classic country-style sourdough loaf with a blistered crust and open crumb, made with wild yeast starter.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,sourdoughbread?lock=408',
                    'prep_time_minutes'=> 45,
                    'cook_time_minutes'=> 40,
                    'servings'         => 8,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',   'name'=>'Unbleached Bread Flour',    'state'=>'sifted'],
                    ['sort_order'=>1,'amount'=>350,'unit'=>'ml',  'name'=>'Lukewarm Water',             'state'=>null],
                    ['sort_order'=>2,'amount'=>100,'unit'=>'g',   'name'=>'Active Sourdough Starter',  'state'=>'fed'],
                    ['sort_order'=>3,'amount'=>10, 'unit'=>'g',   'name'=>'Fine Sea Salt',             'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Autolyse: Mix flour and water until no dry spots remain. Cover and rest 45 minutes.'],
                    ['step_number'=>2,'instruction'=>'Add starter and salt, dimple in then fold until fully incorporated.'],
                    ['step_number'=>3,'instruction'=>'Perform 4 sets of stretch-and-folds every 30 minutes over 2 hours bulk fermentation.'],
                    ['step_number'=>4,'instruction'=>'Preshape into a loose round. Rest 20 minutes then final shape into a boule.'],
                    ['step_number'=>5,'instruction'=>'Proof in a banneton overnight in the fridge for 12–16 hours.'],
                    ['step_number'=>6,'instruction'=>'Bake in Dutch oven at 230°C covered 20 min, uncovered 20 min until deep mahogany.'],
                ],
            ],

            // ── 2 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Classic Chocolate Chip Cookies',
                    'slug'             => 'classic-chocolate-chip-cookies',
                    'excerpt'          => 'Crisp on the edges, soft and chewy in the center, loaded with premium dark chocolate chunks.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,cookies?lock=401',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 12,
                    'servings'         => 24,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>225,'unit'=>'g',  'name'=>'Unsalted Butter',          'state'=>'browned & cooled'],
                    ['sort_order'=>1,'amount'=>150,'unit'=>'g',  'name'=>'Dark Brown Sugar',         'state'=>'packed'],
                    ['sort_order'=>2,'amount'=>100,'unit'=>'g',  'name'=>'Granulated White Sugar',   'state'=>null],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'pcs','name'=>'Large Eggs',               'state'=>'room temperature'],
                    ['sort_order'=>4,'amount'=>300,'unit'=>'g',  'name'=>'All-Purpose Flour',        'state'=>null],
                    ['sort_order'=>5,'amount'=>200,'unit'=>'g',  'name'=>'Dark Chocolate Chunks',    'state'=>'70% cocoa, chopped'],
                    ['sort_order'=>6,'amount'=>5,  'unit'=>'g',  'name'=>'Flaky Sea Salt',           'state'=>'for topping'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Brown butter in a saucepan over medium heat until nutty and golden. Cool completely.'],
                    ['step_number'=>2,'instruction'=>'Whisk cooled butter with both sugars. Whisk in eggs one at a time.'],
                    ['step_number'=>3,'instruction'=>'Fold in sifted flour, baking soda, and salt. Fold in chocolate chunks last.'],
                    ['step_number'=>4,'instruction'=>'Scoop dough into large balls and chill 24 hours for best results.'],
                    ['step_number'=>5,'instruction'=>'Bake at 190°C for 10–12 minutes until edges golden. Top immediately with flaky salt.'],
                ],
            ],

            // ── 3 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Spaghetti Carbonara',
                    'slug'             => 'spaghetti-carbonara',
                    'excerpt'          => 'The authentic Roman recipe: just eggs, Pecorino Romano, guanciale, and black pepper — no cream ever.',
                    'cover_image'      => '/images/recipes/carbonara.png',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 20,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>400,'unit'=>'g',  'name'=>'Spaghetti or Rigatoni',    'state'=>null],
                    ['sort_order'=>1,'amount'=>200,'unit'=>'g',  'name'=>'Guanciale or Pancetta',    'state'=>'cubed'],
                    ['sort_order'=>2,'amount'=>4,  'unit'=>'pcs','name'=>'Egg Yolks',                'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'pcs','name'=>'Whole Egg',                'state'=>null],
                    ['sort_order'=>4,'amount'=>100,'unit'=>'g',  'name'=>'Pecorino Romano',          'state'=>'finely grated'],
                    ['sort_order'=>5,'amount'=>2,  'unit'=>'tsp','name'=>'Freshly Cracked Black Pepper','state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Cook guanciale in a cold pan until rendered and crisp. Reserve fat.'],
                    ['step_number'=>2,'instruction'=>'Whisk yolks, whole egg, Pecorino and generous black pepper in a bowl.'],
                    ['step_number'=>3,'instruction'=>'Cook pasta in well-salted water until al dente. Reserve 1 cup pasta water.'],
                    ['step_number'=>4,'instruction'=>'Off heat, toss pasta in guanciale pan. Add egg mixture, toss vigorously.'],
                    ['step_number'=>5,'instruction'=>'Add pasta water splash by splash until silky. Serve immediately with more Pecorino.'],
                ],
            ],

            // ── 4 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Tonkotsu Ramen',
                    'slug'             => 'tonkotsu-ramen',
                    'excerpt'          => 'Rich, milky pork bone broth simmered 12 hours, topped with chashu pork, soft-boiled egg, and nori.',
                    'cover_image'      => '/images/recipes/ramen.png',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 720,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1500,'unit'=>'g', 'name'=>'Pork Trotters & Bones',    'state'=>'blanched'],
                    ['sort_order'=>1,'amount'=>400, 'unit'=>'g', 'name'=>'Pork Belly (Chashu)',       'state'=>'rolled & tied'],
                    ['sort_order'=>2,'amount'=>400, 'unit'=>'g', 'name'=>'Fresh Ramen Noodles',       'state'=>null],
                    ['sort_order'=>3,'amount'=>4,   'unit'=>'pcs','name'=>'Soft-Boiled Eggs (Ajitsuke)','state'=>'marinated'],
                    ['sort_order'=>4,'amount'=>4,   'unit'=>'pcs','name'=>'Nori Sheets',              'state'=>null],
                    ['sort_order'=>5,'amount'=>4,   'unit'=>'tbsp','name'=>'Tare (soy tare)',          'state'=>null],
                    ['sort_order'=>6,'amount'=>50,  'unit'=>'g', 'name'=>'Green Onions',              'state'=>'sliced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Blanch bones 10 min, rinse thoroughly. Simmer in fresh water 12+ hours.'],
                    ['step_number'=>2,'instruction'=>'Roll and tie pork belly. Sear then braise in soy, mirin, and sake 2 hours.'],
                    ['step_number'=>3,'instruction'=>'Marinate soft-boiled eggs in 1:1 soy/mirin mix for at least 4 hours.'],
                    ['step_number'=>4,'instruction'=>'Strain broth, season with tare. Taste and adjust salt.'],
                    ['step_number'=>5,'instruction'=>'Cook noodles per package. Place in bowl with hot broth, chashu, egg, nori.'],
                ],
            ],

            // ── 5 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Chocolate Lava Cake',
                    'slug'             => 'chocolate-lava-cake',
                    'excerpt'          => 'Individual molten chocolate cakes with a gooey liquid center. Ready in 20 minutes — impressive & effortless.',
                    'cover_image'      => '/images/recipes/lava-cake.png',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 12,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>200,'unit'=>'g',  'name'=>'Dark Chocolate (70%)',     'state'=>'chopped'],
                    ['sort_order'=>1,'amount'=>150,'unit'=>'g',  'name'=>'Unsalted Butter',          'state'=>'cubed'],
                    ['sort_order'=>2,'amount'=>4,  'unit'=>'pcs','name'=>'Whole Eggs',               'state'=>null],
                    ['sort_order'=>3,'amount'=>4,  'unit'=>'pcs','name'=>'Egg Yolks',                'state'=>null],
                    ['sort_order'=>4,'amount'=>120,'unit'=>'g',  'name'=>'Caster Sugar',             'state'=>null],
                    ['sort_order'=>5,'amount'=>60, 'unit'=>'g',  'name'=>'Plain Flour',              'state'=>'sifted'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Preheat oven to 200°C. Butter and flour 4 ramekins.'],
                    ['step_number'=>2,'instruction'=>'Melt chocolate and butter together in a double boiler. Cool slightly.'],
                    ['step_number'=>3,'instruction'=>'Whisk eggs, yolks, and sugar until pale. Fold in chocolate mixture.'],
                    ['step_number'=>4,'instruction'=>'Fold in flour until just combined. Divide into ramekins.'],
                    ['step_number'=>5,'instruction'=>'Bake 10–12 min until edges set but center jiggles. Invert onto plate immediately.'],
                ],
            ],

            // ── 6 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Neapolitan Margherita Pizza',
                    'slug'             => 'neapolitan-margherita-pizza',
                    'excerpt'          => 'Blistered, chewy Neapolitan crust with San Marzano tomatoes, fresh fior di latte mozzarella, and basil.',
                    'cover_image'      => '/images/recipes/margherita-pizza.png',
                    'prep_time_minutes'=> 120,
                    'cook_time_minutes'=> 5,
                    'servings'         => 2,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>300,'unit'=>'g',  'name'=>'Tipo 00 Flour',            'state'=>null],
                    ['sort_order'=>1,'amount'=>195,'unit'=>'ml', 'name'=>'Water',                    'state'=>'room temperature'],
                    ['sort_order'=>2,'amount'=>6,  'unit'=>'g',  'name'=>'Fine Sea Salt',            'state'=>null],
                    ['sort_order'=>3,'amount'=>0.5,'unit'=>'g',  'name'=>'Dry Yeast',                'state'=>null],
                    ['sort_order'=>4,'amount'=>200,'unit'=>'g',  'name'=>'San Marzano Tomatoes',     'state'=>'crushed by hand'],
                    ['sort_order'=>5,'amount'=>200,'unit'=>'g',  'name'=>'Fior di Latte Mozzarella', 'state'=>'torn'],
                    ['sort_order'=>6,'amount'=>10, 'unit'=>'pcs','name'=>'Fresh Basil Leaves',       'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Mix flour, yeast and water. Add salt. Knead 10 min until smooth and elastic.'],
                    ['step_number'=>2,'instruction'=>'Bulk ferment 8–12 hours at room temp, or 24 hours in fridge.'],
                    ['step_number'=>3,'instruction'=>'Ball the dough, rest 3 hours. Stretch by hand to thin round.'],
                    ['step_number'=>4,'instruction'=>'Top with crushed tomatoes, season with salt. Add mozzarella after sauce.'],
                    ['step_number'=>5,'instruction'=>'Bake on preheated steel/stone at maximum oven temp (280°C+) for 4–5 min.'],
                    ['step_number'=>6,'instruction'=>'Finish with fresh basil and a drizzle of extra virgin olive oil.'],
                ],
            ],

            // ── 7 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Chicken Tikka Masala',
                    'slug'             => 'chicken-tikka-masala',
                    'excerpt'          => 'Tender charred chicken in a velvety, spiced tomato-cream sauce — the world\'s most beloved curry.',
                    'cover_image'      => '/images/recipes/chicken-tikka.png',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 40,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>700,'unit'=>'g',  'name'=>'Chicken Thighs',          'state'=>'boneless, cubed'],
                    ['sort_order'=>1,'amount'=>200,'unit'=>'g',  'name'=>'Full-Fat Yogurt',         'state'=>null],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'tbsp','name'=>'Garam Masala',           'state'=>null],
                    ['sort_order'=>3,'amount'=>400,'unit'=>'g',  'name'=>'Crushed Tomatoes',        'state'=>null],
                    ['sort_order'=>4,'amount'=>200,'unit'=>'ml', 'name'=>'Heavy Cream',             'state'=>null],
                    ['sort_order'=>5,'amount'=>3,  'unit'=>'pcs','name'=>'Garlic Cloves',           'state'=>'minced'],
                    ['sort_order'=>6,'amount'=>30, 'unit'=>'g',  'name'=>'Fresh Ginger',            'state'=>'grated'],
                    ['sort_order'=>7,'amount'=>2,  'unit'=>'pcs','name'=>'Large Onions',            'state'=>'finely diced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Marinate chicken in yogurt, half the spices, and salt. Rest 4 hours minimum.'],
                    ['step_number'=>2,'instruction'=>'Grill or broil chicken on highest heat until charred. Set aside.'],
                    ['step_number'=>3,'instruction'=>'Sauté onions until golden. Add garlic, ginger, spices. Cook 2 min.'],
                    ['step_number'=>4,'instruction'=>'Add crushed tomatoes. Simmer 15 min until thickened.'],
                    ['step_number'=>5,'instruction'=>'Stir in cream and charred chicken. Simmer 10 min. Adjust seasoning.'],
                ],
            ],

            // ── 8 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Street Beef Tacos',
                    'slug'             => 'street-beef-tacos',
                    'excerpt'          => 'Carne asada street tacos with cilantro, white onion, lime and salsa verde on warm corn tortillas.',
                    'cover_image'      => '/images/recipes/beef-tacos.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 15,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>600,'unit'=>'g',  'name'=>'Skirt or Flank Steak',    'state'=>null],
                    ['sort_order'=>1,'amount'=>12, 'unit'=>'pcs','name'=>'Small Corn Tortillas',    'state'=>'warmed'],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'pcs','name'=>'White Onion',             'state'=>'finely chopped'],
                    ['sort_order'=>3,'amount'=>30, 'unit'=>'g',  'name'=>'Fresh Cilantro',          'state'=>'roughly chopped'],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'pcs','name'=>'Limes',                   'state'=>'quartered'],
                    ['sort_order'=>5,'amount'=>3,  'unit'=>'pcs','name'=>'Tomatillos',              'state'=>'for salsa verde'],
                    ['sort_order'=>6,'amount'=>1,  'unit'=>'pcs','name'=>'Jalapeño',                'state'=>'for salsa verde'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Marinate steak with lime juice, cumin, garlic, and salt for 1 hour.'],
                    ['step_number'=>2,'instruction'=>'Cook tomatillos and jalapeño on a dry comal until charred. Blend with salt.'],
                    ['step_number'=>3,'instruction'=>'Grill steak over very high heat 3–4 min per side. Rest 5 min then slice thin.'],
                    ['step_number'=>4,'instruction'=>'Warm tortillas on comal. Double them up.'],
                    ['step_number'=>5,'instruction'=>'Fill with steak, onion, cilantro. Squeeze lime over. Serve with salsa verde.'],
                ],
            ],

            // ── 9 ─────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Classic Caesar Salad',
                    'slug'             => 'classic-caesar-salad',
                    'excerpt'          => 'Crisp romaine with house-made anchovy Caesar dressing, Parmigiano-Reggiano, and golden sourdough croutons.',
                    'cover_image'      => '/images/recipes/caesar-salad.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 10,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>2,  'unit'=>'pcs','name'=>'Romaine Hearts',           'state'=>'leaves separated'],
                    ['sort_order'=>1,'amount'=>6,  'unit'=>'pcs','name'=>'Anchovy Fillets',          'state'=>null],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'pcs','name'=>'Garlic Cloves',            'state'=>null],
                    ['sort_order'=>3,'amount'=>30, 'unit'=>'ml', 'name'=>'Fresh Lemon Juice',        'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp','name'=>'Dijon Mustard',            'state'=>null],
                    ['sort_order'=>5,'amount'=>2,  'unit'=>'pcs','name'=>'Egg Yolks',                'state'=>null],
                    ['sort_order'=>6,'amount'=>80, 'unit'=>'ml', 'name'=>'Extra Virgin Olive Oil',   'state'=>null],
                    ['sort_order'=>7,'amount'=>80, 'unit'=>'g',  'name'=>'Parmigiano-Reggiano',      'state'=>'shaved'],
                    ['sort_order'=>8,'amount'=>100,'unit'=>'g',  'name'=>'Sourdough Croutons',       'state'=>'torn & toasted'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Mash anchovy and garlic with salt until paste. Whisk in yolks, Dijon, lemon.'],
                    ['step_number'=>2,'instruction'=>'Slowly drizzle in olive oil whisking constantly until emulsified.'],
                    ['step_number'=>3,'instruction'=>'Tear sourdough; toss with olive oil and bake 200°C until golden.'],
                    ['step_number'=>4,'instruction'=>'Toss romaine with dressing. Plate, top with shaved Parmigiano and croutons.'],
                ],
            ],

            // ── 10 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'French Onion Soup',
                    'slug'             => 'french-onion-soup',
                    'excerpt'          => 'Slow-caramelised onions in rich beef broth, topped with a crouton and bubbling Gruyère cheese crust.',
                    'cover_image'      => '/images/recipes/french-onion-soup.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 75,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1200,'unit'=>'g', 'name'=>'Yellow Onions',            'state'=>'thinly sliced'],
                    ['sort_order'=>1,'amount'=>60,  'unit'=>'g', 'name'=>'Unsalted Butter',          'state'=>null],
                    ['sort_order'=>2,'amount'=>120, 'unit'=>'ml','name'=>'Dry White Wine',           'state'=>null],
                    ['sort_order'=>3,'amount'=>1.5, 'unit'=>'l', 'name'=>'Beef Stock',               'state'=>'good quality'],
                    ['sort_order'=>4,'amount'=>4,   'unit'=>'pcs','name'=>'Baguette Slices',          'state'=>'toasted'],
                    ['sort_order'=>5,'amount'=>150, 'unit'=>'g', 'name'=>'Gruyère',                  'state'=>'grated'],
                    ['sort_order'=>6,'amount'=>2,   'unit'=>'pcs','name'=>'Thyme Sprigs',             'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Melt butter in a heavy pot. Add onions and a pinch of salt. Cook 45–60 min, stirring, until deeply caramelised.'],
                    ['step_number'=>2,'instruction'=>'Add wine, deglaze and reduce. Add thyme and beef stock. Simmer 15 min.'],
                    ['step_number'=>3,'instruction'=>'Ladle into oven-safe crocks. Top with toasted baguette slice.'],
                    ['step_number'=>4,'instruction'=>'Cover generously with Gruyère. Broil until bubbly and golden-brown.'],
                ],
            ],

            // ── 11 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Pad Thai with Shrimp',
                    'slug'             => 'pad-thai-shrimp',
                    'excerpt'          => 'The iconic Thai stir-fried rice noodle dish — sweet, sour, salty, and packed with texture.',
                    'cover_image'      => '/images/recipes/pad-thai.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 15,
                    'servings'         => 2,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>200,'unit'=>'g',  'name'=>'Flat Rice Noodles (Sen Lek)','state'=>'soaked 30 min'],
                    ['sort_order'=>1,'amount'=>200,'unit'=>'g',  'name'=>'Large Shrimp',              'state'=>'peeled & deveined'],
                    ['sort_order'=>2,'amount'=>3,  'unit'=>'tbsp','name'=>'Tamarind Paste',           'state'=>null],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'tbsp','name'=>'Fish Sauce',               'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tbsp','name'=>'Palm Sugar',               'state'=>null],
                    ['sort_order'=>5,'amount'=>2,  'unit'=>'pcs','name'=>'Eggs',                      'state'=>null],
                    ['sort_order'=>6,'amount'=>80, 'unit'=>'g',  'name'=>'Bean Sprouts',              'state'=>null],
                    ['sort_order'=>7,'amount'=>40, 'unit'=>'g',  'name'=>'Roasted Peanuts',           'state'=>'crushed'],
                    ['sort_order'=>8,'amount'=>2,  'unit'=>'pcs','name'=>'Green Onions',              'state'=>'sliced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Mix tamarind, fish sauce, and palm sugar. Set sauce aside.'],
                    ['step_number'=>2,'instruction'=>'Stir-fry shrimp in very hot wok with oil until pink. Set aside.'],
                    ['step_number'=>3,'instruction'=>'Push to side, scramble eggs. Add noodles and sauce; toss vigorously.'],
                    ['step_number'=>4,'instruction'=>'Add shrimp and bean sprouts. Toss 1 minute.'],
                    ['step_number'=>5,'instruction'=>'Plate; top with peanuts, green onions, lime, and dried chilli flakes.'],
                ],
            ],

            // ── 12 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Beef Bourguignon',
                    'slug'             => 'beef-bourguignon',
                    'excerpt'          => 'Julia Child\'s legendary Burgundy beef stew — fall-apart tender beef, mushrooms, and pearl onions in Pinot Noir.',
                    'cover_image'      => '/images/recipes/beef-bourguignon.png',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 180,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1500,'unit'=>'g','name'=>'Beef Chuck',              'state'=>'cut into 5cm cubes'],
                    ['sort_order'=>1,'amount'=>750, 'unit'=>'ml','name'=>'Burgundy Pinot Noir',    'state'=>null],
                    ['sort_order'=>2,'amount'=>200, 'unit'=>'g', 'name'=>'Lardons (smoked bacon)', 'state'=>null],
                    ['sort_order'=>3,'amount'=>300, 'unit'=>'g', 'name'=>'Pearl Onions',           'state'=>'peeled'],
                    ['sort_order'=>4,'amount'=>400, 'unit'=>'g', 'name'=>'Cremini Mushrooms',      'state'=>'quartered'],
                    ['sort_order'=>5,'amount'=>500, 'unit'=>'ml','name'=>'Beef Stock',             'state'=>null],
                    ['sort_order'=>6,'amount'=>3,   'unit'=>'pcs','name'=>'Carrots',               'state'=>'chunked'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Brown lardons, set aside. Brown beef in batches in same pot. Set aside.'],
                    ['step_number'=>2,'instruction'=>'Sauté carrots and onion. Return beef. Add wine, stock, tomato paste, herbs.'],
                    ['step_number'=>3,'instruction'=>'Braise covered in 160°C oven for 2.5–3 hours until very tender.'],
                    ['step_number'=>4,'instruction'=>'Sauté pearl onions and mushrooms separately until golden.'],
                    ['step_number'=>5,'instruction'=>'Strain sauce, reduce if needed. Combine all. Serve over mashed potatoes.'],
                ],
            ],

            // ── 13 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Eggs Benedict',
                    'slug'             => 'eggs-benedict',
                    'excerpt'          => 'Perfectly poached eggs on English muffins with Canadian bacon and silky homemade hollandaise.',
                    'cover_image'      => '/images/recipes/eggs-benedict.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 15,
                    'servings'         => 2,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>4,   'unit'=>'pcs','name'=>'Eggs',                   'state'=>'very fresh'],
                    ['sort_order'=>1,'amount'=>2,   'unit'=>'pcs','name'=>'English Muffins',        'state'=>'split & toasted'],
                    ['sort_order'=>2,'amount'=>4,   'unit'=>'slices','name'=>'Canadian Bacon',      'state'=>null],
                    ['sort_order'=>3,'amount'=>3,   'unit'=>'pcs','name'=>'Egg Yolks (hollandaise)','state'=>null],
                    ['sort_order'=>4,'amount'=>150, 'unit'=>'g',  'name'=>'Clarified Butter',       'state'=>'warm'],
                    ['sort_order'=>5,'amount'=>15,  'unit'=>'ml', 'name'=>'Lemon Juice',            'state'=>null],
                    ['sort_order'=>6,'amount'=>1,   'unit'=>'tbsp','name'=>'White Wine Vinegar',    'state'=>'for poaching'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Hollandaise: Whisk yolks and lemon over a double boiler until ribbon stage.'],
                    ['step_number'=>2,'instruction'=>'Slowly drizzle warm clarified butter whisking constantly. Season with salt and cayenne.'],
                    ['step_number'=>3,'instruction'=>'Bring water to simmer with vinegar. Create a gentle whirlpool; slip in egg. Poach 3.5 min.'],
                    ['step_number'=>4,'instruction'=>'Pan-fry Canadian bacon until warm. Place on toasted muffin.'],
                    ['step_number'=>5,'instruction'=>'Top with poached egg, hollandaise, and fresh chives.'],
                ],
            ],

            // ── 14 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Tiramisu',
                    'slug'             => 'tiramisu',
                    'excerpt'          => 'Classic Italian dessert with espresso-soaked savoiardi, whipped mascarpone cream, and cocoa dust.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,tiramisu?lock=402',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 0,
                    'servings'         => 8,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',  'name'=>'Mascarpone',              'state'=>'room temperature'],
                    ['sort_order'=>1,'amount'=>6,  'unit'=>'pcs','name'=>'Egg Yolks',               'state'=>null],
                    ['sort_order'=>2,'amount'=>150,'unit'=>'g',  'name'=>'Caster Sugar',            'state'=>null],
                    ['sort_order'=>3,'amount'=>300,'unit'=>'ml', 'name'=>'Strong Espresso',         'state'=>'cooled'],
                    ['sort_order'=>4,'amount'=>60, 'unit'=>'ml', 'name'=>'Marsala or Dark Rum',     'state'=>null],
                    ['sort_order'=>5,'amount'=>40, 'unit'=>'pcs','name'=>'Savoiardi (Ladyfingers)', 'state'=>null],
                    ['sort_order'=>6,'amount'=>30, 'unit'=>'g',  'name'=>'Unsweetened Cocoa Powder','state'=>'for dusting'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Whisk yolks and sugar over a double boiler until thick and pale (zabaglione).'],
                    ['step_number'=>2,'instruction'=>'Fold mascarpone into zabaglione until smooth and creamy.'],
                    ['step_number'=>3,'instruction'=>'Mix espresso and marsala. Briefly dip each ladyfinger (2 sec per side).'],
                    ['step_number'=>4,'instruction'=>'Layer: soaked biscuits → cream → biscuits → cream.'],
                    ['step_number'=>5,'instruction'=>'Refrigerate 8+ hours. Dust generously with cocoa before serving.'],
                ],
            ],

            // ── 15 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Perfect Beef Steak',
                    'slug'             => 'perfect-beef-steak',
                    'excerpt'          => 'Reverse-seared ribeye with herb brown butter — the foolproof method for a restaurant-quality steak at home.',
                    'cover_image'      => '/images/recipes/beef-steak.png',
                    'prep_time_minutes'=> 5,
                    'cook_time_minutes'=> 35,
                    'servings'         => 2,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>2,  'unit'=>'pcs','name'=>'Ribeye Steaks (3cm thick)','state'=>'room temperature'],
                    ['sort_order'=>1,'amount'=>30, 'unit'=>'g',  'name'=>'Unsalted Butter',         'state'=>null],
                    ['sort_order'=>2,'amount'=>4,  'unit'=>'pcs','name'=>'Garlic Cloves',           'state'=>'crushed'],
                    ['sort_order'=>3,'amount'=>3,  'unit'=>'pcs','name'=>'Thyme Sprigs',            'state'=>null],
                    ['sort_order'=>4,'amount'=>3,  'unit'=>'pcs','name'=>'Rosemary Sprigs',         'state'=>null],
                    ['sort_order'=>5,'amount'=>10, 'unit'=>'g',  'name'=>'Flaky Sea Salt',          'state'=>null],
                    ['sort_order'=>6,'amount'=>2,  'unit'=>'g',  'name'=>'Freshly Cracked Black Pepper','state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Season steaks generously. Bake on wire rack at 120°C until internal temp 50°C (~25 min).'],
                    ['step_number'=>2,'instruction'=>'Heat cast iron until smoking. Sear steaks 60 seconds per side and edges.'],
                    ['step_number'=>3,'instruction'=>'Add butter, garlic, thyme. Baste continuously for 90 seconds.'],
                    ['step_number'=>4,'instruction'=>'Rest on wire rack 10 minutes. Slice against the grain.'],
                ],
            ],

            // ── 16 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Dragon Roll Sushi',
                    'slug'             => 'dragon-roll-sushi',
                    'excerpt'          => 'Crispy shrimp tempura roll topped with thinly sliced avocado, tobiko, and spicy mayo.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,sushi,dragonroll?lock=404',
                    'prep_time_minutes'=> 60,
                    'cook_time_minutes'=> 30,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>300,'unit'=>'g',  'name'=>'Japanese Short-Grain Rice','state'=>'cooked & seasoned'],
                    ['sort_order'=>1,'amount'=>4,  'unit'=>'pcs','name'=>'Nori Sheets',             'state'=>'halved'],
                    ['sort_order'=>2,'amount'=>8,  'unit'=>'pcs','name'=>'Large Tiger Shrimp',      'state'=>'tempura-fried'],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'pcs','name'=>'Ripe Avocados',           'state'=>'thinly sliced'],
                    ['sort_order'=>4,'amount'=>30, 'unit'=>'g',  'name'=>'Tobiko (Flying Fish Roe)','state'=>null],
                    ['sort_order'=>5,'amount'=>4,  'unit'=>'tbsp','name'=>'Japanese Mayonnaise',    'state'=>null],
                    ['sort_order'=>6,'amount'=>2,  'unit'=>'tsp','name'=>'Sriracha',                'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Season cooked rice with rice vinegar, sugar, salt mix. Fan until body temp.'],
                    ['step_number'=>2,'instruction'=>'Fry shrimp in light tempura batter until golden and crispy.'],
                    ['step_number'=>3,'instruction'=>'Place nori on bamboo mat, spread rice evenly, flip over (rice side down).'],
                    ['step_number'=>4,'instruction'=>'Place shrimp at near edge, roll tightly using the mat.'],
                    ['step_number'=>5,'instruction'=>'Top with overlapping avocado slices, press gently. Slice and top with tobiko.'],
                ],
            ],

            // ── 17 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Greek Salad (Horiatiki)',
                    'slug'             => 'greek-salad-horiatiki',
                    'excerpt'          => 'Authentic Greek village salad — chunky tomatoes, cucumber, Kalamata olives, and a slab of Feta. No lettuce.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,greeksalad?lock=405',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 0,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>4,  'unit'=>'pcs','name'=>'Ripe Tomatoes',           'state'=>'cut in wedges'],
                    ['sort_order'=>1,'amount'=>1,  'unit'=>'pcs','name'=>'English Cucumber',        'state'=>'chunked'],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'pcs','name'=>'Green Bell Pepper',       'state'=>'sliced into rings'],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'pcs','name'=>'Red Onion',               'state'=>'thinly sliced'],
                    ['sort_order'=>4,'amount'=>120,'unit'=>'g',  'name'=>'Kalamata Olives',         'state'=>'pitted'],
                    ['sort_order'=>5,'amount'=>200,'unit'=>'g',  'name'=>'Greek Feta Block',        'state'=>null],
                    ['sort_order'=>6,'amount'=>60, 'unit'=>'ml', 'name'=>'Extra Virgin Olive Oil',  'state'=>'best quality'],
                    ['sort_order'=>7,'amount'=>1,  'unit'=>'tsp','name'=>'Dried Oregano',           'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Combine tomatoes, cucumber, pepper, onion and olives in a large bowl.'],
                    ['step_number'=>2,'instruction'=>'Place feta block on top — do not crumble it.'],
                    ['step_number'=>3,'instruction'=>'Drizzle generously with olive oil, sprinkle dried oregano on feta.'],
                    ['step_number'=>4,'instruction'=>'Season with salt and pepper. Serve immediately with crusty bread.'],
                ],
            ],

            // ── 18 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Butter Chicken (Murgh Makhani)',
                    'slug'             => 'butter-chicken',
                    'excerpt'          => 'Silky, mildly spiced tomato-butter sauce with tandoor-charred chicken — India\'s most exported curry.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,butterchicken?lock=406',
                    'prep_time_minutes'=> 25,
                    'cook_time_minutes'=> 40,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>700,'unit'=>'g',  'name'=>'Chicken Thighs',          'state'=>'cubed'],
                    ['sort_order'=>1,'amount'=>100,'unit'=>'g',  'name'=>'Unsalted Butter',         'state'=>null],
                    ['sort_order'=>2,'amount'=>400,'unit'=>'g',  'name'=>'Canned Crushed Tomatoes', 'state'=>null],
                    ['sort_order'=>3,'amount'=>200,'unit'=>'ml', 'name'=>'Heavy Cream',             'state'=>null],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'tsp','name'=>'Kashmiri Chilli Powder',  'state'=>null],
                    ['sort_order'=>5,'amount'=>1,  'unit'=>'tsp','name'=>'Fenugreek Leaves (Kasuri Methi)','state'=>'crushed'],
                    ['sort_order'=>6,'amount'=>1,  'unit'=>'tbsp','name'=>'Garam Masala',           'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Marinate and grill/broil chicken until charred. Rest and cube.'],
                    ['step_number'=>2,'instruction'=>'Melt butter in pan. Sauté garlic and ginger. Add tomatoes and spices. Cook 15 min.'],
                    ['step_number'=>3,'instruction'=>'Blend sauce until completely smooth. Pass through a sieve.'],
                    ['step_number'=>4,'instruction'=>'Return sauce to pan. Add chicken and cream. Simmer 10 min.'],
                    ['step_number'=>5,'instruction'=>'Finish with crushed kasuri methi and butter. Serve with naan.'],
                ],
            ],

            // ── 19 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Shakshuka',
                    'slug'             => 'shakshuka',
                    'excerpt'          => 'Eggs poached in a spiced, chunky tomato-pepper sauce. A Middle Eastern and North African one-pan wonder.',
                    'cover_image' => 'https://loremflickr.com/800/600/food,shakshuka?lock=407',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 25,
                    'servings'         => 3,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>2,  'unit'=>'tbsp','name'=>'Olive Oil',             'state'=>null],
                    ['sort_order'=>1,'amount'=>1,  'unit'=>'pcs', 'name'=>'Onion',                 'state'=>'chopped'],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'pcs', 'name'=>'Red Bell Pepper',       'state'=>'chopped'],
                    ['sort_order'=>3,'amount'=>3,  'unit'=>'pcs', 'name'=>'Garlic Cloves',         'state'=>'minced'],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'tsp', 'name'=>'Ground Cumin',          'state'=>null],
                    ['sort_order'=>5,'amount'=>1,  'unit'=>'tsp', 'name'=>'Paprika',               'state'=>null],
                    ['sort_order'=>6,'amount'=>800,'unit'=>'g',   'name'=>'Canned Crushed Tomatoes', 'state'=>null],
                    ['sort_order'=>7,'amount'=>5,  'unit'=>'pcs', 'name'=>'Eggs',                  'state'=>null],
                    ['sort_order'=>8,'amount'=>50, 'unit'=>'g',   'name'=>'Feta Cheese',           'state'=>'crumbled'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Heat olive oil in a large skillet. Sauté onion and bell pepper until soft (~5 min).'],
                    ['step_number'=>2,'instruction'=>'Add garlic, cumin, and paprika; cook for 1 minute until fragrant.'],
                    ['step_number'=>3,'instruction'=>'Pour in tomatoes, season with salt and pepper, and simmer for 10 minutes until thickened.'],
                    ['step_number'=>4,'instruction'=>'Use a spoon to make 5 small wells in the sauce. Crack an egg into each well.'],
                    ['step_number'=>5,'instruction'=>'Cover and cook on low heat for 5-8 minutes until whites are set but yolks are runny.'],
                    ['step_number'=>6,'instruction'=>'Top with crumbled feta cheese and fresh cilantro before serving.'],
                ],
            ],

            // ── 20 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Bananas Foster Pancakes',
                    'slug'             => 'bananas-foster-pancakes',
                    'excerpt'          => 'Thick buttermilk pancakes topped with sliced bananas caramelised in brown butter, brown sugar, and dark rum.',
                    'cover_image'      => 'https://loremflickr.com/800/600/food,pancakes?lock=403',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 15,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>240,'unit'=>'g',  'name'=>'All-Purpose Flour',       'state'=>null],
                    ['sort_order'=>1,'amount'=>350,'unit'=>'ml', 'name'=>'Buttermilk',              'state'=>null],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'pcs','name'=>'Eggs',                    'state'=>null],
                    ['sort_order'=>3,'amount'=>3,  'unit'=>'pcs','name'=>'Ripe Bananas',            'state'=>'sliced'],
                    ['sort_order'=>4,'amount'=>100,'unit'=>'g',  'name'=>'Brown Sugar',             'state'=>null],
                    ['sort_order'=>5,'amount'=>60, 'unit'=>'g',  'name'=>'Unsalted Butter',         'state'=>null],
                    ['sort_order'=>6,'amount'=>45, 'unit'=>'ml', 'name'=>'Dark Rum',                'state'=>null],
                    ['sort_order'=>7,'amount'=>1,  'unit'=>'tsp','name'=>'Cinnamon',                'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Whisk together flour, baking powder, salt. In another bowl, whisk buttermilk, eggs, butter.'],
                    ['step_number'=>2,'instruction'=>'Combine wet and dry until just combined (lumps are fine). Rest 5 min.'],
                    ['step_number'=>3,'instruction'=>'Cook on greased griddle over medium heat until bubbles form; flip once.'],
                    ['step_number'=>4,'instruction'=>'Melt butter with brown sugar and cinnamon until bubbling. Add bananas; cook 2 min.'],
                    ['step_number'=>5,'instruction'=>'Add rum (carefully — may flame). Spoon over stacked pancakes and serve.'],
                ],
            ],

            // ── 21 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Classic American Hamburger',
                    'slug'             => 'classic-american-hamburger',
                    'excerpt'          => 'A juicy grilled beef patty topped with lettuce, tomato, cheese, and signature burger sauce on a toasted brioche bun.',
                    'cover_image'      => '/images/recipes/hamburger.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 10,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>600,'unit'=>'g',   'name'=>'Ground Beef (80/20)',    'state'=>'shaped into 4 patties'],
                    ['sort_order'=>1,'amount'=>4,  'unit'=>'pcs', 'name'=>'Brioche Buns',           'state'=>'split & toasted'],
                    ['sort_order'=>2,'amount'=>4,  'unit'=>'slices','name'=>'Cheddar Cheese',       'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'pcs', 'name'=>'Beefsteak Tomato',       'state'=>'sliced'],
                    ['sort_order'=>4,'amount'=>4,  'unit'=>'leaves','name'=>'Iceberg Lettuce',      'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Preheat grill or cast-iron skillet to high heat.'],
                    ['step_number'=>2,'instruction'=>'Season patties generously with salt and pepper on both sides.'],
                    ['step_number'=>3,'instruction'=>'Sear patties for 3-4 minutes on the first side. Flip, place cheese on top, and cook another 3 minutes.'],
                    ['step_number'=>4,'instruction'=>'Toast brioche buns on the grill/pan with butter.'],
                    ['step_number'=>5,'instruction'=>'Assemble: bottom bun -> burger sauce -> lettuce -> patty with melted cheese -> tomato -> top bun.'],
                ],
            ],
            // ── 22 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Crispy Pepperoni Pizza',
                    'slug'             => 'crispy-pepperoni-pizza',
                    'excerpt'          => 'Homemade pizza with crispy pepperoni slices, bubbling mozzarella, and savory tomato sauce.',
                    'cover_image'      => '/images/recipes/pepperoni-pizza.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 12,
                    'servings'         => 3,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1,  'unit'=>'pcs', 'name'=>'Pizza Dough Ball',        'state'=>'store-bought or homemade'],
                    ['sort_order'=>1,'amount'=>120,'unit'=>'g',   'name'=>'Mozzarella Cheese',      'state'=>'shredded'],
                    ['sort_order'=>2,'amount'=>80, 'unit'=>'g',   'name'=>'Pepperoni Slices',       'state'=>null],
                    ['sort_order'=>3,'amount'=>80, 'unit'=>'ml',  'name'=>'Pizza Sauce',            'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Preheat oven to 250°C. Stretch pizza dough onto a round baking sheet.'],
                    ['step_number'=>2,'instruction'=>'Spread pizza sauce evenly, leaving a border for the crust.'],
                    ['step_number'=>3,'instruction'=>'Sprinkle shredded mozzarella cheese, then lay pepperoni slices on top.'],
                    ['step_number'=>4,'instruction'=>'Bake for 10-12 minutes until crust is golden and cheese is bubbly.'],
                ],
            ],
            // ── 23 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Traditional Beef Lasagna',
                    'slug'             => 'traditional-beef-lasagna',
                    'excerpt'          => 'Layers of flat pasta sheets, hearty meat sauce, and creamy béchamel, baked to perfection.',
                    'cover_image'      => '/images/recipes/lasagna.png',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 45,
                    'servings'         => 8,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',   'name'=>'Ground Beef',            'state'=>null],
                    ['sort_order'=>1,'amount'=>12, 'unit'=>'pcs', 'name'=>'Lasagna Sheets',         'state'=>null],
                    ['sort_order'=>2,'amount'=>500,'unit'=>'ml',  'name'=>'Marinara Sauce',         'state'=>null],
                    ['sort_order'=>3,'amount'=>300,'unit'=>'g',   'name'=>'Ricotta Cheese',         'state'=>null],
                    ['sort_order'=>4,'amount'=>200,'unit'=>'g',   'name'=>'Mozzarella Cheese',      'state'=>'shredded'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Brown the ground beef in a skillet. Add marinara sauce and simmer for 10 minutes.'],
                    ['step_number'=>2,'instruction'=>'Boil lasagna sheets according to package directions, then drain.'],
                    ['step_number'=>3,'instruction'=>'In a baking dish, layer meat sauce, lasagna sheets, ricotta, and mozzarella.'],
                    ['step_number'=>4,'instruction'=>'Repeat layers, finishing with meat sauce and mozzarella on top.'],
                    ['step_number'=>5,'instruction'=>'Bake at 190°C for 35 minutes until bubbling and golden.'],
                ],
            ],
            // ── 24 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Fudgy Chocolate Brownies',
                    'slug'             => 'fudgy-chocolate-brownies',
                    'excerpt'          => 'Rich, dense, and super fudgy brownies with a shiny crinkle top and pockets of melted chocolate.',
                    'cover_image'      => '/images/recipes/brownies.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 25,
                    'servings'         => 16,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>200,'unit'=>'g',   'name'=>'Unsalted Butter',        'state'=>'melted'],
                    ['sort_order'=>1,'amount'=>200,'unit'=>'g',   'name'=>'Granulated Sugar',       'state'=>null],
                    ['sort_order'=>2,'amount'=>100,'unit'=>'g',   'name'=>'Cocoa Powder',           'state'=>'sifted'],
                    ['sort_order'=>3,'amount'=>3,  'unit'=>'pcs', 'name'=>'Large Eggs',               'state'=>null],
                    ['sort_order'=>4,'amount'=>100,'unit'=>'g',   'name'=>'Chocolate Chips',        'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Preheat oven to 180°C. Line an 8x8 inch baking pan with parchment paper.'],
                    ['step_number'=>2,'instruction'=>'Whisk melted butter and sugar. Add eggs one at a time, whisking well.'],
                    ['step_number'=>3,'instruction'=>'Fold in cocoa powder and a pinch of salt. Fold in chocolate chips.'],
                    ['step_number'=>4,'instruction'=>'Pour batter into pan and bake for 22-25 minutes. Cool completely before slicing.'],
                ],
            ],
            // ── 25 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Cheesy Chicken Quesadilla',
                    'slug'             => 'cheesy-chicken-quesadilla',
                    'excerpt'          => 'Warm flour tortillas filled with juicy seasoned chicken and a blend of melted Mexican cheeses.',
                    'cover_image'      => '/images/recipes/quesadilla.png',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 10,
                    'servings'         => 2,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>2,  'unit'=>'pcs', 'name'=>'Large Flour Tortillas',   'state'=>null],
                    ['sort_order'=>1,'amount'=>200,'unit'=>'g',   'name'=>'Cooked Chicken Breast',  'state'=>'shredded'],
                    ['sort_order'=>2,'amount'=>150,'unit'=>'g',   'name'=>'Mexican Blend Cheese',   'state'=>'shredded'],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'tbsp','name'=>'Butter',                 'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Heat a skillet over medium heat. Melt half the butter.'],
                    ['step_number'=>2,'instruction'=>'Place one tortilla in the skillet. Top with half the cheese, chicken, and remaining cheese.'],
                    ['step_number'=>3,'instruction'=>'Fold the tortilla in half. Cook for 3-4 minutes per side until golden and cheese is melted.'],
                    ['step_number'=>4,'instruction'=>'Slice into wedges and serve with sour cream and salsa.'],
                ],
            ],
            // ── 26 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Golden French Fries',
                    'slug'             => 'golden-french-fries',
                    'excerpt'          => 'Crispy on the outside, fluffy on the inside, salted to perfection.',
                    'cover_image'      => '/images/recipes/french-fries.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 20,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>4,  'unit'=>'pcs', 'name'=>'Russet Potatoes',         'state'=>'peeled & cut into sticks'],
                    ['sort_order'=>1,'amount'=>1,  'unit'=>'l',   'name'=>'Vegetable Oil',          'state'=>'for deep frying'],
                    ['sort_order'=>2,'amount'=>5,  'unit'=>'g',   'name'=>'Sea Salt',               'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Soak potato sticks in cold water for 30 minutes to remove excess starch.'],
                    ['step_number'=>2,'instruction'=>'Drain and dry potato sticks completely with a towel.'],
                    ['step_number'=>3,'instruction'=>'Fry in oil preheated to 160°C for 5 minutes, then remove and drain.'],
                    ['step_number'=>4,'instruction'=>'Increase oil temp to 190°C and fry again for 3-4 minutes until golden-brown and crispy.'],
                    ['step_number'=>5,'instruction'=>'Toss immediately with sea salt.'],
                ],
            ],
            // ── 27 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Aromatic Chicken Biryani',
                    'slug'             => 'aromatic-chicken-biryani',
                    'excerpt'          => 'Fragrant basmati rice layered with spiced chicken, caramelized onions, and fresh herbs.',
                    'cover_image'      => '/images/recipes/biryani.png',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 45,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',   'name'=>'Basmati Rice',           'state'=>null],
                    ['sort_order'=>1,'amount'=>600,'unit'=>'g',   'name'=>'Chicken Thighs',          'state'=>'boneless, cubed'],
                    ['sort_order'=>2,'amount'=>2,  'unit'=>'pcs', 'name'=>'Onions',                 'state'=>'sliced & fried'],
                    ['sort_order'=>3,'amount'=>200,'unit'=>'g',   'name'=>'Yogurt',                 'state'=>null],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'tbsp','name'=>'Biryani Masala',          'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Marinate chicken in yogurt, biryani masala, and salt for 1 hour.'],
                    ['step_number'=>2,'instruction'=>'Parboil basmati rice with whole spices until 70% cooked. Drain.'],
                    ['step_number'=>3,'instruction'=>'Cook marinated chicken in a large pot until tender.'],
                    ['step_number'=>4,'instruction'=>'Layer the rice on top of chicken, sprinkle fried onions and fresh mint.'],
                    ['step_number'=>5,'instruction'=>'Cover tightly and cook on low heat (dum) for 15-20 minutes.'],
                ],
            ],
            // ── 28 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Crispy Peking Duck',
                    'slug'             => 'crispy-peking-duck',
                    'excerpt'          => 'A classic Chinese roast duck with sweet crispy skin, served with thin pancakes and hoisin sauce.',
                    'cover_image'      => '/images/recipes/peking-duck.png',
                    'prep_time_minutes'=> 180,
                    'cook_time_minutes'=> 90,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1,  'unit'=>'pcs', 'name'=>'Whole Duck',              'state'=>'cleaned'],
                    ['sort_order'=>1,'amount'=>3,  'unit'=>'tbsp','name'=>'Maltose Syrup',           'state'=>null],
                    ['sort_order'=>2,'amount'=>12, 'unit'=>'pcs', 'name'=>'Mandarin Pancakes',      'state'=>null],
                    ['sort_order'=>3,'amount'=>80, 'unit'=>'ml',  'name'=>'Hoisin Sauce',            'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'pcs', 'name'=>'Cucumber',               'state'=>'julienned'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Blanch the duck skin with boiling water to tighten it.'],
                    ['step_number'=>2,'instruction'=>'Brush with maltose syrup and hang in a cool, drafty place to dry for 24 hours.'],
                    ['step_number'=>3,'instruction'=>'Roast the duck in a preheated oven at 180°C for 70-80 minutes until skin is crispy.'],
                    ['step_number'=>4,'instruction'=>'Carve skin and meat into thin slices. Serve wrapped in pancakes with cucumber and hoisin.'],
                ],
            ],
            // ── 29 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'British Fish and Chips',
                    'slug'             => 'british-fish-and-chips',
                    'excerpt'          => 'Crispy beer-battered cod fillets served alongside chunky golden-brown potato chips.',
                    'cover_image'      => '/images/recipes/fish-and-chips.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 15,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>600,'unit'=>'g',   'name'=>'Cod Fillets',            'state'=>'boneless'],
                    ['sort_order'=>1,'amount'=>200,'unit'=>'g',   'name'=>'All-Purpose Flour',       'state'=>null],
                    ['sort_order'=>2,'amount'=>250,'unit'=>'ml',  'name'=>'Cold Beer',              'state'=>null],
                    ['sort_order'=>3,'amount'=>4,  'unit'=>'pcs', 'name'=>'Large Potatoes',         'state'=>'cut into wedges'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Whisk flour with cold beer and a pinch of salt to form a smooth batter.'],
                    ['step_number'=>2,'instruction'=>'Pat fish fillets dry, dust with flour, and dip into the beer batter.'],
                    ['step_number'=>3,'instruction'=>'Deep fry fish at 180°C until batter is golden-brown and crispy.'],
                    ['step_number'=>4,'instruction'=>'Double-fry potato wedges until crispy and serve hot with tartar sauce.'],
                ],
            ],
            // ── 30 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Elegant Beef Wellington',
                    'slug'             => 'elegant-beef-wellington',
                    'excerpt'          => 'Tender beef tenderloin wrapped in rich mushroom duxelles, parma ham, and golden puff pastry.',
                    'cover_image'      => '/images/recipes/beef-wellington.png',
                    'prep_time_minutes'=> 60,
                    'cook_time_minutes'=> 40,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>800,'unit'=>'g',   'name'=>'Beef Tenderloin Center-Cut','state'=>'trimmed'],
                    ['sort_order'=>1,'amount'=>400,'unit'=>'g',   'name'=>'Mixed Mushrooms',        'state'=>'finely chopped'],
                    ['sort_order'=>2,'amount'=>8,  'unit'=>'slices','name'=>'Prosciutto di Parma',   'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'pcs', 'name'=>'Puff Pastry Sheet',       'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Sear beef tenderloin on all sides in a hot skillet, then brush with mustard and cool.'],
                    ['step_number'=>2,'instruction'=>'Sauté chopped mushrooms until all liquid evaporates to make a dry paste (duxelles).'],
                    ['step_number'=>3,'instruction'=>'Lay prosciutto on plastic wrap, spread duxelles, then wrap around the beef.'],
                    ['step_number'=>4,'instruction'=>'Wrap the entire log tightly in puff pastry. Chill for 30 minutes.'],
                    ['step_number'=>5,'instruction'=>'Bake at 200°C for 30-35 minutes until pastry is golden-brown.'],
                ],
            ],
            // ── 31 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Vietnamese Beef Pho',
                    'slug'             => 'vietnamese-beef-pho',
                    'excerpt'          => 'A comforting noodle soup with aromatic beef broth, flat rice noodles, and tender beef slices.',
                    'cover_image'      => '/images/recipes/pho.png',
                    'prep_time_minutes'=> 30,
                    'cook_time_minutes'=> 360,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>1000,'unit'=>'g',  'name'=>'Beef Marrow Bones',      'state'=>null],
                    ['sort_order'=>1,'amount'=>400,'unit'=>'g',   'name'=>'Flat Rice Noodles',      'state'=>null],
                    ['sort_order'=>2,'amount'=>250,'unit'=>'g',   'name'=>'Beef Sirloin',           'state'=>'sliced paper-thin'],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'pcs', 'name'=>'Onion',                  'state'=>'charred'],
                    ['sort_order'=>4,'amount'=>40, 'unit'=>'g',   'name'=>'Fresh Ginger',            'state'=>'charred'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Simmer beef bones, charred onion, and ginger with star anise, cinnamon, and cloves for 6 hours.'],
                    ['step_number'=>2,'instruction'=>'Strain broth and season with fish sauce and rock sugar.'],
                    ['step_number'=>3,'instruction'=>'Cook rice noodles and place in serving bowls.'],
                    ['step_number'=>4,'instruction'=>'Arrange raw sirloin slices on noodles, then pour boiling hot broth over to cook the beef.'],
                ],
            ],
            // ── 32 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Fluffy French Toast',
                    'slug'             => 'fluffy-french-toast',
                    'excerpt'          => 'Thick slices of brioche bread soaked in a rich custard of eggs, milk, cinnamon, and vanilla, griddled golden brown.',
                    'cover_image'      => '/images/recipes/french-toast.png',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 10,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>8,  'unit'=>'slices','name'=>'Brioche Bread',         'state'=>'thickly sliced'],
                    ['sort_order'=>1,'amount'=>4,  'unit'=>'pcs', 'name'=>'Large Eggs',               'state'=>null],
                    ['sort_order'=>2,'amount'=>240,'unit'=>'ml',  'name'=>'Whole Milk',             'state'=>null],
                    ['sort_order'=>3,'amount'=>5,  'unit'=>'ml',  'name'=>'Vanilla Extract',        'state'=>null],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'g',   'name'=>'Ground Cinnamon',        'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Whisk eggs, milk, vanilla, and cinnamon in a shallow dish.'],
                    ['step_number'=>2,'instruction'=>'Dip brioche slices in the egg mixture, allowing them to soak for 15-20 seconds per side.'],
                    ['step_number'=>3,'instruction'=>'Cook on a buttered griddle over medium heat until golden-brown on both sides.'],
                    ['step_number'=>4,'instruction'=>'Serve warm with maple syrup and fresh berries.'],
                ],
            ],
            // ── 33 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Hearty Beef Burrito',
                    'slug'             => 'hearty-beef-burrito',
                    'excerpt'          => 'A large flour tortilla wrapped around seasoned ground beef, Mexican rice, black beans, and melted cheese.',
                    'cover_image'      => '/images/recipes/burrito.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 15,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>4,  'unit'=>'pcs', 'name'=>'Extra Large Tortillas',   'state'=>null],
                    ['sort_order'=>1,'amount'=>400,'unit'=>'g',   'name'=>'Ground Beef',            'state'=>'seasoned'],
                    ['sort_order'=>2,'amount'=>200,'unit'=>'g',   'name'=>'Cooked Black Beans',     'state'=>null],
                    ['sort_order'=>3,'amount'=>200,'unit'=>'g',   'name'=>'Cooked Rice',            'state'=>null],
                    ['sort_order'=>4,'amount'=>150,'unit'=>'g',   'name'=>'Cheddar Cheese',         'state'=>'shredded'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Warm flour tortillas in a pan.'],
                    ['step_number'=>2,'instruction'=>'Lay cooked rice, seasoned beef, black beans, and shredded cheese down the center.'],
                    ['step_number'=>3,'instruction'=>'Fold in the sides and roll tightly from the bottom up.'],
                    ['step_number'=>4,'instruction'=>'Sear the seam-side down in a hot pan for 1 minute to seal the burrito.'],
                ],
            ],
            // ── 34 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Creamy Clam Chowder',
                    'slug'             => 'creamy-clam-chowder',
                    'excerpt'          => 'New England-style clam chowder featuring sweet chopped clams, diced potatoes, and crispy bacon in a rich cream broth.',
                    'cover_image'      => '/images/recipes/clam-chowder.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 25,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>400,'unit'=>'g',   'name'=>'Canned Clams',           'state'=>'drained, juice reserved'],
                    ['sort_order'=>1,'amount'=>3,  'unit'=>'pcs', 'name'=>'Potatoes',               'state'=>'peeled & diced'],
                    ['sort_order'=>2,'amount'=>100,'unit'=>'g',   'name'=>'Bacon',                  'state'=>'chopped'],
                    ['sort_order'=>3,'amount'=>240,'unit'=>'ml',  'name'=>'Heavy Cream',             'state'=>null],
                    ['sort_order'=>4,'amount'=>1,  'unit'=>'pcs', 'name'=>'Onion',                  'state'=>'diced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Cook chopped bacon in a pot until crisp; remove bacon but leave fat.'],
                    ['step_number'=>2,'instruction'=>'Sauté onion in bacon fat until soft. Sprinkle with 2 tbsp flour and cook 1 min.'],
                    ['step_number'=>3,'instruction'=>'Slowly whisk in reserved clam juice and potatoes. Simmer until potatoes are tender.'],
                    ['step_number'=>4,'instruction'=>'Stir in heavy cream and clams; simmer 5 minutes. Top with crispy bacon.'],
                ],
            ],
            // ── 35 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Baked Macaroni and Cheese',
                    'slug'             => 'baked-macaroni-cheese',
                    'excerpt'          => 'Creamy, rich baked macaroni and cheese with a golden-brown crispy breadcrumb topping.',
                    'cover_image'      => '/images/recipes/mac-and-cheese.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 25,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>400,'unit'=>'g',   'name'=>'Elbow Macaroni',         'state'=>null],
                    ['sort_order'=>1,'amount'=>300,'unit'=>'g',   'name'=>'Sharp Cheddar Cheese',   'state'=>'grated'],
                    ['sort_order'=>2,'amount'=>50, 'unit'=>'g',   'name'=>'Butter',                 'state'=>null],
                    ['sort_order'=>3,'amount'=>500,'unit'=>'ml',  'name'=>'Whole Milk',             'state'=>null],
                    ['sort_order'=>4,'amount'=>50, 'unit'=>'g',   'name'=>'Breadcrumbs',            'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Boil macaroni al dente. Melt butter in a pot, whisk in flour, then slowly add milk.'],
                    ['step_number'=>2,'instruction'=>'Stir in grated cheese until fully melted to form a rich cheese sauce.'],
                    ['step_number'=>3,'instruction'=>'Combine cheese sauce and cooked macaroni in a baking dish.'],
                    ['step_number'=>4,'instruction'=>'Top with extra cheese and breadcrumbs. Bake at 190°C for 25 minutes.'],
                ],
            ],
            // ── 36 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Seafood Paella Valenciana',
                    'slug'             => 'seafood-paella-valenciana',
                    'excerpt'          => 'Saffron-infused Spanish rice cooked with shrimp, mussels, calamari, and sweet peas.',
                    'cover_image'      => '/images/recipes/paella.png',
                    'prep_time_minutes'=> 25,
                    'cook_time_minutes'=> 30,
                    'servings'         => 6,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>300,'unit'=>'g',   'name'=>'Bomba Rice (Spanish)',   'state'=>null],
                    ['sort_order'=>1,'amount'=>8,  'unit'=>'pcs', 'name'=>'Jumbo Shrimp',           'state'=>'peeled'],
                    ['sort_order'=>2,'amount'=>8,  'unit'=>'pcs', 'name'=>'Mussels',                'state'=>'cleaned'],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'g',   'name'=>'Saffron Threads',         'state'=>null],
                    ['sort_order'=>4,'amount'=>800,'unit'=>'ml',  'name'=>'Seafood Broth',          'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Sauté shrimp and calamari in a paella pan with olive oil, then remove.'],
                    ['step_number'=>2,'instruction'=>'Sauté garlic and tomato paste. Add rice and stir to coat.'],
                    ['step_number'=>3,'instruction'=>'Pour in saffron-infused seafood broth. Bring to a simmer without stirring.'],
                    ['step_number'=>4,'instruction'=>'Nestle shrimp, mussels, and peas into the rice. Cook until rice absorbs broth and forms a crispy crust at the bottom (socarrat).'],
                ],
            ],
            // ── 37 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Greek Lamb Gyro',
                    'slug'             => 'greek-lamb-gyro',
                    'excerpt'          => 'Seasoned roasted lamb wrapped in warm pita bread with lettuce, tomatoes, red onions, and cool tzatziki sauce.',
                    'cover_image'      => '/images/recipes/gyro.png',
                    'prep_time_minutes'=> 20,
                    'cook_time_minutes'=> 20,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>500,'unit'=>'g',   'name'=>'Ground Lamb',            'state'=>'seasoned with garlic/oregano'],
                    ['sort_order'=>1,'amount'=>4,  'unit'=>'pcs', 'name'=>'Pita Buns',              'state'=>'warmed'],
                    ['sort_order'=>2,'amount'=>150,'unit'=>'ml',  'name'=>'Tzatziki Sauce',         'state'=>null],
                    ['sort_order'=>3,'amount'=>1,  'unit'=>'pcs', 'name'=>'Red Onion',               'state'=>'sliced'],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'pcs', 'name'=>'Roma Tomatoes',           'state'=>'sliced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Shape seasoned lamb into thin patties and cook in a skillet until browned.'],
                    ['step_number'=>2,'instruction'=>'Slice cooked lamb into thin strips.'],
                    ['step_number'=>3,'instruction'=>'Spread tzatziki sauce on warm pita bread.'],
                    ['step_number'=>4,'instruction'=>'Top with lamb strips, tomatoes, onion, and lettuce; wrap tightly in foil.'],
                ],
            ],
            // ── 38 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'New England Lobster Roll',
                    'slug'             => 'new-england-lobster-roll',
                    'excerpt'          => 'Sweet lobster meat tossed in a light lemon-mayo dressing, stuffed inside a butter-toasted split-top bun.',
                    'cover_image'      => '/images/recipes/lobster-roll.png',
                    'prep_time_minutes'=> 15,
                    'cook_time_minutes'=> 5,
                    'servings'         => 2,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>300,'unit'=>'g',   'name'=>'Fresh Lobster Meat',     'state'=>'cooked & chopped'],
                    ['sort_order'=>1,'amount'=>30, 'unit'=>'ml',  'name'=>'Mayonnaise',             'state'=>null],
                    ['sort_order'=>2,'amount'=>1,  'unit'=>'tbsp','name'=>'Butter',                 'state'=>'melted'],
                    ['sort_order'=>3,'amount'=>2,  'unit'=>'pcs', 'name'=>'Split-Top Brioche Rolls','state'=>null],
                    ['sort_order'=>4,'amount'=>10, 'unit'=>'ml',  'name'=>'Fresh Lemon Juice',        'state'=>null],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Toss lobster meat with mayonnaise, lemon juice, and chopped chives.'],
                    ['step_number'=>2,'instruction'=>'Brush brioche rolls with melted butter.'],
                    ['step_number'=>3,'instruction'=>'Grill rolls on both sides in a skillet until golden-brown.'],
                    ['step_number'=>4,'instruction'=>'Stuff lobster salad into toasted rolls and serve immediately.'],
                ],
            ],
            // ── 39 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Creamy Tomato Basil Soup',
                    'slug'             => 'creamy-tomato-basil-soup',
                    'excerpt'          => 'Smooth tomato soup simmered with fresh basil and garlic, finished with a splash of heavy cream.',
                    'cover_image'      => '/images/recipes/tomato-soup.png',
                    'prep_time_minutes'=> 10,
                    'cook_time_minutes'=> 20,
                    'servings'         => 4,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>800,'unit'=>'g',   'name'=>'Canned Crushed Tomatoes', 'state'=>null],
                    ['sort_order'=>1,'amount'=>500,'unit'=>'ml',  'name'=>'Vegetable Broth',        'state'=>null],
                    ['sort_order'=>2,'amount'=>100,'unit'=>'ml',  'name'=>'Heavy Cream',             'state'=>null],
                    ['sort_order'=>3,'amount'=>20, 'unit'=>'g',   'name'=>'Fresh Basil Leaves',      'state'=>'chopped'],
                    ['sort_order'=>4,'amount'=>2,  'unit'=>'pcs', 'name'=>'Garlic Cloves',           'state'=>'minced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Sauté garlic in olive oil in a pot until fragrant.'],
                    ['step_number'=>2,'instruction'=>'Add crushed tomatoes, vegetable broth, and basil. Simmer for 15 minutes.'],
                    ['step_number'=>3,'instruction'=>'Puree soup with an immersion blender until smooth.'],
                    ['step_number'=>4,'instruction'=>'Stir in heavy cream, season with salt and pepper, and simmer 2 minutes.'],
                ],
            ],
            // ── 40 ────────────────────────────────────────────────────────
            [
                'meta' => [
                    'title'            => 'Sweet Strawberry Cheesecake',
                    'slug'             => 'sweet-strawberry-cheesecake',
                    'excerpt'          => 'Rich and creamy baked New York cheesecake topped with a sweet strawberry compote.',
                    'cover_image'      => '/images/recipes/cheesecake.png',
                    'prep_time_minutes'=> 25,
                    'cook_time_minutes'=> 60,
                    'servings'         => 12,
                    'status'           => 'published',
                ],
                'ingredients' => [
                    ['sort_order'=>0,'amount'=>200,'unit'=>'g',   'name'=>'Graham Crackers',        'state'=>'crushed'],
                    ['sort_order'=>1,'amount'=>600,'unit'=>'g',   'name'=>'Cream Cheese',           'state'=>'room temperature'],
                    ['sort_order'=>2,'amount'=>150,'unit'=>'g',   'name'=>'Sugar',                  'state'=>null],
                    ['sort_order'=>3,'amount'=>3,  'unit'=>'pcs', 'name'=>'Eggs',                    'state'=>null],
                    ['sort_order'=>4,'amount'=>200,'unit'=>'g',   'name'=>'Fresh Strawberries',     'state'=>'sliced'],
                ],
                'steps' => [
                    ['step_number'=>1,'instruction'=>'Mix graham cracker crumbs with 50g melted butter. Press into a springform pan and bake 8 min at 180°C.'],
                    ['step_number'=>2,'instruction'=>'Beat cream cheese and sugar until smooth. Add eggs one at a time.'],
                    ['step_number'=>3,'instruction'=>'Pour filling over crust. Bake at 160°C for 50-60 minutes until edges are set.'],
                    ['step_number'=>4,'instruction'=>'Chill overnight. Top with strawberry compote before serving.'],
                ],
            ],

        ];

        // ─────────────────────────────────────────────────────────────────────
        // Insert all 40 recipes
        // ─────────────────────────────────────────────────────────────────────
        foreach ($recipes as $data) {
            $title = $data['meta']['title'];
            $catSlug = 'main-courses'; // default
            if (Str::contains($title, ['Cookies', 'Cake', 'Tiramisu', 'Cheesecake', 'Chocolate'], true)) {
                $catSlug = 'desserts';
            } elseif (Str::contains($title, ['Salad', 'Soup', 'Tacos', 'Clam Chowder', 'Fries'], true)) {
                $catSlug = 'appetizers-salads';
            } elseif (Str::contains($title, ['Eggs', 'Toast', 'Shakshuka', 'Benedict'], true)) {
                $catSlug = 'breakfast-brunch';
            } elseif (Str::contains($title, ['Bread'], true)) {
                $catSlug = 'side-dishes';
            }

            $catId = isset($categories[$catSlug]) ? $categories[$catSlug]->id : null;

            $recipe = Recipe::create(array_merge($data['meta'], [
                'tenant_uuid' => $tenantUuid,
                'author_id'   => $user->id,
                'category_id' => $catId,
                'description_html' => '<p>This is a delicious recipe for ' . e($title) . '. Perfect for sharing with friends and family!</p>',
            ]));

            $recipe->ingredients()->createMany($data['ingredients']);
            $recipe->steps()->createMany($data['steps']);
        }

        $this->call(ImportedRecipesSeeder::class);
    }
}

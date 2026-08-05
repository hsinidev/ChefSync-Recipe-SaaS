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

class WorldRecipesSeeder extends Seeder
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

        // 100 Recipes Data from 10 Famous Countries (10 each)
        $recipesData = [
            // ---------------- ITALY ----------------
            [
                'title' => 'Classic Meat Lasagna', 'country' => 'Italy', 'category' => 'main-courses',
                'excerpt' => 'Layers of fresh pasta sheet, rich slow-cooked bolognese meat sauce, creamy bechamel, and melted mozzarella.',
                'image' => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 45, 'servings' => 8
            ],
            [
                'title' => 'Pizza Margherita', 'country' => 'Italy', 'category' => 'main-courses',
                'excerpt' => 'The simple and classic Neapolitan pizza with crushed San Marzano tomatoes, fresh mozzarella, and basil.',
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 10, 'servings' => 2
            ],
            [
                'title' => 'Risotto alla Milanese', 'country' => 'Italy', 'category' => 'main-courses',
                'excerpt' => 'Saffron-infused creamy Arborio rice slow-cooked with white wine, broth, and finished with Parmigiano.',
                'image' => 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Tomato Bruschetta', 'country' => 'Italy', 'category' => 'appetizers-salads',
                'excerpt' => 'Toasted crusty Italian bread topped with ripe diced tomatoes, fresh garlic, basil, and extra virgin olive oil.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 5, 'servings' => 4
            ],
            [
                'title' => 'Fettuccine Alfredo', 'country' => 'Italy', 'category' => 'main-courses',
                'excerpt' => 'Indulgently rich and creamy pasta tossed in a luxurious butter and freshly grated Parmigiano-Reggiano sauce.',
                'image' => 'https://images.unsplash.com/photo-1645112411341-6c4fd023714a?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Tuscan Minestrone Soup', 'country' => 'Italy', 'category' => 'appetizers-salads',
                'excerpt' => 'A hearty Italian vegetable soup filled with seasonal vegetables, beans, and small pasta in a tomato broth.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 30, 'servings' => 6
            ],
            [
                'title' => 'Potato Gnocchi', 'country' => 'Italy', 'category' => 'main-courses',
                'excerpt' => 'Soft, pillowy homemade potato dumplings served in a fresh, aromatic tomato and basil sauce.',
                'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=800&auto=format&fit=crop',
                'prep' => 45, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Vanilla Panna Cotta', 'country' => 'Italy', 'category' => 'desserts',
                'excerpt' => 'Silky and delicate Italian cooked cream dessert served with a fresh, sweet raspberry coulis.',
                'image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 5, 'servings' => 4
            ],
            [
                'title' => 'Sicilian Cannoli', 'country' => 'Italy', 'category' => 'desserts',
                'excerpt' => 'Crispy fried pastry shells stuffed with a sweet, creamy ricotta cheese filling and chocolate chips.',
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 15, 'servings' => 8
            ],
            [
                'title' => 'Osso Buco alla Milanese', 'country' => 'Italy', 'category' => 'main-courses',
                'excerpt' => 'Tender cross-cut veal shanks braised in white wine, broth, and vegetables, garnished with gremolata.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 120, 'servings' => 4
            ],

            // ---------------- FRANCE ----------------
            [
                'title' => 'Coq au Vin', 'country' => 'France', 'category' => 'main-courses',
                'excerpt' => 'Classic French chicken braised in full-bodied red Burgundy wine, lardons, mushrooms, and pearl onions.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 25, 'cook' => 60, 'servings' => 6
            ],
            [
                'title' => 'Provencal Ratatouille', 'country' => 'France', 'category' => 'side-dishes',
                'excerpt' => 'A colorful stewed vegetable dish from Nice, featuring eggplant, zucchini, bell peppers, and tomatoes.',
                'image' => 'https://images.unsplash.com/photo-1572453800999-e8d2d1589b7c?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 40, 'servings' => 4
            ],
            [
                'title' => 'Marseille Bouillabaisse', 'country' => 'France', 'category' => 'main-courses',
                'excerpt' => 'A traditional rich fish stew originating from the port city of Marseille, infused with saffron and fennel.',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 45, 'servings' => 6
            ],
            [
                'title' => 'Quiche Lorraine', 'country' => 'France', 'category' => 'breakfast-brunch',
                'excerpt' => 'A savory French tart consisting of a flaky pastry crust filled with rich custard, lardons, and Swiss cheese.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 35, 'servings' => 6
            ],
            [
                'title' => 'French Onion Soup', 'country' => 'France', 'category' => 'appetizers-salads',
                'excerpt' => 'Sweet caramelized onions simmered in beef broth, topped with toasted baguette and bubbling gruyere.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 60, 'servings' => 4
            ],
            [
                'title' => 'Classic Crème Brûlée', 'country' => 'France', 'category' => 'desserts',
                'excerpt' => 'Rich, creamy vanilla custard base topped with a contrasting layer of hardened caramelized sugar.',
                'image' => 'https://images.unsplash.com/photo-1470324161839-ce2bb6fa6bc3?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 45, 'servings' => 4
            ],
            [
                'title' => 'Croque Monsieur', 'country' => 'France', 'category' => 'breakfast-brunch',
                'excerpt' => 'An iconic toasted ham and cheese sandwich topped with velvety bechamel sauce and baked until bubbling.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 10, 'servings' => 2
            ],
            [
                'title' => 'French Cassoulet', 'country' => 'France', 'category' => 'main-courses',
                'excerpt' => 'A rich, slow-cooked casserole containing white beans, duck confit, pork skin, and sausage.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 40, 'cook' => 180, 'servings' => 8
            ],
            [
                'title' => 'Boeuf Bourguignon', 'country' => 'France', 'category' => 'main-courses',
                'excerpt' => 'A classic French beef stew braised in red Burgundy wine, beef stock, carrots, garlic, and onions.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 25, 'cook' => 150, 'servings' => 6
            ],
            [
                'title' => 'Garlic Butter Escargots', 'country' => 'France', 'category' => 'appetizers-salads',
                'excerpt' => 'Snails cooked in a delicious, aromatic butter sauce containing parsley, garlic, and shallots.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 4
            ],

            // ---------------- MEXICO ----------------
            [
                'title' => 'Tacos al Pastor', 'country' => 'Mexico', 'category' => 'main-courses',
                'excerpt' => 'Thinly sliced spit-roasted pork marinated in dried chilies and spices, served with fresh pineapple.',
                'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 20, 'servings' => 6
            ],
            [
                'title' => 'Enchiladas Verdes', 'country' => 'Mexico', 'category' => 'main-courses',
                'excerpt' => 'Corn tortillas stuffed with shredded chicken, rolled and bathed in a tangy salsa verde and sour cream.',
                'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Chunky Guacamole', 'country' => 'Mexico', 'category' => 'appetizers-salads',
                'excerpt' => 'Mashed ripe avocados mixed with fresh lime juice, red onions, cilantro, jalapeños, and ripe tomatoes.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Cheese Quesadillas', 'country' => 'Mexico', 'category' => 'appetizers-salads',
                'excerpt' => 'Warm flour tortillas filled with melted Monterey Jack cheese, folded and toasted on a dry griddle.',
                'image' => 'https://images.unsplash.com/photo-1618040996337-56904b7850b9?w=800&auto=format&fit=crop',
                'prep' => 5, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Mole Poblano Chicken', 'country' => 'Mexico', 'category' => 'main-courses',
                'excerpt' => 'Shredded chicken smothered in a complex, rich, dark Mexican sauce made with chilies, nuts, and dark chocolate.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 45, 'cook' => 60, 'servings' => 6
            ],
            [
                'title' => 'Chiles en Nogada', 'country' => 'Mexico', 'category' => 'main-courses',
                'excerpt' => 'Poblano chilies stuffed with spiced picadillo meat, covered in walnut-cream sauce and pomegranate seeds.',
                'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800&auto=format&fit=crop',
                'prep' => 45, 'cook' => 30, 'servings' => 4
            ],
            [
                'title' => 'Traditional Pozole Rojo', 'country' => 'Mexico', 'category' => 'main-courses',
                'excerpt' => 'A comforting, ancestral soup made with hominy corn, pork, and dried red chilies, garnished with radish.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 90, 'servings' => 6
            ],
            [
                'title' => 'Pork Tamales', 'country' => 'Mexico', 'category' => 'breakfast-brunch',
                'excerpt' => 'Corn masa dough stuffed with spiced pork, wrapped in corn husks and steamed to tender perfection.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 60, 'cook' => 45, 'servings' => 12
            ],
            [
                'title' => 'Mexican Churros', 'country' => 'Mexico', 'category' => 'desserts',
                'excerpt' => 'Crispy fried choux pastry sticks rolled in cinnamon sugar, served with warm chocolate dipping sauce.',
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 6
            ],
            [
                'title' => 'Elote (Street Corn)', 'country' => 'Mexico', 'category' => 'side-dishes',
                'excerpt' => 'Grilled sweet corn on the cob slathered in mayonnaise, chili powder, cotija cheese, and fresh lime.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 15, 'servings' => 4
            ],

            // ---------------- JAPAN ----------------
            [
                'title' => 'Salmon Nigiri Sushi', 'country' => 'Japan', 'category' => 'main-courses',
                'excerpt' => 'Perfectly seasoned sushi rice hand-molded and topped with fresh, melt-in-the-mouth raw salmon.',
                'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 0, 'servings' => 2
            ],
            [
                'title' => 'Crispy Shrimp Tempura', 'country' => 'Japan', 'category' => 'appetizers-salads',
                'excerpt' => 'Light, airy, and crispy battered deep-fried shrimp and seasonal vegetables served with tentsuyu sauce.',
                'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Chicken Teriyaki', 'country' => 'Japan', 'category' => 'main-courses',
                'excerpt' => 'Tender pan-seared chicken thighs glazed in a glossy, sweet, and savory homemade teriyaki sauce.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Traditional Miso Soup', 'country' => 'Japan', 'category' => 'side-dishes',
                'excerpt' => 'A warm, soothing Japanese soup made with dashi stock, miso paste, tofu cubes, and green onions.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 5, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Katsudon (Pork Cutlet Bowl)', 'country' => 'Japan', 'category' => 'main-courses',
                'excerpt' => 'Crispy breaded pork cutlet simmered with egg and sweet dashi onions, served over hot steamed rice.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 2
            ],
            [
                'title' => 'Cabbage Okonomiyaki', 'country' => 'Japan', 'category' => 'main-courses',
                'excerpt' => 'Savory Japanese pancakes containing shredded cabbage, pork belly slices, topped with okonomiyaki sauce.',
                'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 15, 'servings' => 2
            ],
            [
                'title' => 'Chicken Yakitori', 'country' => 'Japan', 'category' => 'appetizers-salads',
                'excerpt' => 'Skewered chicken and green onions grilled to perfection and basted with sweet soy glaze (tare).',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Pork Gyoza Dumplings', 'country' => 'Japan', 'category' => 'appetizers-salads',
                'excerpt' => 'Pan-fried Japanese dumplings stuffed with seasoned ground pork, cabbage, ginger, and garlic.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Tempura Udon Noodles', 'country' => 'Japan', 'category' => 'main-courses',
                'excerpt' => 'Thick wheat udon noodles in hot, savory dashi broth, served with light and crispy shrimp tempura.',
                'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 2
            ],
            [
                'title' => 'Octopus Takoyaki', 'country' => 'Japan', 'category' => 'appetizers-salads',
                'excerpt' => 'Crispy on the outside, soft batter balls filled with octopus pieces, topped with bonito flakes and mayo.',
                'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 4
            ],

            // ---------------- INDIA ----------------
            [
                'title' => 'Butter Chicken', 'country' => 'India', 'category' => 'main-courses',
                'excerpt' => 'Tender tandoori chicken cooked in a rich, buttery, velvety tomato and cream curry.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 30, 'servings' => 4
            ],
            [
                'title' => 'Hyderabadi Chicken Biryani', 'country' => 'India', 'category' => 'main-courses',
                'excerpt' => 'Fragrant basmati rice layered with spiced marinated chicken, saffron threads, and caramelized onions.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 45, 'servings' => 6
            ],
            [
                'title' => 'Chicken Tikka Masala Curry', 'country' => 'India', 'category' => 'main-courses',
                'excerpt' => 'Charred skewered chicken pieces simmered in a mildly spiced tomato and cream gravy.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 30, 'servings' => 4
            ],
            [
                'title' => 'Vegetable Samosas', 'country' => 'India', 'category' => 'appetizers-salads',
                'excerpt' => 'Crispy triangular pastry shells stuffed with spiced mashed potatoes and green peas.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 20, 'servings' => 8
            ],
            [
                'title' => 'Chana Masala', 'country' => 'India', 'category' => 'side-dishes',
                'excerpt' => 'A flavorful vegetarian dish of chickpeas simmered in a tangy tomato, onion, and spice gravy.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Palak Paneer', 'country' => 'India', 'category' => 'main-courses',
                'excerpt' => 'Indian cottage cheese cubes (paneer) in a thick, vibrant, spiced spinach puree.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Garlic Naan Bread', 'country' => 'India', 'category' => 'side-dishes',
                'excerpt' => 'Soft, pillowy Indian flatbread brushed with garlic butter, traditionally baked in a tandoor.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 10, 'servings' => 6
            ],
            [
                'title' => 'Tandoori Chicken Skewers', 'country' => 'India', 'category' => 'main-courses',
                'excerpt' => 'Chicken marinated in yogurt and spices (tandoori masala), roasted at high heat.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Aloo Gobi', 'country' => 'India', 'category' => 'side-dishes',
                'excerpt' => 'A classic dry curry of potatoes (aloo) and cauliflower (gobi) tossed in turmeric and cumin.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Gulab Jamun', 'country' => 'India', 'category' => 'desserts',
                'excerpt' => 'Deep-fried dough balls made of milk solids, soaked in cardamom and rose water syrup.',
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 8
            ],

            // ---------------- CHINA ----------------
            [
                'title' => 'Peking Duck', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'Famous roasted duck prized for its thin, crispy skin, served with wraps and hoisin sauce.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 45, 'cook' => 90, 'servings' => 6
            ],
            [
                'title' => 'Kung Pao Chicken', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'A spicy stir-fried dish made with chicken cubes, peanuts, vegetables, and chili peppers.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Dim Sum Shumai', 'country' => 'China', 'category' => 'appetizers-salads',
                'excerpt' => 'Steamed open-faced pork and shrimp dumplings wrapped in a thin wheat flour wrapper.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Sweet and Sour Pork', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'Crispy pork bites stir-fried in a tangy, bright red pineapple sweet and sour sauce.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Sichuan Mapo Tofu', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'Tofu set in a spicy, numbing sauce cooked with ground beef, Sichuan peppercorns, and chili oil.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Crispy Vegetable Spring Rolls', 'country' => 'China', 'category' => 'appetizers-salads',
                'excerpt' => 'Golden fried pastry cylinders filled with seasoned cabbage, carrots, and glass noodles.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 25, 'cook' => 15, 'servings' => 6
            ],
            [
                'title' => 'Pork Wonton Soup', 'country' => 'China', 'category' => 'appetizers-salads',
                'excerpt' => 'Delicate wrappers stuffed with seasoned ground pork, boiled and served in a hot clear broth.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 25, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Yangzhou Fried Rice', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'Classic Chinese wok-fried rice with shrimp, char siu pork, scrambled eggs, and peas.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Chicken Chow Mein', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'Stir-fried egg noodles with tender chicken strips, cabbage, bean sprouts, and soy sauce.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 4
            ],
            [
                'title' => 'Dan Dan Noodles', 'country' => 'China', 'category' => 'main-courses',
                'excerpt' => 'Spicy Sichuan noodles served in a rich sesame paste broth, topped with minced pork.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 2
            ],

            // ---------------- THAILAND ----------------
            [
                'title' => 'Classic Pad Thai', 'country' => 'Thailand', 'category' => 'main-courses',
                'excerpt' => 'Stir-fried flat rice noodles with shrimp, eggs, firm tofu, tamarind sauce, bean sprouts, and peanuts.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 2
            ],
            [
                'title' => 'Tom Yum Goong Soup', 'country' => 'Thailand', 'category' => 'appetizers-salads',
                'excerpt' => 'Hot and sour Thai soup cooked with shrimp, lemongrass, galangal, kaffir lime leaves, and mushrooms.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Thai Green Chicken Curry', 'country' => 'Thailand', 'category' => 'main-courses',
                'excerpt' => 'Tender chicken slices and bamboo shoots simmered in spicy, sweet green chili-coconut milk curry.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Massaman Beef Curry', 'country' => 'Thailand', 'category' => 'main-courses',
                'excerpt' => 'A rich, relatively mild Thai curry flavored with warm cardamoms, cinnamon, cloves, beef, and potatoes.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 60, 'servings' => 4
            ],
            [
                'title' => 'Mango Sticky Rice', 'country' => 'Thailand', 'category' => 'desserts',
                'excerpt' => 'Sweet glutinous sticky rice cooked with coconut milk, served with fresh, sweet mango slices.',
                'image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Som Tum (Green Papaya Salad)', 'country' => 'Thailand', 'category' => 'appetizers-salads',
                'excerpt' => 'Shredded unripened green papaya pounded with garlic, chilies, cherry tomatoes, and lime-fish dressing.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Pad Kra Pao (Basil Pork)', 'country' => 'Thailand', 'category' => 'main-courses',
                'excerpt' => 'Minced pork stir-fried at high heat with fiery bird\'s eye chilies and aromatic holy basil leaves.',
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 10, 'servings' => 2
            ],
            [
                'title' => 'Chicken Satay Skewers', 'country' => 'Thailand', 'category' => 'appetizers-salads',
                'excerpt' => 'Skewered chicken tenders marinated in turmeric and coconut milk, grilled, served with peanut sauce.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Crispy Thai Spring Rolls', 'country' => 'Thailand', 'category' => 'appetizers-salads',
                'excerpt' => 'Deep fried pastries filled with carrots, bean threads, cabbage, served with sweet plum dipping sauce.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Pad See Ew (Soy Soy Noodles)', 'country' => 'Thailand', 'category' => 'main-courses',
                'excerpt' => 'Wide flat rice noodles stir-fried with Chinese broccoli, eggs, beef, and sweet soy sauce.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 2
            ],

            // ---------------- SPAIN ----------------
            [
                'title' => 'Paella Valenciana', 'country' => 'Spain', 'category' => 'main-courses',
                'excerpt' => 'Traditional Spanish saffron rice dish containing chicken, rabbit, butter beans, and green beans.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 25, 'cook' => 40, 'servings' => 6
            ],
            [
                'title' => 'Patatas Bravas Tapas', 'country' => 'Spain', 'category' => 'appetizers-salads',
                'excerpt' => 'Crispy cubed fried potatoes served with a spicy, smoky tomato bravas sauce and garlic aioli.',
                'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Andalucian Gazpacho', 'country' => 'Spain', 'category' => 'appetizers-salads',
                'excerpt' => 'A refreshing cold Spanish soup blended from fresh tomatoes, cucumbers, bell peppers, garlic, and oil.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Tortilla Española', 'country' => 'Spain', 'category' => 'breakfast-brunch',
                'excerpt' => 'The beloved Spanish potato omelet containing thinly sliced potatoes and onions cooked in olive oil.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Churros con Chocolate', 'country' => 'Spain', 'category' => 'desserts',
                'excerpt' => 'Traditional fried dough pastries dusted with sugar and served with a cup of thick drinking chocolate.',
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Jamón Ibérico Toast', 'country' => 'Spain', 'category' => 'breakfast-brunch',
                'excerpt' => 'Pan con Tomate topped with rich, savory, thin slices of dry-cured Jamon Iberico.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 5, 'servings' => 2
            ],
            [
                'title' => 'Crema Catalana', 'country' => 'Spain', 'category' => 'desserts',
                'excerpt' => 'Catalan custard flavored with citrus peel and cinnamon, topped with caramelized sugar crust.',
                'image' => 'https://images.unsplash.com/photo-1470324161839-ce2bb6fa6bc3?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Gambas al Ajillo', 'country' => 'Spain', 'category' => 'appetizers-salads',
                'excerpt' => 'Sizzling shrimp cooked in extra virgin olive oil loaded with sliced garlic, chilies, and sherry.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 5, 'servings' => 4
            ],
            [
                'title' => 'Cordoban Salmorejo', 'country' => 'Spain', 'category' => 'appetizers-salads',
                'excerpt' => 'A thick, creamy chilled tomato soup enriched with breadcrumbs, garnished with chopped egg and jamon.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Spanish Ham Croquetas', 'country' => 'Spain', 'category' => 'appetizers-salads',
                'excerpt' => 'Creamy bechamel and serrano ham fritters, breaded and fried to golden-brown perfection.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 15, 'servings' => 6
            ],

            // ---------------- GREECE ----------------
            [
                'title' => 'Greek Moussaka', 'country' => 'Greece', 'category' => 'main-courses',
                'excerpt' => 'An oven-baked layered dish containing eggplants, sliced potatoes, seasoned meat, topped with thick bechamel.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 35, 'cook' => 60, 'servings' => 8
            ],
            [
                'title' => 'Pork Souvlaki Skewers', 'country' => 'Greece', 'category' => 'main-courses',
                'excerpt' => 'Skewered pork cubes marinated in lemon juice, olive oil, and oregano, grilled until juicy.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Horiatiki Greek Salad', 'country' => 'Greece', 'category' => 'appetizers-salads',
                'excerpt' => 'Traditional Greek rustic salad made with ripe tomatoes, cucumber, red onions, kalamata olives, and block of feta.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Creamy Tzatziki Dip', 'country' => 'Greece', 'category' => 'side-dishes',
                'excerpt' => 'A cool cucumber yogurt dip flavored with garlic, extra virgin olive oil, and fresh dill leaves.',
                'image' => 'https://images.unsplash.com/photo-1592417817098-8f3d6eb19675?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Spanakopita (Spinach Pie)', 'country' => 'Greece', 'category' => 'breakfast-brunch',
                'excerpt' => 'Crispy, buttery phyllo triangles stuffed with a flavorful spinach and crumbled feta cheese filling.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 25, 'servings' => 6
            ],
            [
                'title' => 'Greek Honey Baklava', 'country' => 'Greece', 'category' => 'desserts',
                'excerpt' => 'Rich, sweet pastry layers filled with chopped nuts and sweetened with cinnamon-infused honey syrup.',
                'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 40, 'servings' => 12
            ],
            [
                'title' => 'Chicken Gyros Wrap', 'country' => 'Greece', 'category' => 'main-courses',
                'excerpt' => 'Seasoned grilled chicken wrapped in warm pita bread with lettuce, tomato, onions, and tzatziki.',
                'image' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 15, 'servings' => 4
            ],
            [
                'title' => 'Baked Pastitsio', 'country' => 'Greece', 'category' => 'main-courses',
                'excerpt' => 'Greek baked pasta dish containing tubular pasta, spiced meat sauce, and creamy bechamel sauce.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 45, 'servings' => 8
            ],
            [
                'title' => 'Stuffed Dolmades', 'country' => 'Greece', 'category' => 'appetizers-salads',
                'excerpt' => 'Grape vine leaves stuffed with a mixture of seasoned herb rice and ground lamb.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 40, 'servings' => 6
            ],
            [
                'title' => 'Greek Lemon Potatoes', 'country' => 'Greece', 'category' => 'side-dishes',
                'excerpt' => 'Thick potato wedges roasted in chicken broth, lemon juice, garlic, and dried oregano.',
                'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 45, 'servings' => 4
            ],

            // ---------------- USA ----------------
            [
                'title' => 'Classic Bacon Cheeseburger', 'country' => 'USA', 'category' => 'main-courses',
                'excerpt' => 'Flame-grilled prime beef patty topped with melted cheddar, crispy bacon, lettuce, tomato, and burger sauce.',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 10, 'servings' => 2
            ],
            [
                'title' => 'Chocolate Chip Cookies', 'country' => 'USA', 'category' => 'desserts',
                'excerpt' => 'The ultimate American cookie: soft and chewy in the middle, crispy on the edges, loaded with chocolate chips.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 12, 'servings' => 12
            ],
            [
                'title' => 'Smoked BBQ Baby Back Ribs', 'country' => 'USA', 'category' => 'main-courses',
                'excerpt' => 'Tender pork ribs rubbed with brown sugar spices, slow-smoked, and brushed with sweet BBQ sauce.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 180, 'servings' => 4
            ],
            [
                'title' => 'Baked Macaroni and Cheese', 'country' => 'USA', 'category' => 'side-dishes',
                'excerpt' => 'Elbow pasta baked in a rich, velvety cheese sauce made from sharp cheddar and gruyere, breadcrumb topping.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 20, 'servings' => 6
            ],
            [
                'title' => 'Spicy Buffalo Chicken Wings', 'country' => 'USA', 'category' => 'appetizers-salads',
                'excerpt' => 'Crispy deep-fried chicken wings tossed in a spicy, buttery cayenne pepper buffalo sauce, blue cheese dip.',
                'image' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Classic Apple Pie', 'country' => 'USA', 'category' => 'desserts',
                'excerpt' => 'A sweet baked pie containing sliced green apples coated in brown sugar and cinnamon, in flaky pastry crust.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&auto=format&fit=crop',
                'prep' => 30, 'cook' => 50, 'servings' => 8
            ],
            [
                'title' => 'Clam Chowder', 'country' => 'USA', 'category' => 'appetizers-salads',
                'excerpt' => 'A rich, creamy potato and clam soup originating from New England, cooked with salted pork and cream.',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 25, 'servings' => 4
            ],
            [
                'title' => 'Southern Fried Chicken', 'country' => 'USA', 'category' => 'main-courses',
                'excerpt' => 'Juicy buttermilk-marinated chicken pieces coated in seasoned flour and deep-fried to maximum crispiness.',
                'image' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=800&auto=format&fit=crop',
                'prep' => 20, 'cook' => 20, 'servings' => 4
            ],
            [
                'title' => 'Caesar Salad with Croutons', 'country' => 'USA', 'category' => 'appetizers-salads',
                'excerpt' => 'Crisp romaine lettuce hearts tossed in garlic-anchovy dressing, parmesan cheese, and toasted croutons.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&auto=format&fit=crop',
                'prep' => 15, 'cook' => 0, 'servings' => 4
            ],
            [
                'title' => 'Buttermilk Pancakes', 'country' => 'USA', 'category' => 'breakfast-brunch',
                'excerpt' => 'Fluffy, thick breakfast griddle cakes served warm with a pat of butter and maple syrup.',
                'image' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800&auto=format&fit=crop',
                'prep' => 10, 'cook' => 10, 'servings' => 4
            ],
        ];

        // Seeding loop
        foreach ($recipesData as $index => $item) {
            $catSlug = $item['category'];
            $catId = isset($categories[$catSlug]) ? $categories[$catSlug]->id : null;
            $slug = Str::slug($item['title'] . '-' . $item['country']);

            // 4. Create the Recipe
            $recipe = Recipe::create([
                'tenant_uuid' => $tenantUuid,
                'author_id'   => $user->id,
                'category_id' => $catId,
                'title'       => $item['title'],
                'slug'        => $slug,
                'excerpt'     => $item['excerpt'] . " (Classic recipe from {$item['country']})",
                'cover_image' => "https://loremflickr.com/800/600/food," . urlencode(implode(',', explode(' ', strtolower($item['title'])))) . "?lock=" . ($index + 100),
                'prep_time_minutes' => $item['prep'],
                'cook_time_minutes' => $item['cook'],
                'servings'    => $item['servings'],
                'status'      => 'published',
                'description_html' => "<p>Discover the authentic flavors of {$item['country']} with this classic recipe for " . e($item['title']) . ". Perfect for home cooks looking to recreate top-tier world cuisine.</p>",
            ]);

            // 5. Generate Ingredients Programmatically based on category
            $ingredients = [];
            if ($catSlug === 'desserts') {
                $ingredients = [
                    ['sort_order' => 0, 'amount' => 200, 'unit' => 'g', 'name' => 'All-Purpose Flour', 'state' => 'sifted'],
                    ['sort_order' => 1, 'amount' => 150, 'unit' => 'g', 'name' => 'Granulated Sugar', 'state' => null],
                    ['sort_order' => 2, 'amount' => 100, 'unit' => 'g', 'name' => 'Unsalted Butter', 'state' => 'melted'],
                    ['sort_order' => 3, 'amount' => 2, 'unit' => 'pcs', 'name' => 'Large Eggs', 'state' => null],
                    ['sort_order' => 4, 'amount' => 5, 'unit' => 'ml', 'name' => 'Vanilla Extract', 'state' => null],
                ];
            } elseif ($catSlug === 'appetizers-salads') {
                $ingredients = [
                    ['sort_order' => 0, 'amount' => 300, 'unit' => 'g', 'name' => 'Fresh Mixed Greens or Tomatoes', 'state' => 'washed and chopped'],
                    ['sort_order' => 1, 'amount' => 2, 'unit' => 'tbsp', 'name' => 'Extra Virgin Olive Oil', 'state' => null],
                    ['sort_order' => 2, 'amount' => 2, 'unit' => 'pcs', 'name' => 'Garlic Cloves', 'state' => 'minced'],
                    ['sort_order' => 3, 'amount' => 1, 'unit' => 'tsp', 'name' => 'Sea Salt & Pepper', 'state' => null],
                    ['sort_order' => 4, 'amount' => 10, 'unit' => 'g', 'name' => 'Fresh Herbs (Cilantro/Basil/Parsley)', 'state' => 'chopped'],
                ];
            } elseif ($catSlug === 'breakfast-brunch') {
                $ingredients = [
                    ['sort_order' => 0, 'amount' => 250, 'unit' => 'g', 'name' => 'Flour or Grains', 'state' => null],
                    ['sort_order' => 1, 'amount' => 150, 'unit' => 'ml', 'name' => 'Milk or Water', 'state' => null],
                    ['sort_order' => 2, 'amount' => 2, 'unit' => 'pcs', 'name' => 'Eggs', 'state' => 'whisked'],
                    ['sort_order' => 3, 'amount' => 30, 'unit' => 'g', 'name' => 'Butter', 'state' => 'melted'],
                    ['sort_order' => 4, 'amount' => 2, 'unit' => 'tbsp', 'name' => 'Honey or Maple Syrup', 'state' => null],
                ];
            } else { // main-courses & side-dishes
                $ingredients = [
                    ['sort_order' => 0, 'amount' => 500, 'unit' => 'g', 'name' => 'Primary Protein (Chicken/Beef/Pork/Seafood/Tofu)', 'state' => 'cleaned and chopped'],
                    ['sort_order' => 1, 'amount' => 1, 'unit' => 'pcs', 'name' => 'Onion', 'state' => 'chopped'],
                    ['sort_order' => 2, 'amount' => 3, 'unit' => 'pcs', 'name' => 'Garlic Cloves', 'state' => 'minced'],
                    ['sort_order' => 3, 'amount' => 30, 'unit' => 'ml', 'name' => 'Olive Oil or Cooking Oil', 'state' => null],
                    ['sort_order' => 4, 'amount' => 2, 'unit' => 'tbsp', 'name' => 'Famous Country Spice Blend', 'state' => null],
                    ['sort_order' => 5, 'amount' => 1, 'unit' => 'tsp', 'name' => 'Salt and Pepper', 'state' => null],
                ];
            }

            $recipe->ingredients()->createMany($ingredients);

            // 6. Generate Steps Programmatically
            $steps = [
                ['step_number' => 1, 'instruction' => 'Prepare and measure all ingredients. Wash, chop, and set aside veggies and proteins.'],
                ['step_number' => 2, 'instruction' => 'Preheat the pan, oven, or griddle. Add butter or cooking oil and let it reach cooking temperature.'],
                ['step_number' => 3, 'instruction' => 'Cook the aromatic base (onion, garlic, or spices) until fragrant to build the depth of flavor.'],
                ['step_number' => 4, 'instruction' => 'Add the primary ingredients and cook according to the recipe times, seasoning with salt and pepper.'],
                ['step_number' => 5, 'instruction' => 'Simmer, bake, or stir-fry until fully cooked. Garnish with fresh herbs or sweet toppings and serve hot.'],
            ];

            $recipe->steps()->createMany($steps);
        }
    }
}

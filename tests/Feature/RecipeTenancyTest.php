<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Scopes\TenantScope;
use App\Services\RecipeSchemaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecipeTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_database_resolver_middleware_sets_tenant_scope_on_matching_host(): void
    {
        // 1. Create a tenant
        $uuid = Str::uuid()->getBytes();
        $tenant = Tenant::create([
            'uuid' => $uuid,
            'name' => 'Pizza Blog',
            'domain' => 'pizza.localhost',
            'uses_isolated_db' => false,
        ]);

        // 2. Create User
        $user = User::factory()->create();

        // 3. Set scope context and seed a recipe
        TenantScope::setTenantId($uuid);
        $recipe = Recipe::create([
            'tenant_uuid' => $uuid,
            'author_id' => $user->id,
            'title' => 'Neapolitan Pizza',
            'slug' => 'neapolitan-pizza',
            'excerpt' => 'Classic pizza from Naples',
            'prep_time_minutes' => 30,
            'cook_time_minutes' => 2,
            'servings' => 2,
            'status' => 'published',
        ]);

        // Reset scope to test middleware setting it
        TenantScope::setTenantId(null);

        // 4. Request the tenant domain URL
        $response = $this->get('http://pizza.localhost/');

        // 5. Assert the request succeeds and sets the correct tenant ID in scope
        $response->assertStatus(200);
        $this->assertEquals($uuid, TenantScope::getTenantId());
    }

    public function test_recipes_are_isolated_by_tenant_scope(): void
    {
        $user = User::factory()->create();

        // Tenant 1
        $uuid1 = Str::uuid()->getBytes();
        $tenant1 = Tenant::create([
            'uuid' => $uuid1,
            'name' => 'Italian Food',
            'domain' => 'italian.localhost',
        ]);

        // Tenant 2
        $uuid2 = Str::uuid()->getBytes();
        $tenant2 = Tenant::create([
            'uuid' => $uuid2,
            'name' => 'Mexican Food',
            'domain' => 'mexican.localhost',
        ]);

        // Create recipe for Tenant 1
        TenantScope::setTenantId($uuid1);
        Recipe::create([
            'tenant_uuid' => $uuid1,
            'author_id' => $user->id,
            'title' => 'Pasta Carbonara',
            'slug' => 'pasta-carbonara',
            'prep_time_minutes' => 15,
            'cook_time_minutes' => 15,
            'status' => 'published',
        ]);

        // Create recipe for Tenant 2
        TenantScope::setTenantId($uuid2);
        Recipe::create([
            'tenant_uuid' => $uuid2,
            'author_id' => $user->id,
            'title' => 'Tacos al Pastor',
            'slug' => 'tacos-al-pastor',
            'prep_time_minutes' => 20,
            'cook_time_minutes' => 10,
            'status' => 'published',
        ]);

        // Query under Tenant 1 context
        TenantScope::setTenantId($uuid1);
        $this->assertCount(1, Recipe::all());
        $this->assertEquals('Pasta Carbonara', Recipe::first()->title);

        // Query under Tenant 2 context
        TenantScope::setTenantId($uuid2);
        $this->assertCount(1, Recipe::all());
        $this->assertEquals('Tacos al Pastor', Recipe::first()->title);
    }

    public function test_recipe_schema_service_generates_valid_json_ld(): void
    {
        $user = User::factory()->create();
        $uuid = Str::uuid()->getBytes();
        
        TenantScope::setTenantId($uuid);
        $recipe = Recipe::create([
            'tenant_uuid' => $uuid,
            'author_id' => $user->id,
            'title' => 'Pancakes',
            'slug' => 'pancakes',
            'prep_time_minutes' => 10,
            'cook_time_minutes' => 15,
            'status' => 'published',
        ]);

        $schemaService = new RecipeSchemaService();
        $json = $schemaService->generate($recipe);

        $this->assertJson($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('https://schema.org', $decoded['@context']);
        $this->assertEquals('Recipe', $decoded['@type']);
        $this->assertEquals('Pancakes', $decoded['name']);
    }
}

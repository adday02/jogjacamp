<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User; // Add this line to import the User model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsUser()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user); // Authenticate the user
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_categories()
    {
        $this->actingAsUser(); // Ensure the user is authenticated
        $category = Category::factory()->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJson([$category->toArray()]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_category()
    {
        $this->actingAsUser(); // Ensure the user is authenticated
        $data = [
            'name' => 'New Category',
            'is_publish' => 1
        ];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(201)
                 ->assertJson($data);

        $this->assertDatabaseHas('categories', $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_category()
    {
        $this->actingAsUser(); // Ensure the user is authenticated
        $category = Category::factory()->create();

        $data = [
            'name' => 'Updated Category',
            'is_publish' => 1
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(200)
                 ->assertJson($data);

        $this->assertDatabaseHas('categories', $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_delete_a_category()
    {
        $this->actingAsUser(); // Ensure the user is authenticated
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}

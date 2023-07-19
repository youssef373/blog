<?php

namespace Tests\Unit\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install');

        $this->withoutExceptionHandling();
    }

    public function testStore()
    {
        $this->actingAs($user = User::factory()->create());

        Passport::actingAs($user);

        $requestData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category' => $this->faker->word,
            'file' => UploadedFile::fake()->image('test.jpg'),
        ];

        Storage::fake('public');

        $response = $this->postJson('/api/posts', $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post created successfully',
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => $requestData['title'],
            'content' => $requestData['content'],
            'category' => $requestData['category'],
            'user_id' => $user->id,
        ]);

        Storage::disk('public')->assertExists('images/' . $requestData['file']->hashName());
    }

    public function testDestroy()
    {
        $this->actingAs($user = User::factory()->create());

        Passport::actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post deleted successfully',
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

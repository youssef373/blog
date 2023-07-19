<?php

namespace Tests\Unit\Controllers\Api;

use App\Http\Controllers\CommentController;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install');
    }

    public function testStore()
    {
        Passport::actingAs(User::factory()->create());

        $postData = [
            'content' => $this->faker->paragraph,
        ];

        $request = new Request($postData);
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        $controller = new CommentController();

        $response = $controller->store($request);

        $this->assertDatabaseHas('comments', [
            'content' => $postData['content'],
        ]);

        $this->assertEquals('success', $response->getSession()->get('status'));
        $this->assertEquals('Comment created successfully', $response->getSession()->get('message'));
    }

    public function testUpdate()
    {
        Passport::actingAs($user = User::factory()->create());

        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $updatedContent = $this->faker->paragraph;

        $postData = [
            'content' => $updatedContent,
        ];

        $request = new Request($postData);
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        $controller = new CommentController();

        $response = $controller->update($request, $comment->id);

        $this->assertEquals($updatedContent, $comment->fresh()->content);

        $this->assertEquals('success', $response->getSession()->get('status'));
        $this->assertEquals('Comment updated successfully', $response->getSession()->get('message'));
    }

    public function testDestroy()
    {
        Passport::actingAs($user = User::factory()->create());

        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $controller = new CommentController();

        $controller->destroy($comment->id);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}

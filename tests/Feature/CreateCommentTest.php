<?php

namespace Tests\Feature;

use App\Features\Comments\Actions\CreateCommentAction;
use App\Features\Comments\DTOs\CreateCommentDto;
use App\Features\Comments\Events\CommentCreated;
use App\Features\Comments\Models\Comment;
use App\Features\Posts\Models\Post;
use App\Features\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Post $post;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->post = Post::factory()->create([
            'text' => 'Test Post',
        ]);

        $this->token = $this->user->createToken('test-token', ['comments:create', 'comments:update', 'comments:delete'])->plainTextToken;
    }

    /** @test */
    public function authenticated_user_can_create_comment()
    {
        $response = $this->withToken($this->token)
            ->postJson("/api/posts/{$this->post->id}/comment", [
                'text' => 'Test comment text'
            ]);

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn ($json) =>
            $json->where('text', 'Test comment text')
                ->where('post_id', $this->post->id)
                ->where('user.id', $this->user->id)
                ->where('user.name', $this->user->name)
                ->has('created_at')
                ->has('updated_at')
                ->etc()
            )
            );

        $this->assertDatabaseHas('comments', [
            'text' => 'Test comment text',
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
        ]);

        Event::assertDispatched(CommentCreated::class);
    }

    /** @test */
    public function unauthenticated_user_cannot_create_comment()
    {
        $response = $this->postJson("/api/posts/{$this->post->id}/comment", [
            'text' => 'Test comment text'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);

        $this->assertDatabaseCount('comments', 0);
        Event::assertNotDispatched(CommentCreated::class);
    }

    /** @test */
    public function cannot_create_comment_on_non_existing_post()
    {
        $nonExistingPostId = 9999;

        $response = $this->withToken($this->token)
            ->postJson("/api/posts/{$nonExistingPostId}/comment", [
                'text' => 'Test comment text'
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'The requested resource was not found.'
            ]);

        $this->assertDatabaseCount('comments', 0);
        Event::assertNotDispatched(CommentCreated::class);
    }

    /** @test */
    public function text_is_required()
    {
        $response = $this->withToken($this->token)
            ->postJson("/api/posts/{$this->post->id}/comment", [
                'text' => ''
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['text']);

        $this->assertDatabaseCount('comments', 0);
        Event::assertNotDispatched(CommentCreated::class);
    }

    /** @test */
    public function text_must_be_at_least_3_characters()
    {
        $response = $this->withToken($this->token)
            ->postJson("/api/posts/{$this->post->id}/comment", [
                'text' => 'ab'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['text']);

        $this->assertDatabaseCount('comments', 0);
        Event::assertNotDispatched(CommentCreated::class);
    }

    /** @test */
    public function text_must_not_exceed_10000_characters()
    {
        $response = $this->withToken($this->token)
            ->postJson("/api/posts/{$this->post->id}/comment", [
                'text' => str_repeat('a', 10001)
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['text']);

        $this->assertDatabaseCount('comments', 0);
        Event::assertNotDispatched(CommentCreated::class);
    }

    /** @test */
    public function create_comment_action_works_correctly()
    {
        $action = app(CreateCommentAction::class);

        $dto = new CreateCommentDto(
            text: 'Test comment',
            userId: $this->user->id,
            postId: $this->post->id
        );

        $comment = $action->execute($dto);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('Test comment', $comment->text);
        $this->assertEquals($this->user->id, $comment->user_id);
        $this->assertEquals($this->post->id, $comment->post_id);
    }

    /** @test */
    public function comment_created_event_is_dispatched_with_correct_data()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        event(new CommentCreated($comment));

        Event::assertDispatched(CommentCreated::class, function ($event) use ($comment) {
            return $event->comment->id === $comment->id
                && $event->broadcastOn() instanceof \Illuminate\Broadcasting\PrivateChannel
                && $event->broadcastAs() === 'comment.created'
                && $event->broadcastWith() === [
                    'id' => $comment->id,
                    'text' => $comment->text,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name
                    ],
                    'created_at' => $comment->created_at->toDateTimeString()
                ];
        });
    }
}

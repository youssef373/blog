<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('comments')->orderBy('created_at', 'desc')->get();
        $postsResource = PostResource::collection($posts);
        return $this->successResponse($postsResource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        if (!Auth::check()) {
            return $this->errorResponse('Unauthorized action', 401);
        }
        $user = Auth::user();
        $image = $request->file('file');
        $imageName = time() . '.' . $image->extension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $imageName);

        $post = new Post();
        $post->user_id = $user->id;
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->image = $imageName;
        $post->category = $request->input('category');
        $post->save();

        $postResource = new PostResource($post);

        return $this->successResponse($postResource, 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$post) {
            return $this->errorResponse('Post not found', 404);
        }

        $postResource = new PostResource($post);

        return $this->successResponse($postResource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id)
    {
        if (!Auth::check()) {
            return $this->errorResponse('Unauthorized action', 401);
        }

        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post not found', 404);
        }

        if ($post->user_id != Auth::user()->id) {
            return $this->errorResponse('Unauthorized action', 401);
        }

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->category = $request->input('category');

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '.' . $image->extension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $imageName);
            $post->image = $imageName;
        }

        $post->save();

        $postResource = new PostResource($post);

        return $this->successResponse($postResource, 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $post = Post::find($id);

        if (!$post) {
            return $this->errorResponse('Post not found', 404);
        }

        if ($user->id !== $post->user_id) {
            return $this->errorResponse('Unauthorized action', 401);
        }

        try {
            $post->delete();
            return $this->successResponse([], 'Post deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting post', 500);
        }
    }
}

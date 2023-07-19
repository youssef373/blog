<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('comments')->orderBy('created_at', 'desc')->get();
        return view('post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login-form');
        }
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login-form');
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

        return redirect()->route('posts.show', $post->id)->with([
            'status' => 'success',
            'message' => 'Post updated successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);
        return view('post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login-form');
        }

        $post = Post::find($id);
        return view('post.update', compact('post'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login-form');
        }

        $post = Post::find($id);

        if ($post->user_id != Auth::user()->id) {
            abort(403, 'Unauthorized action');
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

        return redirect()->route('posts.show', $post->id)->with([
            'status' => 'success',
            'message' => 'Post updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        if ($user->id !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $post->delete();
            return redirect()->route('posts.index')->with([
                'status' => 'success',
                'message' => 'Post deleted successfully',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Error deleting post.');
        }

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}

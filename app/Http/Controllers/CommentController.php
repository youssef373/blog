<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->post_id = $request->input('post_id');
            $comment->content = $request->input('content');
            $comment->save();

            return redirect()->route('posts.show', $comment->post_id)->with([
                'status' => 'success',
                'message' => 'Comment created successfully',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Failed to create comment: ' . $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            $comment = Comment::find($id);
            if ($user->id !== $comment->user_id) {
                abort(403, 'Unauthorized action.');
            }
            $comment->content = $request->input('content');
            $comment->save();

            return redirect()->route('posts.show', $comment->post_id)->with([
                'status' => 'success',
                'message' => 'Comment updated successfully',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Failed to update comment: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $comment = Comment::find($id);
            if ($user->id !== $comment->user_id) {
                abort(403, 'Unauthorized action.');
            }
            $comment->delete();

            return redirect()->route('posts.show', $comment->post_id)->with([
                'status' => 'success',
                'message' => 'Comment deleted successfully',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Failed to delete comment: ' . $e->getMessage(),
            ]);
        }
    }
}

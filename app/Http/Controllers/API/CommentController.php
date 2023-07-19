<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiResponseTrait;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $user = Auth::user();
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $request->input('post_id');
        $comment->content = $request->input('content');
        $comment->save();

        return $this->successResponse(new CommentResource($comment), 'Comment created successfully');
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $user = Auth::user();
        $comment = Comment::find($id);
        if ($user->id !== $comment->user_id) {
            return $this->errorResponse('Unauthorized action.', 403);
        }
        $comment->content = $request->input('content');
        $comment->save();

        return $this->successResponse(new CommentResource($comment), 'Comment updated successfully');
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $comment = Comment::find($id);
        if ($user->id !== $comment->user_id) {
            return $this->errorResponse('Unauthorized action.', 403);
        }
        $comment->delete();

        return $this->successResponse([], 'Comment deleted successfully');
    }
}

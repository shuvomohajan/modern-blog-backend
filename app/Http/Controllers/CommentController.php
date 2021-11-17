<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'string', 'exists:posts,id'],
            'content' => ['required', 'string']
        ]);
        auth()->user()->comments()->create($validated);
        return $this->apiResponse(201, 'Comment created.');
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string']
        ]);
        $comment->update($validated);
        return $this->apiResponse(201, 'Comment updated.');
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return $this->apiResponse(200, 'Comment deleted.');
    }
}

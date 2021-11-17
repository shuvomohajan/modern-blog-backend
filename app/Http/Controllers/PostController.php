<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::all();
        return $this->apiResponse(200, 'Post list.', ['data' => $posts]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'title'       => ['required', 'string'],
            'content'     => ['required', 'string'],
            'status'      => ['nullable', 'boolean']
        ]);
        auth()->user()->posts()->create($validated);
        return $this->apiResponse(201, 'Post created.');
    }

    public function show(Post $post): JsonResponse
    {
        return $this->apiResponse(200, 'Post show.', ['data' => $post]);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'title'       => ['required', 'string'],
            'content'     => ['required', 'string'],
            'status'      => ['nullable', 'boolean']
        ]);
        $post->update($validated);
        return $this->apiResponse(201, 'Post updated.');
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return $this->apiResponse(200, 'Post deleted.');
    }

    public function postLike(Request $request): JsonResponse
    {
        $request->validate([
            'post_id' => ['required', 'string', 'exists:posts,id']
        ]);
        Post::find($request->input('post_id'))->increment('like');
        return $this->apiResponse(201, 'Post liked.');
    }

    public function postUnlike(Request $request): JsonResponse
    {
        $request->validate([
            'post_id' => ['required', 'string', 'exists:posts,id']
        ]);
        Post::find($request->input('post_id'))->decrement('like');
        return $this->apiResponse(201, 'Post liked.');
    }
}

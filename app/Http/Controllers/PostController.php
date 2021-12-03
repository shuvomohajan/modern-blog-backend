<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::withCount('upVotes', 'downVotes')->latest()->paginate($this->itemsPerPage);
        return $this->apiResponseResourceCollection(200, 'Post list.', PostResource::collection($posts));
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
        $post->loadCount('upVotes', 'downVotes')->load(['user', 'comments.user']);
        return $this->apiResponseResourceCollection(200, 'Post show.', PostResource::make($post));
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
}

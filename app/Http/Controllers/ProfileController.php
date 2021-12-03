<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile()
    {
        return $this->apiResponse(200, 'Profile data.', ['data' => auth()->user()]);
    }

    public function profilePosts()
    {
        $posts = auth()->user()->posts()->withCount(['upVotes', 'downVotes'])->latest()->paginate($this->itemsPerPage);
        return $this->apiResponseResourceCollection(200, 'User Posts.', PostResource::collection($posts));
    }
}

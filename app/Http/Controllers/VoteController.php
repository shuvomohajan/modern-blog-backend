<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'post_id' => ['required', 'string', 'exists:posts,id'],
            'vote'    => ['required', Rule::in(Vote::UP_VOTE, Vote::DOWN_VOTE)]
        ]);

        Vote::updateOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $request->input('post_id'),
        ],[
            'vote'    => $request->input('vote'),
        ]);

        return $this->apiResponse(201, 'Post vote saved.');
    }
}

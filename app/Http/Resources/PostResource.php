<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->diffForHumans(),
            'up_votes_count' => $this->up_votes_count,
            'down_votes_count' => $this->down_votes_count,
            'post_writer_user_id' => $this->user_id,
            'post_writer_name' => $this->user->name,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}

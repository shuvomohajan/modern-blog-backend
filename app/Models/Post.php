<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = $post->createSlug($post->title);
        });
        static::updating(function ($post) {
            $post->slug = $post->createSlug($post->title);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function upVotes(): HasMany
    {
        return $this->hasMany(Vote::class)->whereVote(Vote::UP_VOTE);
    }

    public function downVotes(): HasMany
    {
        return $this->hasMany(Vote::class)->whereVote(Vote::DOWN_VOTE);
    }

    private function createSlug($title): string
    {
        $slug = Str::slug($title);
        if ($count = Post::where('slug', 'like', "$slug%")->count())
            $slug .= "-$count";
        return $slug;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'profession' => $this->profession,
            'bio' => $this->bio,
            'photo_profile_url' => $this->photo_profile_url,
            'articles_count' => $this->whenCounted('articles'),
            'comments_count' => $this->whenNotNull($this->comments_count),
            'total_likes' => $this->whenNotNull($this->total_likes),
            'created_at' => $this->created_at,
        ];
    }
}

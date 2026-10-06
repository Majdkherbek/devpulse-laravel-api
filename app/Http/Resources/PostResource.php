<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * تشكيل وتنسيق مخرجات بيانات المقال للـ API.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'body'        => $this->body,
            'image_url'   => $this->image ? asset('storage/' . $this->image) : null,
            'author'      => new UserResource($this->whenLoaded('user')),
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
            'created_at'  => $this->created_at?->toIso8601String(),
        ];
    }
}

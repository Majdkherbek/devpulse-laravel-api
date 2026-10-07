<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * تشكيل وتنسيق مخرجات بيانات التعليق للـ API.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'post_id'     => $this->post_id,
            'body'        => $this->body,
            'author'      => new UserResource($this->whenLoaded('user')),
            'created_at'  => $this->created_at?->toIso8601String(),
        ];
    }
}

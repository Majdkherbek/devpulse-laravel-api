<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagCollection extends ResourceCollection
{
    /**
     * تشكيل وتغليف قائمة الوسوم المرجعة.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => TagResource::collection($this->collection),
        ];
    }
}

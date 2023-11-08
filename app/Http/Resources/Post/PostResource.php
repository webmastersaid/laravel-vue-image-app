<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Image\ImageResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this->created_at);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'images' => ImageResource::collection($this->images),
            'created_at' => $date->longRelativeDiffForHumans(),
        ];
    }
}

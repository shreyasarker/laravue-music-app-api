<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'author' => $this->user->name,
            'author_image' => $this->getImage(),
            'title' => $this->title,
            'location' => $this->location,
            'description' => $this->description,
            'description_excerpt' => implode(' ', array_slice(explode(' ', $this->description), 0, 25)).'.....',
            'image' => asset($this->image),
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y')
        ];
    }

    private function getImage()
    {
        if($this->user->image) {
            return url($this->user->image);
        }
        return null;
    }
}

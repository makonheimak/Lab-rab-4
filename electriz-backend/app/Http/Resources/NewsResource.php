<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class NewsResource extends JsonResource {
    public function toArray($request) { return ['id' => $this->id, 'title' => $this->title, 'body' => $this->body, 'image' => $this->image, 'published_at' => $this->published_at]; }
}

<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\ResourceCollection;
class ProductCollection extends ResourceCollection {
    public $collects = ProductResource::class;
    public function toArray($request) { return ['data' => $this->collection, 'meta' => ['total' => $this->total(), 'per_page' => $this->perPage(), 'current_page' => $this->currentPage()]]; }
}

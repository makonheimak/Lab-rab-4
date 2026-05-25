<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class OrderResource extends JsonResource {
    public function toArray($request) { return ['id' => $this->id, 'status' => $this->status, 'total' => $this->total, 'address' => $this->address, 'payment_method' => $this->payment_method, 'comment' => $this->comment, 'items' => OrderItemResource::collection($this->whenLoaded('items')), 'payment' => new PaymentHistoryResource($this->whenLoaded('payment')), 'created_at' => $this->created_at]; }
}

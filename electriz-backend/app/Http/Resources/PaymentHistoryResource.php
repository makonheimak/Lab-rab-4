<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'provider' => $this->provider,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'receipt_number' => $this->receipt_number,
            'created_at' => $this->created_at,
        ];
    }
}

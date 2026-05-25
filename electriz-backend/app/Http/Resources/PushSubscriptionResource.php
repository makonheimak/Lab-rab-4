<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PushSubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'endpoint' => $this->endpoint,
            'created_at' => $this->created_at,
        ];
    }
}

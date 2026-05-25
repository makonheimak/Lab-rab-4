<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}

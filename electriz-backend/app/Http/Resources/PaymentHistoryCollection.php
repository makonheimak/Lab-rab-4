<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentHistoryCollection extends ResourceCollection
{
    public $collects = PaymentHistoryResource::class;
}

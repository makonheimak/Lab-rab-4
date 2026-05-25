<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = ['user_id', 'order_id', 'provider', 'status', 'amount', 'currency', 'receipt_number', 'meta'];
    protected $casts = ['amount' => 'float', 'meta' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
}

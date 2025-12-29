<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // THIS IS THE KEY LINE. Without it, Laravel blocks all orders!
    protected $fillable = ['item_name', 'price', 'customer_id', 'status', 'payment_method'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
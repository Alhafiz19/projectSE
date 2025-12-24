<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name', 
        'price', 
        'status', 
        'customer_id' // Added this so we can link the order to a customer
    ];

    // Optional: This helper allows you to easily get the customer details from an order later
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    
    // Add 'image' to this list
    protected $fillable = ['name', 'category', 'price', 'description', 'image'];
}
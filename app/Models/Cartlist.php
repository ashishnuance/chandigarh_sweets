<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'product_qty',
        'product_price',
        'order_type',
        'product_variant_id',
        'user_id'
    ];
}

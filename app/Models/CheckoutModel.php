<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'user_id','order_type','product_json'
    ];
}

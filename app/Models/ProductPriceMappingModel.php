<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceMappingModel extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','company_id','start_date','end_date','item_code','price','discount','buyer_type'];
}

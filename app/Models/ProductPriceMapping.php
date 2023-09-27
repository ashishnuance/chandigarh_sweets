<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceMapping extends Model
{
    use HasFactory;
    protected $table ='product_price_mapping_models';

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
}

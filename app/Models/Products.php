<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ProductCategoryModel,ProductSubCategory};


class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name', 'product_code','product_slug','description','product_catid','product_subcatid','food_type','blocked'
    ];

    public function category()
    {
        return $this->hasOne(ProductCategoryModel::class, 'id', 'product_catid');
    }
    public function subcategory()
    {
        return $this->hasOne(ProductSubCategory::class, 'id', 'product_subcatid');
    }

}

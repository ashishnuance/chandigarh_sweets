<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategoryModel;
use App\Models\ProductSubCategory;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name', 'product_code','product_slug','description','product_catid','product_subcatid','food_type','blocked'
    ];

    public function category()
    {
    return $this->hasOne(ProductCategoryModel::class, 'company_user_mappings', 'user_id', 'company_id');
    }

}

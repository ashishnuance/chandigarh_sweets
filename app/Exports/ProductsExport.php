<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Products;

class ProductsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Products::select('product_code','product_name','description','product_slug','product_catid','product_subcatid','food_type','blocked',"created_at","updated_at")->get();
    }

    public function headings(): array
    {
        return ['Product Code','Product Name','Description','Slug','Product Category','Product Subcategory','Food Type','Blocked',"Created date","Updated date"];
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ProductCategoryModel;

class ProductCategoryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProductCategoryModel::select('company_id','category_name')->get();
    }

    public function headings(): array
    {
        return ['Company Id','Category Name',];
    }
}

<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{ProductCategoryModel,ProductSubCategory};
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Helper;

class SubCategoryImport implements ToCollection, WithStartRow
{
    use Importable;
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $subCategory = [];
        $userType = auth()->user()->role()->first()->name;
        foreach ($collection as $row) 
        {
            if(count($row)==2){
                if($userType!=config('custom.superadminrole')){
                    $category_id = ProductCategoryModel::select('id');
                }else{
                    $category_id = $row[0];
                }

                $checkCatgoryName = ProductSubCategory::where('procat_id',$category_id)->where('subcat_name','like',$row[1]);
                if($checkCatgoryName->count()==0){
                    $subCategory[] = [
                        'procat_id'     => $category_id,
                        'subcat_name'    => $row[1],
                    ];
                }
            }
        }
        if(!empty($subCategory)){
            ProductSubCategory::insert($subCategory);
        }
    }
}

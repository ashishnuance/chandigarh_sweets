<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{ProductCategoryModel,ProductSubCategory};
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Helper;

class ProductCategoryImport implements ToCollection, WithStartRow
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
        $productCategory = [];
        $userType = auth()->user()->role()->first()->name;
        foreach ($collection as $row) 
        {
            if(count($row)==2){
                if($userType!=config('custom.superadminrole')){
                    $company_id = Helper::loginUserCompanyId();
                }else{
                    $company_id = $row[0];
                }

                $checkCatgoryName = ProductCategoryModel::where('company_id',$company_id)->where('category_name','like',$row[1]);
                if($checkCatgoryName->count()==0){
                    $productCategory[] = [
                        'company_id'     => $company_id,
                        'category_name'    => $row[1],
                    ];
                }
            }
        }
        if(!empty($productCategory)){
            ProductCategoryModel::insert($productCategory);
        }
    }
}

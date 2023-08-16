<?php

namespace App\Imports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToCollection,WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if(count($row)==8){
                $product = Products::create([
                    
                    'product_code'     => $row[0],
                    'product_name'    => $row[1], 
                    'product_slug' => $row[2],
                    'description' => $row[3],
                    'product_catid' => $row[4],
                    'product_subcatid' => $row[5],
                    'foodType' => ($row[6]!='' && in_array($row[6],array('veg','non-veg'))) ? $row[6] : 'veg',
                    'blocked' => (is_int($row[7])) ? $row[7] : 1,
                    
                ]);
                // print_r($product); exit();
            }
        }
    }
}

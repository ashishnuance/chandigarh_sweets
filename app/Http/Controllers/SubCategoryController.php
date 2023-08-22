<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\{Products,ProductsVariations,ProductImagesModel,ProductsVariationsOptions};
use App\Models\{ProductCategoryModel,ProductSubCategory};
use App\Models\{Cartlist,Orderlist};
use App\Exports\ProductsExport;
use App\Exports\UserProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use Helper;
use File;
use Image;

class SubCategoryController extends Controller
{
 
    public function index(Request $request,$id='')
    {
        $perpage = 5;//config('app.perpage');
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "Sub Category", 'name' => "Sub Category"], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Company List');

        $sub_category_list = ProductSubCategory::with('categoryname')->orderBy('id','DESC')->paginate($perpage);

        //  dd($sub_category_list); die;

       $category_id = ProductCategoryModel::get();

        return view('pages.sub-category.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'sub_category_list'=>$sub_category_list, 'category_id'=>$category_id]);
    }
 
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "Sub Category", 'name' => " Sub Category"], ['name' => "Add"],
        ];
        //Pageheader set true for breadcrumbs

        $category_id = ProductCategoryModel::get();

        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Product category Add');
        return view('pages.sub-category.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'category_id'=>$category_id]);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'subcat_name' => 'required',
          
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        // echo '<pre>';print_r($request->all());  exit();
        $insert_data = [
            'procat_id' => $request->input('category_id'), // Set the category ID
            'subcat_name' => $request->input('subcat_name'),
        ];
        $create = ProductSubCategory::create($insert_data);

        return redirect()->route('sub-category.index')->with('success',__('locale.sub_category_success'));

      
    }

    public function show($id)
    {
        exit('show');
    }


    public function edit($id=0)
    {

        if($id > 0){
            $data_check = ProductSubCategory::where(['id'=>$id]);
            if($data_check->count() > 0 ){
                $this->data['result'] = $data_check->first();
                $this->data['category_id'] = ProductCategoryModel::get();

            }else{
                return redirect()->back()->with('error','Data Not found in Database');
            }
            return view('pages.sub-category.create',$this->data);
        }else{
            return redirect()->back()->with('error','Data Not found in Database');
        }

        // echo $id; exit();
        // $product_category_edit = ProductCategoryModel::find($id);

        // $company_list = Company::get();
        //     return view('pages.product-category.create',['product_category_edit' => $product_category_edit,'company_list' => $company_list]); 
        
    }

    public function update(Request $request, $id=0)
    {
        $result = ProductSubCategory::findOrFail($id);

        $result->subcat_name = $request->input('subcat_name');
        $result->procat_id = $request->input('category_id');

        $result->save();
        
        return redirect()->route('sub-category.index')->with('success','Data Updated Successfully');
        
    }

    public function destroy($id)
    {
        ProductSubCategory::find($id)->delete();
        return redirect()->back()->with('success','Data Deleted Successfully');
    }

  
}
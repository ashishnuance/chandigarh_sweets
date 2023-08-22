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

class ProductCategoryController extends Controller
{
 
    public function index(Request $request,$id='')
    {
        $perpage = config('app.perpage');
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "product category", 'name' => "product category"], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Company List');

        $product_cate_list = ProductCategoryModel::with('companyname')->orderBy('id','DESC')->paginate($perpage);

        // echo '<pre>'; print_r($product_cate_lis); die;
        $company_list = Company::get();

        return view('pages.product-category.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'product_category_list'=>$product_cate_list, 'company_list'=>$company_list]);
    }
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "product category", 'name' => "product category"], ['name' => "Add"],
        ];
        //Pageheader set true for breadcrumbs

        $company = Company::get();

        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Product category Add');
        return view('pages.product-category.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'company'=>$company]);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
          
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        // echo '<pre>';print_r($request->all());  exit();
        $insert_data=[];

        $insert_data['company_id'] = $request['company_id'];
        $insert_data['category_name'] = $request['category_name'];
    
        $create = ProductCategoryModel::create($insert_data);

        return redirect()->route('product-category.index')->with('success',__('locale.product_category_success'));  
    }

    public function show($id)
    {
        exit('show');
    }

    public function edit($id=0)
    {

        if($id > 0){
            $data_check = ProductCategoryModel::where(['id'=>$id]);
            if($data_check->count() > 0 ){
                $this->data['result'] = $data_check->first();
                $this->data['company'] = Company::get();

            }else{
                return redirect()->back()->with('error','Data Not found in Database');
            }
            return view('pages.product-category.create',$this->data);
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
            $result = ProductCategoryModel::findOrFail($id);

            $result->category_name = $request->input('category_name');
            $result->company_id = $request->input('company_id');

            $result->save();
            
            return redirect()->route('product-category.index')->with('success','Data Updated Successfully');
        
    }
    
    public function destroy($id)
    {
        ProductCategoryModel::find($id)->delete();
        return redirect()->back()->with('success','Data Deleted Successfully');
    } 
    
}
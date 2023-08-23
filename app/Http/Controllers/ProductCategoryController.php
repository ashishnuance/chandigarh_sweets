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
        $userType = auth()->user()->role()->first()->name;
        $perpage = config('app.perpage');
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/superadmin", 'name' => "Home"], ['link' => "superadmin/product category", 'name' => "product category"], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Category List');
        $deleteUrl = 'superadmin.product-category.delete';

        $product_cate_list = ProductCategoryModel::with('companyname')->orderBy('id','DESC');

        if($userType!=config('custom.superadminrole')){
            $company_id = Helper::loginUserCompanyId();
            $product_cate_list = $product_cate_list->whereHas('companyname',function($query) use ($company_id) {
                $query->where('company_id',$company_id);
            });
          
        }

        $product_cate_list = $product_cate_list->paginate($perpage);

        if($product_cate_list->count()>0){
            $product_category_list = $product_cate_list;
        }

        $company_list = Company::get();

        return view('pages.product-category.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'product_category_list'=>$product_category_list,'company_list'=>$company_list]);
    }
    public function create()
    {
        $userType = auth()->user()->role()->first()->name;

        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "product category", 'name' => "product category"], ['name' => "Add"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $formUrl = 'superadmin.product-category.store';

        $company = Company::get();

        if($userType!=config('custom.superadminrole')){
            $company_id = Helper::loginUserCompanyId();
            $productCategoryResult = ProductCategoryModel::where('company_id',$company_id)->get(["category_name", "id"]);
            $formUrl = 'product-category.store';
        }

        $pageTitle = __('locale.Product category Add');
        return view('pages.product-category.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'company'=>$company,'formUrl'=>$formUrl]);
    }

    public function store(Request $request)
    {
        $userType = auth()->user()->role()->first()->name;
        
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        $insert_data=[];
        
        $insert_data['company_id'] = $request['company_id'];
        $insert_data['category_name'] = $request['category_name'];
        // $insert_data = $request->all(); 
        
        $product_category = ProductCategoryModel::create($insert_data);
        $listUrl = 'product-category.index';
        // echo '<pre>';print_r($product_category); exit();
        if($userType!=config('custom.superadminrole')){
            $listUrl = 'superadmin.product-category.index';
        }
      

        return redirect()->route($listUrl)->with('success',__('locale.product_category_success'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id=0)
    {
        $userType = auth()->user()->role()->first()->name;

        if($id > 0){
            $data_check = ProductCategoryModel::where(['id'=>$id]);
            if($data_check->count() > 0 ){
                $this->data['result'] = $data_check->first();
                $this->data['company'] = Company::get();

                if($userType!=config('custom.superadminrole')){
                    $formUrl = 'product-category.update';
                }

            }else{
                return redirect()->back()->with('error','Data Not found in Database');
            }
            return view('pages.product-category.create',$this->data);
        }else{
            return redirect()->back()->with('error','Data Not found in Database');
        }
        
    }

    public function update(Request $request, $id=0)
    {

            $validator = Validator::make($request->all(), [
                'category_name' => 'required',
            
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput();
            }

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
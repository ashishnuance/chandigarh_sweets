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
 
    public function index(Request $request)
    {
        $perpage = config('app.perpage');
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "product category", 'name' => "product category"], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Company List');
        $product_cate_list = ProductCategoryModel::select(['id','category_name'])->orderBy('id','DESC')->paginate($perpage);

      
        return view('pages.product-category.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'product_category_list'=>$product_cate_list]);
    }
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "product category", 'name' => "product category"], ['name' => "Add"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Product category Add');
        return view('pages.product-category.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle]);
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
        $product_category = ProductCategoryModel::create($request->all());

        return redirect()->route('product-category.index')->with('success',__('locale.product_category_success'));

      
    }

    public function show($id)
    {
        exit('show');
    }
}
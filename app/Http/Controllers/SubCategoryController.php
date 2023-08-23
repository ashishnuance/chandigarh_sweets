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

        // Urls
        $perpage = config('app.perpage');
        $userType = auth()->user()->role()->first()->name;
        $editUrl = 'superadmin.sub-category.edit';
        $deleteUrl = 'superadmin.sub-category.delete';
        $paginationUrl = 'superadmin.sub-category.index';
        if($userType!=config('custom.superadminrole')){
            $editUrl = 'sub-category.edit';
            $deleteUrl = 'sub-category.delete';
            $paginationUrl = 'sub-category.index';
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "Sub Category", 'name' => __('local.Sub Category')], ['name' => "List"],
        ];


          //Pageheader set true for breadcrumbs
          $pageConfigs = ['pageHeader' => true];
          $pageTitle = __('locale.Company List');

        $subCategoryList = ProductSubCategory::with('categoryname')->orderBy('id','DESC')->paginate($perpage);

        //  dd($sub_category_list); die;

       $categoryResult = ProductCategoryModel::get();

        return view('pages.sub-category.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'sub_category_list'=>$subCategoryList, 'category_list'=>$categoryResult,'editUrl'=>$editUrl,'deleteUrl'=>$deleteUrl,'userType'=>$userType]);
    }
 
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => route("superadmin.sub-category.index"), 'name' => __('locale.sub category')], ['name' => "Add"],
        ];
        $userType = auth()->user()->role()->first()->name;
        $formUrl = 'superadmin.sub-category.store';
        if($userType!=config('custom.superadminrole')){
            $formUrl = 'sub-category.store';
        }
        //Pageheader set true for breadcrumbs
        $category = ProductCategoryModel::get();

        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Product category Add');
        return view('pages.sub-category.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'category'=>$category,'formUrl'=>$formUrl]);
    }

    public function store(Request $request)
    {
        $userType = auth()->user()->role()->first()->name;
        $listUrl = 'superadmin.sub-category.index';
        if($userType!=config('custom.superadminrole')){
            $listUrl = 'sub-category.index';
        }

        $validator = Validator::make($request->all(), [
            'subcat_name' => 'required',
          
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $checkCatgoryName = ProductSubCategory::where('procat_id ',$request->category_id)->where('subcat_name','like',$request->subcat_name);
        if($checkCatgoryName->count()>0){
            return redirect()->back()
            ->withErrors(__('locale.name_exits'))
            ->withInput();
            // return redirect()->back()->with('error',__('locale.name_exits'))->withInput();
        }
        
        $insert_data=[];
        
        $insert_data['procat_id'] = $request['category_id'];
        $insert_data['subcat_name'] = $request['subcat_name'];
        $create = ProductSubCategory::create($insert_data);
        
        // echo '<pre>';print_r($request->all());  exit();
        return redirect()->route($listUrl)->with('success',__('locale.product_category_success'));  
       
    }

    public function show($id)
    {
        exit('show');
    }


    public function edit($id=0)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => route("superadmin.sub-category.index"), 'name' => __('locale.sub category')], ['name' => "Add"],
        ];

        $userType = auth()->user()->role()->first()->name;
        $formUrl = 'superadmin.sub-category.update';
        
        if($userType!=config('custom.superadminrole')){
            $formUrl = 'sub-category.update';
        }
      
        $validator = Validator::make($request->all(), [
            'subcat_name' => 'required',
          
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $category= ProductCategoryModel::get();

        $SubCategoryResult = ProductSubCategory::findOrFail($id);

        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.sub category');
        return view('pages.sub-category.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'category'=>$category,'result'=>$SubCategoryResult,'formUrl'=>$formUrl]);
        
    }

    public function update(Request $request, $id=0)
    {

        $userType = auth()->user()->role()->first()->name;
        $listUrl = 'superadmin.sub-category.index';
        if($userType!=config('custom.superadminrole')){
            $listUrl = 'sub-category.index';
        }
        
    
        $result = ProductSubCategory::findOrFail($id);

        $result->subcat_name = $request->input('subcat_name');
        $result->procat_id = $request->input('category_id');

        $result->save();
        
        return redirect()->route($listUrl)->with('success',__('locale.sub_category_update_success'));  
        
    }

    public function destroy($id)
    {

        ProductSubCategory::find($id)->delete();
        return redirect()->back()->with('success',__('locale.sub category delete successmessage'));

    }

  
}
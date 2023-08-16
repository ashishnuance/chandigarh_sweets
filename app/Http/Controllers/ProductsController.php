<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Products;
use App\Models\{ProductCategoryModel,ProductSubCategory,ProductImagesModel};
use App\Exports\ProductsExport;
use App\Exports\UserProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Helper;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perpage = config('app.perpage');
        $productResultResponse = [];
        $userType = 'admin';
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/superadmin", 'name' => "Home"], ['link' => "superadmin/product", 'name' => __('locale.Items')], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Items List');
        
        $productResult = Products::select(['id','product_code','product_name','product_slug','product_catid','product_subcatid','food_type','blocked'])->orderBy('id','DESC');

        if($request->ajax()){
            $productResult = $productResult->when($request->seach_term, function($q)use($request){
                            $q->where('id', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('product_name', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('product_code', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('food_type', 'like', '%'.$request->seach_term.'%');
                        })
                        ->when($request->status, function($q)use($request){
                            $q->where('blocked',$request->status);
                        })
                        ->paginate($perpage);
            return view('pages.products.ajax-list', compact('productResult'))->render();
        }
        $productResult = $productResult->paginate($perpage);

        if($productResult->count()>0){
            $productResultResponse = $productResult;
        }
        return view('pages.products.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'productResult'=>$productResultResponse,'userType'=>$userType]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id='')
    {
        $userType = 'admin';
        $product_result=$states=$productSubCategoryResult=false;
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "product", 'name' => __('locale.Items')], ['name' => (($id!='') ? __('locale.Edit') : __('locale.Create') )]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        
        $companies = Company::get(["company_name", "id","company_code"]);
        $productCategoryResult = ProductCategoryModel::get(["category_name", "id"]);
        $foodTypeResult = ['veg','non-veg'];
        $pageTitle = __('locale.Company Admin'); 
        $productCode = Helper::setNumber();
        $formUrl = 'product.create';
        if($id!=''){
            $product_result = Products::with('category')->find($id);
            $productSubCategoryResult = ProductCategoryModel::get(["category_name", "id"])->where('procat_id',$product_result->product_catid);
            $productCode = $product_result->product_code;
            $formUrl = 'product.update';
            
        }
        
        return view('pages.products.create', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs,'pageTitle'=>$pageTitle,'companies'=>$companies,'product_result'=>$product_result,'userType'=>$userType,'productCategoryResult'=>$productCategoryResult,'productSubCategoryResult'=>$productSubCategoryResult,'foodTypeResult'=>$foodTypeResult,'productCode'=>$productCode,'formUrl'=>$formUrl]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function productImport(){
        try{
            
            $import = new ProductsImport;
            Excel::import($import, request()->file('importfile'));
            // print_r($import); exit();
            return redirect()->back()->with('success', __('locale.import_message'));
        }catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            
            return redirect()->route('product')->with('error', __('locale.try_again'));
        }
    }
    public function productExport($type='admin'){
        if($type=='admin'){
            $companyUser = new ProductsExport;
        }else{
            $companyUser = new UserProductsExport;
        }
        return Excel::download($companyUser, 'products-'.$type.time().'.xlsx');
    }
}

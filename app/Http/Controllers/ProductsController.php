<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\{Products,ProductsVariations,ProductImagesModel};
use App\Models\{ProductCategoryModel,ProductSubCategory};
use App\Exports\ProductsExport;
use App\Exports\UserProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Helper;
use File;
use Image;

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
        $formUrl = 'product.store';
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
        // echo '<pre>';print_r($request->all()); exit();
        $product_info = [
            'product_name'=>$request->product_name,
            'product_code'=>$request->product_code,
            'product_catid'=>($request->product_catid!='') ? $request->product_catid : 0,
            'product_subcatid'=>($request->product_subcatid!='') ? $request->product_subcatid : 0,
            'food_type'=>($request->food_type!='') ? $request->food_type : 'veg',
            'blocked'=>$request->blocked,
            'description'=>$request->description,
        ];

        $product = Products::create($product_info);
        
        if($request->has('product_image')) {
            $allowedfileExtension=['pdf','jpg','png'];
            $folder = storage_path('/product/images/');
            
            if (!File::exists($folder)) {
            File::makeDirectory($folder, $mode = 0777, true, true);
            }
            
            foreach ($request->file('product_image') as $key => $value) {

                $file= $value;
                $extension =  $file->getClientOriginalExtension();

                $check=in_array($extension, $allowedfileExtension);
                
                if($check) {
                    $filename = time(). $file->getClientOriginalName();
                    $location = storage_path('/product/images/'.$filename);
                    Image::make($file)->resize(800,400,function ($constraint) {
                            $constraint->aspectRatio();                 
                        })->save($location);
                    ProductImagesModel::create(['product_id'=>$product->id,'image'=>$filename,'image_order'=>1]);
                } 
            }
            
        }
        
        
        if($request->has('variation')){
            $product_variation = [];
            $pro_new1 ='pro_new';
            foreach($request->variation as $key => $variation_val){
                
                
                for($i=0; $i<count($request->variation[$key]); $i++){
                    $product_variation[$i]['product_id'] = $product->id;
                    $product_variation[$i][$key] = $request->variation[$key][$i];
                }
                if(count($request->variation[$key])<count($request->variation['name'])){
                    $index = count($request->variation['name'])-count($request->variation[$key]);
                    $product_variation[$index][$key] = 0;
                }
                

            }
            
        }
        $product_variation_result = ProductsVariations::insert($product_variation);
        
        return redirect()->route('product.index')->with('success',__('locale.success common add'));
        
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
        $formUrl = 'product.store';
        if($id!=''){
            $product_result = Products::with('category')->with('subcategory')->find($id);
            $productSubCategoryResult = ProductCategoryModel::get(["category_name", "id"])->where('procat_id',$product_result->product_catid);
            $productCode = $product_result->product_code;
            $formUrl = 'product.update';
            
        }
        
        return view('pages.products.create', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs,'pageTitle'=>$pageTitle,'companies'=>$companies,'product_result'=>$product_result,'userType'=>$userType,'productCategoryResult'=>$productCategoryResult,'productSubCategoryResult'=>$productSubCategoryResult,'foodTypeResult'=>$foodTypeResult,'productCode'=>$productCode,'formUrl'=>$formUrl]);
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

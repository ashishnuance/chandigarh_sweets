<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\ProductCompanyMapping as ProductCompanyMappingModel;
use App\Models\{Products,ProductsVariations,ProductImagesModel,ProductsVariationsOptions};
use App\Models\{ProductCategoryModel,ProductSubCategory,ProductPriceMapping};
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Helper;
use File;
use Image;

class ProductCompanyMapping extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userType = auth()->user()->role()->first()->name;
        $perpage = config('app.perpage');
        $productResultResponse = [];
        
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/superadmin", 'name' => "Home"], ['link' => "superadmin/product-mapping", 'name' => __('locale.product mapping')], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.product mapping');
        $editUrl = 'product-mapping.edit';
        $deleteUrl = 'product-mapping.delete';
        $paginationUrl = 'product-mapping.index';
        $companyResult = Company::select(['id','company_name','company_code'])->get();
        $productResult = Products::select(['id','product_code','product_name'])->get();
        $productMappingResult = ProductCompanyMappingModel::with(['company','product']);
        
        // dd($productMappingResult->get()); exit();
        if($request->ajax()){
            $productMappingResult = $productMappingResult->whereHas('company',function($query) use ($request) {
                $query->where('company_name','like', '%'.$request->seach_term.'%');
            })->whereHas('product',function($q) use($request){
                $q->where('product_name', 'like', '%'.$request->seach_term.'%');
            })->paginate($perpage);
            return view('pages.product-mapping.ajax-list', compact('productMappingResult','editUrl','deleteUrl'))->render();
        }
        $productMappingResult = $productMappingResult->paginate($perpage);

        $productMappingResponse  = [];
        if($productMappingResult->count()>0){
            $productMappingResponse = $productMappingResult;
        }
        return view('pages.product-mapping.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'productMappingResult'=>$productMappingResponse,'userType'=>$userType,'editUrl'=>$editUrl,'deleteUrl'=>$deleteUrl]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userType = auth()->user()->role()->first()->name;
        $formUrl = 'product-mapping.store';
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "superadmin/product-mapping", 'name' => __("locale.product mapping")], ['name' => "Add"],
        ];
        //Pageheader set true for breadcrumbs

        $companyResult = Company::select(['id','company_name','company_code'])->get();
        $productResult =[];
        $productResult = Products::with('productvariationWithName')->select(['id','product_code','product_name','product_slug','product_order_type'])->get();
        if($userType!=config('custom.superadminrole')){
            $company_id = Helper::loginUserCompanyId();
            
            $productResult = Products::with('productvariationWithName')->select(['id','product_code','product_name','product_slug','product_order_type'])->whereHas('company',function($query) use ($company_id) {
                $query->where('company_id',$company_id);
            })->get();
        }

        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Product category Add');
        return view('pages.product-mapping.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'companyResult'=>$companyResult,'productResult'=>$productResult,'formUrl'=>$formUrl]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            "product_ids"    => "required|array|min:1",
            "product_variant"    => "required|array|min:1",
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        $product_mapping=[];
        // echo '<pre>';
        for($p=0;$p<count($request->product_ids);$p++){
            $request->product_ids[$p];
            
            if(isset($request->product_variant) && !empty($request->product_variant)){
                
                foreach($request->product_variant as $k => $val){
                    if($request->product_ids[$p]==$k){
                        
                        foreach($val as $vk => $vl){
                            
                            for($vari=0;$vari<count($vl);$vari++){
                                $product_mapping[$vari][$vk] = $vl[$vari];
                                if($vk=='valid_date'){
                                    $valid_date = explode('-',$vl[$vari]);
                                    $product_mapping[$vari]['start_date'] = date('Y-m-d',strtotime($valid_date[0]));
                                    $product_mapping[$vari]['end_date'] = date('Y-m-d',strtotime($valid_date[1]));
                                }
                                $product_mapping[$vari]['company_id'] = $request->company_id;
                                $product_mapping[$vari]['product_id'] = $request->product_ids[$p];
                                if($vk=='id'){
                                    $product_mapping[$vari]['product_variant_id'] = $vl[$vari];
                                }
                                $product_mapping[$vari]['buyer_type'] = 1;
                                unset($product_mapping[$vari]['valid_date']);
                                unset($product_mapping[$vari]['id']);
                            }
                        }
                    }
                    
                }
                
            }
        }
        
        if(!empty($product_mapping)){
            ProductPriceMapping::insert($product_mapping);
            return redirect()->route('product-mapping.index')->with('success',__('locale.success common add'));
        }
        return redirect()->route('product-mapping.index')->with('error',__('locale.try_again'));
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
        $productIds = [];
        $formUrl = 'product-mapping.update';
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "superadmin/product-mapping", 'name' => __("locale.product mapping")], ['name' => "Edit"],
        ];
        //Pageheader set true for breadcrumbs

        $companyResult = Company::select(['id','company_name','company_code'])->get();
        $productResult = Products::select(['id','product_code','product_name'])->get();
        $mappingResult = ProductCompanyMappingModel::select('id','company_id','product_id')->where('company_id',$id)->get();
        foreach($mappingResult as $map_val){
            $productIds[] = $map_val->product_id;
        }
        
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Product category Add');
        return view('pages.product-mapping.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'companyResult'=>$companyResult,'productResult'=>$productResult,'formUrl'=>$formUrl,'mappingResult'=>$mappingResult,'productIds'=>$productIds]);
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
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            "product_ids"    => "required|array|min:1",
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        // dd($request->all());
        $productMappingData = [];
        for($p=0;$p<count($request->product_ids);$p++){
            $productMappingData[] = ['company_id'=>$request->company_id,'product_id'=>$request->product_ids[$p]];
        }
        if(!empty($productMappingData)){
            ProductCompanyMappingModel::where('company_id',$id)->delete();
            ProductCompanyMappingModel::insert($productMappingData);
            return redirect()->route('product-mapping.index')->with('success',__('locale.success common update'));
        }
        return redirect()->route('product-mapping.index')->with('error',__('locale.try_again'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mappingResult = ProductCompanyMappingModel::where('id',$id)->count();
        
        if($mappingResult>0){
            ProductCompanyMappingModel::where('id',$id)->delete();
            return redirect()->back()->with('success',__('locale.delete_message'));
        }else{
            return redirect()->back()->with('error',__('locale.try_again'));
        }
    }
}

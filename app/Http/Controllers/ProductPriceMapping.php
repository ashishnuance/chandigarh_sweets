<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\ProductCompanyMapping as ProductCompanyMappingModel;
use App\Models\{Products,ProductsVariations,ProductImagesModel,ProductsVariationsOptions};
use App\Models\{ProductCategoryModel,ProductSubCategory,BuyerTypeChannel};
use Helper;

class ProductPriceMapping extends Controller
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
            return view('pages.product-price-mapping.ajax-list', compact('productMappingResult','editUrl','deleteUrl'))->render();
        }
        $productMappingResult = $productMappingResult->paginate($perpage);

        $productMappingResponse  = [];
        if($productMappingResult->count()>0){
            $productMappingResponse = $productMappingResult;
        }
        return view('pages.product-price-mapping.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'productMappingResult'=>$productMappingResponse,'userType'=>$userType,'editUrl'=>$editUrl,'deleteUrl'=>$deleteUrl]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userType = auth()->user()->role()->first()->name;
        $formUrl = 'price-mapping.store';
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "superadmin/price-mapping", 'name' => __("locale.price_mapping")], ['name' => "Add"],
        ];
        //Pageheader set true for breadcrumbs

        $companyResult = Company::select(['id','company_name','company_code'])->get();
        $productResult = Products::with('company')->select(['id','product_code','product_name'])->get();
        $buyerTypeResult = BuyerTypeChannel::select(['id','name'])->get();
        if($userType!='superadmin'){
            $company_id = Helper::loginUserCompanyId();
            $buyerTypeResult = BuyerTypeChannel::select('id','name')->where('company_id',$company_id)->get();
            $productResult = Products::with('company')->select(['id','product_code','product_name'])->whereHas('company',function($query) use ($company_id) {
                $query->where('company_id',$company_id);
            })->get();
        }
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.price_mapping');
        return view('pages.product-price-mapping.create',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'companyResult'=>$companyResult,'productResult'=>$productResult,'formUrl'=>$formUrl,'userType'=>$userType,'buyerTypeResult'=>$buyerTypeResult]);
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
}

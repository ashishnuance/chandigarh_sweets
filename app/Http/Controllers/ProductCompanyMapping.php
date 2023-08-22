<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductCompanyMapping extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userType = auth()->user()->role()->first()->name;
        $perpage = config('app.perpage');
        $productResultResponse = [];
        
        // Breadcrumbs
        $breadcrumbs = [
            ['link' => "/superadmin", 'name' => "Home"], ['link' => "superadmin/product", 'name' => __('locale.Items')], ['name' => "List"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = __('locale.Items List');
        $editUrl = 'superadmin.product.edit';
        $deleteUrl = 'superadmin.product.delete';
        $sampleFileName = 'product-import.csv';
        $productResult = Products::with(['category','subcategory','company'])->select(['id','product_code','product_name','product_slug','product_catid','product_subcatid','food_type','blocked'])->orderBy('id','DESC');

        if($userType!=config('custom.superadminrole')){
            $company_id = Helper::loginUserCompanyId();
            $productResult = $productResult->whereHas('company',function($query) use ($company_id) {
                $query->where('company_id',$company_id);
            });
            $editUrl = 'product.edit';
            $deleteUrl = 'product.delete';
        }
        // dd($productResult->get()); exit();
        if($request->ajax()){
            $productResult = $productResult->when($request->seach_term, function($q)use($request){
                            $q->where('product_name', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('product_code', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('food_type', 'like', '%'.$request->seach_term.'%');
                        })
                        ->when($request->status, function($q)use($request){
                            $q->where('blocked',$request->status);
                        })
                        ->paginate($perpage);
            return view('pages.products.ajax-list', compact('productResult','editUrl','deleteUrl'))->render();
        }
        $productResult = $productResult->paginate($perpage);

        if($productResult->count()>0){
            $productResultResponse = $productResult;
        }
        return view('pages.products-mapping.list',['breadcrumbs' => $breadcrumbs], ['pageConfigs' => $pageConfigs,'pageTitle'=>$pageTitle,'productResult'=>$productResultResponse,'userType'=>$userType,'editUrl'=>$editUrl,'deleteUrl'=>$deleteUrl,'sampleFileName'=>$sampleFileName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

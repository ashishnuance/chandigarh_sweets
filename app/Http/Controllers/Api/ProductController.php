<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\{Products,User};
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;
   
class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $user =  auth('sanctum')->user();
        
        $auth_user_result = User::with('company')->find($user->id);
        // print_r($auth_user_result);
        $select = ['id','product_code','product_name','product_slug','description','food_type','product_catid','product_subcatid','product_type'];
        $user_company_id = $auth_user_result->company[0]->id;
        $user_type = $auth_user_result->user_type;
        $products = Products::with(['product_variation','product_price'])->where(['product_type'=>$user->user_type,'blocked'=>1])->select($select)
        ->whereHas('company',function($q) use($user_company_id){
            $q->where('company_id',$user_company_id);
        });
        if($request->has('search')){
        
            $products->where('product_name','like','%'.$request->search.'%')
            ->orWhere('product_slug','like','%'.$request->search.'%')
            ->orWhere('product_code','like','%'.$request->search.'%');
        }
        if($products->count()>0){
            $products = $products->get();
    
            return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
        }else{
            return $this->sendError('Product not found.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product = Products::create($input);
   
        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug): JsonResponse
    {
        $product = Products::where('product_slug','like',$slug)->first();
  
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
   
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $product): JsonResponse
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
   
        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $product): JsonResponse
    {
        $product->delete();
   
        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
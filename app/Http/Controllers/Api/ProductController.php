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
        
        $select = ['id','product_code','product_name','product_slug','description','food_type','product_catid','product_subcatid','product_type'];
        $user_company_id = $auth_user_result->company[0]->id;
        $user_type = $auth_user_result->user_type;
        $products = Products::with(['product_variation','product_price','product_images'])->where(['blocked'=>1])->select($select)
        ->whereHas('company',function($q) use($user_company_id){
            $q->where('company_id',$user_company_id);
        });
        if($request->has('search')){
        
            $products->where('product_name','like','%'.$request->search.'%')
            ->orWhere('product_slug','like','%'.$request->search.'%')
            ->orWhere('product_code','like','%'.$request->search.'%');
        }
        
        if($products->count()>0){
            
            // print_r($products->get()); exit();
            foreach($products->get() as $key => $pro_val){
                $products_result[$key]['id'] = $pro_val->id;
                $products_result[$key]['product_code'] = $pro_val->product_code;
                $products_result[$key]['product_name'] = $pro_val->product_name;
                $products_result[$key]['product_slug'] = $pro_val->product_slug;
                $products_result[$key]['description'] = $pro_val->description;
                $products_result[$key]['food_type'] = $pro_val->food_type;
                $products_result[$key]['product_type'] = $pro_val->product_type;
                if(isset($pro_val->product_images) && !empty($pro_val->product_images)){
                    foreach($pro_val->product_images as $im => $image_val){
                        $products_result[$key]['product_images'][$im]['image'] = route('image.displayImage',$image_val->image);
                        $products_result[$key]['product_images'][$im]['image_order'] = $image_val->image_order;

                    }
                }
                $products_result[$key]['product_variation'] = $pro_val->product_variation;
                if(isset($pro_val->product_price) && !empty($pro_val->product_price)){
                    foreach($pro_val->product_price as $p => $price_val){
                        if($price_val->buyer_type==$user_type && $price_val->start_date>=date('Y-m-d')){
                            $products_result[$key]['product_price']['price'] = $price_val->price;
                            $products_result[$key]['product_price']['discount'] = $price_val->discount;
                        }
                    }

                }
            }
            return $this->sendResponse(ProductResource::collection($products_result), 'Products retrieved successfully.');
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
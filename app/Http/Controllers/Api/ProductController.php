<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\{Products,User,ProductsVariations,ProductPriceMapping,ProductVariationType,ProductCompanyMapping};
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
        
        $select = ['id','product_code','product_name','product_slug','description','food_type','product_catid','product_subcatid','product_type','product_order_type'];
        $user_company_id = (isset($auth_user_result->company[0]) && !empty($auth_user_result->company[0])) ? $auth_user_result->company[0]->id : 0;
        
        $user_type = $auth_user_result->user_type;
        $products = Products::with(['product_variation','product_images','company'])->where(['blocked'=>1])->select($select)
        ->whereHas('company',function($q) use($user_company_id){
            if($user_company_id>0){
            $q->where('company_id',$user_company_id);
            }
        });
        if($request->has('search')){
        
            $products->where('product_name','like','%'.$request->search.'%')
            ->orWhere('product_slug','like','%'.$request->search.'%')
            ->orWhere('product_code','like','%'.$request->search.'%');
        }
        
        if($products->count()>0){
            $products_result = [];
            foreach($products->get() as $key => $pro_val){
                $products_result[$key]['id'] = $pro_val->id;
                $products_result[$key]['product_code'] = $pro_val->product_code;
                $products_result[$key]['product_name'] = $pro_val->product_name;
                $products_result[$key]['product_slug'] = $pro_val->product_slug;
                $products_result[$key]['description'] = $pro_val->description;
                $products_result[$key]['food_type'] = $pro_val->food_type;
                $products_result[$key]['product_type'] = $pro_val->product_type;
                $products_result[$key]['product_order_type'] = $pro_val->product_order_type;
                
                if(isset($pro_val->product_images) && !empty($pro_val->product_images)){
                    foreach($pro_val->product_images as $im => $image_val){
                        $products_result[$key]['product_images'][$im]['image'] = route('image.displayImage',$image_val->image);
                        $products_result[$key]['product_images'][$im]['image_order'] = $image_val->image_order;

                    }
                }
                if(!empty($pro_val->product_variation) && $pro_val->product_variation!=''){
                    foreach($pro_val->product_variation as $pvr_key => $pro_vari_val){
                        unset($pro_vari_val['offer_price']);
                        unset($pro_vari_val['created_at']);
                        unset($pro_vari_val['updated_at']);
                        $products_result[$key]['product_variation'][$pvr_key] = $pro_vari_val;
                        $products_result[$key]['product_variation'][$pvr_key]['variation_value'] = $pro_vari_val->quantity;
                        $products_result[$key]['product_variation'][$pvr_key]['variation_type'] = ProductVariationType::where('id',$pro_vari_val->variation_type)->first()->name;
                        unset($pro_vari_val['quantity']);
                        if($pro_val->id && $pro_vari_val->id){
                            $productPriceMapping = ProductPriceMapping::where(['product_id'=>$pro_val->id,'product_variant_id'=>$pro_vari_val->id]);
                            if($productPriceMapping->count()>0){
                                $products_result[$key]['product_variation'][$pvr_key]['main_price'] = $productPriceMapping->latest()->first()->price;
                                $products_result[$key]['product_variation'][$pvr_key]['discount'] = $productPriceMapping->latest()->first()->discount;
                            }
                        }
                    }
                }
                $products_result[$key]['company_id'] = $pro_val->company[0]->id;
            }
            return $this->sendResponse(ProductResource::collection($products_result), 'Products retrieved successfully.');
        }else{
            return $this->sendError('Product not found.','',400);
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
            return $this->sendError('Validation Error.', $validator->errors(),400);       
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
        $user =  auth('sanctum')->user();
        
        $auth_user_result = User::with('company')->find($user->id);
        
        $select = ['id','product_code','product_name','product_slug','description','food_type','product_catid','product_subcatid','product_type','product_order_type'];
        $user_company_id = (isset($auth_user_result->company[0]) && !empty($auth_user_result->company[0])) ? $auth_user_result->company[0]->id : 0;
        
        $user_type = $auth_user_result->user_type;
        $products = Products::with(['product_variation','product_images'])->where(['blocked'=>1])->select($select)->where('product_slug','like',$slug);
        
        
        $products_result = [];
        if($products->count()>0){
            $pro_val = $products->first();
            // foreach($products->get() as $key => $pro_val){
                $products_result['id'] = $pro_val->id;
                $products_result['product_code'] = $pro_val->product_code;
                $products_result['product_name'] = $pro_val->product_name;
                $products_result['product_slug'] = $pro_val->product_slug;
                $products_result['description'] = $pro_val->description;
                $products_result['food_type'] = $pro_val->food_type;
                $products_result['product_type'] = $pro_val->product_type;
                $products_result['product_order_type'] = $pro_val->product_order_type;
                // print_r($products_result); exit();
                $products_result['product_images'] = [];
                if(isset($pro_val->product_images) && !empty($pro_val->product_images)){
                    foreach($pro_val->product_images as $im => $image_val){
                        $products_result['product_images'][$im]['image'] = route('image.displayImage',$image_val->image);
                        $products_result['product_images'][$im]['image_order'] = $image_val->image_order;

                    }
                }
                if(!empty($pro_val->product_variation) && $pro_val->product_variation!=''){
                    foreach($pro_val->product_variation as $pvr_key => $pro_vari_val){
                        unset($pro_vari_val['offer_price']);
                        unset($pro_vari_val['created_at']);
                        unset($pro_vari_val['updated_at']);
                        $products_result['product_variation'][$pvr_key] = $pro_vari_val;
                        $products_result['product_variation'][$pvr_key]['variation_value'] = $pro_vari_val->quantity;
                        $products_result['product_variation'][$pvr_key]['variation_type'] = ProductVariationType::where('id',$pro_vari_val->variation_type)->first()->name;
                        unset($pro_vari_val['quantity']);
                        if($pro_val->id && $pro_vari_val->id){
                            $productPriceMapping = ProductPriceMapping::where(['product_id'=>$pro_val->id,'product_variant_id'=>$pro_vari_val->id]);
                            if($productPriceMapping->count()>0){
                                $products_result['product_variation'][$pvr_key]['main_price'] = $productPriceMapping->latest()->first()->price;
                                $products_result['product_variation'][$pvr_key]['discount'] = $productPriceMapping->latest()->first()->discount;
                            }
                        }
                    }
                }
            // }
            return $this->sendResponse(new ProductResource($products_result), 'Products retrieved successfully.');
        }else{
            return $this->sendError('Product not found.','',400);
        }

        $select = ['id','product_code','product_name','product_slug','description','food_type','product_catid','product_subcatid','product_type','product_order_type'];
        $product = Products::with(['product_variation','product_price','product_images'])->where(['blocked'=>1])->select($select)->where('product_slug','like',$slug)->first();
  
        if(isset($product->product_images) && !empty($product->product_images)){
            foreach($product->product_images as $im => $image_val){
                unset($image_val->created_at);
                unset($image_val->updated_at);
                unset($image_val->product_id);
                $product->product_images[$im]['image'] = route('image.displayImage',$image_val->image);
                $product->product_images[$im]['image_order'] = $image_val->image_order;

            }
        }
        if (is_null($product)) {
            return $this->sendError('Product not found.','',400);
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
            return $this->sendError('Validation Error.', $validator->errors(),400);       
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
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\{User,Cartlist,ProductsVariations,Products,CheckoutModel,Company};
use Illuminate\Support\Facades\Hash;

class CheckoutController extends BaseController
{
    function add_to_cart(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'product_qty' => 'required:min:1',
            'product_variant_id' => 'required',
            'order_type' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);       
        }
        if(!in_array(strtolower($request->order_type),['order','quotation'])){
            return $this->sendError('Validation Error.', 'Order type not valid',400);  
        }
        if($request->order_type=='order' && $request->product_price==''){
            return $this->sendError('Validation Error.', 'Product price required',400);  
        }
        
        // print_r($request->all()); exit();
        if(ProductsVariations::where('id',$request->product_variant_id)->count()==0){
            return $this->sendError('Validation Error.', 'Product variant id not correct',400);  
        }
        if(Products::where('id',$request->product_id)->count()==0){
            return $this->sendError('Validation Error.', 'Product id not correct',400);  
        }
        
        $input = $request->all();
        $input['product_price'] = ($request->order_type=='order') ? $request->product_price : 0 ;
        $input['user_id'] = auth('sanctum')->user()->id;
        $checkCart = Cartlist::where(['product_id'=>$request->product_id,'product_variant_id'=>$request->product_variant_id,'user_id'=>$input['user_id']]);
        
        if($checkCart->count()>0){
            $cartupdate['product_qty'] = ($checkCart->first()->product_qty+$request->product_qty);
            $cartResult = Cartlist::where('id',$checkCart->first()->id)->update($cartupdate);
            $cartResult=true;
            $successMessage = __('locale.success common update');
        }else{
            $cartResult = Cartlist::create($input);
            $successMessage = __('locale.success common add');
        }

        if($cartResult){
            return $this->sendResponse('',$successMessage);
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.try_again')]);
        }
    }

    function cart_list(){
        try{
            $user_id = auth('sanctum')->user()->id;

            $cartlistResult = Cartlist::with('products')->where('user_id',$user_id);
            
            if($cartlistResult->count()>0){
                $products_result = [];
                foreach($cartlistResult->get() as $key => $cart_val){
                    $products_result[$key]= $cart_val;
                    $products_result[$key]['product_code'] = $cart_val->products->product_code;
                    $products_result[$key]['product_name'] = $cart_val->products->product_name;
                    $products_result[$key]['product_slug'] = $cart_val->products->product_slug;
                    $products_result[$key]['food_type'] = $cart_val->products->food_type;
                    $products_result[$key]['product_images'] = '';
                    if(isset($cart_val->products->product_images) && !empty($cart_val->products->product_images)){
                        foreach($cart_val->products->product_images as $im => $image_val){
                            $products_result[$key]['product_images'] = route('image.displayImage',$image_val->image);    
                        }
                    }
                    unset($products_result[$key]->products);
                }
                return $this->sendResponse($products_result,__('locale.found_successfully'));
            }else{
                return $this->sendError('Failed.', ['error'=>__('locale.cart_empty')],400);
            }
        }catch(\Exception $e){
            return $this->sendError('Failed.', ['error'=>$$e>getMessage()],400);
        }
    }

    function remove_item(Request $request){
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'user_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);       
        }


        $cartlistResult = Cartlist::where(['id'=>$request->cart_id,'user_id'=>$request->user_id]);
        if($cartlistResult->count()>0){
            if($cartlistResult->delete()){
                return $this->sendResponse('',__('locale.delete_message'));
            }else{
                return $this->sendError('Failed.', ['error'=>__('locale.try_again')],400);
            }
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.cart_delete_error_msg')],400);
        }
    }

    function delete_cart(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);       
        }

        

        $cartlistResult = Cartlist::where(['user_id'=>$request->user_id]);
        if($cartlistResult->count()>0){
            if($cartlistResult->delete()){
                return $this->sendResponse('',__('locale.delete_message'));
            }else{
                return $this->sendError('Failed.', ['error'=>__('locale.try_again')],400);
            }
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.cart_empty')],400);
        }
    }

    function checkout(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'product_data'=>"required|array|min:1",
            'order_type' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);       
        }

        if(Company::where('id',$request->company_id)->count()==0){
            return $this->sendError('Validation Error.', 'Company id is not correct',400);  
        }

        $error_array = [];
        $product_data = [];
        if($request->has('product_data') && !empty($request->product_data)){
            foreach($request->product_data as $pro_val){
                
                if(ProductsVariations::where('id',$pro_val['product_variant_id'])->count()==0){
                    $error_array['product_variant_id'][] = 'Product variant id '.$pro_val['product_variant_id'].' is not correct';
                }
                $productResult = Products::where('id',$pro_val['product_id']);
                if($productResult->count()==0){
                    $error_array['product_id'][] = 'Product id '.$pro_val['product_id'].' is not correct'; 
                }
                if($pro_val['product_qty']<=0){
                    $error_array['product_qty'][] = 'Product qty '.$pro_val['product_qty'].' is not correct'; 
                }
            }
        }
        if(!empty($error_array)){
            return $this->sendError('Validation Error.', $error_array,400);  
        }

        $checkoutData = $request->input();
        $checkoutData['product_json'] = json_encode($request->product_data);
        if(CheckoutModel::create($checkoutData)){
            return $this->sendResponse('',__('locale.order_success_submit'));
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.try_again')],400);
        }
    }

    function user_orders($type='order'){
        
        $orderDataResult = CheckoutModel::where('order_type',$type);
        if($orderDataResult->count()>0){
            
            return $this->sendResponse($orderDataResult->get(),__('locale.found_successfully'));
        
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.no_record_found')],400);
        }
    }

    function user_orders_detail($order_id){
        
        $orderDataResult = CheckoutModel::where('id',$order_id);
        $orderDataResultArray = [];
        if($orderDataResult->count()>0){
            
            $product_json = json_decode($orderDataResult->first()->product_json);
            foreach($product_json as $pro_key => $product_value){
                $productResult = Products::where('id',$product_value->product_id)->whereHas('product_variation',function($q) use($product_value){
                    $q->where('id',$product_value->product_variant_id);
                });
                if($productResult->count()>0){
                    $orderDataResultArray[$pro_key] = $productResult->first();
                }
            }
            return $this->sendResponse($orderDataResultArray,__('locale.found_successfully'));
        
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.no_record_found')],400);
        }
    }
}

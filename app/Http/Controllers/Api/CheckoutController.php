<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\{User,Cartlist};
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
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if($request->order_type!='order' && ($request->product_price=='' || $request->product_price==0)){
            return $this->sendError('Validation Error.', 'Product price required');  
        }
   
        $input = $request->all();
        $input['product_price'] = ($request->order_type!='order') ? $request->product_price : 0 ;
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
            return $this->sendError('Faild.', ['error'=>__('locale.try_again')]);
        }
    }

    function cart_list(){
        
        $user_id = auth('sanctum')->user()->id;
        $cartlistResult = Cartlist::where('user_id',$user_id);
        if($cartlistResult){
            return $this->sendResponse($cartlistResult->get(),__('locale.found_successfully'));
        }else{
            return $this->sendError('Faild.', ['error'=>__('locale.cart_empty')]);
        }
    }
}

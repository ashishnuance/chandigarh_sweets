<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfile extends BaseController
{
    function generate_password(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $userCheck = User::where('id',$input['user_id']);
        if($userCheck->count()>0){
            $success['password'] = Hash::make($input['password']);
            User::where('id',$input['user_id'])->update(['password'=>$success['password']]);
            return $this->sendResponse('', __('locale.api_password_change'));
        }else{
            return $this->sendError('Faild.', ['error'=>__('locale.api_register_error')]);
        }
    }
}

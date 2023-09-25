<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'invite_code' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $userCheck = User::where('email',$input['email'])->where('invite_code',$input['invite_code']);
        if($userCheck->count()>0){
            $success['user_id'] = $userCheck->first()->id;
            $success['name'] = $userCheck->first()->name;
            $success['email'] = $userCheck->first()->email;
            $success['token'] =  $userCheck->first()->createToken('MyApp')->plainTextToken;
            return $this->sendResponse($success, 'User register successfully.');
        }else{
            return $this->sendError('Faild.', ['error'=>__('locale.api_register_error')]);
        }
        
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        //print_r($request->all); exit();
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            $success['user_id'] =  $user->id;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
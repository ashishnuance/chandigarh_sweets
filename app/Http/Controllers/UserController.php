<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\{Country, State, City};
use App\Models\{User,Role};
use App\Models\Company;
use App\Models\CompanyUserMapping;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Exports\AdminExport;
use Maatwebsite\Excel\Facades\Excel;
use Helper;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $userType = 'admin';
        $perpage = config('app.perpage');
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => __('locale.Company Admin')], ['name' => __('locale.Company Admin').__('locale.List')]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = 'Company Admin';
        $usersResult = User::whereHas(
            'role', function($q){
                $q->where('name', 'company-admin');
            }
        )->select(['id','name','email','phone','address','image','website_url','blocked'])->orderBy('id','DESC');

        if($request->ajax()){
            $usersResult = $usersResult->when($request->seach_term, function($q)use($request){
                $q->where('id', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('name', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('email', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('phone', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('address', 'like', '%'.$request->seach_term.'%');
                        })
                        ->when($request->status, function($q)use($request){
                            $q->where('users.blocked',$request->status);
                        })
                        ->paginate($perpage);
                        
            return view('pages.users.users-list-ajax', compact('usersResult'))->render();
        }

        $usersResult = $usersResult->paginate($perpage);
        
        return view('pages.users.users-list', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs,'usersResult'=>$usersResult,'pageTitle'=>$pageTitle,'userType'=>$userType]);
    }


    public function create($id='')
    {
        
        $user_result=$states=$cities=false;
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => __('locale.Company Admin')], ['name' => (($id!='') ? __('locale.Edit') : __('locale.Create') )]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $countries = Country::get(["name", "id"]);
        $companies = Company::get(["company_name", "id","company_code"]);
        $pageTitle = __('locale.Company Admin'); 
        if($id!=''){
            $user_result = User::with('company')->find($id);
            if($user_result){
            $states = State::where('country_id',$user_result->country)->get(["name", "id"]);
            $cities = City::where('state_id',$user_result->state)->get(["name", "id"]);
            }
            // echo '<pre>';print_r($user_result); exit();
        }
        return view('pages.users.users-create', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs,'countries'=>$countries,'pageTitle'=>$pageTitle,'companies'=>$companies,'user_result'=>$user_result,'states'=>$states,'cities'=>$cities]);
    }


    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|unique:users|max:250',
            'phone' => 'required|max:20',
            'address' => 'max:250',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        $role = Role::where('name', 'company-admin')->first();
        $random_password = Str::random(6);
        $request['password'] = Hash::make($random_password);
        $user = User::create($request->all());
        $user->company()->attach($request->company);
        $user->role()->attach( $role->id);
        return redirect()->route('company-admin-list')->with('success',__('locale.company_admin_create_success'));
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|max:250',
            'phone' => 'required|max:20',
            'address' => 'max:250',
            'password' => 'max:10',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        if(User::where('email',$request->email)->where('id','!=',$id)->count()>0){
            return redirect()->back()
            ->with('email','The Email Has Already Been Taken.')
            ->withInput();
        }
        
        
        unset($request['_method']);
        unset($request['_token']);
        unset($request['action']);
        unset($request['company']);
        unset($request['importcompany']);

        if(isset($request['password']) && $request['password']!=''){
            $request['password'] = Hash::make($request['password']);
        }else{
            unset($request['password']);
        }

        $user = User::where('id',$id)->update($request->all());

        return redirect()->route('company-admin-list')->with('success',__('locale.company_admin_create_success'));
    }

    
    public function companyUserImport(){
        try{
            
            $import = new UsersImport;
            Excel::import($import, request()->file('importcompany'));
            // print_r($import); exit();
            return redirect()->back()->with('success', __('locale.import_message'));
        }catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            
            return redirect()->route('company')->with('error', __('locale.try_again'));
        }
            
    }

    public function companyUserExport($type='admin') 
    {
        if($type=='admin'){
            $companyUser = new AdminExport;
        }else{
            $companyUser = new UsersExport;
        }
        return Excel::download($companyUser, 'company-'.$type.time().'.xlsx');
    }

    public function user_fetchState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)->get(["name", "id"]);
        return response()->json($data);
    }
    public function user_fetchCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)->get(["name", "id"]);
        return response()->json($data);
    }


    public function usersList(Request $request)
    {
        $userType = 'user';
        $perpage = config('app.perpage');
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => __('locale.Company User')], ['name' => __('locale.Company User').__('locale.List')]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $pageTitle = 'Company User';
        $paginationUrl = 'companyadmin.company-user-list';
        $usersResult = User::whereHas(
            'role', function($role_q){
                $role_q->where('name', 'company-user');
            }
        )->select(['name','email','phone','address','image','website_url','id','blocked'])->orderBy('id','DESC');
        $currentPage = 1;
        if($request->ajax()){
            exit('sdasd');
            $currentPage = $request->get('page');
            $usersResult = $usersResult->when($request->seach_term, function($q)use($request){
                $q->where('id', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('name', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('email', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('phone', 'like', '%'.$request->seach_term.'%')
                            ->orWhere('address', 'like', '%'.$request->seach_term.'%');
                        })
                        ->when($request->status, function($q)use($request){
                            $q->where('users.blocked',$request->status);
                        })
                        ->paginate($perpage);
                        
            return view('pages.users.users-list-ajax', compact('usersResult','currentPage'))->render();
        }

        $usersResult = $usersResult->paginate($perpage);
        
        return view('pages.users.users-list', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs,'usersResult'=>$usersResult,'pageTitle'=>$pageTitle,'paginationUrl'=>$paginationUrl,'currentPage'=>$currentPage,'userType'=>$userType]);
    }

    public function usersCreate($id='')
    {
        // print_r(Helper::loginUserCompanyId());
        $user_result=$states=$cities=false;
        $userType = auth()->user()->role()->first();
        
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => __('locale.Company User')], ['name' => (($id!='') ? __('locale.Edit') : __('locale.Create') )]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        $formUrl = 'company-user-create';
        $countries = Country::get(["name", "id"]);
        $companies = Company::get(["company_name", "id","company_code"]);
        $pageTitle = __('locale.Company User    '); 
        if($id!=''){
            $formUrl = 'company-user-update';
            $user_result = User::with('company')->find($id);
            if($user_result){
            $states = State::where('country_id',$user_result->country)->get(["name", "id"]);
            $cities = City::where('state_id',$user_result->state)->get(["name", "id"]);
            }
            
        }
        return view('pages.users.users-create', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs,'countries'=>$countries,'pageTitle'=>$pageTitle,'companies'=>$companies,'user_result'=>$user_result,'states'=>$states,'cities'=>$cities,'formUrl'=>$formUrl,'userType'=>$userType]);
    }

    public function usersUpdate(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|max:250',
            'phone' => 'required|max:20',
            'address' => 'max:250',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        if(User::where('email',$request->email)->where('id','!=',$id)->count()>0){
            return redirect()->back()
            ->with('email','The Email Has Already Been Taken.')
            ->withInput();
        }
        
        unset($request['_method']);
        unset($request['_token']);
        unset($request['action']);
        unset($request['company']);
        unset($request['importcompany']);


        $user = User::where('id',$id)->update($request->all());

        return redirect()->route('company-user-list')->with('success',__('locale.company_admin_create_success'));
    }

    public function userStore(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|unique:users|max:250',
            'phone' => 'required|max:20',
            'address' => 'max:250',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        $role = Role::where('name', 'company-user')->first();
        $random_password = Str::random(6);
        $request['password'] = Hash::make($random_password);
        $user = User::create($request->all());
        $user->company()->attach($request->company);
        $user->role()->attach( $role->id);
        return redirect()->route('companyadmin.company-user-list')->with('success',__('locale.company_admin_create_success'));
    }

}

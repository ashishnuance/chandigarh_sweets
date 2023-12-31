<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Helper;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getUserPermissionsModule($company_user='company_user',$guard_name='index'){
        // echo $company_user;
        if(auth()->user()->role()->first()->name==config('custom.superadminrole')){
            // exit('saddd');
            return true;
        }
        return (in_array($guard_name,Helper::getUserPermissionsModule($company_user))) ?: false;
    }
}

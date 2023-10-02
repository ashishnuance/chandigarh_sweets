<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\{User,ApiSettingsModel};
use Illuminate\Support\Facades\Hash;

class CompanyController extends BaseController
{
    

    function settings(){
        $company_id = 2;
        
        $settings = ApiSettingsModel::where('company_id',$company_id);
        // if($settings->count()>0){
        $settingData = [];
        if($settings->count()>0){
            $setting_data = $settings->first();
            $settingData['home_banner'] = (isset($setting_data->home_banner) && $setting_data->home_banner!='') ? json_decode($setting_data->home_banner) : [];
            if(!empty($settingData['home_banner'])){
                foreach($settingData['home_banner'] as $k => $val){
                    $settingData['home_banner'][$k]->image = route('image.displayImage',$val->image);
                }
            }

            $settingData['logos'] = (isset($setting_data->logos) && $setting_data->logos!='') ? json_decode($setting_data->logos) : [];
            if(!empty($settingData['logos'])){
                
                $settingData['logos']->header_logo = route('image.displayImage',$settingData['logos']->header_logo);
                $settingData['logos']->footer_logo = route('image.displayImage',$settingData['logos']->footer_logo);
            }
            $settingData['footer_content'] = (isset($setting_data->footer_content) && $setting_data->footer_content!='') ? json_decode($setting_data->footer_content) : [];
            return $this->sendResponse($settingData, __('locale.api_settings_success'));
        }else{
            return $this->sendError('Failed.', ['error'=>__('locale.no_record_found')],400);
        }
    }
}

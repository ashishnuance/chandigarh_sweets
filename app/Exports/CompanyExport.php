<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompanyExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return 
        return Company::with(['countryname','statename','cityname'])->get()->map(function($company){
            return [
                'id'=>$company->id,
                'company_code'=>$company->company_code,
                'company_name'=>$company->company_name,
                'address1'=>$company->address1,
                'address2'=>$company->address2,
                'city'=>(isset($company->cityname->name) && $company->cityname->name!='') ? $company->cityname->name : '',
                'state'=>(isset($company->statename->name) && $company->statename->name!='') ? $company->statename->name : '',
                'country'=>(isset($company->countryname->name) && $company->countryname->name!='') ? $company->countryname->name : '',
                'pincode'=>$company->pincode,
                'contact_person'=>$company->contact_person,
                'contact_mobile'=>$company->contact_mobile,
                'phone_no'=>$company->phone_no,
                'licence_valid_till'=>$company->licence_valid_till,
                'blocked'=>($company->blocked==1) ? 'UnBlocked' : 'Blocked',
                'created_at'=>$company->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['id','company code','company name','address1','address2','city','state','country','zipcode','contact person name','mobile','phone','licence till date','blocked','created date'];
    }
}

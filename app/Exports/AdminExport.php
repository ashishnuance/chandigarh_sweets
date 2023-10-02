<?php

namespace App\Exports;
use App\Models\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $userRecord = User::with(['company','countryname','statename','cityname'])->select("id","users.name","email","phone","address","country","state","city","website_url","image","blocked","created_at")->whereHas(
            'role', function($q){
                $q->where('name', 'company-admin');
            }
        )->get()->map(function($userVal){
            return [
                'id'=>$userVal->id,
                'company_name'=>(isset($userVal->company[0]->company_name) && $userVal->company[0]->company_name!='') ? $userVal->company[0]->company_name : '',
                'name'=>$userVal->name,
                'email'=>$userVal->email,
                'phone'=>$userVal->phone,
                'address'=>$userVal->address,
                'country'=>(isset($userVal->countryname->name) && $userVal->countryname->name!='') ? $userVal->countryname->name : '',
                'state'=>(isset($userVal->statename->name) && $userVal->statename->name!='') ? $userVal->statename->name : '',
                'city'=>(isset($userVal->cityname->name) && $userVal->cityname->name!='') ? $userVal->cityname->name : '',
                'website_url'=>$userVal->website_url,
                'image'=>($userVal->image!=null) ? route('image.displayImage',[$userVal->image]): '',
                'blocked'=>($userVal->blocked==1) ? 'UnBlocked' : 'Blocked',
                'created_at'=>$userVal->created_at,
            ];
        });
        
        return $userRecord;
    }

    public function headings(): array
    {
        return ["id","company name","name","email","phone","address","country","state","city","website_url","image","blocked","created"
        ];
    }
}

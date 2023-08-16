<table class="responsive-table">
<thead>
    <tr>
    <th data-field="company_code">{{__('locale.company_code')}}</th>
    <th data-field="company_name">{{__('locale.company_name')}}</th>
    <th data-field="address1">{{__('locale.address1')}}</th>
    <th data-field="city">{{__('locale.city')}}</th>
    <th data-field="state">{{__('locale.state')}}</th>
    <th data-field="country">{{__('locale.country')}}</th>
    <th data-field="contact_person">{{__('locale.contact_person')}}</th>
    <th data-field="licence_valid_till">{{__('locale.licence_valid_till')}}</th>
    <th data-field="blocked">{{__('locale.blocked')}} Status</th>
    <th data-field="action">{{__('locale.action')}}</th>
    </tr>
</thead>
<tbody>
    @if(isset($companyResult) && !empty($companyResult))
    @foreach($companyResult as $company_value)
    <tr>
    <td>{{$company_value->company_code}}</td>
    <td>{{$company_value->company_name}}</td>
    <td>{{$company_value->address1}}</td>
    <td>{{$company_value->city}}</td>
    <td>{{$company_value->state}}</td>
    <td>{{$company_value->country}}</td>
    <td>{{$company_value->contact_person}}</td>
    <td>{{$company_value->licence_valid_till}}</td>
    <td>{{($company_value->blocked==1) ? 'Blocked' : 'Un-blocked'}}</td>
    <td>
        <a href="{{route('company.destroy',$company_value->id)}}" class="btn" onclick="return confirm('Are you sure you want to delete this item')"><i class="material-icons">delete</i></a>
        <a href="{{asset('company/'.$company_value->id.'/edit')}}" class="btn"><i class="material-icons">edit</i></a></td>
    </tr>
    @endforeach
    @else
    <tr>
    <td colspan="10"><p class="center">{{__('locale.no_record_found')}}</p></td>
    </tr>
    @endif
</tbody>
</table>
@if(isset($companyResult) && !empty($companyResult))
{!! $companyResult->links('panels.paginationCustom') !!}
@endif
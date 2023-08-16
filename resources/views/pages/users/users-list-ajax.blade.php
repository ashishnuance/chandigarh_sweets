<table class="table">
  <thead>
    <tr>
      <th></th>
      <th>{{__('locale.name')}}</th>
      <th>{{__('locale.email')}}</th>
      <th>{{__('locale.phone')}}</th>
      <th>{{__('locale.address')}}</th>
      <th>{{__('locale.website_url')}}</th>
      <th>{{__('locale.status')}}</th>
      <th>{{__('locale.action')}}</th>
    </tr>
  </thead>
  <tbody>
    @if(isset($usersResult) && !empty($usersResult->items()))
    @foreach($usersResult as $user_key => $user_value)
    <tr>
    <td>{{$user_key+1}}</td>
    <td>{{$user_value->name}}</td>
    <td>{{$user_value->email}}</td>
    <td>{{$user_value->phone}}</td>
    <td>{{$user_value->address}}</td>
    <td>{{$user_value->website_url}}</td>
    <td>{{($user_value->blocked==1) ? 'Blocked' : 'Un-blocked'}}</td>
    <td>
    <td>
      <a href="{{route('company-admin-edit',$user_value->id)}}"><i class="material-icons">edit</i></a>
    </td>      
    </tr>
    @endforeach
    @else
    
    <tr>
    <td colspan="10"><p class="center">{{__('locale.no_record_found')}}</p></td>
    </tr>
    @endif
  </tbody>
</table>
@if(isset($usersResult) && !empty($usersResult))
{!! $usersResult->links('panels.paginationCustom') !!}
@endif
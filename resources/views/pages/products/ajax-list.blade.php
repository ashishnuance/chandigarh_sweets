<table class="table">
  <thead>
    <tr>
      <th></th>
      <th>{{__('locale.name')}}</th>
      <th>{{__('locale.product code')}}</th>
      <th>{{__('locale.slug')}}</th>
      <th>{{__('locale.category')}}</th>
      <th>{{__('locale.sub category')}}</th>
      <th>{{__('locale.food type')}}</th>
      <th>{{__('locale.status')}}</th>
      <th>{{__('locale.action')}}</th>
    </tr>
  </thead>
  <tbody>
    @if(isset($productResult) && !empty($productResult->items()))
    @foreach($productResult as $product_key => $product_value)
    <tr>
    <td>{{$product_key+1}}</td>
    <td>{{$product_value->product_name}}</td>
    <td>{{$product_value->product_code}}</td>
    <td>{{$product_value->product_slug}}</td>
    <td>{{$product_value->product_catid}}</td>
    <td>{{$product_value->product_subcatid}}</td>
    <td>{{$product_value->food_type}}</td>
    <td>{{($product_value->blocked==1) ? 'Blocked' : 'Un-blocked'}}</td>
    <td>
    <td>
      <a href="{{route('company-admin-edit',$product_value->id)}}"><i class="material-icons">edit</i></a>
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
@if(isset($productResult) && !empty($productResult))
{!! $productResult->links('panels.paginationCustom') !!}
@endif
<table class="responsive-table">
<thead>
    <tr>
        <th></th>
        <th data-field="group_code">{{__('locale.company_code')}}</th>
        <th data-field="group_name">{{__('locale.buyer_type')}}</th>
        <th data-field="type">{{__('locale.start_date')}}</th>
        <th data-field="type">{{__('locale.end_date')}}</th>
        <th data-field="type">{{__('locale.item_price')}}</th>
        <th data-field="type">{{__('locale.discount')}}</th>
        <th data-field="type">{{__('locale.action')}}</th>
    </tr>
</thead>
<tbody>
    @if(isset($productMappingResult) && !empty($productMappingResult))
    @foreach($productMappingResult as $key => $buyer_value)
    @if(isset($buyer_value))
    <tr>
    <td>{{$key+1}}</td>
    <td>{{$buyer_value->company_code}}</td>
    <td>{{$buyer_value->buyer_type}}</td>
    <td>{{$buyer_value->start_date}}</td>
    <td>{{$buyer_value->end_date}}</td>
    <td>{{$buyer_value->item_price}}</td>
    <td>{{$buyer_value->discount}}</td>
    
    <td>
    <a href="{{asset('price-mapping/'.$buyer_value->id.'/edit')}}" class="btn"><i class="material-icons">edit</i></a>
    <a href="{{route('price-mapping.destroy',$buyer_value->id)}}" class="btn" onclick="return confirm('Are you sure you want to delete this item')"><i class="material-icons">delete</i></a>
    </td>
    </td>
    </tr>
    @endif
    @endforeach
    @else
    <tr>
    <td colspan="10">{{__('locale.no_record_found')}}</td>
    </tr>
    @endif
</tbody>
</table>
@if(isset($productMappingResult) && !empty($productMappingResult))
{!! $productMappingResult->links('panels.paginationCustom') !!}
@endif
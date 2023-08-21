{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@include('panels.page-title')

{{-- page style --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="section">
  <div class="card">
    
  </div>

  <!-- Responsive Table -->
  <div class="row">
    <div class="col s12 m12 l12">
      <div id="responsive-table" class="card card card-default scrollspy">
        <div class="card-content">
           
          <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 table-result">
                
            <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="s.no">{{__('locale.S.no')}}</th>
                <th data-field="category_name">{{__('locale.category_name')}}</th>
                <th data-field="action">{{__('locale.action')}}</th>
            </tr>
            </thead>
            <tbody>
                @if(isset($product_category_list) && !empty($product_category_list))
                @foreach($product_category_list as $key => $product_category_data)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$product_category_data->category_name}}</td>
                </tr>
                @endforeach
                @else
                <tr>
                <td colspan="10"><p class="center">{{__('locale.no_record_found')}}</p></td>
                </tr>
                @endif
            </tbody>
            </table>
            @if(isset($product_category_list) && !empty($product_category_list))
            {!! $product_category_list->links('panels.paginationCustom') !!}
            @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

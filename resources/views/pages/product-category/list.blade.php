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


  <!-- Responsive Table -->
  <div class="row">
    <div class="col s12 m12 l12">
    @include('panels.flashMessages')

      <div id="responsive-table" class="card card card-default scrollspy">
        <div class="card-content">
           
          <div class="row">
            <div class="col s12">
                <div class="col s4">
                    <select name="company_id" id="company_id" onchange="filtercompany(this)" data-url='{{route("product-category.store")}}'>
                      <option value="Select" disabled selected>Select Company</option>
                      @foreach($company_list as $company)
                      <option value="{{$company->id}}" {{ (request()->route()->id == $company->id) ? 'selected' : '' }}>{{$company->company_name}}</option>
                      @endforeach
                    </select>
                 </div>
            </div>
            <div class="col s12 table-result">
                
            <table class="responsive-table" id="category_table">
            <thead>
            <tr>
                <th data-field="s.no">{{__('locale.S.no')}}</th>
                <th data-field="category_name">{{__('locale.category_name')}}</th>
                <th data-field="company_name">{{__('locale.company_name')}}</th>

                <th data-field="action">{{__('locale.action')}}</th>
            </tr>
            </thead>
            <tbody id="category_table_body">
                @if(isset($product_category_list) && !empty($product_category_list))
                @foreach($product_category_list as $key => $product_category_data)

                
                <tr>
                <td>{{$key+1}}</td>
                <td>{{$product_category_data->category_name}}</td> <!-- Handle empty data -->
               
                <td>
                    <!-- <a href="mailto:{{ $product_category_data->company_id }}">{{ $product_category_data->company_name }}</a> -->


                    {{$product_category_data->companyname->company_name}}

                </td>

                <td> <a href="{{route('product-category.edit',$product_category_data->id)}}"><i class="material-icons">edit</i></a>

                <a href="{{route('product-category.delete',$product_category_data->id)}}" onclick="return confirm('Are you sure?')"><i class="material-icons">delete</i></a>

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

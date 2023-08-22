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
                    <select name="category_id" id="category_id" onchange="filtercompany(this)" data-url='{{route("sub-category.store")}}'>
                      <option value="Select" disabled selected>Select Category</option>
                      @foreach($category_id as $categoryname)
                      <option value="{{$categoryname->id}}" {{ (request()->route()->id == $categoryname->id) ? 'selected' : '' }}>{{$categoryname->category_name}}</option>
                      @endforeach
                    </select>
                 </div>
            </div>
            <div class="col s12 table-result">
                
            <table class="responsive-table" id="category_table">
            <thead>
            <tr>
                <th data-field="s.no">{{__('locale.S.no')}}</th>
                <th data-field="category_name">{{__('locale.sub_category_name')}}</th>
                <th data-field="category_name">{{__('locale.category_name')}}</th>
                <th data-field="company_name">{{__('locale.company_name')}}</th>

                <th data-field="action">{{__('locale.action')}}</th>
            </tr>
            </thead>
            <tbody id="category_table_body">
                @if(isset($sub_category_list) && !empty($sub_category_list))
                @foreach($sub_category_list as $key => $sub_category_data)

                <tr>
                <td>{{$key+1}}</td>
                <td>{{$sub_category_data->subcat_name}}</td> <!-- Handle empty data -->
                <td>{{$sub_category_data->categoryname->category_name}}</td>
                <td>{{$sub_category_data->categoryname->companyname->company_name}}</td>
                
                <td>
                    <a href="{{route('sub-category.edit',$sub_category_data->id)}}"><i class="material-icons">edit</i></a>
                    <a href="{{route('sub-category.delete',$sub_category_data->id)}}" onclick="return confirm('Are you sure?')"><i class="material-icons">delete</i></a>
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
            @if(isset($sub_category_list) && !empty($sub_category_list))
            {!! $sub_category_list->links('panels.paginationCustom') !!}
            @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

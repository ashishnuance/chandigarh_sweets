@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@include('panels.page-title')

{{-- vendor style --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="section">
  <div class="card">
    
  </div>

  <!-- HTML VALIDATION  -->


  <div class="row">
    <div class="col s12">
      @include('panels.flashMessages')

      <div id="validations" class="card card-tabs">
        <div class="card-content">
          <div class="card-title">
            <div class="row">
              <div class="col s12 m6 l10">
                
              </div>
            </div>
          </div>
          <div id="view-validations">
         
          <form class="formValidate" method="post" action="{{ isset($result) ? route('product-category.update', $result['id']) : route('product-category.store') }}">

            @csrf

              @if(isset($result))
                  @method('PUT') <!-- Use PUT for updating -->
              @endif

              <div class="input-field col s12">

                 <select name="company_id" id="company" required>
                  <option value="Select" disabled selected>Select Company</option>
                  @if(isset($company) && !empty($company))
                  @foreach($company as $company_val)
                  {{$company_val->id}}
                  <option value="{{ $company_val->id }}" {{ (isset($result->company_id) &&$result->company_id == $company_val->id ) ? 'selected' : '' }}>{{ $company_val->company_name }}</option>
                    @endforeach
                  @endif
                </select>
                @error('company_id')
                <div style="color:red">{{$message}}</div>
                @enderror
             </div>             
              
           <div class="row">
              <div class="input-field col m6 s12">
                <label for="category_name">Category Name</label>
                <input id="category_name" class="validate" name="category_name" type="text" data-error=".errorTxt1" value="{{ (isset($result['category_name']) && $result['category_name'] !='' ) ? $result['category_name'] :  old('category_name') }}">
                @error('category_name')
                    <div style="color:red">{{ $message }}</div>
                @enderror
                <small class="errorTxt1"></small>
              </div>
                          
                <div class="input-field col s12">
                  <button class="btn waves-effect waves-light right submit" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                  </button>
                </div>
              </div>

             


            </form>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection




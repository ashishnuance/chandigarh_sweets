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
      <div id="validations" class="card card-tabs">
        <div class="card-content">
          <div class="card-title">
            <div class="row">
              <div class="col s12 m6 l10">
                
              </div>
            </div>
          </div>
          <div id="view-validations">
            @include('panels.flashMessages')
            <form class="formValidate" action="{{route('product-category.store')}}" id="formValidateCompany" method="post">

            @csrf()

              <div class="row">
                <div class="input-field col m6 s12">
                  <label for="category_name">Category Name</label>
                  <input id="category_name" class="validate" name="category_name" type="text" data-error=".errorTxt1" value="">
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




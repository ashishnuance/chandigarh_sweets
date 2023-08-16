@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Form Wizard')

{{-- vendor style --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/materialize-stepper/materialize-stepper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/dropify/css/dropify.min.css')}}">
@endsection
{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/form-wizard.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="section section-form-wizard">
  <div class="card">
    
  </div>

  <!-- Horizontal Stepper -->

  <div class="row">
    <div class="col s12">
      <div class="card">
        @include('panels.flashMessages')
        <div class="card-content pb-0">
          <div class="card-header mb-2">
            <h4 class="card-title">Horizontal Stepper</h4>
          </div>
          @if(isset($product_result->id))
          <?php $formUrl = (isset($formUrl) && $formUrl!='') ? $formUrl : 'product.update'; ?>
            <form class="formValidate" action="{{route($formUrl,$product_result->id)}}" id="formValidateCompany" method="post">
            {!! method_field('post') !!}
            @else
            <?php $formUrl = (isset($formUrl) && $formUrl!='') ? $formUrl : 'company-admin-create'; ?>
            <form id="accountForm" action="{{route($formUrl)}}" method="post">
            @endif
            @csrf()
          <ul class="stepper horizontal" id="horizStepper">
            <li class="step active">
              <div class="step-title waves-effect">{{__('locale.product info')}}</div>
              <div class="step-content">
                <div class="row">
                @if(isset($userType) && $userType!='admin')
                  <input type="hidden" name="company" value="{{Helper::loginUserCompanyId()}}"/>
                  @endif
                  <div class="input-field col m6 s12">
                    <label for="product_name">{{__('locale.product name')}}: <span class="red-text">*</span></label>
                    <input type="text" id="product_name" name="product_name" value="{{(isset($product_result->product_name)) ? $product_result->product_name : old('product_name')}}" class="validate" required>
                  </div>
                  <div class="input-field col m6 s12">
                    <label for="product_code">{{__('locale.product code')}}: <span class="red-text">*</span></label>
                    <input type="text" id="product_code" class="validate" name="product_code" required value="{{(isset($product_result->product_code)) ? $product_result->product_code : $productCode }}">
                  </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 input-field">
                        <select name="product_catid" id="product_catid">
                        <option value="">Choose {{__('locale.category')}}</option>
                        
                            @foreach ($productCategoryResult as $category_value)
                            <option value="{{$category_value->id}}">{{$category_value->category_name}}</option>
                            @endforeach
                        
                        </select>
                        <label for="country">{{__('locale.category')}}</label>
                    </div>
                    <div class="col s12 m6 input-field">
                        <select name="state" id="state">
                        <option value="">Choose {{__('locale.sub category')}}</option>
                        @if(isset($product_result->state) && isset($states) && !empty($states))
                            @foreach ($states as $state_value)
                            <option value="{{$state_value->id}}">{{$state_value->name}}</option>
                            @endforeach
                        @endif
                        </select>
                        <label for="state">{{__('locale.sub category')}}</label>
                    </div>
                    
                    <div class="col s12 m6 input-field">
                        <select name="food_type" id="food_type">
                        <option value="">Choose {{__('locale.food type')}}</option>
                        
                            @foreach ($foodTypeResult as $type_value)
                            <option value="{{$type_value}}">{{ucwords($type_value)}}</option>
                            @endforeach
                        
                        </select>
                        <label for="food_type">{{__('locale.food type')}}</label>
                    </div>
                    <div class="col s12 m6 input-field">
                        <select name="blocked">
                        <option value="1">Blocked</option>
                        <option value="0">Un-Blocked</option>
                        </select>
                        <label>{{__('locale.status')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="textarea2" name="description" class="materialize-textarea">{{(isset($product_result->description)) ? $product_result->description : old('description')}}</textarea>
                        <label for="textarea2">{{__('locale.description')}}</label>
                    </div>
                </div>
                <div class="step-actions">
                  <div class="row">
                    <div class="col m4 s12 mb-3">
                      <button class="red btn btn-reset" type="reset">
                        <i class="material-icons left">clear</i>Reset
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="btn btn-light previous-step" disabled>
                        <i class="material-icons left">arrow_back</i>
                        Prev
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="waves-effect waves dark btn btn-primary next-step" type="submit">
                        Next
                        <i class="material-icons right">arrow_forward</i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="step">
              <div class="step-title waves-effect">{{__('locale.product images')}}</div>
              <div class="step-content">
                
                <div class="row section">
                    <div class="col s12 m12 l3">
                        <p>{{__('locale.product images')}}</p>
                    </div>
                    <div class="col s12">
                    <div class="col s12 m4">
                        <input type="file" id="input-file-now" class="dropify" data-default-file="" />
                    </div>
                    <div class="col s12 m4">
                        <input type="file" id="input-file-now" class="dropify" data-default-file="" />
                    </div>
                    <div class="col s12 m4">
                        <input type="file" id="input-file-now" class="dropify" data-default-file="" />
                    </div>
                    </div>
                </div>
                    
                
                
                
                <div class="step-actions">
                  <div class="row">
                    <div class="col m4 s12 mb-3">
                      <button class="red btn btn-reset" type="reset">
                        <i class="material-icons left">clear</i>Reset
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="btn btn-light previous-step">
                        <i class="material-icons left">arrow_back</i>
                        Prev
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="waves-effect waves dark btn btn-primary next-step" type="submit">
                        Next
                        <i class="material-icons right">arrow_forward</i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="step">
              <div class="step-title waves-effect">{{__('locale.product variation')}}</div>
              <div class="step-content">
                <div class="row">
                  <div class="input-field col m6 s12">
                    <label for="eventName">Event Name: <span class="red-text">*</span></label>
                    <input type="text" class="validate" id="eventName" name="eventName" required>
                  </div>
                  <div class="input-field col m6 s12">
                    <select>
                      <option value="Select" disabled selected>Select Event Type</option>
                      <option value="Wedding">Wedding</option>
                      <option value="Party">Party</option>
                      <option value="FundRaiser">Fund Raiser</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col m6 s12">
                    <select>
                      <option value="Select" disabled selected>Select Event Status</option>
                      <option value="Planning">Planning</option>
                      <option value="In Progress">In Progress</option>
                      <option value="Completed">Completed</option>
                    </select>
                  </div>
                  <div class="input-field col m6 s12">
                    <select>
                      <option value="Select" disabled selected>Event Location</option>
                      <option value="New York">New York</option>
                      <option value="Queens">Queens</option>
                      <option value="Washington">Washington</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col m6 s12">
                    <label for="Budget">Event Budget: <span class="red-text">*</span></label>
                    <input type="Number" class="validate" id="Budget" name="Budget">
                  </div>
                  <div class="input-field col m6 s12">
                    <p> <label>Requirments</label></p>
                    <p> <label>
                        <input type="checkbox">
                        <span>Staffing</span>
                      </label></p>
                    <p><label>
                        <input type="checkbox">
                        <span>Catering</span>
                      </label></p>
                  </div>
                </div>
                <div class="step-actions">
                  <div class="row">
                    <div class="col m6 s12 mb-1">
                      <button class="red btn mr-1 btn-reset" type="reset">
                        <i class="material-icons">clear</i>
                        Reset
                      </button>
                    </div>
                    <div class="col m6 s12 mb-1">
                      <button class="waves-effect waves-dark btn btn-primary" type="submit">Submit</button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
            </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Linear Stepper -->

  <div class="row" style="display:none">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          

          <ul class="stepper linear" id="linearStepper">
            <li class="step">
              <div class="step-title waves-effect">Step 1</div>
              <div class="step-content">
                
                
                
              </div>
            </li>
            <>
            <>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Non Linear Stepper -->

  
</div>
@endsection

{{-- vendor script --}}
@section('vendor-script')
<script src="{{asset('vendors/materialize-stepper/materialize-stepper.min.js')}}"></script>
<script src="{{asset('vendors/dropify/js/dropify.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/form-wizard.js')}}"></script>
<script src="{{asset('js/scripts/form-file-uploads.js')}}"></script>

@endsection
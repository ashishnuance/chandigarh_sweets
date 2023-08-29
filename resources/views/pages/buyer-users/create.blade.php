{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@include('panels.page-title')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <!-- <div class="card-body"> -->
      
      <div class="row">
        <div class="col s12" id="account">
          
          <!-- users edit media object ends -->
          <!-- users edit account form start -->
          @include('panels.flashMessages')
          @if(isset($user_result->id))
          <?php //$formUrl = (isset($formUrl) && $formUrl!='') ? $formUrl : 'company-admin-update'; ?>
            <form class="formValidate" action="{{route($formUrl,$user_result->id)}}" id="formValidateCompany" method="post">
            {!! method_field('post') !!}
            @else
            <?php //$formUrl = (isset($formUrl) && $formUrl!='') ? $formUrl : 'company-admin-create'; ?>
          <form id="accountForm" action="{{route($formUrl)}}" method="post">
            @endif
            @csrf()
            <div class="row">
              <div class="col s12 m12">
                <div class="row">
                  @if(isset($userType) && $userType==config('custom.superadminrole'))
                  <div class="col s12 input-field">
                    <select class="error" id="company" name="company" data-error=".errorTxt7" required>
                      <option value="">Choose {{__('locale.Company')}}</option>
                      @if(isset($companies) && !empty($companies))
                        @foreach ($companies as $company_value)
                          <option value="{{$company_value->id}}">{{$company_value->company_name}} ({{$company_value->company_code}})</option>
                        @endforeach
                      @endif
                    </select>
                    <label for="company">{{__('locale.Companies')}}</label>
                    <small class="errorTxt7"></small>
                  </div>
                  @else
                  <input type="hidden" name="company" value="{{Helper::loginUserCompanyId()}}"/>
                  @endif
                  <div class="col s12 m6 input-field">
                    <input id="name" name="name" type="text" class="validate" value="{{(isset($user_result->name)) ? $user_result->name : old('name')}}"
                      data-error=".errorTxt2">
                    <label for="name">{{__('locale.name')}}</label>
                    <small class="errorTxt2"></small>
                  </div>
                  <div class="col s12 m6 input-field">
                    <input id="email" name="email" type="email" class="validate" value="{{(isset($user_result->email)) ? $user_result->email : old('email')}}"
                      data-error=".errorTxt3">
                    <label for="email">{{__('locale.email')}}</label>
                    <small class="errorTxt3"></small>
                  </div>
                  
                  <div class="col s12 m6 input-field">
                    <select name="user_type" id="country" require>
                        <option value="">Choose {{__('locale.type')}}</option>
                        <option value="domestic">Domestic</option>
                        <option value="foreign">Foreign</option>
                      
                    </select>
                    <label for="country">{{__('locale.type')}}</label>
                  </div>
                  
                  <div class="col s12 m6 input-field">
                    <select name="blocked">
                      <option value="1">Blocked</option>
                      <option value="0">Un-Blocked</option>
                    </select>
                    <label>{{__('locale.status')}}</label>
                  </div>
                </div>
              </div>
              
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo">
                  Save changes</button>
                <button type="reset" class="btn btn-light">Cancel</button>
              </div>
            </div>
          </form>
          <!-- users edit account form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/form-validation.js')}}"></script>
<script>
  window.onload=function(){
    var country_value = "{{(isset($user_result->country) && $user_result->country!='NULL') ? $user_result->country : old('country')}}";
    var country_value_edit = "{{(isset($user_result->country) && $user_result->country!='NULL') ? $user_result->country : ''}}";
    var state_value = "{{(isset($user_result->state) && $user_result->state!='NULL') ? $user_result->state : old('state')}}";
    var city_value = "{{(isset($user_result->city) && $user_result->city!='NULL') ? $user_result->city : old('state')}}";
    var company_value = "{{(isset($user_result->company[0]->id) && $user_result->company[0]->id!='NULL') ? $user_result->company[0]->id : old('company')}}";
    console.log(state_value);
    $('#country').val(country_value);
    $('#country').formSelect();
    $('#state').val(state_value);
    $('#state').formSelect();
    $('#city').val(city_value);
    $('#city').formSelect();
    $('#company').val(company_value);
    if(country_value_edit && country_value_edit!=''){
      $('#company').attr('disabled',true);
    }
    $('#company').formSelect();
  }
    $(document).ready(function () {
      

        $('#country').on('change', function () {
            var idCountry = this.value;
            console.log(idCountry);
            $("#state").html('');
            $.ajax({
                url: "{{url('api/fetch-states')}}",
                type: "POST",
                data: {
                    country_id: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#state').html('<option value="">Select State</option>');
                    $.each(result.states, function (key, value) {
                        $("#state").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    $('#state').formSelect();
                    $('#city').html('<option value="">Select City</option>');
                }
            });
        });
        $('#state').on('change', function () {
            var idState = this.value;
            $("#city").html('');
            $.ajax({
                url: "{{url('api/fetch-cities')}}",
                type: "POST",
                data: {
                    state_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#city').html('<option value="">Select City</option>');
                    $.each(res.cities, function (key, value) {
                        $("#city").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    $('#city').formSelect();
                }
            });
        });
    });
</script>
@endsection
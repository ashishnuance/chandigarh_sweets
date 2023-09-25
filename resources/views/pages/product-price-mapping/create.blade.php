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
            @if(isset($buyer_result))
            <form class="formValidate" action="{{route('buyer-type.update',$buyer_result->id)}}" id="formValidateCompany" method="POST">
            {!! method_field('patch') !!}
            @else
            <form class="formValidate" action="{{route('buyer-type.store')}}" id="formValidateCompany" method="POST">
            {!! method_field('post') !!}
            @endif
            @csrf()
              <div class="row">
                @if(isset($userType) && $userType==config('custom.superadminrole'))
                <div class="col s12 input-field">
                  <select class="error" id="company" name="company" data-error=".errorTxt7" required>
                    <option value="">Choose {{__('locale.Company')}}</option>
                    @if(isset($companyResult) && !empty($companyResult))
                      @foreach ($companyResult as $company_value)
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
                  <select class="error" id="buyer_type" name="buyer_type" data-error=".errorTxt7" required>
                    <option value="">Choose {{__('locale.buyer_type')}}</option>
                    @if(isset($buyerTypeResult) && !empty($buyerTypeResult))
                      @foreach ($buyerTypeResult as $type_value)
                        <option value="{{$type_value->id}}">{{$type_value->name}}</option>
                      @endforeach
                    @endif
                  </select>
                  <label for="buyer_type">{{__('locale.buyer_type')}}</label>
                  <small class="errorTxt7"></small>
                </div>
                
                <div class="col s12 m6 input-field">
                  <select class="error" id="product" name="product" data-error=".errorTxt7" required>
                    <option value="">Choose {{__('locale.Items')}}</option>
                    @if(isset($productResult) && !empty($productResult))
                      @foreach ($productResult as $product_value)
                        <option value="{{$product_value->id}}">{{$product_value->product_name}}({{$product_value->product_code}})</option>
                      @endforeach
                    @endif
                  </select>
                  <label for="product">{{__('locale.Items')}}</label>
                  <small class="errorTxt7"></small>
                </div>

                <div class="col s12 m6 input-field">
                  <input id="start_date" name="start_date" type="text" class="validate"  value="{{(isset($result->start_date)) ? $result->start_date : old('start_date')}}"
                    data-error=".errorTxt1">
                  <label for="name">{{__('locale.start_date')}}</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m6 input-field">
                  <input id="end_date" name="end_date" type="text" class="validate"  value="{{(isset($result->end_date)) ? $result->end_date : old('end_date')}}"
                    data-error=".errorTxt1">
                  <label for="name">{{__('locale.end_date')}}</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m6 input-field">
                  <input id="price" name="price" type="text" class="validate"  value="{{(isset($result->price)) ? $result->price : old('price')}}"
                    data-error=".errorTxt1">
                  <label for="name">{{__('locale.item_price')}}</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m6 input-field">
                  <input id="discount" name="discount" type="text" class="validate"  value="{{(isset($result->discount)) ? $result->discount : old('discount')}}"
                    data-error=".errorTxt1">
                  <label for="name">{{__('locale.discount')}}</label>
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

{{-- vendor script --}}
@section('vendor-script')
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/form-validation.js')}}"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>
  window.onload=function(){
    var type_value = "{{(isset($buyer_result->type) && $buyer_result->type!='NULL') ? $buyer_result->type : old('type')}}";
    var currency_value = "{{(isset($buyer_result->currency_code) && $buyer_result->currency_code!='NULL') ? $buyer_result->currency_code : old('currency_code')}}";
    // var city_value = "{{(isset($company_result->city) && $company_result->city!='NULL') ? $company_result->city : old('state')}}";
    // console.log(state_value);

    $('#type').val(type_value);
    $('#type').formSelect();

    $('#currency_code').val(currency_value);
    $('#currency_code').formSelect();

    $('#state').val(state_value);
    $('#state').formSelect();
    $('#city').val(city_value);
    $('#city').formSelect();
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
                            .name + '">' + value.name + '</option>');
                    });
                    $('#city').formSelect();
                }
            });
        });
    });
</script>
@endsection


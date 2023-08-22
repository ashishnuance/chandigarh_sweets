@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@include('panels.page-title')

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
            
          </div>
          
          @if(isset($product_result->id))
          <?php //$formUrl = (isset($formUrl) && $formUrl!='') ? $formUrl : 'product.update'; ?>
            <form class="formValidate" action="{{route($formUrl,$product_result->id)}}" id="formValidateCompany" method="post" enctype="multipart/form-data">
            {!! method_field('patch') !!}
          @else
            <?php 
            
            //$formUrl = (isset($formUrl) && $formUrl!='') ? $formUrl : 'superadmin/product/update'; ?>
            <form id="accountForm" action="{{route($formUrl)}}" method="post" enctype="multipart/form-data">
            {!! method_field('post') !!}
          @endif
            @csrf()
          <ul class="stepper horizontal product-create-section" id="horizStepper">
            <li class="step active">
              <div class="step-title waves-effect">{{__('locale.product info')}}</div>
              <div class="step-content">
                <div class="row">
                  
                  @if(isset($userType) && $userType!=config('custom.superadminrole'))
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
                            <option value="{{$category_value->id}}">{{ucwords($category_value->category_name)}}</option>
                            @endforeach
                        
                        </select>
                        <label for="country">{{__('locale.category')}}</label>
                    </div>
                    <div class="col s12 m6 input-field">
                        <select name="product_subcatid" id="product_subcatid">
                        <option value="">Choose {{__('locale.sub category')}}</option>
                        @if(isset($product_result->product_subcatid) && isset($productSubCategoryResult) && !empty($productSubCategoryResult))
                            @foreach ($productSubCategoryResult as $subcat_value)
                            <option value="{{$subcat_value->id}}">{{ucwords($subcat_value->subcat_name)}}</option>
                            @endforeach
                        @endif
                        </select>
                        <label for="product_subcatid">{{__('locale.sub category')}}</label>
                    </div>
                    
                    <div class="col s12 m6 input-field">
                        <select name="food_type" class="food_type">
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
                      <div class="col s12">
                          <p>{{__('locale.product images')}}</p>
                      </div>
                      </div>
                      <div class="col s12 clone-product-image-section">
                      
                        
                        @if(isset($product_result->product_images) && !empty($product_result->product_images))
                        @foreach($product_result->product_images as $image_value)
                        <div class="col s12 m4 clone-image mb-2">
                            <input type="file" name="product_image[]" class="dropify" data-default-file="{{route('image.displayImage',$image_value->image)}}" />
                            <button type="button" class="btn btn-primary mt-1 remove-image">Remove</button>
                        </div>
                        @endforeach
                        @endif
                        <div class="col s12 m4 clone-image mb-2">
                            <input type="file" id="input-file-now" name="product_image[]" class="dropify" data-default-file="" />
                            <button type="button" class="btn btn-primary mt-1 remove-image">Remove</button>
                        </div>
                        
                      </div>
                      <div class="col s12 m4">
                        <div class="col s12">
                        <button class="dark btn btn-primary" id="add-product-image" type="button">Add</button>
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
                  <div class="clone-product-variation">
                    
                    @if(isset($product_result->product_variation) && !empty($product_result->product_variation) && count($product_result->product_variation)>0)
                    
                    @foreach($product_result->product_variation as $provar_value)
                    
                    <div class="product-variation">
                      <div class="input-field col m6 s12">
                        <label for="name">{{__('locale.name')}}: <span class="red-text">*</span></label>
                        <input type="text" class="validate name" name="variation[name][]" value="{{(isset($provar_value->name)) ? $provar_value->name : '' }}" required>
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="sku">{{__('locale.SKU')}}: <span class="red-text">*</span></label>
                        <input type="text" class="validate sku" name="variation[sku][]" value="{{(isset($provar_value->sku)) ? $provar_value->sku : '' }}" required>
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="main_price">{{__('locale.Main Price')}}: <span class="red-text">*</span></label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" class="validate main_price" name="variation[main_price][]" value="{{(isset($provar_value->main_price)) ? $provar_value->main_price : '' }}" required>
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="offer_price">{{__('locale.Offer Price')}}: <span class="red-text">*</span></label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" class="validate offer_price" name="variation[offer_price][]" value="{{(isset($provar_value->offer_price)) ? $provar_value->offer_price : '' }}">
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="quantity">{{__('locale.Quantity')}}: <span class="red-text">*</span></label>
                        <input type="text" class="validate quantity" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" name="variation[quantity][]" value="{{(isset($provar_value->quantity)) ? $provar_value->quantity : '' }}">
                      </div>
                      <?php 
                     // echo"<pre>"; print_r($productsVariationsOptions); die; ?>
                      <div class="input-field col m6 s12">
                        <select name="variation[variation_type][]" class="variation_type" id="productsVariations">
                          <option value="" disabled selected>{{__('locale.select type')}}</option>
                          @if(isset($productsVariationsOptions) && !empty($productsVariationsOptions))
                          @foreach($productsVariationsOptions as $option_value)
                          <option value="{{$option_value->name}}">{{$option_value->name}}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      <button type="button" class="btn btn-primary mt-1 remove-variation">Remove</button>
                    </div>
                    @endforeach
                    @else
                    
                    <div class="product-variation">
                      <div class="input-field col m6 s12">
                        <label for="name">{{__('locale.name')}}: <span class="red-text">*</span></label>
                        <input type="text" class="validate name" name="variation[name][]" required>
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="sku">{{__('locale.SKU')}}: <span class="red-text">*</span></label>
                        <input type="text" class="validate sku" name="variation[sku][]" required>
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="main_price">{{__('locale.Main Price')}}: <span class="red-text">*</span></label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" class="validate main_price" name="variation[main_price][]" required>
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="offer_price">{{__('locale.Offer Price')}}: <span class="red-text">*</span></label>
                        <input type="text" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" class="validate offer_price" name="variation[offer_price][]">
                      </div>
                      <div class="input-field col m6 s12">
                        <label for="quantity">{{__('locale.Quantity')}}: <span class="red-text">*</span></label>
                        <input type="text" class="validate quantity" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" name="variation[quantity][]">
                      </div>
                      <div class="input-field col m6 s12">
                        <select name="variation[variation_type][]" class="variation_type">
                          <option value="" disabled selected>{{__('locale.select type')}}</option>
                          <option value="1">Wedding</option>
                          <option value="2">Party</option>
                          <option value="3">Fund Raiser</option>
                        </select>
                      </div>
                      <button type="button" class="btn btn-primary mt-1 remove-variation">Remove</button>
                    </div>
                    @endif
                  </div>
                  
                  <div class="col s12 m4">
                    <div class="col s12">
                    <button class="dark btn btn-primary" id="add-product-variation" type="button">Add</button>
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
<div class="cloneimagesection hide" style="display:none">
  <div class="col s12 m4 clone-image mb-2">
      <input type="file" name="product_image[]" class="dropify" data-default-file="" />
      <button type="button" class="btn btn-primary mt-1 remove-image">Remove</button>
  </div>

  <div class="product-variation">
    <div class="input-field col m6 s12">
      <label for="name">{{__('locale.name')}}: <span class="red-text">*</span></label>
      <input type="text" class="validate name" name="variation[name][]" required>
    </div>
    <div class="input-field col m6 s12">
      <label for="sku">{{__('locale.SKU')}}: <span class="red-text">*</span></label>
      <input type="text" class="validate sku" name="variation[sku][]" required>
    </div>
    <div class="input-field col m6 s12">
      <label for="main_price">{{__('locale.Main Price')}}: <span class="red-text">*</span></label>
      <input type="text" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" class="validate main_price" name="variation[main_price][]" required>
    </div>
    <div class="input-field col m6 s12">
      <label for="offer_price">{{__('locale.Offer Price')}}: <span class="red-text">*</span></label>
      <input type="text" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" class="validate offer_price" name="variation[offer_price][]">
    </div>
    <div class="input-field col m6 s12">
      <label for="quantity">{{__('locale.Quantity')}}: <span class="red-text">*</span></label>
      <input type="text" class="validate quantity" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" name="variation[quantity][]">
    </div>
    <div class="input-field col m6 s12">
      <select name="variation[variation_type][]" class="variation_type">
        <option value="" disabled selected>{{__('locale.select type')}}</option>
        <option value="1">Wedding</option>
        <option value="2">Party</option>
        <option value="3">Fund Raiser</option>
      </select>
    </div>
    <button type="button" class="btn btn-primary mt-1 remove-variation">Remove</button>
  </div>
</div>
{{-- vendor script --}}
@section('vendor-script')
<script src="{{asset('vendors/materialize-stepper/materialize-stepper.min.js')}}"></script>
<script src="{{asset('vendors/dropify/js/dropify.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/form-wizard.js')}}"></script>
<script src="{{asset('js/scripts/form-file-uploads.js')}}"></script>
<script>

  window.onload=function(){
    var category_value = "{{(isset($product_result->product_catid) && $product_result->product_catid!='NULL') ? $product_result->product_catid : old('product_catid')}}";
    var subcategory_value = "{{(isset($product_result->product_subcatid) && $product_result->product_subcatid!='NULL') ? $product_result->product_subcatid : old('product_subcatid')}}";
    var food_type = "{{(isset($product_result->food_type) && $product_result->food_type!='NULL') ? $product_result->food_type : old('food_type')}}";

    var option_value = "{{(isset($productsVariationsOptions->name) && $productsVariationsOptions->name!='NULL') ? $productsVariationsOptions->name : old('name')}}";
    
    $('#product_catid').val(category_value);
    $('#product_catid').formSelect();
    $('#product_subcatid').val(subcategory_value);
    $('#product_subcatid').formSelect();
    $('.food_type').val(food_type);
    $('.food_type').formSelect();
    $('#productsVariations').val(option_value);

    $('#productsVariations').formSelect();

  }

  $(document).ready(function(){
    $('#add-product-image').click(function(){
      
      let image_clone_html = $('.clone-product-image-section .clone-image:first-child').clone(true);
      
      console.log('image_clone_html',image_clone_html.html());
      image_clone_html.appendTo('.clone-product-image-section');//.find('img').remove();

    })

    $('#add-product-variation').click(function(){
      
      // let variation_clone_html = $('.clone-product-variation').find('.product-variation').eq(0).clone();
      let variation_clone_html = $('.cloneimagesection').find('.product-variation').eq(0).clone();
      console.log(variation_clone_html.html());
      
      variation_clone_html.appendTo('.clone-product-variation');
      $('.variation_type').formSelect();

    })
    
    $('#product_catid').on('change', function () {
      var idCategory = this.value;
      console.log(idCategory);
      $("#product_subcatid").html('');
      $.ajax({
          url: "{{url('api/fetch-subcategory')}}",
          type: "POST",
          data: {
              category_id: idCategory,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
              $('#product_subcatid').html('<option value="">Select Subcategory</option>');
              $.each(result.subcategory, function (key, value) {
                  $("#product_subcatid").append('<option value="' + value
                      .id + '">' + value.subcat_name + '</option>');
              });
              $('#product_subcatid').formSelect();
          }
      });
    });

  })
  $(document).on('click', '.remove-image', function () {
    if($('.clone-product-image-section .clone-image').length>1){
	    $(this).parent().remove();
    }
  });

  $(document).on('click', '.remove-variation', function () {
    if($('.clone-product-variation .product-variation').length>1){
	    $(this).parent().remove();
    }
  });
  
</script>
@endsection
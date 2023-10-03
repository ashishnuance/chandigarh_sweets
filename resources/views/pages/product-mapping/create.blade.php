@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@include('panels.page-title')

{{-- vendor style --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
@endsection

{{-- page content --}}
@section('content')
<style>
  .product-variant-label{
    display:flex;
    gap:20
  }
  .product-variant-label > span{
    width: auto;
    min-width: 180px;
  }
  .product-variant-label input[type="text"]{
    
    margin-left: 5px;
  }
  .product-variant-div{
    margin-left: 30px;
    display:none;
  }
  </style>
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
         
          <form class="formValidate" method="post" action="{{ isset($mappingResult[0]) ? route($formUrl, $mappingResult[0]->company_id) : route($formUrl) }}">

            @csrf

              @if(isset($mappingResult[0]))
                  @method('PUT') <!-- Use PUT for updating -->
              @endif

            

              <div class="input-field col s12">

                 <select name="company_id" id="company" required>
                  <option value="Select" disabled selected>{{__('locale.Select Company')}} *</option>
                  @if(isset($companyResult) && !empty($companyResult))
                  @foreach($companyResult as $company_val)
                  {{$company_val->id}}
                  <option value="{{ $company_val->id }}">{{ strtolower($company_val->company_name) }}</option>
                    @endforeach
                  @endif
                </select>
                @error('company_id')
                <div style="color:red">{{$message}}</div>
                @enderror
             </div>             
              <?php //echo '<pre>';print_r($productIds); exit(); ?>
           <div class="row">
                <div class="row">
                  
                  <div class="input-field col m12 s12">
                    <p> <label>{{__('locale.Items')}}</label></p>
                    @if(isset($productResult) && !empty($productResult) && count($productResult)>0)
                    @foreach($productResult as $productValue)
                    
                    <p><label>
                        <input type="checkbox" class="product_check" name="product_ids[]" value="{{ $productValue->id }}" {{ (isset($productIds) && !empty($productIds) && in_array($productValue->id,$productIds)) ? 'checked="checked"' : '' }}>
                        <span>{{ ucfirst($productValue->product_name) }}</span>
                      </label>

                      @if(isset($productValue->productvariationWithName) && !empty($productValue->productvariationWithName))
                      <div class="product-variant-div product-variant-div-{{$productValue->id}} ">
                          @foreach($productValue->productvariationWithName as $vaiant_value)
                          <?php 
                    // dd($vaiant_value->productvariationName->name); 
                    ?>
                          <label class="product-variant-label">
                            <input type="checkbox" name="product_variant[{{$productValue->id}}][id][]" value="{{ $vaiant_value->id }}">
                            <span>{{ ucfirst($vaiant_value->name) }} ({{$vaiant_value->quantity.' '.($vaiant_value->productvariationName->name)}})</span>
                            <input type="text" name="product_variant[{{$productValue->id}}][price][]" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" placeholder="Price" />
                            <input type="text" name="product_variant[{{$productValue->id}}][discount][]" oninput="this.value=this.value.replace(/[^0-9.,]/g,'');" placeholder="Discount %"  />
                            <input type="text" class="dates" name="product_variant[{{$productValue->id}}][valid_date][]" placeholder="Start Date From"  />
                          </label>
                          @endforeach
                        
                      </div>
                      @endif
                    </p>
                      @endforeach
                    @endif
                    
                  </div>
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
@section('page-script')


<script>
window.onload=function(){
  $('input.dates').daterangepicker();
    var company_value = "{{(isset($mappingResult[0]->company_id) && $mappingResult[0]->company_id!='NULL') ? $mappingResult[0]->company_id : old('company_id')}}";
    console.log('company_value',company_value);
    $('#company').val(company_value);
    $('#company').formSelect();
  }

  $(document).ready(function(){
    $('body').on('click','.product_check',function(){
      let product_id = $(this).val();
      let product_variant = `.product-variant-div-${product_id}`;
      if($(this).is(':checked')){
        $(product_variant).show();
        $(product_variant).find('input').attr('required',true);
      }else{
        $(product_variant).hide();
        $(product_variant).find('input').removeAttr('required');
      }
    });

    $('#company').change(function(){
      let com_id = $(this).val();
      $.ajax({ 
        url: '{{route("company-product")}}/'+com_id,
        success:function(data){
          console.log(data);
            
        }
      })
    })
  })
  </script>
@endsection


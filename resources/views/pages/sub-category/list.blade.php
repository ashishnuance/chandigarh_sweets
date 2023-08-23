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
    @include('panels.flashMessages')

      <div id="responsive-table" class="card card card-default scrollspy">
        <div class="card-content">
           
          <div class="row">
            <div class="col s12">
                <div class="col s4">
                    <select name="category_id" id="category_id" onchange="filtercompany(this)" data-url='{{route("sub-category.store")}}'>
                      <option value="Select" disabled selected>Select Category</option>
                      @foreach($category_list as $categoryname)
                      <option value="{{$categoryname->id}}" {{ (request()->route()->id == $categoryname->id) ? 'selected' : '' }}>{{$categoryname->category_name}}</option>
                      @endforeach
                    </select>
                 </div>
            </div>

            <div class="responsive-table table-result">
                    @include('pages.sub-category.ajax-list')
            </div>
             <input type="hidden" name="hidden_page" id="hidden_page" value="{{(isset($currentPage) && $currentPage>0) ? $currentPage : 1}}" />

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection



{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script>
  $(document).ready(function(){
    var paginationUrl = "{{(isset($paginationUrl) && $paginationUrl!='') ? route($paginationUrl) : '' }}";
 
    const fetch_data = (page, status, seach_term) => {
        if(status === undefined){
            status = "";
        }
        if(seach_term === undefined){
            seach_term = "";
        }
        $.ajax({ 
            url: paginationUrl+"?page="+page+"&status="+status+"&seach_term="+seach_term,
            success:function(data){
              console.log(data);
                $('.table-result').html('');
                $('.table-result').html(data);
            }
        })
    }

    $('body').on('keyup', '#serach', function(){
        var status = $('#status').val();
        var seach_term = $('#serach').val();
        var page = $('#hidden_page').val();
        fetch_data(page, status, seach_term);
    });

    $('body').on('change', '#users-list-status', function(){
        var status = $('#users-list-status').val();
        var seach_term = $('#serach').val();
        var page = $('#hidden_page').val();
        fetch_data(page, status, seach_term);
    });

    $('body').on('click', '.pager a', function(event){
        console.log('ssss');
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var serach = $('#serach').val();
        var seach_term = $('#status').val();
        fetch_data(page,status, seach_term);
    });
});
</script>
@endsection
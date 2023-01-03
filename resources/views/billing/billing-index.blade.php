@extends(backpack_view('blank'))

@section('header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<section class="container-fluid">
        <div class="row p-2">
             <div class="col-md-7 page-header"> 
                 <h4><span class="text"></span><i class="la la-columns"></i> Sales Billing
                    <small><a href="{{ backpack_url('sales') }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
                </h4>

             </div>
             {{-- <div class="col-md-5"> 
                <span id="patient_search"></span>
             </div> --}}
 
        </div>
	</section>
@endsection

@section('content') 
    <div class="row">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header bg-blue p-1 text-white"></div>
                
                <div class="card-body py-0" id="page_content">
                </div>
            </div>
        </div>
    </div>
      
@endsection


@push('after_scripts')
<script>
    $(document).ready(function () {
        loadItemDetail(); // loadPatientDetail
    });

    function loadItemDetail(id= null){
        $('#page_content').html('<div class="text-center"><img src="/images/loading.gif"/></div>');
        LMS.lmsLoading(true,'Loading...');
        $.ajax({
            type: "GET",
            url: "/admin/sales/get-item-info",
            data: {patient_id: id},
            success: function(response){
                LMS.lmsLoading(false);
                $('#page_content').html(response);
            }
        });   
    }
</script>
@endpush
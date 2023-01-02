@extends(backpack_view('blank'))
@php
$patients = json_encode($patients);
@endphp

@section('header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<section class="container-fluid">
        <div class="row p-2">
             <div class="col-md-7 page-header"> 
                 <h4><span class="text"></span><i class="la la-columns"></i> Patient Lab Billing
                    <small><a href="{{ url()->previous() }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
                </h4>

             </div>
             <div class="col-md-5"> 
                {{-- <input class="form-control ml-1" id="patient_search" type="search" name="patient_search" placeholder="&#xF002;  Patient Name / Patient no. / Phone"> --}}
                <input type="hidden" name="selected_patient" id="selected_patient">
                <span id="patient_search"></span>
             </div>
 
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
    let patients = <?php echo $patients ?>;
    $(document).ready(function () {
        let availableLists = [];

        if(patients){
            patients.forEach(function(patient){
                availableLists.push({'id':patient.id,'name':patient.name, 'patient_no':patient.patient_no,'cell_phone':patient.cell_phone});
            });
        }

        var patient_data = $('#patient_search').tautocomplete({
            width:"550px",
            columns:['Name','Patient No','Phone'],
            hide: [false],
            norecord:"No Records Found",
            regex:"^[a-zA-Z0-9\b\d./-]+$",
            theme:"white",
            placeholder:'Search ... Patient Name / Patient no. / Phone',
            ajax:null,
            data: function () {
                    try{
                        var data=availableLists;
                    }catch(e){
                        alert(e);
                    }
                    var filterData = [];
                    $('.awhite table tbody tr:not(:first-child)').remove();
                    var searchData = eval("/" + patient_data.searchdata() + "/gi");

                    $.each(data, function(i,v)
                    {
                        if ((v.name.search(new RegExp(searchData)) != -1) || (v.patient_no.search(new RegExp(searchData)) != -1) || (v.cell_phone.search(new RegExp(searchData)) != -1) ) {
                            filterData.push(v);
                        }
                    });
                    return filterData;

            },
            highlight:"",
            onchange:function(){
                $('#selected_patient').val(patient_data.id());

                if(patient_data.id() != null){
                    loadPatientDetail(patient_data.id());
                }
            },

        });
        
        //get clicked_patient_id from session
        let clicked_patient_id = sessionStorage.getItem('clicked_patient_id');
        if(clicked_patient_id != ''){
            $('#selected_patient').val(clicked_patient_id);
            loadPatientDetail(clicked_patient_id);
            sessionStorage.removeItem('clicked_patient_id');
        }else{
            loadPatientDetail();
        }
    });

    function loadPatientDetail(id= null){
        $('#page_content').html('<div class="text-center"><img src="/images/loading.gif"/></div>');
        LMS.lmsLoading(true,'Loading...');
        $.ajax({
            type: "GET",
            url: "/admin/billing/get-patient-info",
            data: {patient_id: id},
            success: function(response){
                LMS.lmsLoading(false);
                $('#page_content').html(response);
            }
        });   
    }
</script>
@endpush
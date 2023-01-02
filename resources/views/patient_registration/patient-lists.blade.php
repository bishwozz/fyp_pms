@extends(backpack_view('blank'))

@section('content') 
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-header bg-primary p-1">
                        <div class="row">
                            <div class="col-md-6">
                                <i class="fa fa-hospital-o"></i>
                                Patients
                            </div>
                            <div class="col-md-6">
                                <a href="{{ backpack_url('/patient/create') }}" class="btn btn-sm btn-primary float-right patientCreateBtn"  title="add"><span class="fa fa-plus font-weight-bold"></span>  {{trans('backpack::crud.add')}}</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            @if(!$items->isEmpty())
                                <table id="patient_list_table" class="table table-striped p-2" width="100%">
                                    <thead class="p-1">                       
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Photo</th>
                                            <th>Patient No.</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td> {{ $loop->iteration }} </td>
                                                <td>
                                                    @if($item->photo_name)
                                                        <img width="40px;" data-check=1 class="profile_image" src="{{ '/storage/'.$item->photo_name }}" alt="{{ $item->name ?? '' }}">
                                                    @else
                                                        <img width="40px;" data-check=0 class="profile_image" src="{{ gender_image($item->gender_id==1?'Male':'Female') }}" alt="{{ $item->name ?? '' }}">
                                                    @endif
                                                </td>
                                                <td><a class="btn-link" href="{{ backpack_url('patient/'.$item->id.'/edit') }}"  data-toggle="tooltip" title="Edit Patient Details"> {{ $item->patient_no }} </a></td>
                                                <td><a class="btn-link" href="{{ backpack_url('patient/'.$item->id.'/edit') }}"  data-toggle="tooltip" title="Edit Patient Details"> {{ $item->name }} </a></td>
                                                <td> {{ $item->cell_phone }} </td>
                                                <td>
                                                    <a href="{{ backpack_url('patient/'.$item->id.'/edit') }}" class="btn btn-sm  btn-success edit-btn mt-2" data-toggle="tooltip" title="Edit Patient Details"><i class="la la-edit"></i></a>&nbsp;
                                                    {{-- <a href="{{ backpack_url('patient/'.$item->id.'/edit') }}" class="btn btn-sm text-white btn-secondary show-btn mt-2 ml-3" data-toggle="tooltip" title="billing"><i class="la la-send"></i> Billing</a> --}}
                                                    <a href="javascript:;" class="btn btn-sm text-white btn-secondary show-btn mt-2 ml-3 billing_redirect_btn" data-patient_id="{{$item->id}}" data-toggle="tooltip" title="billing"><i class="la la-send"></i> Billing</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="col text-center font-weight-bold">No Related Patients.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>     
      
@endsection

@push('after_styles')
    <style>
        .heading{
            margin-left: 3%;
        }
        #patient_list_table_wrapper{
            width: 100%;
        }
    </style>
@endpush
@push('after_scripts')
<script>
    $('body').on('keyup', function(e) {
        e.preventDefault();
        
        let keyPressed = e.which ? e.which : e.keyCode;
        //open new form
        if(e.altKey && keyPressed === LMS._formNewFormKey){
           window.location = $('a.patientCreateBtn').attr('href');
        }
    });

$(document).ready(function () {
    $('#patient_list_table').DataTable(); 
    
    $('.billing_redirect_btn').click(function(){
       let patient_id = $(this).data('patient_id');
       if(patient_id != ''){
            sessionStorage.setItem('clicked_patient_id',patient_id);
            window.location.href = '/admin/billing/patient-billing/recent/create';
       }
    });
   
});
</script>
@endpush
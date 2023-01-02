<div id="cancel-bill-box" style="display: none; width:70%;">
    <form id="PatientApproveCancelForm" name="PatientApproveCancelForm" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="patient_id" value="{{$entry_data->id}}">
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="bill_no">Full Name</label>
                <input type="text" class="form-control" value="{{$entry_data->full_name}}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="patient_name">Email</label>
                <input type="text" class="form-control" value="{{$entry_data->email}}"  readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="created_datetime">Phone</label>
                <input type="text" class="form-control" value="{{$entry_data->cell_phone}}"  readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="created_datetime">Appoinment Date</label>
                <input type="text" class="form-control" value="{{$entry_data->appointment_date}}"  readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label class="_required" for="approved_by">Approved By</label>
                <select class="form-control" id="approved_by" name="approved_by">
                    <option value="">-</option>
                    @foreach ($approver as $approve_by)
                        <option value="{{ $approve_by->id }}">{{ $approve_by->full_name }}</option>
                    @endforeach
                </select>
                
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary float-right" id="savePatientApproveForm">Submit</button>
            <button class="btn btn-danger float-right mr-5" id="closePatientApproveCancelForm">Close</button>
        </div>
    </form>
</div>

<script>
$('.fancybox').fancybox({
    closeClick:false,
    clickSlide: false, // disable close on outside click
    touch: false // disable close on swipe
});

    $("#savePatientApproveForm").click(function (event) {
        
        event.preventDefault();

        var data = $("#PatientApproveCancelForm").serializeArray();

        if($('#approved_by').val() == ''){
            alert('Please select approver !!')
        }else{
            $.ajax({
            type:'POST',
            url:'/admin/patient-appointment/approve-save',
            data: data,
            success:function(data) {
                if(data.status=='success'){
                    $.fancybox.close();
                    swal('Info','Bill cancel successful !!','info');
                    setTimeout(() => {
                        location.reload(true);
                    }, 700);
                }else{
                    $("#PatientApproveCancelForm").trigger("reset");
                    $.fancybox.close();
                }
            }
        });
        }

      
    });

    document.getElementById("PatientApproveCancelForm").reset();

    $("#closePatientApproveCancelForm").click(function (event) {
        $("#PatientApproveCancelForm").trigger("reset");
        $.fancybox.close();
    });
</script>
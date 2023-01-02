<div id="cancel-bill-box" style="display: none; width:70%;">
    <form id="billCancelForm" name="billCancelForm" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="bill_id" value="{{$entry_data->id}}">
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="bill_no">Bill no.</label>
                <input type="text" class="form-control" value="{{$entry_data->bill_no}}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="patient_name">Patient Name</label>
                <input type="text" class="form-control" value="{{$entry_data->customer_name}}"  readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="created_datetime">Generated Date</label>
                <input type="text" class="form-control" value="{{$entry_data->created_at}}"  readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label class="_required" for="cancelled_reason">Cancellation Reason<sub> (Please mention reason for bill cancellation.)</sub></label>
                <textarea rows="3" style="width:100%;" maxlength="200" name="cancelled_reason" id="cancelled_reason" required></textarea>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary float-right" id="saveBillCancelForm">Submit</button>
            <button class="btn btn-danger float-right mr-5" id="closeBillCancelForm">Close</button>
        </div>
    </form>
</div>

<script>
$('.fancybox').fancybox({
    closeClick:false,
    clickSlide: false, // disable close on outside click
    touch: false // disable close on swipe
});

    $("#saveBillCancelForm").click(function (event) {
        
        event.preventDefault();

        var data = $("#billCancelForm").serializeArray();

        if($('#cancelled_reason').val() == ''){
            alert('Please enter cancellation reason !!')
        }else{
            $.ajax({
            type:'POST',
            url:'/admin/billing/patient-billing/default/update-bill-cancel-status',
            data: data,
            success:function(data) {
                if(data.status=='success'){
                    $.fancybox.close();
                    swal('Info','Bill cancel successful !!','info');
                    setTimeout(() => {
                        location.reload(true);
                    }, 700);
                }else{
                    $("#billCancelForm").trigger("reset");
                    $.fancybox.close();
                }
            }
        });
        }

      
    });

    document.getElementById("billCancelForm").reset();

    $("#closeBillCancelForm").click(function (event) {
        $("#billCancelForm").trigger("reset");
        $.fancybox.close();
    });
</script>
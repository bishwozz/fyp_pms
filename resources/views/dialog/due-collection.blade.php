<div id="due-collection-box" style="display: none; width:60%;">
    <form id="dueCollectionForm" name="dueCollectionForm" method="POST" action="/admin/billing/patient-billing/default/update-due-collection">
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
            <div class="col-md-4 mb-3">
                <label for="net_payable">Net Payable</label>
                <input type="text" class="form-control" value="{{$entry_data->total_net_amount}}" name="net_payable"  readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="due_received_by" class="_required">Due Collected By</label>
                <select class="form-control" name="due_received_by" id="due_received_by" required>
                    @foreach($employees as $item)
                        <option value="{{$item->id}}">{{$item->full_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="payment_method_type" class="_required"> Payment Method</label>
                <select class="form-control" name="payment_method_type" id="payment_method_type" required onchange="toggleFields()">
                    @foreach($payment_methods as $pt)
                        <option value="{{$pt->id}}">{{$pt->title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="bank_id" class="_required">Bank</label>
                <select class="form-control" name="bank_id" id="bank_id">
                    @foreach($banks as $b)
                        <option value="{{$b->id}}">{{$b->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="cheque_no" class="_required">Cheque no.</label>
                <input class="form-control text-right" type="text" name="cheque_no" id="cheque_no"/> 
            </div>
            <div class="col">
                <label for="card_id" class="_required">Card</label>
                <select class="form-control" name="card_id" id="card_id">
                    @foreach($card_type as $key=>$title)
                        <option value="{{$key}}">{{$title}}</option>
                    @endforeach
                </select>
            </div>
    
            <div class="col">
                <label for="transaction_number" class="_required">Transaction no.</label>
                <input class="form-control text-right" type="text" name="transaction_number" id="transaction_number"/> 
            </div>
            
        </div>

        <div class="form-group mt-5">
            <button class="btn btn-primary float-right" id="saveDueCollectionForm">Submit</button>
            <a class="btn btn-danger float-right mr-5" href="javascript:;" id="closeDueCollectionForm">Close</a>
        </div>
    </form>
</div>

<script>
    toggleFields();
    // hide payment fields
    function hideFields()
    {
        $('#bank_id,#card_id,#cheque_no,#transaction_number').val('');
        $('#bank_id,#card_id,#cheque_no,#transaction_number').parent().hide();
    }
    //toggle fields acc to payment selection type
    function toggleFields()
    {
        let itemValue = $('#payment_method_type').val();
        hideFields();

        if(itemValue == '1')
        {
            hideFields();
        }
        
        if(itemValue == '2')
        {
            hideFields();
            $('#bank_id,#transaction_number').parent().show();
        }
        if(itemValue == '2')
        {
            hideFields();
            $('#bank_id,#transaction_number').parent().show();

        }
        if(itemValue == '3')
        {
            hideFields();
            $('#bank_id,#card_id,#transaction_number').parent().show();

        }
        if(itemValue == '4' || itemValue == '5')
        {
            hideFields();
        }

        if(itemValue == '7'){
            hideFields();
            $('#cheque_no').parent().show();
        }
   
    }
    $('form#dueCollectionForm').click(function(event){
        $('div.fancybox-container').css('z-index','10000');
    });

    $('form#dueCollectionForm').validate({
        submitHandler: function(form) {
            swal({
                closeOnClickOutside: false,
                title: "Confirm And Save !!",
                text: 'Are you sure you want to Proceed ?.',
                buttons: {
                    no: {
                        text: " No ",
                        value: false,
                        visible: true,
                        className: "btn btn-secondary",
                        closeModal: true,
                    },
                    yes: {
                        text: " Yes ",
                        value: true,
                        visible: true,
                        className: "btn btn-success",
                        closeModal: true,
                    }
                },
            }).then((confirmResponse) => {
                if (confirmResponse) {
                    LMS.lmsLoading(true, 'Saving...');
                    let data = new FormData(form);
                    let url = form.action;
                    axios.post(url, data)
                    .then((response) => {
                        document.location = response.data.url;
                        LMS.lmsLoading(false);
                    }, (error) => {
                        swal("Error !", error.response.data.message, "error")
                        LMS.lmsLoading(false);
                    });
                   
                }
            });
        }
	});

    // $("#saveDueCollectionForm").click(function (event) {
        
    //     event.preventDefault();

    //     var data = $("#dueCollectionForm").serializeArray();

    //     if($('#cancelled_reason').val() == ''){
    //         alert('Please enter cancellation reason !!')
    //     }else{
    //         $.ajax({
    //         type:'POST',
    //         url:'/admin/billing/patient-billing/update-due-collection',
    //         data: data,
    //         success:function(data) {
    //             if(data.status=='success'){
    //                 $.fancybox.close();
    //                 swal('Info','Bill cancel successful !!','info');
    //                 setTimeout(() => {
    //                     location.reload(true);
    //                 }, 700);
    //             }else{
    //                 $("#dueCollectionForm").trigger("reset");
    //                 $.fancybox.close();
    //             }
    //         }
    //     });
    //     }

      
    // });

    document.getElementById("dueCollectionForm").reset();

    $("#closeDueCollectionForm").click(function (event) {
        $("#dueCollectionForm").trigger("reset");
        $.fancybox.close();
    });
</script>
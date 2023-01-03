<form role="form" action="{{backpack_url('sales/store-bill')}}" method="POST" id="lab_items_billing_form" style="width: 100%">
    {!! csrf_field() !!}
    <div class="row lab-billing-content">
        <div class="col-md-12">
            <div class="card h-100 lab-billing-items-header">
                <div class="card-header p-0 px-2">
                    <div class="form-row mb-2">
                        <div class="col-md-4">
                            <label for="span_name" class="_required">Billing Services</label>
                        </div>
                        <div class="col text-right">
                            <label for="span_quantity" class="_required">Qty.</label>
                        </div>
                        <div class="col text-right">
                            <label for="span_rate" class="_required">Rate</label>  
                        </div>
                        <div class="col text-right">
                            <label for="span_amount">Amount</label>
                        </div>
                        <div class="col-md-2 text-right">
                            <label for="span_net_amount">Net Amount</label>
                        </div>
                        <div class="col text-right">
                            <label for="span_action">Action</label>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0 m-2" id="lab-billing-item-main-content">
                    <div class="form-row lab-billing-item-row d-none mt-2" id="lab-billing-item-row">
                        <div class="col-md-4">
                            <span id="item_search"></span>
                            <input type="hidden" id="selected_item" value="">
                        </div>
                        <div class="col">
                            <input class="form-control text-right item_quantity" type="number" id="item_quantity"  value="1" min="0" onchange="quantityChangeEvent(this)">
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_rate"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_amount"  value="0" min="0" readonly>
                        </div>
                        <div class="col-md-2">
                            <input class="form-control text-right" type="number" id="item_net_amount"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <a class="btn btn-sm btn-danger float-right remove_lab_item_row p-0 px-2 mr-2" role="button" style="cursor: pointer;" title="Remove this row"><i class="fa fa-close text-white"></i></a>
                        </div>
                    </div>

                    <div class="form-row lab-billing-item-row mt-2" id="lab-billing-item-row-1">
                        <div class="col-md-4">
                            <span id="item_search-1"></span>
                            <input type="hidden" id="selected_item-1"value="">
                        </div>
                        <div class="col">
                            <input class="form-control text-right item_quantity" type="number" id="item_quantity-1"  value="1" min="0" onchange="quantityChangeEvent(this)">
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_rate-1"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_amount-1"  value="0" min="0" readonly>
                        </div>
                        {{-- <div class="col">
                            <input class="form-control text-right" type="number" id="item_discount-1"  value="0" min="0" onchange="discountChangeEvent(this)">
                        </div> --}}
                        {{-- <div class="col">
                            <input class="form-control text-right" type="number" id="item_tax-1"  value="0" readonly>
                        </div> --}}
                        <div class="col-md-2">
                            <input class="form-control text-right" type="number" id="item_net_amount-1"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <a class="btn btn-sm btn-danger float-right remove_lab_item_row p-0 px-2 mr-2" role="button" style="cursor: pointer;" title="Remove this row"><i class="fa fa-close text-white"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col text-right mr-5 text-red text-size-25">Net Amount : <span id="total_balance">0</span></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-8 left-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col">
                                            <label for="total_discount_type"> Discount Type</label>
                                            <select class="form-control" name="total_discount_type" id="total_discount_type" onchange="totalDiscountChangeEvent()">
                                                <option value="" class="text-muted">-- select --</option>
                                                <option value="fixed">Rs</option>
                                                <option value="percentage">%</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="payment_method_type" class="_required"> Payment Method</label>
                                            <select class="form-control" name="payment_method_type" id="payment_method_type" required onchange="toggleFields()">
                                                @foreach($payment_methods as $pt)
                                                    <option value="{{$pt->id}}">{{$pt->title}}</option>
                                                @endforeach
                                                 <option value="0">-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col">
                                            <label for="total_discount_value">Discount</label>
                                            <input class="form-control text-right" type="number" name="total_discount_value" id="total_discount_value" min="0" max="100" value="0" onchange="totalDiscountChangeEvent()"/> 
                                        </div>
                                        <div class="col">
                                            <label for="total_receipt_amount" class="_required">Receipt Amount</label>
                                            <input class="form-control text-right" type="number" name="total_receipt_amount" id="total_receipt_amount" min="0" value="0" required/> 
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label for="discount_approved_by">Discount Approver</label>
                                            <select class="form-control" name="discount_approved_by" id="discount_approved_by">
                                                {{-- @foreach($discounters as $item)
                                                    <option value="{{$item->id}}">{{$item->full_name}}</option>
                                                @endforeach --}}
                                                <option value="0">-</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <p><label for="remarks">Remarks</label></p>
                                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col">
                                            <label for="credit_approved_by" class="_required">Credit Approver</label>
                                            <select class="form-control" name="credit_approved_by" id="credit_approved_by">
                                                @foreach($creditors as $item)
                                                    <option value="{{$item->id}}">{{$item->full_name}}</option>
                                                @endforeach
                                                <option value="0">-</option>

                                            </select> 
                                        </div>
                                        <div class="col">
                                            <label for="bank_id" class="_required">Bank</label>
                                            <select class="form-control" name="bank_id" id="bank_id">
                                                {{-- @foreach($banks as $b)
                                                    <option value="{{$b->id}}">{{$b->name}}</option>
                                                @endforeach --}}
                                                <option value="0">-</option>

                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="cheque_no" class="_required">Cheque no.</label>
                                            <input class="form-control text-right" type="text" name="cheque_no" id="cheque_no"/> 
                                        </div>
                                    </div>
                                    <div class="row mt-2">    
                                        <div class="col">
                                            <label for="card_id" class="_required">Card</label>
                                            <select class="form-control" name="card_id" id="card_id">
                                                {{-- @foreach($card_type as $key=>$title)
                                                    <option value="{{$key}}">{{$title}}</option>
                                                @endforeach --}}
                                                <option value="0">-</option>

                                            </select>
                                        </div>
                                
                                        <div class="col">
                                            <label for="transaction_number" class="_required">Transaction no.</label>
                                            <input class="form-control text-right" type="text" name="transaction_number" id="transaction_number"/> 
                                        </div>
                                    </div>
                                    {{-- fields for parment method --}}
                                </div>
                                
                            </div>
                            
                        </div>

                        <div class="col-md-4 right-section">
                            <div class="form-row mt-2">
                                <div class="col"><label >Gross Amount</label></div>      
                                <div class="col-md-5"> <input type="number" id="total_gross_amount" class="form-control text-right font-weight-bold" name="total_gross_amount" readonly value="0" /> </div>
                            </div>

                            <div class="form-row mt-2">
                                <div class="col"><label >Discount</label></div>
                                <div class="col-md-5"> <input type="number" id="total_discount_amount"  class="form-control text-right font-weight-bold" name="total_discount_amount" readonly value="0" /> </div>
                            </div>

                            <div class="form-row mt-2">
                                <div class="col"><label >Tax Amount</label></div>      
                                <div class="col-md-5"> <input type="number" id="total_tax_amount" class="form-control text-right font-weight-bold" name="total_tax_amount" value="0" readonly/> </div>
                            </div>

                            <div class="form-row mt-2">
                                <div class="col"><label >Net Amount</label></div>      
                                <div class="col-md-5"> <input type="number" id="total_net_amount" class="form-control text-right font-weight-bold" name="total_net_amount" value="0" readonly> </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col"><label class="_required">Paid Amount</label></div>      
                                <div class="col-md-5"> <input type="number" id="total_paid_amount" class="form-control text-right font-weight-bold" name="total_paid_amount" value="0" onchange="calculatePaidRefundAmount()"/> </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col"><label >Refund</label></div>      
                                <div class="col-md-5"> <input type="number" id="total_refund_amount" class="form-control text-right font-weight-bold" name="total_refund_amount" value="0" min=0 readonly/> </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="hr-line">
                <div class="row mb-4">
                    <div class="col text-right">
                        @if(isset($entry))
                            @if($entry == false)
                                <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i>Update</button>
                            @else    
                                {{-- <a target = "_blank" href="{{ backpack_url('/pharmacy/phr_item_sales/'.$entry->id.'/generate_phr_item_sales_bill') }}" class="btn btn-primary"><i class="fa fa-print"></i> Print Bill</a> --}}
                            @endif    
                        @else
                            <button type="submit" class="btn btn-sm btn-blue mr-5" role="button"><i class="fa fa-floppy-o"></i>&nbsp; Generate Bill </button>
                            {{-- <button type="submit" class="btn btn-sm btn-blue mr-2" role="button"><i class="fa fa-floppy-o"></i>&nbsp; Approve </button> --}}
                        @endif
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</form> 


<script>
    var index=1;
    var pushedIds=[];
    processAutocomplete(index);
    toggleFields();
    totalDiscountChangeEvent();

    function processAutocomplete(active_index)
    {
            window['item_data_'+active_index] = $('#item_search-'+active_index).tautocomplete({
            width:"400px",
            columns:['Name','Qty','Price'],
            hide: [false],
            norecord:"No Records Found",
            regex:"^[a-zA-Z0-9\b]+$",
            theme:"white",
            placeholder:'Search ... Test / Item Name',
            ajax:{
                url:'/admin/sales/items',
                type:"GET",
                data:function(){
                    return {qs:eval('item_data_'+active_index).searchdata()};
                },
                success:function(data){
                    if(data.items){
                        var filterData = [];
                        $('.awhite table tbody tr:not(:first-child)').remove();
                        var searchData = eval("/" + eval('item_data_'+active_index).searchdata() + "/gi");
                        $.each(data.items, function(i,v)
                        {
                            if ((v.name.search(new RegExp(searchData)) != -1) || (v.code.search(new RegExp(searchData)) != -1) ) {
                                filterData.push(v);
                            }
                        });
                        return filterData;
                    }else{
                        swal('No Items Data Found !','Please search Items first !!','error')
                    }
                        
                }
            },
            highlight:"",
            onchange:function(){
                let current_item_id = eval('item_data_'+active_index).id();
                $('#selected_item-'+active_index).val(current_item_id);
                if(current_item_id != null){
                    if(pushedIds.includes(current_item_id)){
                        swal("Duplicate Item !", 'Item already added !!', "error")
                    }else{
                        pushedIds.push(current_item_id);
                        loadItemDetail(active_index,current_item_id);
                    }
                }
            },
        });
        
    }
   
    //get rate for particular item;
    //add new row and assign name and ids to each rows
    function loadItemDetail(active_index,item_id)
    {
        if(item_id){
            $.get('/admin/sales/get-item-rate',{item_id:item_id},function(response){
                if(response.status == 'success'){
                    $('#item_rate-'+active_index).val(parseFloat(response.item.amount).toFixed(2));

                    $('#item_quantity-'+active_index).attr('max',response.item.qty);////===========
                    //set name to current rows

                    //first get div for which name should be set
                    let current_form = document.getElementById('lab-billing-item-row-'+active_index);

                    //find all inputs and loop through all, to set name using id and index counter;
                    $(current_form).find('input').each(function(){
                        $(this).attr('name',function(){
                            let id = this.id
                            let new_string = id.split("-");
                            return new_string[0]+'['+active_index+']';
                        })
                    });

                    //increment the current index to create new row with +1 index
                    index++;

                    let form = document.getElementById('lab-billing-item-row');
                    let clone = form.cloneNode(true);
                    clone.id = 'lab-billing-item-row-' + index;

                    //clone row from hidden row and remove d-none property;
                    $(clone).filter('.lab-billing-item-row').removeClass('d-none');

                    //assign new id to autocomplete span;
                    $(clone).find('span').each(function(){

                        $(this).attr('id',function(_,id){
                            return id+'-'+index;
                        });
                    });

                    //assign new ids to each row items
                    $(clone).find('input').each(function(){

                        $(this).attr('id',function(_,id){
                            return id+'-'+index;
                        });
                    });
                    form.parentNode.appendChild(clone);

                    //disable item change for previous rows after new row is added
                    $(current_form).find('.acontainer input').attr('readonly',true);

                    //trigger change for calculation on item net amount
                    $('#item_quantity-'+active_index).trigger('change');

                    processAutocomplete(index);

                }else{
                    swal("Error !", 'Something is wrong !!', "error")
                }
            })
        }
    }

    //remove row
    $('body').on('click', '.remove_lab_item_row', function(){
        let id = $(this).closest('div[id^="lab-billing-item-row"]').attr('id');
        let id_index = id.split("-");

        if($('div[id^="lab-billing-item-row"]').length > 2) {

            if($('#selected_item-'+id_index[4]).val() != ''){
                pushedIds=pushedIds.filter(item=>item !== $('#selected_item-'+id_index[4]).val());

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((is_delete) => {
                        if (is_delete) {
                            $(this).closest('div[id^="lab-billing-item-row"]').remove();
                            calculateTotal();
                        } else {
                            swal("Your data is safe!");
                        }
                    });
            }else{
                swal("Warning !", "This row can't be removed !!", "error");
            }
        }
        else {
            swal("Warning !", "You must have at least one billing service row, so you cannot delete this !!", "error");
            return false;
        }
    });


    //calculate individual item net payable
    function calculateItemNetPayable(quantity,rate,discount,tax)
    {
        if(discount='undefined'){
            discount = 0;
        }
        if(tax='undefined'){
            tax = 0;
        }
        let item_amount=quantity*rate;
        let item_discount=discount;

        let discount_type = $('#item_discount_type').val();

        if(discount_type === "percentage"){
            item_discount = (discount*item_amount)/100;
        }else if(discount_type=='fixed'){
            item_discount = discount;
        }

        let item_net_amount = item_amount-item_discount;

        return {
            'item_amount':item_amount,
            'item_net_amount':item_net_amount
        }
    }


    //calculation for billing

    function calculateTotal()
    {

        let total_gross_amount=0;
        let total_net_amount=0; 
        let total_discount_amount=0;

        $('input[id^="item_net_amount-"]').each(function(){
            total_gross_amount += parseFloat($(this).val());
        });
        
        //set total gross amount
        $('#total_gross_amount').val(total_gross_amount.toFixed(2));

        //check for discount type and discount
        if($('#total_discount_type').val() == 'percentage'){
            total_discount_amount= (parseFloat($('#total_discount_value').val())*total_gross_amount)/100;
        }else if($('#total_discount_type').val() == 'fixed'){
            total_discount_amount = parseFloat($('#total_discount_value').val());
        }

        //set total discout amount
        $('#total_discount_amount').val(total_discount_amount.toFixed(2));

        //set total net amount
        $('#total_net_amount').val(parseFloat(total_gross_amount-total_discount_amount).toFixed(2));
        $('#total_receipt_amount').val(parseFloat(total_gross_amount-total_discount_amount).toFixed(2));
        $('#total_balance').html(parseFloat(total_gross_amount-total_discount_amount).toFixed(2));

        //calculate paid and refund amount
        // calculatePaidRefundAmount();
    }


    function quantityChangeEvent(item)
    {

        let row_item_id = item.id
        let row_item_index = row_item_id.split('-').pop();

        let row_item_quantity=$('#item_quantity-'+row_item_index).val();
        let row_item_rate = $('#item_rate-'+row_item_index).val();;
        let row_item_discount=$('#item_discount-'+row_item_index).val();
        let row_item_tax=$('#item_tax-'+row_item_index).val();

        let net_payable_return = calculateItemNetPayable(row_item_quantity,row_item_rate,row_item_discount,row_item_tax);
        
        $('#item_amount-'+row_item_index).val(parseFloat(net_payable_return.item_amount).toFixed(2));
        $('#item_net_amount-'+row_item_index).val(parseFloat(net_payable_return.item_net_amount).toFixed(2));
        calculateTotal();
    }

    function calculatePaidRefundAmount()
    {
        let total_paid_amount = parseFloat($("#total_paid_amount").val());
        let change_amount = (total_paid_amount - $("#total_net_amount").val());
        $("#total_refund_amount").val(change_amount.toFixed(2));
    }

    function totalDiscountChangeEvent()
    {
        if($('#total_discount_type').val() !=''){
            $('#discount_approved_by').parent().show();
            $('#total_discount_value').removeAttr('readonly');
        }else {
            $('#discount_approved_by').parent().hide();
            $('#total_discount_value').attr('readonly','true');
        }

        calculateTotal();
    }

    //calulate item wise discount change
    function itemDiscountChangeEvent()
    {
        $('input[id^="item_discount-"]').each(function(index,item){
           quantityChangeEvent(item);
        });
    }

    function discountChangeEvent(item)
    {
        quantityChangeEvent(item);
    }

    // hide payment fields
    function hideFields()
    {
        $('#credit_approved_by,#bank_id,#card_id,#cheque_no,#transaction_number').val('');
        $('#credit_approved_by,#bank_id,#card_id,#cheque_no,#transaction_number').parent().hide();
    }

    //toggle fields acc to payment selection type
    function toggleFields()
    {
        let itemValue = $('#payment_method_type').val();
        hideFields();
        
        if($('#total_discount_type').val() ==''){
            $('#discount_approved_by').val('');
            $('#discount_approved_by').parent().hide();
        }

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
        if(itemValue == '6')
        {
            hideFields();
            $('#total_receipt_amount,#total_paid_amount').val(0);
            $('#total_receipt_amount,#total_paid_amount').attr('readonly','true');
            $('#credit_approved_by').parent().show();
        }else{
            $('#total_receipt_amount,#total_paid_amount').removeAttr('readonly');
            calculateTotal();
        }

        if(itemValue == '7'){
            hideFields();
            $('#cheque_no').parent().show();
        }
   

    }

    //js for saving form
    $('#lab_items_billing_form').validate({
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
                    let bill_price = parseInt($('#total_net_amount').val());
                    LMS.lmsLoading(bill_price > 0 ? true:false, 'Saving...');
                    let data = new FormData(form);
                    let url = form.action;
                    if(bill_price > 0){
                        axios.post(url, data)
                        .then((response) => {
                            document.location = response.data.url;
                            LMS.lmsLoading(false);
                        }, (error) => {
                            swal("Error !", error.response.data.message, "error")
                            LMS.lmsLoading(false);
                        });
                    }else{
                        swal('No entries found !!')
                    }
                }
            });
        }
    });

    

    // $(document).ready(function() {
    //     // Your code goes here
    //     $('.item_quantity').on('keyup', function() {

    //         $dd = $('#selected_item').val();
    //         debugger;
    //     })
    //     // $.ajax({
    //     //     type: "GET",
    //     //     url: "/admin/sales/check-item-qty",
    //     //     data: {item: item},
    //     //     success: function(response){
    //     //         debugger;
    //     //         LMS.lmsLoading(false);
    //     //         $('#page_content').html(response);
    //     //     }
    //     // }); 
    // });

</script>

    

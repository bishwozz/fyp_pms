@php
    $referral_data=[];
    foreach($referral as $ref)
    {
        $referral_data[$ref->id] = $ref->discount_percentage;
    }
    $referral_data =json_encode($referral_data);
@endphp
<form role="form" action="{{backpack_url('billing/patient-billing/default/store-bill')}}" method="POST" id="lab_patient_billing_form" style="width: 100%">
    {!! csrf_field() !!}
    <div class="row lab-billing-content">
        <input type="hidden" name="patient_id" value="{{isset($patient)?$patient->id:''}}">
        <table class="table table-responsive-lg patient-info-header">
            <thead>
                <tr>
                    <th class="_required">Patient Name</th>
                    <th><input class="form-control" type="text" name="patient_name" value="{{isset($patient) ? $patient->name:''}}" readonly required></th>
                    <th class="_required">Patient No</th>
                    <th><input class="form-control" type="text" name="patient_no" value="{{isset($patient) ? $patient->patient_no:''}}" readonly required></th>
                    <th class="_required">Age / Sex</th>
                    <th><input class="form-control" type="text" name="age_sex" value="{{isset($patient) ? $patient->gender_age():''}}" readonly required></th>
                </tr>
                <tr>
                    <th class="_required">Rate Type</th>
                    <th>
                        <select class="form-control" name="rate_type" id="rate_type" required>
                            @foreach($rate_type as $key=>$rt)
                            <option value="{{$key}}">{{$rt}}</option>
                            @endforeach
                        </select>
                    </th>
                    <th>Discount Type</th>
                    <th>
                        <select class="form-control" name="item_discount_type" id="item_discount_type" onchange="itemDiscountChangeEvent()">
                            <option value="" class="text-muted">-- select --</option>
                            <option value="fixed">Rs</option>
                            <option value="percentage">%</option>
                        </select>
                    </th>
                    <th class="_required">Referral</th>
                    <th>
                        <a data-fancybox data-src="#popup-box" href="javascript:;" class="btn btn-sm float-right pt-1 mr-2" style="position: absolute; font-size:18px; right: 0em;"><span class="fa fa-plus"></span></a>
                        <select class="form-control" name="referred_by" id="referred_by" required style="width: 90%" onchange="loadReferralData()">
                            <option value=""> - </option>
                            @foreach($referral as $ref)
                                <option value="{{$ref->id}}"> {{ $ref->name }}</option>
                            @endforeach
                        </select>
                    </th>
                </tr>
            </thead>
        </table>
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
                        <div class="col text-right">
                            <label for="span_discount">Discount</label>
                        </div>
                        {{-- <div class="col text-right">
                            <label for="span_tax">Tax</label>
                        </div> --}}
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
                            <input class="form-control text-right" type="number" id="item_quantity"  value="1" min="0" onchange="quantityChangeEvent(this)">
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_rate"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_amount"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_discount"  value="0" min="0" onchange="discountChangeEvent(this)">
                        </div>
                        {{-- <div class="col">
                            <input class="form-control text-right" type="number" id="item_tax"  value="0" readonly>
                        </div> --}}
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
                            <input class="form-control text-right" type="number" id="item_quantity-1"  value="1" min="0" onchange="quantityChangeEvent(this)">
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_rate-1"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_amount-1"  value="0" min="0" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control text-right" type="number" id="item_discount-1"  value="0" min="0" onchange="discountChangeEvent(this)">
                        </div>
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
                                                <option value="" class="text-muted">-- select --</option>
                                                @foreach($payment_methods as $pt)
                                                    <option value="{{$pt->id}}">{{$pt->title}}</option>
                                                @endforeach
                                                
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
                                                @foreach($discounters as $item)
                                                    <option value="{{$item->id}}">{{$item->full_name}}</option>
                                                @endforeach
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
                                            </select> 
                                        </div>
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
                                    </div>
                                    <div class="row mt-2">    
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
                                <div class="col-md-5"> <input type="number" id="total_paid_amount" class="form-control text-right font-weight-bold" name="total_paid_amount" value="0" onchange="calculatePaidRefundAmount()" required/> </div>
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
                            <button type="submit" class="btn btn-sm btn-blue mr-5 mt-2" role="button"><i class="fa fa-floppy-o"></i>&nbsp; Generate Bill </button>
                            {{-- <button type="submit" class="btn btn-sm btn-blue mr-2" role="button"><i class="fa fa-floppy-o"></i>&nbsp; Approve </button> --}}
                        @endif
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</form> 

<!-- Fancybox popup -->
<div id="popup-box" style="display: none; width:70%;">
    <form id="referalForm" name="referalForm" method="POST">
      {!! csrf_field() !!}
      <div class="form-row">
          <div class="col-md-4 mb-3">
            <label for="name">Code</label>
            <input type="text" class="form-control" id="code" name="code" placeholder="Code" disabled>
          </div>
          <div class="col-md-4 mb-3">
            <label for="name">Name<span style="color:red;"> * </span></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Full name" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="referal_type">Referal Type<span style="color:red;"> * </span></label>
            <select class="form-control" name="referal_type" id="referal_type" required>
                  <option value=""> - </option>
                  @foreach($referral_type as $key => $value)
                  <option value="{{$key}}"> {{$value}}</option>
                  @endforeach
              </select>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-6 mb-3">
            <label for="contact_person">Contact Person<span style="color:red;"> * </span></label>
            <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Contact Person" required>
          </div>
          <div class="col-md-3 mb-3">
            <label for="phone">Phone<span style="color:red;"> * </span></label>
            <input type="number" class="form-control" id="phone" name="phone" placeholder="phone" required>
          </div>
          <div class="col-md-3 mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
          </div>
        </div>
  
        <div class="form-row">
          <div class="col-md-4 mb-3">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="discount_percentage">Discount Amount (%)<span style="color:red;"> * </span></label>
            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Discount Percentage" required>
          </div>
        </div>
  
        <div class="form-group">
          <div class="form-check" style="margin-left: -30px;">
              <div class="col-md-12 mb-3">
                  <label class="form-check-label" for="is_active">
                  Active Status
                  </label>
              </div>
              <div class="col-md-12 mb-3">
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="is_active" id="inlineRadio1" value="1" checked>
                      <label class="form-check-label" for="inlineRadio1">Yes</label>
                  </div>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="is_active" id="inlineRadio2" value="0">
                      <label class="form-check-label" for="inlineRadio2">No</label>
                  </div>
              </div>
          </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary float-right" type="submit" id="saveReferalForm">Submit</button>
              <button class="btn btn-danger float-right mr-5"  id="closeReferalForm">Close</button>
        </div>
    </form>
  </div>

<script>
    var index=1;
    var pushedIds=[];
    processAutocomplete(index);
    toggleFields();
    totalDiscountChangeEvent();

    var referral_data = JSON.parse('<?php echo $referral_data; ?>');
    function processAutocomplete(active_index)
    {
        let patient_id = $('#selected_patient').val();
            window['item_data_'+active_index] = $('#item_search-'+active_index).tautocomplete({
            width:"400px",
            columns:['Name','Code','Category','Price'],
            hide: [false],
            norecord:"No Records Found",
            regex:"^[a-zA-Z0-9\b]+$",
            theme:"white",
            placeholder:'Search ... Test / Item Name',
            ajax:{
                url:'/admin/billing/patient-billing/default/lab-items',
                type:"GET",
                data:function(){
                    return {qs:eval('item_data_'+active_index).searchdata()};
                },
                success:function(data){
                    if(patient_id){
                        var filterData = [];
                        $('.awhite table tbody tr:not(:first-child)').remove();
                        var searchData = eval("/" + eval('item_data_'+active_index).searchdata() + "/gi");
                        $.each(data.lab_items, function(i,v)
                        {
                            if ((v.name.search(new RegExp(searchData)) != -1) || (v.code.search(new RegExp(searchData)) != -1) ) {
                                filterData.push(v);
                            }
                        });
                        return filterData;
                    }else{
                        swal('No Patient Data Found !','Please search patient first !!','error')
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
            $.get('/admin/billing/patient-billing/default/get-item-rate',{item_id:item_id},function(response){
                if(response.status == 'success'){
                    $('#item_rate-'+active_index).val(parseFloat(response.item.test_amount).toFixed(2));
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
        $('#lab_patient_billing_form').validate({
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

        function loadReferralData()
        {
            let referral_id = $('#referred_by').val();

            if(referral_id)
            {
                discount_rate = referral_data[referral_id];
                $('#total_discount_type').val('percentage').trigger('change'); 
                $('#total_discount_value').val(discount_rate); 

            }else{
                $('#total_discount_type').val('').trigger('change');
                $('#total_discount_value').val(0); 
            }
            totalDiscountChangeEvent()
           
        }

$(document).ready(function () {
 
    $("#saveReferalForm").click(function (event) {
        
        event.preventDefault();

        var data = $("#referalForm").serializeArray();

        let name = document.forms["referalForm"]["name"].value;
        let referal_type = document.forms["referalForm"]["referal_type"].value;
        let contact_person = document.forms["referalForm"]["contact_person"].value;
        let phone = document.forms["referalForm"]["phone"].value;
        let email = document.forms["referalForm"]["email"].value;
        let address = document.forms["referalForm"]["address"].value;
        let discount_percentage = document.forms["referalForm"]["discount_percentage"].value;

        if (name==null || name=="") {
            // swal.fire("Name must be filled out", "error")
            return false;
        }
        if (referal_type==null || referal_type=="") {
            // swal("Referal Type must be filled out", "error")
            return false;
        }
        if (contact_person==null || contact_person=="") {
            // swal("Contact Person must be filled out", "error")
            return false;
        }
        if (phone==null || phone=="") {
            // swal("Phone must be filled out", "error")
            return false;
        }

        if (discount_percentage==null || discount_percentage=="") {
            // swal("Charge Amount must be filled out", "error")
            return false;
        }

            $.ajax({
                type:'GET',
                url:'/admin/billing/patient-billing/default/getReferalData',
                data: data,
                success:function(data) {
                    if(data['status']=='success'){

                        $('#referred_by').append(`<option value="${data['referal']['id']}"> ${data['referal']['name']} </option>`);
                        $('select[name="referred_by"]').val(data['referal']['id']).change();

                        $('#referalForm').trigger("reset");
                        $.fancybox.close();

                    }else{
                        $('#referalForm').trigger("reset");
                        $.fancybox.close();
                        return false;
                    }
                    
                }
            });
    });

    document.getElementById("referalForm").reset();

    $("#closeReferalForm").click(function (event) {
        $('#referalForm').trigger("reset");
        $.fancybox.close();
    });

});

</script>

    

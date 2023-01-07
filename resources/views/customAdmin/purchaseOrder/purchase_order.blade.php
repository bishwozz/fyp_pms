@extends(backpack_view('blank'))
@php
    use App\Models\Pms\SupStatus;
@endphp

@section('header')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<style>
    .input-group:not(.has-validation)>.dropdown-toggle:nth-last-child(n+3), .input-group:not(.has-validation)>:not(:last-child):not(.dropdown-toggle):not(.dropdown-menu) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .table thead td, .table thead th {
        background: black;
    }
    .table td{
        padding-top: 0 !important;
    }
</style>
    <section class="container-fluid">
        <div class="row p-2">
             <div class="col-md-7 page-header"> 
                 <h4><span class="text"></span><i class="la la-columns"></i> {!! $crud->getHeading() ?? $crud->entity_name_plural !!}
                    <small><a href="{{ url($crud->route) }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
                </h4>

             </div>
        </div>
	</section>
@endsection

@section('content') 
    <div class="row">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header bg-blue p-1 text-white"></div>
                
                <div class="card-body py-0" >
                      {{-- start of Purchase order history modal --}}
                <div class="modal fade bd-example-modal-xl" id="purchase_order_modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="container">
                                    <div class="row">
                                        <h3>Purchase History of <span id="item_name_modal"></span></h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="input-group">
                                                <span class="input-group-text">From</span>
                                                <input type="date" class="form-control" id="po_history_from" value="{{generate_date_with_extra_days(dateToday(),7)}}" name="po_history_from">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <span class="input-group-text">To</span>
                                                <input type="date" class="form-control" id="po_history_to" value="{{convert_ad_from_bs()}}" name="po_history_to">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-success " id="po_history_fetch_btn">Fetch</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div id="modal_table_content"></div>
                            </div>
                            <div class="modal-footer">
                                <h5 class="left">Total Purchase Qty: <span id="total_qty_history"></span></h5>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end of the modal content --}}
                <form action="{{ url($crud->route)}}" role="form" method="POST" id="po_form">
                    @csrf
                    <div class="billing-form main-container">
                        <div class="row mt-3">

                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="supplier">Supplier</label>
                                    <select class="form-control" id="supplier" name="supplier_id">
                                        <option value="">--select supplier--</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contact No</span>
                                    <input type="text" class="form-control" value="" id="phone" name="phone" placeholder="Contact No" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contact Email</span>
                                    <input type="text" class="form-control" value="" id="email" name="email" placeholder="Email" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Expected Delivery</span>
                                    <input type="date" class="form-control" id="expected_delivery" min="<?php echo dateToday(); ?>" name="expected_delivery">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-sm-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="purchase_order_num">PO Number</label>
                                    <select class="form-control" id="purchase_order_num" name="purchase_order_num">
                                        @foreach ($purchaseOrderNumbers as $code => $codeId)
                                            <option value="{{ $codeId }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text bg-primary text-white" onclick="loadModal(this, '4')">
                                        +
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- End of upper form filter design --}}
                    <div class="table-responsive">
                        <table class="table" style="min-width: 1200px;">
                            <thead>

                                <tr class="text-white table-dark">
                                    <!-- <th scope="col">S.No</th> -->
                                    <th scope="col" style="min-width: 13rem">Model Name</th>
                                    <th scope="col">Purch Qty</th>
                                    <th scope="col">Free Qty</th>
                                    <th scope="col">Total Qty</th>
                                    <th scope="col" style="white-space: nowrap">Disc Mode</th>
                                    <th scope="col">Discount </th>
                                    <th scope="col">Tax Vat </th>
                                    <th scope="col">Purch Price</th>
                                    <th scope="col">Sales Price</th>
                                    <th scope="col">Item Amount</th>
                                    <th scope="col" style="width: 6rem">Action</th>
                                </tr>
                            </thead>
                            <tbody id="po-table">
                                <tr class="item-row" id="item-row-1" tr-id="1">
                                    <!-- <th scope="row">1</th> -->
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control p-1 po_item_name" id="po_item_name-1" tr-id="1" name="items_id[1]" placeholder="Search Item">
                                            <input type="hidden" class="po_item_name_hidden" name="po_item_name_hidden[1]">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_purchase_qty" min="0" id="po_purchase_qty-1" tr-id="1" name="purchase_qty[1]" placeholder="Add Qty" size="1">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_free_qty" min="0" id="po_free_qty-1" tr-id="1" name="free_qty[1]" placeholder="Free item" size="1">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_total_qty" id="po_total_qty-1" tr-id="1" name="total_qty[1]" placeholder="Add Qty" size="1" readonly>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group mb-3">
                                            <select class="form-control po_discount_mode" id="po_discount_mode-1" tr-id="1" name="discount_mode_id[1]">
                                                <!-- <option value=''>--select--</option>     -->
                                                @foreach($discount_modes as $mode)
                                                <option value="{{$mode->id}}">{{$mode->name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_discount" id="po_discount-1" tr-id="1" min=0 max=100 name="discount[1]" placeholder="Discount" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="po_tax_vat-1" class="form-control p-1 po_tax_vat" placeholder="" name='tax_vat[1]' size="1" readonly>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_purchase_price" min="0" id="po_purchase_price-1" tr-id="1" name="purchase_price[1]" placeholder="Purchase Price" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_sales_price" min="0" id="po_sales_price-1" tr-id="1" name="sales_price[1]" placeholder="Sales Price" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_item_amount" id="po_item_amount-1" tr-id="1" name="item_amount[1]" placeholder="Item Amount" size="1" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <i type="button" class="fa fa-plus p-1 fireRepeaterClick" id="" aria-hidden="true"></i>
                                        <i type="button" class="fa fa-trash p-1 destroyRepeater d-none" id="itemDestroyer-1" aria-hidden="true" tr-id='1'></i>
                                        <i type="button" class="fa fa-history p-1 po_history_icon" tr-id="1" id="po_history_icon-1" data-toggle="modal" aria-hidden="true"></i>
                                    </td>
                                </tr>

                                <!-- Repeater Row -->
                                <tr class="item-row  d-none" id="repeater">
                                    <!-- <th scope="row">1</th> -->
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control p-1 po_item_name" placeholder="Search Item">
                                            <input type="hidden" class="po_item_name_hidden">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_purchase_qty" placeholder="Add Qty" size="1">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_free_qty" placeholder="Free item" size="1">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_total_qty" placeholder="Add Qty" size="1" readonly>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group mb-3">
                                            <select class="form-control po_discount_mode">
                                                @foreach($discount_modes as $mode)
                                                <option value="{{$mode->id}}">{{$mode->name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_discount" placeholder="Discount" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_tax_vat" placeholder="" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_purchase_price" placeholder="Purchase Price" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_sales_price" placeholder="Sales Price" size="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control p-1 po_item_amount" placeholder="Item Amount" size="1" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <i type="button" class="fa fa-plus p-1 fireRepeaterClick" id="" aria-hidden="true"></i>
                                        <i type="button" class="fa fa-trash p-1  destroyRepeater" aria-hidden="true"></i>
                                        <i type="button" class="fa fa-history p-1 po_history_icon" data-toggle="modal" aria-hidden="true"></i>
                                    </td>
                                </tr>
                                <!-- End of Repeater Row -->
                            </tbody>
                        </table>
                    </div>


                    {{-- End of item search design --}}
                    <hr>
                    <div class="main-container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Created By</span>
                                    <input type="text" class="form-control" id="created_by" value="{{$created_by}}" name="created_by" placeholder="Created By" readonly>
                                </div>
                                <!-- <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Approved By</span>
                                        <select class="form-select" id="" name="approved_by">
                                            <option value="1" selected>Ramesh</option>
                                            <option value="2">Suresh</option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Comments</span>
                                    <input type="text" class="form-control" id="comments" name="comments" placeholder="comments">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <th class="bg-primary text-white">Gross Amount</th>
                                            <td id="" class="" name=""><input id="po_gross_amount" name="gross_amt" class="form-control" readonly></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-primary text-white">Discount Amount</th>
                                            <td id="" name=""><input id="po_discount_amount" name="discount_amt" class="form-control" readonly></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-primary text-white">Tax Amount</th>
                                            <td id="" name=""><input id="po_tax_amount" name="tax_amt" class="form-control" readonly></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-primary text-white">Other Charges</th>
                                            <td id="" name="">
                                                <input type="number" id="po_other_charges" name="other_charges" class="form-control" placeholder="other charges">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-primary text-white">Net Amount</th>
                                            <td id="" name=""><input id="po_net_amount" name="net_amt" class="form-control" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- End of Price table --}}
                    <div class="main-container mb-4">
                        <div class="d-flex justify-content-end">
                            <input id="status" type="hidden" name="status_id" value="">
                            <button id="save" type="submit" class="btn btn-primary st_save me-1">Save</button>
                            @if(backpack_user()->is_po_approver)
                            <button id="approve" type="submit" class="btn btn-success st_approve">Approve</button>
                            @endif
                        </div>
                    </div>
                </form>
                @include('customAdmin.partial._inlineSequenceCreate')
                </div>
            </div>
        </div>
    </div>
      
@endsection






@section('after_scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <!-- validation cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        })
    </script>

<script>
    $(document).ready(function() {


        $('#menu-nav-link').click(function(){
            $('#drop-nav-link').toggleClass('show')
        })
        $('#po_purchase_qty-1').prop("disabled", true);
        $('#po_free_qty-1').prop("disabled", true);
        $('#po_discount_mode-1').prop("disabled", true);
        $('#po_discount-1').prop("disabled", true);
        $('#po_purchase_price-1').prop("disabled", true);
        $('#po_sales_price-1').prop("disabled", true);
        $('#po_tax_vat-1').prop("disabled", true);


        function setAllThings(rowId) {
            let purchaseQty = checkNan(parseInt($('#po_purchase_qty-' + rowId).val()));
            let freeQty = checkNan(parseInt($('#po_free_qty-' + rowId).val()));
            let totalQty = calcTotalQty(purchaseQty, freeQty)
            let purchasePrice = checkNan(parseFloat($('#po_purchase_price-' + rowId).val()));

            let discountMode = $("#po_discount_mode-" + rowId).val();
            let discount = checkNan(parseFloat($('#po_discount-' + rowId).val()));
            let itemDiscount = calcItemDiscount(purchaseQty, purchasePrice, discountMode, discount);
            let itemAmount = calcItemAmount(purchaseQty, purchasePrice, itemDiscount);


            //Everything setter
            $("#po_total_qty-" + rowId).val(totalQty);
            $('#po_item_amount-' + rowId).val(itemAmount.toFixed(2));
            calcBillAmount();
        }

        function checkNan(val) {
            return !isNaN(val) ? val : 0;
        }

        function calcBillAmount() {
            let grossAmt = 0;
            let totalDiscAmt = 0;
            let totalTaxAmt = 0;
            let otherCharges = parseFloat($("#po_other_charges").val());
            let netAmt = 0;

            $(".po_item_amount").each(function() {
                if ($(this).val()) {
                    let currRow = $(this).attr('tr-id');
                    let currItemAmt = checkNan(parseFloat($(this).val()));
                    let taxVat = checkNan(parseFloat($('#po_tax_vat-' + currRow).val()));

                    let purchaseQty = checkNan(parseInt($('#po_purchase_qty-' + currRow).val()));
                    let purchasePrice = checkNan(parseFloat($('#po_purchase_price-' + currRow).val()));
                    let discountMode = $("#po_discount_mode-" + currRow).val();
                    let discount = checkNan(parseFloat($('#po_discount-' + currRow).val()));
                    let itemWiswDiscount = calcItemDiscount(purchaseQty, purchasePrice, discountMode, discount);


                    grossAmt = grossAmt + parseInt($(this).val()) + itemWiswDiscount;
                    totalDiscAmt = totalDiscAmt + itemWiswDiscount;
                    totalTaxAmt = totalTaxAmt + currItemAmt * taxVat / 100;
                    // console.log("#######---CalcBillAmount---############")
                    // console.log("Row:", currRow, "Item Amount:", currItemAmt, "Tax:", taxVat, "Purchase Qty:", purchaseQty, "purchasePrice", purchasePrice, "D-Mode:", discountMode, "Discount:", discount, "Itemwise Disc:", itemWiswDiscount)



                }
            });

            if (!otherCharges) {
                otherCharges = 0;
            }
            netAmt = grossAmt - totalDiscAmt + totalTaxAmt + otherCharges;

            $('#po_gross_amount').val(grossAmt.toFixed(2));
            $('#po_discount_amount').val(totalDiscAmt.toFixed(2));
            $('#po_tax_amount').val(totalTaxAmt.toFixed(2));
            $('#po_net_amount').val(netAmt.toFixed(2));
        }


        function calcTotalQty(purchaseQty, freeQty) {
            if (!freeQty) {
                freeQty = 0;
            }
            if (!purchaseQty) {
                purchaseQty = 0;
            }
            return purchaseQty + freeQty;
        }

        function calcItemDiscount(purchaseQty, purchasePrice, discountMode, discount) {

            if (!purchaseQty || !purchasePrice || discountMode === '0' || !discount) {
                return 0;
            }

            let itemAmount = purchaseQty * purchasePrice;
            if (discountMode === '1') {
                return discount * itemAmount / 100;
            }
            if (discountMode === '2') {
                return discount;
            }
        }

        function calcItemAmount(purchaseQty, purchasePrice, itemDiscount) {
            if (!purchaseQty || !purchasePrice) {
                return 0;
            }

            return purchaseQty * purchasePrice - itemDiscount;
        }


        //Events
        $('.po_purchase_qty').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $('.po_free_qty').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $('.po_purchase_price').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $('.po_discount_mode').change(function() {
            let rowId = $(this).attr('tr-id');

            let discountMode = $("#po_discount_mode-" + rowId).val();

            if (discountMode === '2') {
                $("#po_discount-" + rowId).removeAttr("max");
                // console.log("NRS is selected")
                // $("#po_discount-" + rowId).val();


            } else {
                // console.log("% is selected")

                $("#po_discount-" + rowId).attr({
                    "max": 100,
                });
            }
            setAllThings(rowId);
        });

        $('.po_discount').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $("#po_other_charges").keyup(function() {
            calcBillAmount();
        });

        //Other Scripts
        $(".destroyRepeater").click(function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let tr = this.parentNode.parentNode;

                    let rowId = $(this).attr('tr-id');
                    let itemId = $("#po_item_name-" + rowId).attr('item-id')
                    let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                    listOfItems.splice(indxOfItem, 1);
                    // console.log("Current index:", $(this).attr('tr-id'));






                    indexCntr = counterArray.indexOf(parseInt(this.getAttribute('tr-id')));

                    tr.remove();
                    counterArray.splice(indexCntr, 1);
                    // console.log("index::", indexCntr, "ROW ARRAY:", counterArray);
                    // repeaterCounter--;
                    // console.log(repeaterCounter, 'destroy count')
                    if (counterArray.length == 1) {
                        // console.log('1 item in array', counterArray[0]);
                        // console.log(counterArray[0], "counter0")
                        $('#itemDestroyer-' + counterArray[0]).addClass('d-none');
                    }

                }
            })
        });

    });
</script>

<!-- new script -->
<script>
    var temp_item_name='';
    let repeaterCounter = 1;
    let listOfItems = [];
    let counterArray = [1];

    let availableTags = [{
        'id': '',
        'text': 'Search an item'
    }];
    let all_items = '<?php echo json_encode($item_lists) ?>';
    JSON.parse(all_items).forEach(function(item,index) {
        // debugger;
        availableTags.push({
            'id': item.id,
            'label': item.code + ' : ' + item.name
            // 'label': item.item_entity[index].code + ' : ' + item.item_entity[index].name
        });
        // debugger;
    });

    //For autocomplete item search

    // $('.po_item_name').change(function(){
    //     let parentTr=$(this).parent().parent().parent()[0];
    //     $('#po_tax_vat-1')[0].reset();
    //     parentTr.reset();
    //     debugger;
    // })
    // $('.po_item_name').focusin(function(){
    //     temp_item_name=$(this).val();
    // })
    // $('.po_item_name').focusout(function(){
    //     if($(this).val()===''){
    //         $(this).val(temp_item_name);
    //     }
    // })
    $("#po_item_name-1").autocomplete({
        source: availableTags,
        minLength: 1,
        select: function(event, ui) {
            let dataCntr = $(this).attr('tr-id');

            let hidden_id=parseInt($('#po_item_name-'+dataCntr).attr('item-id'))
                // console.log(hidden_id,ui.item.id,$('#po_item_name-'+dataCntr))
                if(ui.item.id===hidden_id){
                    return;
                }
                else{
                    let rowId = $(this).attr('tr-id');
                    let itemId = $("#po_item_name-" + rowId).attr('item-id')
                    let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                    if(indxOfItem !==-1){

                        listOfItems.splice(indxOfItem, 1);
                        // console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                    }

                }

            let present = checkIfItemExist(ui.item.id);
            if (present) {
                Swal.fire({
                    title: 'Item Already Exits !',
                    confirmButtonText: 'OK',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $("#po_item_name-" + dataCntr).val('');


                        return;
                    }
                })
            } else {
                let itemStock = $("#po_item_name-1");
                itemStock.next().attr('name', 'po_item_name_hidden[1]').val(ui.item.id);
                $("#po_history_icon-1").attr('item-id', ui.item.id);
                $("#po_item_name-1").attr('item-id', ui.item.id);
                listOfItems.push(ui.item.id)
                // console.log("Current Items:", listOfItems);
                getStockItemDetails(ui.item.id, 1);
                enableFields(dataCntr);
            }

        },

    });


    $(document).on('keydown', '.fireRepeater', function(e) {
        if (e.keyCode != 13) return;
        repeater();
    });

    $(document).on('click', '.fireRepeaterClick', function(e) {
        repeater();
    });

    function checkIfItemExist(itemId) {
        let idOfItemSelected = itemId;
        let indexOfItemInArray = listOfItems.indexOf(idOfItemSelected);

        if (indexOfItemInArray !== -1) {
            // console.log("PRESENT")
            // console.log("Item already selected");
            return true
        }
        // console.log(" Not PRESENT")
        return false;
    }

    function getLastArrayData() {
        return counterArray[counterArray.length - 1];
    }



    function repeater() {
        let tr = $('#repeater').clone(true);
        tr.removeAttr('id');
        tr.removeAttr('class');
        tr.children(':first').children(':first').children(':first').addClass('customSelect2');
        setIdToRepeater(getLastArrayData() + 1, tr);
        $('#po-table').append(tr);

        counterArray.push(getLastArrayData() + 1);
        // console.log("ROW ARRAY:", counterArray);


        $("#po_item_name-" + getLastArrayData()).autocomplete({
            source: availableTags,
            minLength: 1,
            select: function(event, ui) {
                let dataCntr = this.getAttribute('tr-id');
                let itemStock = $("#po_item_name-" + dataCntr)
                let hidden_id=parseInt($('#po_item_name-'+dataCntr).attr('item-id'))

                if(ui.item.id===hidden_id){
                    return;
                }
                else{
                    let rowId = $(this).attr('tr-id');
                    let itemId = $("#po_item_name-" + rowId).attr('item-id')
                    let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                    if(indxOfItem !==-1){

                        listOfItems.splice(indxOfItem, 1);
                        // console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                    }

                }

                    let present = checkIfItemExist(ui.item.id,dataCntr);
                    if (present) {
                        Swal.fire({
                            title: 'Item Already Exits !',
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $("#po_item_name-" + dataCntr).val('');
                                // console.log("exists already")
                                return;
                            }
                        })
                    } else {
                        listOfItems.push(ui.item.id)
                    // console.log("LISSST:",listOfItems)

                        itemStock.next().attr('name', 'po_item_name_hidden[' + dataCntr + ']').val(ui.item.id);
                        $("#po_history_icon-" + dataCntr).attr('item-id', ui.item.id);
                        $("#po_item_name-" + dataCntr).attr('item-id', ui.item.id);
                        // console.log("Ok Current Items:", listOfItems);
                        getStockItemDetails(ui.item.id, dataCntr);
                        enableFields(dataCntr);

                    }



                // console.log("Current Items:", listOfItems);

                // getStockItemDetails(ui.item.id, dataCntr);
                // enableFields(dataCntr);

            },
        });

        if (counterArray.length > 1) {
            if ($('#itemDestroyer-1').hasClass('d-none')) {
                $('#itemDestroyer-1').removeClass('d-none')
            }
        }
        if (counterArray.length == 2) {
            $('#itemDestroyer-' + counterArray[0]).removeClass('d-none')
        }
        // console.log(counterArray, "ca");
    }

    function setIdToRepeater(cntr, cloneTr) {
        let classArr = ['po_item_name', 'po_purchase_qty', 'po_free_qty', 'po_total_qty', 'po_discount_mode', 'po_discount', 'po_tax_vat', 'po_purchase_price', 'po_sales_price', 'po_item_amount', 'destroyRepeater'];
        let trDBfields = ['items_id', 'purchase_qty', 'free_qty', 'total_qty', 'discount_mode_id', 'discount', 'tax_vat', 'purchase_price', 'sales_price', 'item_amount'];
        cloneTr.children(':last').children('.destroyRepeater').attr('id', 'itemDestroyer-' + cntr).attr('tr-id', cntr);
        cloneTr.children(':last').children('.po_history_icon').attr('id', 'po_history_icon-' + cntr).attr('tr-id', cntr);
        cloneTr.children(':first').find('input').attr('id', 'po_item_name-' + cntr).attr('tr-id', cntr).attr('name', 'items_id[' + [cntr] + ']');

        for (let i = 1; i < 11; i++) {
            let n = i + 1;
            attr = cloneTr.children(':nth-child(' + n + ')').attr('class');
            if (attr == undefined) {
                cloneTr.children(':nth-child(' + n + ')').children('.input-group').children('.' + classArr[i]).attr('id', classArr[i] + '-' + cntr).attr('tr-id', cntr).attr('name', trDBfields[i] + '[' + cntr + ']').prop("disabled", true).prop('min', '0');
            } else {
                cloneTr.children(':nth-child(' + n + ')').attr('id', classArr[i] + '-' + cntr).attr('tr-id', cntr);
            }
        }
    }

    $("#supplier").change(function() {
        let supplier_id = $(this).find(":selected").val();
        if (supplier_id) {
            // get contact details of requested store[make api]
            getContactDetails(supplier_id, flag = "supplier");
        }

    });
    
    // $("#requested_store").change(function() {
    //     let requested_store_id = $(this).find(":selected").val();
    //     // console.log("STORE:::::", requested_store_id);
    //     if (requested_store_id) {
    //         // get contact details of requested store[make api]
    //         getContactDetails(requested_store_id, flag = "store");

    //     }
    // });


    function getContactDetails(id, flag) {

        let url = '{{ route("custom.contact-details",":id") }}'
        url = url.replace(':id', id);
        $.get(url, {
            flag: flag
        }).then(function(response) {

            // console.log("CONTACT: ", response);
            $("#phone").val(response.phone)
            $("#email").val(response.email)
        })
    }

    function getStockItemDetails(itemId, cntr) {
        let url = '{{ route("custom.po-details", ":id") }}'
        url = url.replace(':id', itemId);
        $.get(url).then(function(response) {

            $('#po_tax_vat-' + cntr).val(response.taxRate);
        })
    }


    // validation script
    $('#po_form').on('submit', function() {
        $('.po_item_amount').prop("disabled", false);
        $('.po_tax_vat').prop("disabled", false);
        $('.po_total_qty').prop("disabled", false);
        $('.po_purchase_qty').prop("disabled", false);
        $('.po_free_qty').prop("disabled", false);
        $('.po_discount_mode').prop("disabled", false);
        $('.po_discount').prop("disabled", false);
        $('.po_purchase_price').prop("disabled", false);
        $('.po_sales_price ').prop("disabled", false);
        // debugger;
        $('.po_item_name').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Field  required",
                }
            });
        });

    });

    $('#po_form').on('submit', function(event) {

        $.each(counterArray, function(index, value) {
            $('#po_item_name-' + value).rules("add", {
                required: true,
                messages: {
                    required: "Field  required",
                }
            });

            $('#po_purchase_qty-' + value).rules("add", {
                required: true,
                number: true,
                messages: {
                    required: "Field Required",
                    number: 'Field must be a number'
                }
            });
            $('#po_type').rules("add", {
                required: true,
                messages: {
                    required: "Field Required",
                }
            });

            // console.log("dfhhjfjsfd",)
            if ($("#po_type").find(":selected").val() === '1') {
                // console.log("Regular")
                $('#supplier').rules("add", {
                    required: true,
                    messages: {
                        required: "Field Required",
                    }
                });
            };
            // if ($("#po_type").find(":selected").val() === '2') {
            //     // console.log("STOCk transfer")
            //     $('#requested_store').rules("add", {
            //         required: true,
            //         messages: {
            //             required: "Field Required",
            //         }
            //     });
            // };



        });

    });


    $('#po_form').validate({
        submitHandler: function(form) {
            let val = $('#b1').val();
            let val2 = $('#b2').val();
            // console.log(val)
            // console.log(val2)
            Swal.fire({
                title: 'Are you sure?',

                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((confirmResponse) => {
                if (confirmResponse.isConfirmed) {

                    let data = $('#po_form').serialize();
                    let url = form.action;
                    // console.log(url, "jhfhjsdfsdfsfjhsfjsd")
                    // debugger;
                    axios.post(url, data).then((response) => {
                        if(response.data['status'] == 'failed'){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href="">'+ response.data['message'] +'</a>'
                            })
                           
                        }else{
                            Swal.fire({
                                icon: 'success',
                                text: response.data['message'],
                            })
                            document.location = response.data['route'];
                        }
                        // INVENTORY.inventoryLoading(false, $('#po_form'));
                    }, (error) => {
                        // swal("Error !", error.response.data.message, "error")
                        // INVENTORY.inventoryLoading(false, $('#po_form'));
                    });


                }
            });
        }
    });

    function enableFields(rowId) {
        $('#po_purchase_qty-' + rowId).prop("disabled", false);
        $('#po_free_qty-' + rowId).prop("disabled", false);
        $('#po_discount_mode-' + rowId).prop("disabled", false);
        $('#po_discount-' + rowId).prop("disabled", false);
        $('#po_purchase_price-' + rowId).prop("disabled", false);
        $('#po_sales_price-' + rowId).prop("disabled", false);
        $('#po_tax_vat-1').prop("disabled", false);
    }

    $('#save').on('click', function() {
        $('#status').val({{ SupStatus::CREATED}});
        // console.log($('#status').val(), "yyyyyyyyyyyy")
        // debugger;
    });
    $('#approve').on('click', function() {
        $('#status').val({{SupStatus::APPROVED}});
        // console.log($('#status').val())
        // debugger;
    });



    //     if (val === '2') {


    //         $("#requested_store").attr("disabled", false)
    //         $("#supplier").attr("disabled", true)
    //         //remove my store
    //         const indexOfObject = requested_store.findIndex(object => {
    //             return object.id === parseInt($("#store").find(":selected").val());
    //         });
    //         requested_store.splice(indexOfObject, 1);
    //         // console.log("Requested:", requested_store);

    //         // set requested_store_options

    //         for (let i = 0; i < requested_store.length; i++) {
    //             $("#requested_store").append($("<option>").val(requested_store[i]['id']).html(requested_store[i]['name_en']));
    //         }
    //     }
    // });
  

    // end of validation script
</script>

<!-- script for modal -->
<script>
    // function resetRowData(rowId){
    //     console.log("iam in")
    //     $('#po_purchase_qty-'+rowId).val(''.trigger('change'));
    //     $('#po_free_qty-'+rowId).val('').trigger('change');
    //     $('#po_total_qty-'+rowId).val('').trigger('change');
    //     // $('#po_discount_mode-'+rowId).val('').trigger('change');
    //     $('#po_discount-'+rowId).val('').trigger('change');
    //     // $('#po_tax_vat-'+rowId).trigger('change');
    //     $('#po_purchase_price-'+rowId).val('').trigger('change');
    //     $('#po_sales_price-'+rowId).val('').trigger('change');
    //     $('#po_item_amount-'+rowId).val('').trigger('change');
    // }

    // $('.po_item_name').change(function(){
    //     let rowId=$(this).attr('tr-id');
    //     debugger;
    //     resetRowData(rowId);
    // })

    $(document).on("click", ".po_history_icon", function() {
        let currRow = $(this).attr('tr-id');
        let item_name = $('#po_item_name-' + currRow).val();
        let item_id = $('#po_history_icon-' + currRow).attr('item-id');

        $("#purchase_order_modal").attr('item-id', item_id);
        $('#item_name_modal').html(item_name);

        let po_history_from = $('#po_history_from').val();
        let po_history_to = $('#po_history_to').val();
        // console.log("history:::", item_id, po_history_from, po_history_to)
        getPurchaseItemHistoryDetails(item_id, po_history_from, po_history_to);
        // console.log("history clicked")

        $("#purchase_order_modal").modal('show');

    });
    $("#po_history_fetch_btn").click(function() {
        let itemId = $('#purchase_order_modal').attr("item-id");
        let po_history_from = $('#po_history_from').val();
        let po_history_to = $('#po_history_to').val();

        getPurchaseItemHistoryDetails(itemId, po_history_from, po_history_to);


    })

    function getPurchaseItemHistoryDetails(itemId, po_history_from, po_history_to) {
        let url = '{{ route("custom.poh-details", [":id",":to",":from"] ) }}'
        url = url.replace(':id', itemId);
        url = url.replace(':to', po_history_from);
        url = url.replace(':from', po_history_to);

        console.log(url, "Test URL");

        $.get(url).then(function(response) {
            $("#modal_table_content").html(response);
        })
    }
    // console.log("Lat ROW ARRAY:", counterArray);
</script>

@endsection
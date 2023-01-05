
@php
$currentURL = URL::current();
use App\Models\SupStatus

@endphp

@extends(backpack_view('blank'))

@section('header')

    <section class="main-container">
        <h2>
            <span class="text-capitalize">Purchase Returns</span>

            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{url('css/style.css')}}">

<div class="modal fade" id="add_stock_item_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" class="barcode_item_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode to Add Qty in "<span id="barcodeItemName"></span>"</h5>
            </div>
            <form id="barcodeForm">

                <div class="modal-body">
                    <select id="barcodeScanner" name="barcode_details[]" class="form-control" multiple="multiple" style="width: 100%;height: auto;">
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="barcodeSave" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>




<form action='{{backpack_url()."/purchase-return"}}' role="form" method="POST" id="pr_form">




    <div class="main-container">
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="disc_type">Store Name</label>
                    @if(isset($grn))
                    <select class="form-select" id="store_id" disabled name="store_id">
                        <option value="{{$grn->store_id}}">{{$grn->storeEntity->name_en}}</option>
                    </select>
                    @else
                    <select class="form-select" id="store_id" name="store_id">
                        @foreach($stores as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    @endif

                </div>
            </div>


            <div class="col-md-4">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="disc_type">Supplier</label>
                    @if(isset($grn))
                    <select class="form-select" id="supplier_id" name="supplier_id" disabled>
                        <option value="{{$grn->supplier_id}}" selected>{{$grn->supplierEntity->name_en}}</option>
                    </select>
                    @else
                    <select class="form-select" id="supplier_id" name="supplier_id">
                        @foreach($suppliers as $key=>$value)
                        <option value="{{$value}}">{{$key}}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>


            @if(!request()->is('admin/purchase-return/create') )

            <input type="hidden" name="grn_id" value="{{$grn->grn_no}}">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="disc_type">Grn/Invoice</label>
                    <select class="form-select" id="invoice_no" name="invoice_no" disabled>
                        <option value="{{$grn->invoice_no}}" selected>{{$grn->invoice_no}}</option>
                    </select>
                </div>
            </div>
            @endif

            <!-- remove -->
            <!-- <div class="col-md-4">
            <div class="input-group mb-3">
                <label class="input-group-text">Return Number</label>
                <select class="form-select" id="gender">
                    <option value="1" selected></option>
                </select>
            </div>
        </div> -->

            <div class="col-md-4">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="disc_type">Return Reason</label>
                    <select class="form-select" id="gender" name="return_reason_id">
                        @foreach($reasons as $reason)
                        <option value="{{$reason->id}}">{{$reason->name_en}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if(!request()->is('admin/purchase-return/create') )
            <div class="col-md-4">
                <div class="input-group mb-2">
                    <label for="return_type">Is Full Return</label>
                    <input type="checkbox" name="return_type" id="return_type" class="mt-2 mx-2">
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- End of upper form filter design? --}}
    <div class="table-responsive">
        <table class="table" style="min-width: 1672px;">
            <thead>
                <tr class="text-white" style="background-color: #192840;">
                    <th scope="col">Product Name</th>
                    @if(!request()->is('admin/purchase-return/create') )

                    <th scope="col">Purch\Invoice Qty</th>
                    <th scope="col">Free Qty</th>
                    <th scope="col">Total Qty</th>
                    @endif
                    <th scope="col">Batch No</th>
                    <th scope="col">Batch Qty</th>
                    <th scope="col">Return Qty</th>
                    <th scope="col">Disc Mode</th>
                    <th scope="col">Discount </th>
                    <th scope="col">Tax Vat </th>
                    <th scope="col">Purch Price</th>
                    <th scope="col">Amount</th>
                    @if(request()->is('admin/purchase-return/create') )
                    <th scope="col">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody id="pr-table">

                @if(!request()->is('admin/purchase-return/create') )
                @foreach($grn_items as $key=>$item)
                @php $key++ @endphp

                <tr>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 ItemName" id="ItemName-{{$key}}" tr-id="{{$key}}" name="ItemName[{{$key}}]" value="{{$item->itemEntity->name}}" readonly>
                            <input type="hidden" class="ItemName_hidden" name="ItemName_hidden[{{$key}}]" value="{{$item->mst_items_id}}">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 PurchaseQty" id="PurchaseQty-{{$key}}" name="PurchaseQty[{{$key}}]" tr-id="{{$key}}" placeholder="P-Qty" size="1" value="{{$item->purchase_qty??$item->invoice_qty}}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 FreeQty" id="FreeQty-{{$key}}" tr-id="{{$key}}" name="FreeQty[{{$key}}]" placeholder="F-Qty" size="1" value={{$item->free_qty}}>
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 TotalQty" id="TotalQty-{{$key}}" tr-id="{{$key}}" name="TotalQty[{{$key}}]" placeholder="T-Qty" size="1" value={{$item->total_qty}}>
                        </div>
                    </td>

                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select BatchNo" id="BatchNo-{{$key}}" tr-id="{{$key}}" name="BatchNo[{{$key}}]">
                                <option value="{{$item->batch_no}}" selected>{{$item->batch_no}}</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 BatchQty" id="BatchQty-{{$key}}" tr-id="{{$key}}" name="BatchQty[{{$key}}]" placeholder="B-Qty" size="1" value="{{$item->total_qty}}">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                            <input type="number" min='1' class="form-control p-1 ReturnQty" id="ReturnQty-{{$key}}" name="ReturnQty[{{$key}}]" tr-id="{{$key}}" max={{$item->total_qty}} placeholder="R-Qty" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select DiscountMode" id="DiscountMode-{{$key}}" tr-id="{{$key}}" name="DiscountMode[{{$key}}]">
                                <option value="1" selected>{{$item->discountModeEntity->name_en}}</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 Discount" id="Discount-{{$key}}" tr-id="{{$key}}" name="Discount[{{$key}}]" placeholder="Discount" size="1" value="{{$item->discount}}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control p-1 TaxVat" id="TaxVat-{{$key}}" tr-id="{{$key}}" name="TaxVat[{{$key}}]" placeholder="Tax/vat" size="1" value="{{$item->tax_vat}}">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1  PurchasePrice" id="PurchasePrice-{{$key}}" tr-id="{{$key}}" name="PurchasePrice[{{$key}}]" placeholder="Purchase Price" size="1" value="{{$item->purchase_price}}">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 ItemAmount" id="ItemAmount-{{$key}}" name="ItemAmount[{{$key}}]" tr-id="{{$key}}" placeholder="Item Amount" size="1">
                        </div>
                    </td>


                </tr>
                @endforeach
                @else
                <!-- PR from PR -->
                <tr>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 ItemName" name="ItemName[1]" id="ItemName-1" tr-id="1">
                            <input type="hidden" class="ItemName_hidden" id="ItemName_hidden-1" tr-id="1" name="ItemName_hidden[1]">
                        </div>
                    </td>

                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select BatchNo" id="BatchNo-1" name="BatchNo[1]" tr-id="1">

                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 BatchQty" id="BatchQty-1" name="BatchQty[1]" tr-id="1" placeholder="B-Qty" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                        @if($multiple_barcode)
                            <button type="button" class="btn btn-primary btn-sm barcodeScan" data-toggle="modal" data-target=" #add_stock_item_modal">+</button>
                        @endif
                            <input type="text" class="form-control p-1 ReturnQty" id="ReturnQty-1" name="ReturnQty[1]" tr-id="1" placeholder="R-Qty" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select DiscountMode" id="DiscountMode-1" name="DiscountMode[1]" tr-id="1">
                                <option value="" selected></option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 Discount" id="Discount-1" name="Discount[1]" tr-id="1" placeholder="Discount" size="1">
                        </div>
                    </td>
                    <td>
                        <div class="input-group mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control p-1 TaxVat" id="TaxVat-1" name="TaxVat[1]" tr-id="1" placeholder="Tax/vat" size="1">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1  PurchasePrice" id="PurchasePrice-1" name="PurchasePrice[1]" tr-id="1" placeholder="Purchase Price" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 ItemAmount" id="ItemAmount-1" tr-id="1" name="ItemAmount[1]" placeholder="Item Amount" size="1">
                        </div>
                    </td>


                    <td>
                        <i type="button" class="fa fa-plus p-1 fireRepeaterClick" id="" aria-hidden="true"></i>
                        <i type="button" class="fa fa-trash p-1  destroyRepeater d-none" id="itemDestroyer-1" aria-hidden="true"></i>
                    </td>

                </tr>
                <!-- Repeater -->
                <tr id="repeater" class="d-none">
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 ItemName">
                            <input type="hidden" class="ItemName_hidden">

                        </div>
                    </td>

                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select BatchNo">
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 BatchQty" placeholder="B-Qty" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                        @if($multiple_barcode)
                            <button type="button" class="btn btn-primary btn-sm barcodeScan" data-toggle="modal" data-target=" #add_stock_item_modal">+</button>
                        @endif
                            <input type="text" class="form-control p-1 ReturnQty" placeholder="R-Qty" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group mb-3">
                            <select class="form-select DiscountMode">
                                <option value="1" selected>%</option>
                                <option value="2">NRS</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 Discount" placeholder="Discount" size="1">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">

                            <input type="text" class="form-control p-1 TaxVat" placeholder="Tax/vat" size="1">

                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1  PurchasePrice" placeholder="Purchase Price" size="1">
                        </div>
                    </td>

                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 ItemAmount" placeholder="Item Amount" size="1">
                        </div>
                    </td>


                    <td>
                        <i type="button" class="fa fa-plus p-1 fireRepeaterClick" id="" aria-hidden="true"></i>
                        <i type="button" class="fa fa-trash p-1  destroyRepeater" aria-hidden="true"></i>
                    </td>

                </tr>
                <!-- End of Repeater -->
                <!-- end of PR from PR -->
                @endif





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
                    <input type="text" class="form-control" placeholder="Created By" value="{{isset($grn)?$grn->createdByEntity->name:backpack_user()->name}}">
                </div>


                <div class="input-group mb-3">
                    <span class="input-group-text">Comments</span>
                    <input type="text" class="form-control" name="comments" placeholder="comments">
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-primary text-white">Gross Total</th>
                            <td id="" class="" name=""><input id="gross_amount" name="gross_amt" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th class="bg-primary text-white">Total Discount</th>
                            <td id="" class="" name=""><input id="total_discount" name="discount_amt" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th class="bg-primary text-white">Taxable Amount</th>
                            <td id="" class="" name=""><input id="taxable_amount" name="taxable_amount" class="form-control" readonly></td>

                        </tr>
                        <tr>
                            <th class="bg-primary text-white">Tax Total</th>
                            <td id="" class="" name=""><input id="tax_total" name="tax_amt" class="form-control" readonly></td>

                        </tr>
                        <tr>
                            <th class="bg-primary text-white">Other Charges</th>
                            <td id="" class="" name=""><input id="other_charge" name="other_charges" class="form-control"></td>
                        </tr>
                        <tr>
                            <th class="bg-primary text-white">Net Amount</th>
                            <td id="" class="" name=""><input id="net_amount" name="net_amt" class="form-control" readonly></td>

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
            <!-- <button class="btn btn-primary me-1" id="save" type="submit">Save</button> -->
            <button class="btn btn-success me-1" id="approve" type="submit">Approve Return</button>
            <a href="{{backpack_url('purchase-return')}}" class="btn btn-danger" type="submit">Cancel</a>
        </div>
    </div>

</form>
@endsection
@section('after_scripts')

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>



<!-- validation cdn -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
    $(document).on('show.bs.modal', '.modal', function() {
        $(this).appendTo('body');
    })
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
<script>
    $(document).ready(function() {
        let pr_type = '<?php echo isset($flag) ? $flag : '' ?>';

        let counterArray = [1];
        let listOfItems = [];

        let availableTags = [{
            'id': '',
            'text': 'Search an item'
        }];

        <?php
            // dd($item_lists);
        ?>
        let all_items = '<?php echo isset($item_lists) ? json_encode($item_lists) : "[]" ?>';
        JSON.parse(all_items).forEach(function(item) {

            availableTags.push({
                'id': item.id,
                // 'label':'<h1>'+ item.code + ' : ' + item.name+':'+item.qty +'</h1>'
                'label':`${item.code} : ${item.name} :  `
            });
        });








        $('.PurchaseQty').prop('readonly', true)
        $('.FreeQty').prop('readonly', true)
        $('.TotalQty').prop('readonly', true)
        $('.BatchNo').prop('disabled', true)
        $('.BatchQty').prop('readonly', true)
        $('.ReturnQty').prop('readonly', true)
        $('.DiscountMode').prop('disabled', true)
        $('.Discount').prop('readonly', true)
        $('.TaxVat').prop('readonly', true)
        $('.PurchasePrice').prop('readonly', true)
        $('.ItemAmount').prop('readonly', true)

        $("#ItemName-1").autocomplete({
            source: availableTags,
            minLength: 1,
            select: function(event, ui) {
                let dataCntr = $(this).attr('tr-id');

                let hidden_id = parseInt($('#ItemName-' + dataCntr).attr('item-id'))
                // console.log(hidden_id,ui.item.id,$('#ItemName-'+dataCntr))
                if (ui.item.id === hidden_id) {
                    return;
                } else {
                    let rowId = $(this).attr('tr-id');
                    let itemId = $("#ItemName-" + rowId).attr('item-id')
                    let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                    if (indxOfItem !== -1) {

                        listOfItems.splice(indxOfItem, 1);
                        // console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                    }

                }

                // let present = checkIfItemExist(ui.item.id);
                let present = false;

                if (present) {
                    Swal.fire({
                        title: 'Item Already Exits !',
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            $("#ItemName-" + dataCntr).val('');
                            return;
                        }
                    })
                } else {
                    let itemStock = $("#ItemName-1");
                    itemStock.next().attr('name', 'ItemName_hidden[1]').val(ui.item.id);

                    $("#ItemName-1").attr('item-id', ui.item.id);
                    listOfItems.push(ui.item.id)
                    getBatchNo(ui.item.id, dataCntr);
            // $('.BatchNo').on('click');

                    $('#BatchNo-1').attr('disabled', false);
                }

            },

        });


        if (pr_type === '1') {
            $('.ReturnQty').prop('readonly', false)
        }

        $('.ReturnQty,#other_charge').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });

        $('#return_type').change(function() {
            if ($(this).is(':checked')) {
                $('.ReturnQty').prop('readonly', true)
                $(".ReturnQty").each(function() {
                    let rowId = $(this).attr('tr-id')
                    $(this).val($('#TotalQty-' + rowId).val()).trigger('keyup')
                })

            }
            if (!$(this).is(':checked')) {
                $('.ReturnQty').prop('readonly', false)
                $('.ReturnQty').val('').trigger('keyup');
            }
        })

        function setAllThings(rowId) {

            let PurchaseQty = checkNan(parseInt($('#PurchaseQty-' + rowId).val()));
            let PurchasePrice = checkNan(parseInt($('#PurchasePrice-' + rowId).val()));
            let ReturnQty = checkNan(parseInt($('#ReturnQty-' + rowId).val()));

            let DiscountMode = $("#DiscountMode-" + rowId).val();
            let Discount = checkNan(parseFloat($('#Discount-' + rowId).val()));
            let TaxVat = checkNan(parseFloat($('#TaxVat-' + rowId).val()));

            let itemDiscount = calcItemDiscount(ReturnQty, PurchasePrice, DiscountMode, Discount);

            let itemAmount = calcItemAmount(ReturnQty, PurchasePrice, itemDiscount);


            //Everything setter

            $('#ItemAmount-' + rowId).val(itemAmount.toFixed(2));
            calcBillAmount();
        }

        function checkNan(val) {
            return !isNaN(val) ? val : 0;
        }

        function calcItemDiscount(ReturnQty, PurchasePrice, DiscountMode, Discount) {
            if (!ReturnQty || !PurchasePrice || DiscountMode === '0' || !Discount) {
                return 0;
            }

            let itemAmount = ReturnQty * PurchasePrice;
            if (DiscountMode === '1') {
                return Discount * itemAmount / 100;
            }
            if (DiscountMode === '2') {
                return Discount;
            }
        }

        function calcItemAmount(ReturnQty, PurchasePrice, itemDiscount) {
            if (!ReturnQty || !PurchasePrice) {
                return 0;
            }

            return ReturnQty * PurchasePrice - itemDiscount;
        }

        function calcBillAmount() {
            let grossAmt = 0;
            let totalDiscAmt = 0;
            let taxableAmt = 0;
            let totalTaxAmt = 0;
            let otherCharges = parseFloat($("#other_charge").val());
            let netAmt = 0;

            $(".ItemAmount").each(function() {
                if ($(this).val()) {
                    let currRow = $(this).attr('tr-id');
                    let currItemAmt = checkNan(parseFloat($(this).val()));
                    let taxVat = checkNan(parseFloat($('#TaxVat-' + currRow).val()));

                    let returnQty = checkNan(parseInt($('#ReturnQty-' + currRow).val()));

                    let purchasePrice = checkNan(parseFloat($('#PurchasePrice-' + currRow).val()));
                    let discountMode = $("#DiscountMode-" + currRow).val();
                    let discount = checkNan(parseFloat($('#Discount-' + currRow).val()));
                    let itemWiswDiscount = calcItemDiscount(returnQty, purchasePrice, discountMode, discount);


                    grossAmt = grossAmt + parseInt($(this).val()) + itemWiswDiscount;
                    totalDiscAmt = totalDiscAmt + itemWiswDiscount;
                    totalTaxAmt = totalTaxAmt + currItemAmt * taxVat / 100;
                    taxableAmt = taxableAmt + currItemAmt;
                    // console.log("#######---CalcBillAmount---############")
                    // console.log("Row:", currRow, "Item Amount:", currItemAmt, "Tax:", taxVat, "Purchase Qty:", purchaseQty, "purchasePrice", purchasePrice, "D-Mode:", discountMode, "Discount:", discount, "Itemwise Disc:", itemWiswDiscount)
                }
            });

            if (!otherCharges) {
                otherCharges = 0;
            }
            netAmt = grossAmt - totalDiscAmt + totalTaxAmt + otherCharges;

            $('#gross_amount').val(grossAmt.toFixed(2));
            $('#total_discount').val(totalDiscAmt.toFixed(2));
            $('#taxable_amount').val(taxableAmt.toFixed(2));
            $('#tax_total').val(totalTaxAmt.toFixed(2));
            $('#net_amount').val(netAmt.toFixed(2));
        }

        // function getBatchDetails(itemId, cntr) {
        //     let url = '{{ route("custom.po-details", ":id") }}'
        //     url = url.replace(':id', itemId);
        //     $.get(url).then(function(response) {

        //         $('#po_tax_vat-' + cntr).val(response.taxRate);
        //     })
        // }

        //script for repeater
        $(document).on('click', '.fireRepeaterClick', function(e) {
            repeater();
        });

        function repeater() {
            let tr = $('#repeater').clone(true);
            tr.removeAttr('id');
            tr.removeAttr('class');
            tr.children(':first').children(':first').children(':first').addClass('customSelect2');
            setIdToRepeater(getLastArrayData() + 1, tr);
            //Pause
            $('#pr-table').append(tr);

            counterArray.push(getLastArrayData() + 1);

            $("#ItemName-" + getLastArrayData()).autocomplete({
                source: availableTags,
                minLength: 1,
                select: function(event, ui) {
                    let dataCntr = this.getAttribute('tr-id');
                    let itemStock = $("#ItemName-" + dataCntr)
                    let hidden_id = parseInt($('#ItemName-' + dataCntr).attr('item-id'))

                    if (ui.item.id === hidden_id) {
                        return;
                    } else {
                        let rowId = $(this).attr('tr-id');
                        let itemId = $("#ItemName-" + rowId).attr('item-id')
                        let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                        if (indxOfItem !== -1) {

                            listOfItems.splice(indxOfItem, 1);
                            // console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                        }

                    }

                    // let present = checkIfItemExist(ui.item.id, dataCntr);
                    let present = false;
                    if (present) {
                        Swal.fire({
                            title: 'Item Already Exits !',
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $("#ItemName-" + dataCntr).val('');
                                // console.log("exists already")
                                return;
                            }
                        })
                    } else {
                        listOfItems.push(ui.item.id)
                        // console.log("LISSST:",listOfItems)

                        itemStock.next().attr('name', 'ItemName_hidden[' + dataCntr + ']').val(ui.item.id);
                        $("#ItemName-" + dataCntr).attr('item-id', ui.item.id);
                        getBatchNo(ui.item.id, dataCntr);

                        $('#BatchNo-' + dataCntr).attr('disabled', false);


                    }
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
        }

        function setIdToRepeater(cntr, cloneTr) {
            let classArr = ['ItemName', 'BatchNo', 'BatchQty', 'ReturnQty', 'DiscountMode', 'Discount', 'TaxVat', 'PurchasePrice', 'ItemAmount', 'destroyRepeater'];
            let trDBfields = ['ItemName', 'BatchNo', 'BatchQty', 'ReturnQty', 'DiscountMode', 'Discount', 'TaxVat', 'PurchasePrice', 'ItemAmount'];
            cloneTr.children(':last').children('.destroyRepeater').attr('id', 'itemDestroyer-' + cntr).attr('tr-id', cntr);
            // cloneTr.children(':last').children('.po_history_icon').attr('id', 'po_history_icon-' + cntr).attr('tr-id', cntr);
            cloneTr.children(':first').find('input').attr('id', 'ItemName-' + cntr).attr('tr-id', cntr).attr('name', 'ItemName[' + [cntr] + ']');
            cloneTr.children(':first').children('.ItemName_hidden').attr('id', 'ItemName_hidden-' + cntr).attr('tr-id', cntr).attr('name', 'ItemName_hidden[' + [cntr] + ']');

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

        function getLastArrayData() {
            return counterArray[counterArray.length - 1];
        }

        function checkIfItemExist(itemId) {
            let idOfItemSelected = itemId;
            let indexOfItemInArray = listOfItems.indexOf(idOfItemSelected);

            if (indexOfItemInArray !== -1) {

                return true
            }
            return false;
        }


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
                    let itemId = $("#ItemName-" + rowId).attr('item-id')
                    let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                    listOfItems.splice(indxOfItem, 1);
                    indexCntr = counterArray.indexOf(parseInt(this.getAttribute('tr-id')));
                    tr.remove();
                    counterArray.splice(indexCntr, 1);
                    if (counterArray.length == 1) {
                        $('#itemDestroyer-' + counterArray[0]).addClass('d-none');
                    }

                }
            })
        });

        $('#save').on('click', function() {
            let tt = $('#status').val({{SupStatus::CREATED}});

        });
        $('#approve').on('click', function() {
            let tt = $('#status').val({{SupStatus::APPROVED}});
        });

        // function atleastOneFieldRequired() {
        //     return $('.ReturnQty').filter(function() { return $(this).val(); }).length > 0;
        // }



        function atleastOneFieldRequired() {
            let filled_counter = 0;

            $('.ReturnQty').each(function() {
                if ($(this).val() !== '' && ($(this).val() > 0)) {
                    filled_counter++;
                }
            });

            if (filled_counter > 0) {
                return true
            } else {
                return false
            }
        }

        // var selectedBatchNo = [];
     
        $('.BatchNo').click(function(){
            console.log("click")
            $(this).children().prop("disabled",false)

            

            // $(this).children().removeClass("hidden")
           // event.preventDefault();
      
           var batchNumbers=[]
            let currRowId = $(this).attr('tr-id')
            let currItemId = $('#ItemName-' + currRowId).attr('item-id')
            // console.log("row:"+currRowId,"Item:"+currItemId)
            // $("#BatchNo-" + currRowId).empty();
            // $("#BatchNo-" + currRowId).append($("<option>").val("").html("--Select--"));
           

            // let url = '{{ route("custom.get-batch", ":id") }}';
            // url = url.replace(':id', currItemId);
            
            // $.get(url).then(function(response) {
                let selbtc=[]
                // batchNumbers=response.batchNumber;  
                
                
                
                selbtc=getSelectedBatchNo(currItemId)
                // debugger;
                // console.log("SELECTED BATCHS:",selbtc)

                
                // $("#BatchNo-"+currRowId+" option:selected").attr('disabled','disabled');
                
                
                // $("#BatchNo-"+currRowId+ "option").each(function()
                // {
                //     console.log($(this)[0].text())
                //     $( "#myselect option:selected" ).text();
                //     debugger;


                // });

                // $("#BatchNo-1 option").each(function() {
                   
                //     console.log(this.val)
                // });

            //     $('#BatchNo-'+currRowId +'option').each(function(index,element){
            //         debugger;
            //         if(selbtc.includes(element.value)){
            //             $(this).attr('disabled','disabled');
            //             // $(this).children("option:selected").val();
            //         }else{
            //             console.log(element.value)
            //         }
            //    });
            // debugger;
            console.log("****************************")
                $('#BatchNo-'+currRowId).children().each(function(index,element){
                    if(selbtc.includes(element.value)){
                        $(this).attr('disabled','disabled');
                        // console.log("Selected:"+element.value)
                        
                        // $(this).children("option:selected").val();
                    }else{
                        if(element.value !=='0')
                            console.log("Optios:"+element.value)
                    }
                    // debugger;
               });
               
                // for(i=0;i<batchNumbers.length;i++){
                //     if(selbtc.includes(batchNumbers[i]) && counterArray.length>1){
                //         console.log("matched:",batchNumbers[i])
                //     }
                //     else{
                //         $("#BatchNo-" + currRowId).append("<option class='optionTest' value=" + batchNumbers[i] + ">" + batchNumbers[i] + "</option>");
                //     }
                // }



            // })
        });

            function getBatchNo(currItemId, cntr) {
            $("#BatchNo-" + cntr).empty();
            $("#BatchNo-" + cntr).append($("<option selected disabled>").val("0").html("--Select--"));

            let url = '{{ route("custom.get-batch", ":id") }}';
            url = url.replace(':id', currItemId);
            $.get(url).then(function(response) {
                let responseData = response.batchNumber;
                console.log(responseData)
                for (i = 0; i < responseData.length; i++) {
                        $("#BatchNo-" + cntr).append("<option value=" + responseData[i] + ">" + responseData[i] + "</option>");
                }
            })
        }
            // console.log("FUN: ",currItemId,cntr)
        


         
        function getSelectedBatchNo(currItemId) {
            let selectedBatch=[];
           $('.BatchNo').each(function() {
               let cntr = $(this).attr('tr-id')
               let itemId=$('#ItemName-'+cntr).attr('item-id');
               let btc=$(this).find(":selected").val();
               if(itemId===currItemId && btc !=='0'){
                   selectedBatch.push(btc)
                }
                console.log("inside",cntr,itemId,btc)
            })
            console.log("From Parent:",currItemId,selectedBatch)
            return selectedBatch;
        }

        $(".BatchNo").change(function() {

            let cntr = $(this).attr('tr-id');
            let itemId = $('#ItemName-' + cntr).attr('item-id');
            let batchId = $('#BatchNo-' + cntr).val();
            $('#ReturnQty-' + cntr).val(0);
            $('#ReturnQty-' + cntr).prop("readonly", false);
            $('#ReturnQty-' + cntr).prop("disabled", false);

            calcBillAmount();
            if (batchId) {
                let url = '{{ route("custom.get-batch-item-detail", [":itemId",":batchId"]) }}';
                url = url.replace(':batchId', batchId);
                url = url.replace(':itemId', itemId);

                $.get(url).then(function(response) {
                    $('#BatchQty-' + cntr).val(response.batchDetail.batch_qty);
                    $('#PurchasePrice-' + cntr).val(response.batchDetail.batch_price);
                    $("#DiscountMode-" + cntr).append("<option value=" + response.itemDetails.discount_mode_id + " selected>" + response.itemDetails.discount_mode + "</option>");
                    $('#Discount-' + cntr).val(response.itemDetails.discount);
                    $('#TaxVat-' + cntr).val(response.itemDetails.tax_vat);

                })

            } else {
                $('#BatchQty-' + cntr).val(0);
                $('#PurchasePrice-' + cntr).val(0);
                $('#Discount-' + cntr).val(0);
                $('#TaxVat-' + cntr).val(0);
                $('#ReturnQty-' + cntr).prop("readonly", true);
            }
        })

        $('#pr_form').validate({
            submitHandler: function(form) {
                let val = $('#b1').val();
                let val2 = $('#b2').val();
                let isFilled = atleastOneFieldRequired();
                if (!isFilled) {
                    Swal.fire("Atleast One ReturnQty field is required");
                    return;
                }
                Swal.fire({
                    title: 'Are you sure?',

                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((confirmResponse) => {
                    if (confirmResponse.isConfirmed) {
                        $('.BatchNo').prop('disabled', false);
                        $('.BatchQty').prop('disabled', false);
                        $('.DiscountMode').prop('disabled', false);
                        $('.Discount').prop('disabled', false);
                        $('.TaxVat').prop('disabled', false);
                        $('.ItemAmount').prop('disabled', false);
                        $('.PurchasePrice').prop('disabled', false);
                        $('#invoice_no').prop('disabled', false);
                        $('#supplier_id').prop('disabled', false);
                        $('#store_id').prop('disabled', false);
                        $('option').prop('disabled', false);

                        let data = $('#pr_form').serialize();
                        let url = form.action;
                        console.log(url, "jhfhjsdfsdfsfjhsfjsd")
                        // debugger;
                        axios.post(url, data).then((response) => {
                            // console.log(data)
                            document.location = response.data.url;
                            // INVENTORY.inventoryLoading(false, $('#po_form'));
                        }, (error) => {
                            // swal("Error !", error.response.data.message, "error")
                            // INVENTORY.inventoryLoading(false, $('#po_form'));
                        });


                    }
                });
            }
        });

    });
</script>

@endsection
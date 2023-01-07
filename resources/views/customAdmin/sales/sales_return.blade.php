@extends(backpack_view('blank'))


@push('after_styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
    integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="{{asset('css/style.css')}}">
<link rel="stylesheet" href="{{ asset('css/nepali.datepicker.v2.2.min.css') }}">

@endpush

@section('header')

<section class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
        <small><a style="color:white" href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                    class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </h2>
</section>
@endsection
@section('content')
<div class="billing_navbar">
    <div class="billing_nav">
        <div class="heading">
            <h1>Sales Return</h1>
        </div>
        <div class="header_icons">
            <a href="{{ url($crud->route).'/create' }}" class='icon-btn'><i class="fa fa-plus"
                    aria-hidden="true"></i></a>
            <a href="{{ url('/') }}" class='icon-btn'><i class="fa fa-home" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
{{-- Modal for adding item to the Previous bill --}}


<div class="modal fade" id="add_stock_item_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" class="barcode_item_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode to Add Qty in "<span id="barcodeItemName"></span>"</h5>
            </div>
            <form id="barcodeForm">

                <div class="modal-body">
                    <select id="barcodeScannerSalesReturn" name="barcode_details[]" class="form-control"
                        multiple="multiple" style="width: 100%;height: auto;">
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="barcodeSaveSalesReturn" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
{{-- <form id="salesForm" action="{{url($crud->route)}}" method="POST"> --}}
    <form id="salesForm" action="{{route('custom.sales-return-store', $sales->id)}}" method="POST">

        {{-- {{ dd($crud->route) }} --}}
        @method('POST')
        @csrf
        <input type='hidden' value="1" id="sales_return_blade" name="return_model">

        <div class="main-container billing-form">
            <div class="row mt-3">
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="input-group mb-3" id="billDiv">
                        <input type="hidden" name="hidden_bill_type" id="hidden-bill-type">
                        <label class="input-group-text" for="bill_type">Bill Type</label>
                        <select class="form-select disableSalesReturnInput" id="bill_type" name="bill_type">
                            <option value="1"  {{ $sales->customerEntity->is_coorporate == false ? 'selected' : ''}}>Individual</option>
                            <option value="2"{{ $sales->bill_type == true ? 'selected' : ''}} >Corporate</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Address</span>
                        <input type="text" class="form-control disableSalesReturnInput" id="address" name="address" value="{{$sales->customerEntity->address}}" placeholder="address">
                    </div>
                    {{-- <div class="input-group mb-3">
                        <span class="input-group-text">Bill Date</span>
                        <input type="date" name="bill_date_ad" readonly class="form-control" placeholder="date">
                    </div> --}}

                    <div class="input-group mb-3">
                        <span class="input-group-text">Bill Date</span>
                        <input type="date" name="bill_date_ad" readonly class="form-control"
                            value="{{$sales->bill_date_ad}}" placeholder="date">
                    </div>
                    <div id="pan_vat_field" class="input-group mb-3">
                        <span class="input-group-text">Pan/Vat</span>
                        <input type="text" class="form-control disableSalesReturnInput" name="pan_vat" id="pan_vat" value="{{$sales->customerEntity->pan_no}}" placeholder="Pan/Vat No">
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Buyer Name</span>
                        <input type="hidden" name="customer_id" id="hidden_customer">
                        <input type="text" class="form-control disableSalesReturnInput" id="full_name" name="full_name" value="{{$sales->customerEntity->name_en}}" placeholder="Buyer" />
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Contact No</span>
                        <input type="text" class="form-control disableSalesReturnInput" id="contact_number" name="contact_number" value="{{$sales->customerEntity->contact_number}}" placeholder="Contact">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Transaction Date</span>
                        <input type="date" name="transaction_date_ad" value="{{$sales->transaction_date_ad}}" class="form-control disableSalesReturnInput" placeholder="date">
                    </div>
                    <div id="company_field" class="input-group mb-3">
                        <span class="input-group-text">Company Name</span>
                        <input type="text" class="form-control disableSalesReturnInput" id="company_name" name="company_name" value="{{$sales->customerEntity->company_name}}" placeholder="Company Name">
                    </div>
                    <div class="input-group mb-3">
                        <label>Is Full Return</label>
                        <input type="checkbox" id="return_type" value="1" name="return_type" class="mt-2 mx-2" checked>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Bill No</span>
                        <input type="text" class="form-control" name="bill_no" readonly value="{{$sales->bill_no}}"
                            placeholder="Bill No">
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="return_sequence">Return Sequence</label>
                        <select class="form-select" id="return_sequence" name="return_sequence">
                            @foreach ($returnSequences as $code => $codeId)
                                <option value="{{ $codeId }}">{{ $code }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text bg-primary text-white" onclick="loadModal(this, '7')">
                            +
                        </span>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="salesDiscountMode">Discount Mode</label>
                        <select class="form-select disableSalesReturnInput" name="discount_type" id="salesDiscountMode">
                            @foreach ($discount_modes as $mode)
                                <option value="{{ $mode->id }}">{{ $mode->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span>Item Wise Discount</span>
                        <input type="checkbox" checked id="discountCheckbox" class="mt-2 mx-2">
                    </div>

                </div>
            </div>
        </div>

        {{-- End of upper form filter design? --}}
        <div class="table-responsive">
            <table class="table" style="min-width: 1200px">
                <thead>
                    <tr class="text-white" style="background-color:#192840">
                        {{-- <th scope="col">S.No</th>--}}
                        <th scope="col">Code/Model Name</th>
                        <th class="{{($sales->status_id==2)?'d-none':''}}" scope="col">Total Qty</th>
                        <th scope="col">Batch No</th>
                        <th class="{{($sales->status_id==2)?'d-none':''}}" scope="col">Batch Qty</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Return Qty</th>
                        <th scope="col">Unit </th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Disc </th>
                        <th scope="col">Tax/Vat</th>
                        <th scope="col">Amount</th>

                    </tr>
                </thead>
                <tbody id="sales-table">
                    @php
                    $count = $sales->saleItems->count();
                    @endphp

                    @foreach($sales->saleItems as $key => $item)
                    @php $key++;
                    $mstItem = $item->mstItem;
                    $itemId = json_encode($item->item_id);
                    if(isset($mstItem->itemQtyDetail->item_qty)? $itemQty = $mstItem->itemQtyDetail->item_qty:$itemQty=0);
                    $total_qty = ($item->total_qty) - ($item->return_qty);

                    $batchQty = $item->batchQty;
                    $batchId = json_encode($batchQty->batch_id);
                    $cntr = json_encode($key);
                    $saleItems = json_encode($sales->saleItems);


                    @endphp
                    <tr>
                        {{-- <th scope="row">1</th> --}}
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control p-1 salesItemStock" name="item_id[{{$key}}]"
                                    value="{{$mstItem->code.':'.$mstItem->name}}" item-id="{{$item->item_id}}"
                                    placeholder="Search Item" id="salesItemStock-{{$key}}" data-cntr="{{$key}}">
                                <input type="hidden" name="itemSalesHidden[{{$key}}]" value="{{$item->item_id}}"
                                    class="itemSalesHidden">

                            </div>
                        </td>
                        <td class="{{($sales->status_id==2)?'d-none':''}}">
                            <div class="input-group">
                                <input id="salesAvailableQty-{{$key}}" class="form-control p-1 salesAvailableQty"
                                    value="{{$itemQty}}" name="sales_availableQty[{{$key}}]" type="text" value=""
                                    id="salesAvailableQty-1" data-cntr="{{$key}}" size="1" readonly />
                        </td>
        </div>
        <td>
            <div class="input-group">

                <select class="form-select p-1 salesBatchNo" name="batch_no[{{$key}}]" id="salesBatchNo-{{$key}}"
                    size="1" data-cntr="{{$key}}">

                    @foreach($item->mstItem->batchQtyDetails as $bd)
                    @if($bd->id == $item->batch_qty_detail_id)
                    <option value="{{$bd->id}}" selected>{{$bd->batch_no}} </option>

                    @else
                    <option value="{{$bd->id}}">{{$bd->batch_no}} </option>
                    @endif
                    @endforeach

                </select>
            </div>
        </td>
        <td class="{{($sales->status_id==2)?'d-none':''}}">
            <div class="input-group">
                <input type="text" class="form-control p-1 salesBatchQty" value="{{$total_qty}}"
                    name="batch_qty[{{$key}}]" id="salesBatchQty-{{$key}}" data-cntr="{{$key}}" readonly value=""
                    size="1" />
        </td>
        </div>
        <td style="max-width:3rem">
            <div class="input-group">
                <input type="number" min="0" class="form-control p-1 salesAddQty" id="sold_qty-{{$key}}" size="1"
                    value="{{$item->total_qty}}" {{$item->barcodeDetails->count() ? "readonly='true'" :'' }}
                name="total_qty[{{$key}}]" placeholder="Qty" size="1"
                data-cntr="{{$key}}"{{$multiple_barcode?'readonly':''}}>
            </div>
        </td>
        <td style="max-width:3rem">
            <div class="input-group">
                <button type="button" class="btn btn-primary btn-sm returnbarcodeScan" id="barcodeScan-1"
                    data-cntr="{{$key}}" data-toggle="modal">+</button>

                <input type="number" min="0" class="form-control p-1 salesAddQty" size="1"
                    {{$item->barcodeDetails->count() ? "readonly='true'" :'' }} name="return_qty[{{$key}}]"
                id="salesAddQty-{{$key}}" placeholder="Qty" size="1"
                data-cntr="{{$key}}"{{$multiple_barcode?'readonly':''}}>
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="text" class="form-control p-1 salesUnit" value="{{$item->mstItem->mstUnitEntity->name_en}}"
                    id="salesUnit-{{$key}}" name="unit_id[{{$key}}]" readonly placeholder=" Unit" size="1"
                    data-cntr="{{$key}}">
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="text" class="form-control p-1 salesUnitPrice" value="{{$item->item_price}}"
                    name="unit_cost_price[{{$key}}]" id="salesUnitPrice-{{$key}}" readonly placeholder="unit Price"
                    size="1" data-cntr="{{$key}}">
            </div>
        </td>
        <td>
            <div class="input-group">
                <input type="text" class="form-control p-1 salesDiscount" value="{{$item->item_discount}}"
                    name="item_discount[{{$key}}]" id="salesDiscount-{{$key}}" placeholder="Discount" size="1"
                    data-cntr="{{$key}}">
            </div>
        </td>
        <td> <input type="text" class="form-control p-1 salesTax" name="tax_vat[{{$key}}]" value="{{$item->tax_vat}}"
                id="salesTax-{{$key}}" data-cntr="{{$key}}" readonly size="1"></td>
        <td><input type="text" class="form-control p-1 salesAmount" name="item_total[{{$key}}]"
                value="{{$item->item_total}}" id="salesAmount-{{$key}}" data-cntr="{{$key}}" readonly size="1"></td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        {{-- End of item search design --}}
        <hr>

        <div class="main-container mb-3">
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Discount</span>
                        <input type="text" class="form-control" id="flatDiscount" value="{{$sales->discount}}"
                            disabled="true" name="discount" placeholder="Discount">
                    </div>

                    {{-- barcode details hidden field  --}}
                    <input type='hidden' id="returnModel" name="returnModel">

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="discount_approver">Discount Approver</label>
                        <select class="form-select" name="discount_approver_id" id="discount_approver">
                            @foreach($discount_approver as $mode)
                            <option value="{{$mode->id}}">{{$mode->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cheque_enabled">
                        <div id="bank_field" class="input-group mb-3">
                            <label class="input-group-text" for="discount_approver">Bank name</label>
                            <input type="text" class="form-control" value="{{$sales->bank_name}}" name="bank_name"
                                id="bank_name">
                        </div>

                        <div id="cheque_field" class="input-group mb-3">
                            <label class="input-group-text" for="cheque_number">Cheque Number</label>
                            <input type="text" class="form-control" value="{{$sales->cheque_number}}"
                                name="cheque_number" id="cheque_number">
                        </div>
                        <div id="acc_holder_field" class="input-group mb-3">
                            <label class="input-group-text" for="ac_holder_name">Ac.Holder Name</label>
                            <input type="text" class="form-control" value="{{$sales->ac_holder_name}}"
                                name="ac_holder_name" id="ac_holder_name">
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Remarks</span>
                        <input type="text" class="form-control" id="return_remarks" name="remarks"
                            value="{{$sales->remarks}}" placeholder="Remarks">
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="Payment_type">Payment Type</label>
                        <select class="form-select" name="payment_type" id="payment_type">
                            <option value="1" {{ $sales->payment_type== 1 ? 'selected' : ''}} >Cash</option>
                            <option value="2" {{ $sales->payment_type== 2 ? 'selected' : ''}}>Cheque</option>
                            <option value="3" {{ $sales->payment_type== 3 ? 'selected' : ''}}>Due</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Receipt Amount</span>
                        <input type="text" id="receipt_amount" disabled="true" value="{{$sales->receipt_amt}}"
                            class="form-control" name="receipt_amt" placeholder="Amount">
                    </div>
                    <div id="due_field" class="input-group mb-3">
                        <label class="input-group-text" for="due_approver">Due Approver</label>
                        <select class="form-select" name="due_approver_id" id="due_approver">
                            @foreach($due_approver as $mode)
                            <option value="{{$mode->id}}" {{ $sales->due_approver_id== $mode->id ? 'selected' :
                                ''}}>{{$mode->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="cheque_enabled">
                        <div id="branch_field" class="input-group mb-3">
                            <span class="input-group-text">Branch Name</span>
                            <input type="text" id="branch_name" class="form-control" name="branch_name"
                                value="{{$sales->branch_name}}" placeholder="Branch Name">
                        </div>

                        <div id="" class="input-group mb-3">
                            <span class="input-group-text">Cheque Date</span>
                            <input type="date" id="cheque_date" class="form-control" value="{{$sales->cheque_date}}"
                                name="cheque_date">
                        </div>
                        <!-- <div class="input-group mb-3">
                        <span class="input-group-text">Cheque Upload</span>
                        <input type="file" id="cheque_upload" class="form-control" name="receipt_amt" placeholder="Amount">
                    </div> -->
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-primary text-white">Sub Total</th>
                                <td><input id="sl_gross_total" type="text" name="gross_amt"
                                        value="{{$sales->gross_amt}}" class="form-control" readonly></td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Total Discount</th>
                                <td> <input id="sl_discount_amount" type="text" name="discount_amt"
                                        value="{{$sales->discount_amt}}" class="form-control" readonly></td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Taxable Amount</th>
                                <td><input id="sl_taxable_amnt" type="text" name="taxable_amt"
                                        value="{{$sales->taxable_amt}}" class="form-control" readonly></td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Tax Total</th>
                                <td><input id="sl_tax_amount" type="text" name="total_tax_vat"
                                        value="{{$sales->total_tax_vat}}" class="form-control" readonly></td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Net Amount</th>
                                <td> <input id="sl_net_amount" type="text" name="net_amt" value="{{$sales->net_amt}}"
                                        class="form-control" readonly></td>
                            </tr>
                            <tr>
                                {{-- <th class="bg-primary text-white">Refund Amount</th> --}}
                                <th class="bg-primary text-white">Paid Amount</th>
                                <td> <input id="sl_paid_amount" type="text" name="paid_amt" value="{{$sales->paid_amt}}"
                                        class="form-control"></td>
                            </tr>
                            {{-- <tr id="sl_due_amt_field">
                                <th class="bg-primary text-white">Due</th>
                                <td> <input id="sl_due_amount" readonly type="text" value="{{$sales->due_amt}}"
                                        name="due_amt" class="form-control"></td>
                            </tr> --}}
                             <tr id="sl_refund_field">
                            <th class="bg-primary text-white">Refund</th>
                            <td> <input id="sl_refund_amount"  readonly type="text" value="{{$sales->refund}}" name="refund" class="form-control"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- End of Price table --}}
        <div class="container mb-4">
            <div class="row ">
                <div class="col-9"></div>
                <div class="col-3">
                    <input id="status" type="hidden" name="status_id" value="">
                    <button id="approve_return" class="btn btn-danger cancel_approved">Approve Return</button>

                </div>
            </div>
        </div>
    </form>
    @include('customAdmin.partial._inlineSequenceCreate')

    @endsection

    @section('after_scripts')
    @include('customAdmin.sales.partials.return-script');
    @endsection

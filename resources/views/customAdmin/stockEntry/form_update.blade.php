@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.add') => false,
    ];
    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@push('after_styles')
    @include('customAdmin.stockEntry.partials.styles')
@endpush

@section('header')

    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small>

            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <form id="stockEntryForm" action="{{url($crud->route).'/'.$stock->id}}" method="PUT">
        @method('PUT')
        @csrf
        <div class="main-container">
            <div class="row mt-3">
                <div class="col-xl-4 col-lg-4 col-sm-6">
                    <div class="input-group mb-3">
                        <select class="form-select client_id" id="client_id"  name="client_id">
                            <option value="" disabled selected>Select a client</option>
                        @foreach($clientLists as $client)
                                <option {{($stock->client_id == $client->id) ?'selected':''}} value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-xl-4 col-lg-4 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Date AD</span>
                        <input type="date" id="stockDateAD" value="{{$stock->entry_date_ad?dateToString($stock->entry_date_ad) : dateToString(\Carbon\Carbon::now())}}"  name='entry_date_ad'  class="form-control" placeholder="Date AD" readonly>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Date BS</span>
                        <input type="text" id="stockDateBS" name="entry_date_bs" value="{{$stock->entry_date_bs??convert_bs_from_ad(dateToString(\Carbon\Carbon::now()))}}" class="form-control" placeholder="Date BS" readonly >
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-sm-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="batch_number">Batch number</label>
                        <select class="form-select" id="batch_number" name="batch_number">
                            @foreach ($batchNumbers as $code => $codeId)
                                <option value="{{ $codeId }}" {{$stock->batch_number == $codeId ?'selected':''}}>{{ $code }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text bg-primary text-white" onclick="loadModal(this, '1')">
                            +
                        </span>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-sm-6">
                    <div class="input-group mb-3">
                        <span>Item Wise Discount</span>
                        <input type="checkbox"  name="itemWiseDiscount" id="discountCheckbox" {{!$stock->flat_discount?"checked" :'' }} class="mt-2 mx-2">
                    </div>
                </div>

            </div>
        </div>

        {{-- End of upper form filter design? --}}
        <div class="table-responsive">
        <table class="table" id="repeaterTable" style="min-width: 1500px;">
            <thead>
            <tr class="text-white" style="background-color: #192840">
                <th scope="col">Code/Model Name</th>
                <th scope="col">Avl Qty</th>
                <th scope="col">Add Qty</th>
                <th scope="col">Total Qty</th>
                <th scope="col">Expiry Date </th>
                <th scope="col">Unit cost </th>
                <th scope="col">unit Sales</th>
                <th scope="col">Disc</th>
                <th scope="col">Tax/vat</th>
                <th scope="col">Amount</th>
                <th scope="col" style="width: 6rem">Action</th>
            </tr>
            </thead>
            <tbody id="stock-table">

            @foreach($stock->items as $key => $item)
                @php
                    $key++;
                    $mstItem = $item->mstItem;
                    if(isset($mstItem->itemQtyDetail)){
                        if(isset($mstItem->itemQtyDetail->item_qty)? $itemQty = $mstItem->itemQtyDetail->item_qty: $itemQty=0);
                    }else{
                        $itemQty = 0;
                    }
                @endphp
                <tr>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 itemStock"  name="mst_item_id[{{$key}}]" value="{{$mstItem->code.":".$mstItem->name}}"  placeholder="Search item by code/name" id='itemStock-{{$key}}' data-cntr="{{$key}}" size="1" style="width:10rem;">
                            <input type="hidden" name="itemStockHidden[{{$key}}]" value="{{$item->item_id}}"  class="itemStockHidden">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input id="availableQty-{{$key}}"  data-cntr="{{$key}}" type="number" min="0" class="form-control p-1 availableQty" name="available_total_qty[{{$key}}]" value="{{$item->available_total_qty}}"  size="1" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control p-1 custom_Qty" value="{{$item->add_qty}}"
                                name="custom_Qty[{{$key}}]" placeholder="Qty" id='custom_Qty-{{$key}}' data-cntr="{{$key}}"
                                size="1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">
                        </div>
                    </td>

                    <td >
                        <div class="input-group">
                            <input id="totalQty-{{$key}}"  data-cntr="{{$key}}"  type="number" min="0" class="form-control p-1 totalQty" name="total_qty[{{$key}}]" value="{{$item->total_qty}}"  size="1" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="date" class="form-control p-1 itemExpiry" name="expiry_date[{{$key}}]" value="{{dateToString($item->expiry_date)}}" id="itemExpiry-{{$key}}" placeholder="Expiry" data-cntr="{{$key}}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control p-1 unitPrice" name="unit_cost_price[{{$key}}]" value="{{$item->unit_cost_price}}" placeholder="Cost Price" id="unitPrice-{{$key}}" size="1" data-cntr="{{$key}}" >
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control p-1 salesPrice" name="unit_sales_price[{{$key}}]" value="{{$item->unit_sales_price}}" placeholder="Sales Price" id="salesPrice-{{$key}}" size="1" data-cntr="{{$key}}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" max="100" class="form-control p-1 fireRepeater discount" name="discount[{{$key}}]" value="{{$item->discount}}"  {{$stock->flat_discount?"disabled='true'" :'' }} placeholder="Discount %" id="discount-{{$key}}" size="1" data-cntr="{{$key}}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input id="itemTax-{{$key}}" data-cntr="{{$key}}"  type="number" min="0" class="form-control p-1 itemTax" name="tax_vat[{{$key}}]" value="{{$item->tax_vat}}"  size="1" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input id="totalAmnt-{{$key}}" data-cntr="{{$key}}" type="number" min="0" class="form-control p-1 totalAmnt" name="item_total[{{$key}}]" value="{{$item->item_total}}"  size="1" readonly>
                        </div>
                    </td>
                    <td>
                        <i class="fa fa-plus p-1 fireRepeaterClick" aria-hidden="true"></i>
                        <i class="fa fa-trash p-1 destroyRepeater {{$loop->count == 1 ? 'd-none':'' }}" data-cntr="{{$key}}" id="itemDestroyer-{{$key}}" aria-hidden="true"></i>
                        <i type ='button' class="fa fa-history p-1 itemHistory" data-cntr="{{$key}}" id="itemHistory-{{$key}}" item-id="{{$item->id}}"  data-toggle="modal"  aria-hidden="true"></i>
                    </td>
                </tr>
            @endforeach
                <tr id="repeater" class="d-none">
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control p-1 itemStock"  placeholder="Search item..."  size="1" style="width:10rem;">
                            <input type="hidden" class="itemStockHidden">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control p-1 availableQty"  value="0"  size="1" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control p-1 custom_Qty" placeholder="Qty" data-cntr="{{$key}}"
                            size="1">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" class="form-control p-1 totalQty" value="0"  size="1" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="date" class="form-control p-1 itemExpiry" placeholder="Expiry" >
                        </div>
                    </td>
                        <td>
                            <div class="input-group">
                                <input type="number" min="0" class="form-control p-1 unitPrice" placeholder="Cost Price" size="1">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="number" min="0" class="form-control p-1 salesPrice" placeholder="Sales Price" size="1">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="number" min="0" max="100" class="form-control p-1 fireRepeater discount" {{$stock->flat_discount?"distabled='true'" :'' }} placeholder="Discount %" size="1">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input  type="number" min="0" class="form-control p-1 itemTax"  value="0"  size="1" readonly>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input  type="number" min="0" class="form-control p-1 totalAmnt" value="0"  size="1" readonly>
                            </div>
                        </td>
                    <td>
                        <i class="fa fa-plus  fireRepeaterClick" aria-hidden="true"></i>
                        <i class="fa fa-trash  destroyRepeater" aria-hidden="true" ></i>
                        <i type ='button' class="fa fa-history  itemHistory" data-toggle="modal"  aria-hidden="true"></i>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        {{-- End of item search design --}}
        <hr>
        <div class="main-container">
            <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" >Discount</span>
                            <input type="number" min="0" max="100" id="flatDiscount" name="flat_discount" {{!$stock->flat_discount ?" disabled='true'":'' }} value="{{$stock->flat_discount}}" class="form-control" placeholder="Discount">
                        </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" >Remarks</span>
                        <textarea class="form-control comment" name="comments" value="{{$stock->comments}}" col="5" placeholder="Remarks" > </textarea>
                    </div>
                </div>
                    <div class="col-md-6 col-sm-12">
                        <table class="table table-sm table-bordered">
                            <tbody>
                            <tr>
                                <th class="bg-primary text-white">Sub Total</th>
                                <td >
                                    <input id="st_gross_total" type="number" min="0" value="{{$stock->gross_total}}" name="gross_total" class="form-control" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Total Discount</th>
                                <td>
                                    <input id="st_discount_amount" type="number" min="0" name="total_discount" value="{{$stock->total_discount}}" class="form-control" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Taxable Amount</th>
                                <td >
                                    <input id="st_taxable_amnt" type="number" min="0" name="taxable_amount" value="{{$stock->taxable_amount}}" class="form-control" readonly>
                                </td>

                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Tax Total</th>
                                <td >
                                    <input id="st_tax_amount" type="number" min="0" name="tax_total" value="{{$stock->tax_total}}" class="form-control" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white">Net Amount</th>
                                <td>
                                    <input id="st_net_amount" type="number" min="0" name="net_amount" value="{{$stock->net_amount}}" class="form-control" readonly>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
        {{-- End of Price table --}}
        <div class="main-container mb-4">
            <div class="row ">
                <div class="col d-flex justify-content-end">

                    @if($stock->sup_status_id !== \App\Models\Pms\SupStatus::APPROVED)
                        <input  id="status" type="hidden" name="sup_status_id" value="">
                        <button id="save" type="submit"  class="btn btn-primary me-1 st_save">Draft</button>
                        {{-- @if(backpack_user()->is_stock_approver) --}}
                            <button id="approve" type="submit"  class="btn btn-success me-1 st_approve">Approve</button>
                        {{-- @endif --}}
                    @endif
                    <a href="{{url($crud->route)}}"><i  class="btn btn-danger me-1">{{$stock->sup_status_id == \App\Models\Pms\SupStatus::APPROVED ?'Back':'Cancel' }}</i></a>
                </div>
            </div>
        </div>
    </form>
    {{-- end of the modal content --}}

    @include('customAdmin.stockEntry.partials.modals')
    @include('customAdmin.partial._inlineSequenceCreate')

@endsection

@include('customAdmin.stockEntry.partials.scripts')

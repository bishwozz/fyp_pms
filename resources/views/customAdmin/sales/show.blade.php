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
<style>
    .upper-container .col-2 {
        flex: 0 0 auto;
        width: 9.666667%;
    }

    .row * {
        border: transparent !important;
    }

    .input-group-text {
        background-color: transparent !important;
        font-weight: bold;

    }
</style>
@endpush

@section('header')
<section class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.preview').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
        <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                    class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </h2>
</section>
@endsection

@php
$status=[
1=>'text-warning',
2=>'text-success',
3=>'text-danger',
4=>'text-warning',
5=>'text-warning'
];
@endphp

@section('content')
<div class="card shadow px-3 mt-4">
    <!-- store name section -->
    <div class="mt-3">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;"> Status: </span> <span
                        class="{{ isset($entry->status_id)? $status[$entry->status_id] : ''}}"
                        style="font-weight: bold;"> {{ucfirst($entry->supStatus->name_en)}}</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Buyer Name: </span> <span>{{$entry->customerEntity->name_en}}</span>
                </div>
            </div>
            {{-- Bill Number --}}
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Bill Number: </span>
                    <span>{{$entry->bill_no?($entry->bill_no):'n/a'}}</span>
                </div>
            </div>


            {{-- Return Number --}}
            @if (isset($entry->return_bill_no))
                <div class="col-lg-3 col-md-4">
                    <div class="mb-3">
                        <span class="me-1" style="font-weight: bold;">Return Number: </span>
                        <span>{{$entry->return_bill_no?($entry->return_bill_no):'n/a'}}</span>
                    </div>
                </div>
            @endif


            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Date Ad: </span>
                    <span>{{$entry->bill_date_ad?dateToString($entry->bill_date_ad):'n/a'}}</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Contact Number: </span>
                    <span>{{$entry->customerEntity->contact_number}}</span>
                </div>
            </div>
            @if($entry->bill_type ==2)
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Pan/Vat: </span> <span>{{$entry->pan_vat}}</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Company Name: </span>
                    <span>{{$entry->company_name}}</span>
                </div>
            </div>
            @endif
            <div class="col-lg-3 col-md-4">
                <div class="mb-3">
                    <span class="me-1" style="font-weight: bold;">Transaction Date: </span>
                    <span>{{$entry->transaction_date_ad}}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- table for item -->
    <div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr style="background-color: rgb(241, 241, 241)">
                        <th scope="col">Code/Model Name</th>
                        <th scope="col">Batch No</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Unit </th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Tax/Vat</th>
                        <th scope="col">Amount</th>

                    </tr>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td scope="col">{{$item->mstItem->name}}</td>
                        <td scope="col">{{$item->batchQty->batch_no??'n/a'}}</td>

                        <td scope="col">{{$item->total_quantity}}</td>

                        <td scope="col">{{$item->mstItem->mstUnitEntity->name_en}}</td>
                        <td scope="col">{{$item->item_price}}</td>
                        <td scope="col">{{$item->tax_vat??'0'}}</td>
                        <td scope="col">{{$item->item_total}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- bottom table section -->
    <div>
        <div class="row">
            <div class="col-md-6 mt-2">
                <div class="">
                    <span style="font-weight: bold;">Remarks:</span>
                    {{$entry->remarks?? 'n/a'}}
                </div>
                <div class="">
                    <span style="font-weight: bold;">Aprroved By:</span>
                    {{$entry->createdByEntity->name}}
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless text-dark">
                    <tr>
                        <td class="font-weight-bold">Gross total:</td>
                        <td>{{$entry->gross_amt}}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Total Disc:</td>
                        <td>{{$entry->discount_amt}}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Taxable Amount:</td>
                        <td>{{$entry->taxable_amt}}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Tax Total:</td>
                        <td>{{$entry->total_tax_vat}}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Net Amount:</td>
                        <td>{{$entry->net_amt}}</td>
                    </tr>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

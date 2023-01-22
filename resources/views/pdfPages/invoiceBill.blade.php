
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        {{ 'Invoice Bill - ' . $sales->bill_no }}
    </title>

    @if (isset($background_encoded))
        <style>
            .mainTable {
                background-image: linear-gradient(to right, rgba(255, 255, 255, 0.8) 0 100%), url('<?php echo $background_encoded; ?>');
                background-repeat: repeat-y;
                /* object-fit: contain; */
            }

        </style>
    @endif
    <style>
        @media print {
            @page {
                size: A4;
                /* margin: 0; */
            }
        }

        .mainTable,
        .mainTable>tbody>tr>th,
        .mainTable>tbody>tr>td,
        .mainTable>tbody>tr>th>td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            padding: 5px 0;
        }

        .pan-row>th {
            border-bottom-style: none !important;
            border-top-style: none !important;
        }

        .pan-row>td {
            border-bottom-style: none !important;
            border-top-style: none !important;
        }

        .panTable {
            border: none !important;
            font-weight: bolder;
        }

        .sideTable,
        .sideTable>tr>th,
        .sideTable>tr>td,
        .sideTable>tr>th>td {
            border: none !important;
            border-collapse: collapse;
            text-align: left;
            padding: 5px 0;
        }

        .tableContent>td {
            /* border-bottom-style: none !important; */
            border-bottom-style: 1px solid #000;
            border-top-style: none !important;
        }

        .footerSignatureTable,
        .footerSignatureTable>tbody>tr,
        .footerSignatureTable>tbody>tr>td {
            width: 100%;
            border: none !important;
            border: 1px solid black;
            /* border-bottom-style: 1px solid black ; */
            /* border-collapse: collapse; */
            text-align: left;
            padding: 5px 0;
        }

        .bottom-row-right {
            text-align: right !important;
            font-weight: bolder;
            padding-right: 5px !important;
            margin: 0 10px 0 0 !important;
        }

        .bottom-row-center {
            text-align: center !important;
            font-weight: bolder;
            /* padding-right: 5px !important;
            margin: 0 10px 0 0 !important; */
        }

        .bottom-row-left {
            text-align: left !important;
            /* font-weight: bolder; */
            padding-left: 5px !important;
            margin: 0 10px 0 0 !important;
        }

        .bottom-row-approved {
            text-align: left !important;
            /* font-weight: bolder; */
            padding-left: 5px !important;
            margin: 0 10px 0 0 !important;
        }

        .page-break {
            page-break-after: always;
        }

        .heading-row-invoice-body,
        .heading-row-invoice-body>th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            border-left: 1px solid #000;
        }

        .invoice-details-row,
        .invoice-details-row>td {
            border-top: 1px solid #000;
            border-right: 1px solid #000;
        }

        .invoiceHeaderLogoDiv {
            float: left;
            border-radius: 25px;
            /* background-color: #000 */
        }

        .mainTable thead tr *,
        .mainTable tfoot tr * {
            padding: 0;
            margin: 0 0 5px 0;
        }

    </style>
</head>

<body>
    {{-- Header of the invoice --}}
    <table class="mainTable">

        <thead>
            {{-- Supplier Pan Card --}}
            @if (isset($header_footer_data->pan_vat))
                <tr class="pan-row">
                    <td colspan="8" style="text-align: left;padding-left:10px;">
                        <b>
                            PAN : {{ $header_footer_data->pan_vat }}
                        </b>
                    </td>
                </tr>
            @endif
            {{-- Supplier Details --}}
            @if (isset($header_footer_data->header))
                <tr class="pan-row">
                    <th colspan="8">
                        @if (isset($logo_encoded))
                            <div class="invoiceHeaderLogoDiv">
                                <img src="{{ $logo_encoded }}" alt=""
                                    style="height: 75px; width:75px;padding:5px 5px 0px 10px;" class="invoiceHeaderLogo">
                            </div>
                        @endif
                        <div class="invoiceHeaderDetailDiv">
                            {!! $header_footer_data->header !!}
                        </div>
                    </th>
                </tr>
            @endif
            {{-- Invoice Details --}}
            <tr class="invoice-details-row">
                <td colspan="2" style="text-align: left!important; padding-left:5px">
                    {{-- <h3 style="margin-bottom:2;"> --}}
                    <em>
                        <strong>
                            Purchaser's Details :
                        </strong>
                    </em>
                    <br>

                    @if (isset($sales->buyer_name) && isset($sales->age) && isset($sales->gender))
                        {{ $sales->buyer_name.'  '.$sales->age .'/'. $sales->gender }}
                        <br>
                    @elseif(isset($sales->buyer_name))
                        {{ $sales->buyer_name }}
                        <br>
                    @elseif(isset($sales->buyer_name))
                        {{ $sales->buyer_name }}
                        <br>
                    @endif
                    
                    @if (isset($sales->contact_number))
                        {{ $sales->contact_number }}
                        <br>
                    @endif

                    @if (isset($sales->buyer_address))
                        {{ $sales->buyer_address }}
                        <br>
                    @endif

                    @if (isset($sales->buyer_pan) && isset($sales->buyer_company_name))
                        {!! '('.$sales->buyer_company_name.') &nbsp;&nbsp;&nbsp;'.'<b>Pan/VAT : </b>' . $sales->buyer_pan !!}
                        <br>
                    @elseif(isset($sales->buyer_pan))
                        {{ 'Party Pan : ' . $sales->buyer_pan }}
                        <br>
                    @elseif(isset($sales->buyer_company_name))
                        {{ '&nbsp;<b>Pan/VAT : </b>' . $sales->buyer_company_name }}
                        <br>
                    @endif

                </td>
                <td colspan="6" style="text-align: left!important; padding-left:5px">
                    <em>
                        <strong>
                            Sale's Details :
                        </strong>
                    </em>
                    <table class="sideTable">
                        @if (isset($sales->payment_type))
                            <tr>
                                <td>Payment Mode</td>
                                <td>:</td>
                                <td>
                                    @if ($sales->payment_type == 1)
                                        Cash
                                    @elseif ($sales->payment_type == 2)
                                        Cheque
                                    @else
                                        Due
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Invoice No.</td>
                            <td>:</td>
                            <td>{{ $sales->bill_no }}</td>
                        </tr>
                        @if (isset($sales->return_bill_no))
                        <tr>
                            <td>Return No.</td>
                            <td>:</td>
                            <td>{{ $sales->return_bill_no }}</td>
                        </tr>
                        @endif
                        @if (isset($sales->bill_date_ad))
                            <tr>
                                <td>Invoice Date</td>
                                <td>:</td>
                                <td>
                                    {{-- {{ $sales->bill_date_ad . ' AD / ' . $sales->bill_date_bs . ' BS' }} --}}
                                    {{ $sales->bill_date_ad . ' AD ' }}
                                </td>
                            </tr>
                        @endif
                        @if (isset($sales->transaction_date_ad))
                            <tr>
                                <td>Transaction Date</td>
                                <td>:</td>
                                <td>
                                    {{ $sales->transaction_date_ad . ' AD' }}
                                </td>
                            </tr>
                        @endif
                    </table>

                </td>
            </tr>

            {{-- Heading Row for Invoice Table --}}
            <tr class="heading-row-invoice-body">
                <th style="width: 5%">
                    S.N.
                </th>
                <th style="width: 40%">
                    Particulars
                </th>
                <th style="width: 10%">
                    Batch
                </th>
                <th style="width: 10%">
                    Quantity
                </th>
                
                <th style="width: 8%">
                    Unit
                </th>
                <th style="width: 10%">
                    Unit Price
                </th>
                <th style="width: 7%">
                    Tax
                </th>
                <th style="width: 10%">
                    Total Price
                </th>
            </tr>
        </thead>

        <tbody class="tbody">
            {{-- Contents Row for Invoice Table --}}
            @php
                $i = 1;
            @endphp
            @foreach ($sales_items as $item)
                <tr class="tableContent">
                    <td style="width: 5%">
                        {{ $i++ . '.' }}
                    </td>
                    <td style="width: 40%">
                        {{ $item->item_name }}
                    </td>
                    <td style="width: 10%">
                        {{ $item->batch_no }}
                    </td>
                    <td style="width: 10%">
                        {{ $item->total_qty }}
                    </td>
                    <td style="width: 8%">
                        {{ $item->unit_name }}
                    </td>
                    <td style="width: 10%">
                        {{ $item->item_price }}
                    </td>
                    <td style="width: 7%">
                        {{ ($item->tax_amount) ? $item->tax_amount : 0 }}
                    </td>
                    <td style="width: 10%">
                        {{ $item->item_total }}
                    </td>
                </tr>
            @endforeach

            {{-- Sub Total --}}
            @if ($sales->receipt_amt)
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Sub Total
                    </td>
                    <td>
                        {{ $sales->gross_amt }}
                    </td>
                </tr>
            @endif

            {{-- Total Discount --}}
            @if ($sales->discount_amt)
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Total Discount
                    </td>
                    <td>
                        {{ $sales->discount_amt }}
                    </td>
                </tr>
            @endif

            {{-- Taxable Amount --}}
            @if ($sales->taxable_amt)
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Taxable Amount
                    </td>
                    <td>
                        {{ $sales->taxable_amt }}
                    </td>
                </tr>
            @endif

            {{-- Tax Total --}}

            @if ($sales->total_tax_vat)
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Total Tax
                    </td>
                    <td>
                        {{ $sales->total_tax_vat }}
                    </td>
                </tr>
            @endif

            {{-- Net Amount / Gross Amount --}}
            @if ($sales->gross_amt)
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Net Amount
                    </td>
                    <td>
                        {{ $sales->net_amt }}
                    </td>
                </tr>
            @endif

            {{-- Paid Amount --}}
            @if ($sales->paid_amt)
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Paid Amount
                    </td>
                    <td>
                        {{ $sales->paid_amt }}
                    </td>
                </tr>
            @endif

            {{-- Refund --}}
            @if (isset($sales->refund))
                <tr>
                    <td colspan="7" class="bottom-row-right">
                        Refund
                    </td>
                    <td>
                        {{ $sales->refund }}
                    </td>
                </tr>
            @endif

            {{-- Amount in Words --}}
            <tr>
                <td colspan="8" class="bottom-row-center">
                    {{-- Twelve Hundred Only --}}
                    {{ $sales->netAmtWords }}
                </td>
            </tr>

            {{-- Approved By --}}
            <tr>
                <td colspan="2" class="bottom-row-right">
                    Approved By
                </td>
                <td colspan="6" class="bottom-row-approved">
                    {{ $sales->user_name }}
                </td>
            </tr>

        </tbody>

            {{-- Footer For Supplier --}}
@if (isset($header_footer_data->footer))
    <tfoot>
            <tr>
                <td colspan="8" style="text-align: left;padding:10px 5px 0 25px">
                    <div>
                        {!! $header_footer_data->footer !!}
                    </div>
                </td>
            </tr>
        </tfoot>
@endif
        
    </table>

</body>

</html>

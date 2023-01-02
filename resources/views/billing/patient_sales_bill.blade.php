<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css"> -->
    <title>Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: content-box;
            font-family: 'Roboto', sans-serif;
            border: 0;
            line-height: inherit;
            vertical-align: top;
        }

        @media print {
            @page {
                margin-top: 5px;
                margin-left: 5px;
                margin-right: 30px;
                margin-bottom: 5px;
            }
        }

        body {
            width: 98%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: auto;
            background: #FFF;
        }
        /* th.bill-head { 
            background: #000;
            border-radius: 0.25em;
            color: #FFF;
            margin: 0 0 1em;
            padding: 0.5em 0;
        }
        tr.bill-head { 
            background: #000;
            border-radius: 0.25em;
            color: #FFF;
            margin: 0 0 1em;
            padding: 0.5em 0;
        } */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .row1{
            display: flex;
            justify-content: center;
            align-items: center;

        }
        .lab-text {
            margin-bottom: 0.5rem;
        }

        .heading {
            border: 2px solid black;
            padding: 1rem;
        }

         .header-img img{
            margin-left: -10rem !important;
        }


        .content,
        .lab-text,
        .endnote {
            text-align: center;

        }

        .table-col {
            width: 100%;
        }

        .table {
            text-align: left;
        }

        .test-table,
        .thead th {
            text-align: left;
            border: 1px solid black;
            border-collapse: collapse;
            padding: 0.1rem;
        }
        .second-table{
            border-left: 1px solid black;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
        }
        .second-table tr td{
            padding: 0.2rem;
        }

        .tbody td {
            padding: 0.2rem;
        }

        .test-name {
            padding: 0.2rem 1rem;
            text-decoration: underline;
        }

        .line {
            width: 100%;
            height: 3px;
            background-color: black;
        }

        .notes h3,
        .notes {
            text-decoration: underline;
            margin-bottom: 0.2rem;
        }

        .signatory,
        footer {
            padding: 1rem 0rem;
        }

        th,
        td,
        p {
            font-size: 0.8rem;
        }

        .td-line {
            border: 1px solid black;
        }

        .note-size ol li, .note-size h4{
            font-size: 0.7rem;
        }

        .note-section{
            margin-top: 1.5rem;
        }
        .saleTable td, th {
            /* border: 1px solid black;  */
            text-align: left; 
            padding: 10px 5px;
        }
        /* .signatory {
            border-top:1px solid grey;
            position: relative;
            top:20px;
        } */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row row1">
            <div class="col-2 header-img">
                <img src="{{$logo_encoded}}" alt="logo-of-lab" style="max-width:150px;">
            </div>
            <div class="col-8 text-center header-content" style="padding:10px">
                <div class="col-8 text-center content">
                    @if($report_header)
                        <h2>{{$report_header->letter_head_title_1?:'Your Main Title'}}</h2>
                        <p>{{$report_header->letter_head_title_2?:'Your second level title will appear here'}}</p>
                        <p>{{$report_header->letter_head_title_3?:''}}</p>
                        <p>{{$report_header->letter_head_title_4?:''}}</p>
                        @if($report_header->remarks)
                            <p>{{$report_header->remarks?:'If you want extra title that will come here from remarks'}}</p>
                        @endif
                        <p>REG NO.: {{$client_details->registration_number?:'-'}}  ,  PAN/VAT No.: {{$client_details->pan_vat_no?:'-'}}</p>
                    @else
                        <h2>Your Main Title</h2>
                        <p>Your second level title will appear here</p>
                        <p>Full address of your company will appear here</p>
                        <p>Email: &nbsp;Your email will apear here&nbsp;&nbsp;Phone No: &nbsp;Your phone number</p>
                        <p>If you want extra title that will come here from remarks</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-col">
                <table class="test-table" style="width:100%;" >
                    <tr class="thead">
                        <th class="bill-head" style="text-align:center;font-size:16px;" colspan="11">Billing</th>
                    </tr>
                    <tr class="tbody">
                        <td style="text-align:left;"><b>Name</b></td>
                        <td style="text-align:left;" colspan="6">:&emsp;{{$lab_bill_details->customer_name}}</td>
                        <td style="text-align:left;"><b>Age</b></td>
                        <td style="text-align:left;">: &emsp;{{$lab_bill_details->age}}</td>
                        <td style="text-align:left;"><b>Patient Id</b></td>
                        <td style="text-align:left;">: &emsp;{{$patient->patient_no}}</td>
                    </tr>
                    <tr class="tbody">
                        <td style="text-align:left;"><b>Gender</b></td>
                        <td style="text-align:left;" colspan="6">:&emsp;{{$lab_bill_details->gender}}</td>
                        <td style="text-align:left;" colspan="2"></td>
                        <td style="text-align:left;"><b>Contact No</b></td>
                        <td style="text-align:left;">:&emsp;{{$patient->cell_phone}}</td>
                    </tr>
                </table>
                <table class="second-table" style="width:100%;">
                    <tr>
                        <td style="text-align:left;"><b>Referral Name</b></td>
                        <td style="text-align:left;">: &emsp;{{ $referred_by }}</td>
                        <td style="text-align:left;">-</td>
                        <td style="text-align:left;"></td>
                        {{-- <td style="text-align:left;"></td> --}}
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td> 
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"><b>Bill NO </b></td>
                        <td style="text-align:left;">:&emsp;{{$lab_bill_details->bill_no}}</td>
                    </tr>
                    <tr>
                        <td style="text-align:left;"><b>Guarantor</b></td>
                        <td style="text-align:left;">: &emsp;- </td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td> 
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td> 
                        <td style="text-align:left;"></td> 
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;"></td>
                        <td style="text-align:left;" ><b>Bill Date </b></td>
                        <td style="text-align:left;">:&emsp;{{$lab_bill_details->generated_date_bs}}</td>
                    </tr>

                </table>
                <table class="second-table saleTable" style="width:100%; min-height:150px;">
                    <thead>
                    <tr class="bill-head" style="border-bottom: 1px solid black;">
                        <th style="text-align:left;">S.No</th>
                        <th>Test</th>
                        <th style="text-align:right;">Qty</th>
                        <th style="text-align:right;">Rate</th>
                        <th style="text-align:right;">Discount Amount</th>
                        <th style="text-align:right;">Net Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($lab_items)
                            @foreach($lab_items as $lab_item)
                                <tr>
                                    <td style="text-align:left;"><b>{{ $loop->iteration }}</b></td>
                                    <td>{{($lab_item->name)?$lab_item->name:$lab_item->lap_panel}}</td>
                                    <td style="text-align:right;">{{$lab_item->quantity}}</td>
                                    <td style="text-align:right;">{{$lab_item->rate}}</td>
                                    <td style="text-align:right;">{{$lab_item->discount}}</td>
                                    <td style="text-align:right;">{{$lab_item->net_amount}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="second-table" style="width:100%;">
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align:left;"><b>Amount In Words :&nbsp;&nbsp;{{ ConvertToEnglishWords($lab_bill_details->total_net_amount) }}</b></td>
                            <td colspan="1"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><b>Gross Amount&emsp;: </b></td>
                            <td colspan="1" style="text-align:right;">{{ $lab_bill_details->total_gross_amount }}&emsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><b>Discount Amt&emsp;:</b></td>
                            <td colspan="1" style="text-align:right;">{{ $lab_bill_details->total_discount_amount }}&emsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><b>Net Amt&emsp;: </b></td>
                            <td colspan="1" style="text-align:right;">{{ $lab_bill_details->total_net_amount }}&emsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><b>Paid&emsp;: </b></td>
                            <td colspan="1" style="text-align:right;">{{ $lab_bill_details->total_paid_amount }}&emsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><b>Refund&emsp;:</b></td>
                            <td colspan="1" style="text-align:right;">{{ $lab_bill_details->total_refund_amount }}&emsp;</td>
                        </tr>
                    </tbody>
                </table>
                <table class="second-table" style="width:100%;">
                    <tr>
                        <td colspan="4" style="text-align:left;"><b>Receipt Details</b></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:left;"><b>Receipt	, {{$patient->patient_no}},	Rs.	{{ $lab_bill_details->total_net_amount }} , {{ $payment_method->code }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                    <tr>
                        <td colspan="4" style="text-align: right; padding-right:20px;">{{$receptionist_name}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right; padding-right:20px;"><b style="border-top:1px solid lightgrey; padding-top:3px;">Receptionist</b></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right; padding-right:20px; padding-top:20px;"><img src="{{$sign_encoded}}" alt="signature" width="80px"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:right; padding-right:20px; padding-bottom:20px;"><b style="border-top:1px solid lightgrey; padding-top:3px;">Authorized Signature</b></td>
                    </tr>

                </table>
            </div>
        </div>
        <div>
            <p> Generated on : {{ $lab_bill_details->generated_date_bs }}</p>
            <p> Printed on : {{now()->format('d/m/Y H:i:s')}}</p>
        </div>


</body>

</html>
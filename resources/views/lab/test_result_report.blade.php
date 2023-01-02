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
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        @media print {
            @page {
                margin-top: 10px;
                margin-left: 30px;
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
        }

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
            border: 1px solid black;
            padding: 0 .5rem;
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

        .test-table{
            border: 1px solid black;
            border-collapse: collapse;
            padding: 0.5rem;
        }
        .thead th {
            text-align: left;
            border: 1px solid black;
            border-collapse: collapse;
            padding: 0.2rem .5rem;
        }
        .test-table thead th{
            text-align: left;
        }

        .heading{
            margin-top:-10px;
        }
       
        .heading table tr{
            line-height: 1rem;
        }
        .heading table tr th,.heading table tr td{
            font-size: 12px !important;
        }
        .test-table table tr, table tbody tr td {
            /* line-height: 1rem !important; */
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
            text-underline-position: under;
            padding-bottom: 10px;
        }

        .signatory h5{
            padding:.5rem;
        }
        .signatory p{
            padding: 0 .5rem;
        }

        /* .signatory,
        footer {
            padding: 1rem 0rem;
        } */

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
        .sample_method{
            font-size: 11px !important;
        }

        .sample-body>tr>td{
            padding-left:18px;
            padding-right: 18px;
        }
        .sample-body tr {
            line-height: 0.9rem;
        }
        .transparent-border{
            border-top: 3px solid transparent !important;
        }
        .sign {
            margin-top:-25px;
        }
        .doc-sign {
           text-align: right;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row row1">
            @if ($patient_photo_encoded)
                <div class="col-2">
                    <img src="{{$logo_encoded}}" alt="logo-of-lab" style="max-width:150px;padding: 0px 10px 0px 0px;margin-left: 0rem !important;">
                </div>
            @else
                <div class="col-2 header-img">
                    <img src="{{$logo_encoded}}" alt="logo-of-lab" style="max-width:150px;padding: 0px 5px 0px 0px;">
                </div>
            @endif
            <div class="col-8 text-center">
                <div class="col-8 text-center content">
                    @if(isset($report_header))
                    <h4>{{$report_header->letter_head_title_1?:'Your Main Title'}}</h4>
                    <p>{{$report_header->letter_head_title_2?:'Your second level title will appear here'}}</p>
                    <p>{{$report_header->letter_head_title_3?:''}}</p>
                    <p>{{$report_header->letter_head_title_4?:''}}</p>
                    @if($report_header->remarks)
                        <p>{{$report_header->remarks?:'If you want extra title that will come here from remarks'}}</p>
                    @endif
                    @else
                        <h2>Your Main Title</h2>
                        <p>Your second level title will appear here</p>
                        <p>Full address of your company will appear here</p>
                        <p>Email: &nbsp;Your email will apear here&nbsp;&nbsp;Phone No: &nbsp;Your phone number</p>
                        <p>If you want extra title that will come here from remarks</p>
                    @endif
                    <h4>Laboratory Report</h4>
                </div>

            </div>
            @if ($patient_photo_encoded)
                <div class="col-md-2">
                    <img src="{{$patient_photo_encoded}}" alt="logo-of-lab" style="max-width:200px;padding-left: 6em !important;">
                </div>
            @endif
        </div>
        <!-- Start of heading info section -->
        <div class="row heading">
            <div class="col-4">
                <table class="table">
                    <tr>
                        <th>Patient ID:</th>
                        <td>{{$patient_detail->patient_no}}</td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>{{$patient_detail->name}}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{$patient_detail->street_address?:''}}</td>
                    </tr>
                    <tr>
                        <th>DOB:</th>
                        <td>{{$patient_detail->date_of_birth?:''}}</td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td>{{$patient_detail->gender->name?:''}}</td>
                    </tr>
                    <tr>
                        <th>Passport No:</th>
                        <td>{{$patient_detail->passport_no?:''}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-4">
                <table class="table">
                    <tr>
                        <th>Order ID:</th>
                        <td>{{$lab_test_detail->order_no?:''}}</td>
                    </tr>
                    <tr>
                        <th>Referral:</th>
                        @if($patient_detail->is_referred)
                            <td>{{$patient_detail->referrer_hospital_name?:''}}<br>{{$patient_detail->referrer_doctor_name?:''}}</td>
                        @else
                            <td>Self</td>
                        @endif
                    </tr>

                    <tr>
                       <td colspan="2"> <div style="margin: 0.5rem 0rem;"><hr></div></td>
                    </tr>
                    <tr>
                        <th>Sample/VTM ID:</th>
                        <td>{{$lab_test_detail->sample_no}}</td>
                    </tr>
                    <tr>
                        <th>Collected Time:</th>
                        <td>{{ $lab_test_detail->collection_datetime?:'-' }}</td>
                    </tr>
                    <tr>
                        <th>Reported Time:</th>
                        <td>{{ $lab_test_detail->reported_datetime?:'-' }}</td>
                    </tr>
                </table>
            </div>
            <div style="margin-top: 30px" class="col-4">
                <table class="table">
                   
                    <tr>
                        <th>Nationality : </th>
                        <td>{{$patient_detail->nationality?:''}}</td>
                    </tr>
                    <tr>
                        <th>ID Type : </th>
                        @if($patient_detail->citizenship_no)
                            <td>Citizenship:</td>
                        @elseif($patient_detail->national_id_no)
                            <td>National Id Card:</td>
                        @elseif($patient_detail->voter_no)
                            <td>Voter Card:</td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                    <tr>
                        <th>Id Number:</th>
                        @if($patient_detail->citizenship_no)
                            <td>{{$patient_detail->citizenship_no}}</td>
                        @elseif($patient_detail->national_id_no)
                            <td>{{$patient_detail->national_id_no}}</td>
                        @elseif($patient_detail->voter_no)
                            <td>{{$patient_detail->voter_no}}</td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                    <tr>
                        <th>Mobile No:</th>
                        <td>{{$patient_detail->cell_phone}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- End of info section -->
        <!-- start of test section -->
        <div class="row">
            <div class="table-col">
                <div class="col lab-text">
                    <h4>{{$currentEntry->category->title}} Report</h4>
                </div>
                <table class="test-table" style="width:100%">
                    <thead class="thead text-left">
                        <th>Test (Method)-Sample Type</th>
                        <th>Result</th>
                        <th>UOM</th>
                        <th>Flag</th>
                        <th>Reference Range</th>
                        {{-- <th>Methodology</th> --}}
                    </thead>
                    <tbody class="sample-body">
                       @if(count($items_order))
                        @foreach ($items_order as $key => $items_array)
                            <tr> 
                                <td><b><u>{{$key}}</u></b> </td>
                                <td colspan="4"></td>
                            </tr>

                            @foreach($items_array as $row_item)
                             {{-- first,check if loop_item is item or group 
                                if it is item, get all value directly
                                if not loop again over group items --}}
                                @if($row_item['type'] == 'item')
                                    <tr class="transparent-border">
                                        <td>
                                            &emsp;&emsp;<b>{{$row_item['item']->name}}</b><br>
                                            &emsp;&emsp;<span class="sample_method">( {{($row_item['item']->sample) ?$row_item['item']->sample->name :''}}, {{($row_item['item']->method) ?$row_item['item']->method->name:''}})</span>
                                        </td>
                                        <td >{{ $row_item['result']->result_value?:'-' }}</td>
                                        <td >{{ $row_item['item']->unit?:'-' }}</td>
                                        <td >{{  $flag_options[$row_item['result']->flag]?:'-' }}</td>
                                        @if($row_item['item']->is_special_reference)
                                            <td >{!! $row_item['item']->special_reference !!}</td>
                                        @else
                                            <td >{{ $row_item['item']->reference_from_value}} - {{$row_item['item']->reference_from_to }}</td>
                                        @endif
                                        {{-- <td >{{ $item['result']->methodology }}</td> --}}
                        
                                    </tr>
                                @else
                                    <tr class="transparent-border">
                                        <td>&emsp;&emsp;<b><u>{{ $row_item['group_name'] }}</u></b></td>
                                        <td colspan="4"></td>
                                    </tr>
                                    {{-- looping through group items --}}
                                    @foreach ($row_item['group_items'] as $key => $g_item)
                                    <tr>
                                        <td>
                                            &emsp;&emsp;&emsp;&emsp;<b>{{$g_item['item']->name}}</b><br/>
                                            &emsp;&emsp;&emsp;&emsp;<span class="sample_method">( {{ ($g_item['item']->sample) ? $g_item['item']->sample->name : ''}}, {{ ($g_item['item']->method) ? $g_item['item']->method->name:''}})</span>
                                        </td>
                                        <td >{{ $g_item['result']->result_value ?:'-' }}</td>
                                        <td >{{ $g_item['item']->unit?:'-' }}</td>
                                        <td >{{ $flag_options[$g_item['result']->flag]}}</td>
                                        @if($g_item['item']->is_special_reference)
                                        <td >{!! $g_item['item']->special_reference !!}</td>
                                        @else
                                        <td >{{ $g_item['item']->reference_from_value}} - {{ $g_item['item']->reference_from_to }}</td>
                                        @endif
                                        {{-- <td >{{ $group['result']->methodology }}</td> --}}
                                    </tr>
                                    @endforeach

                                @endif
                            @endforeach
                        @endforeach

                       @endif

                        @if($items)
                            @foreach($items as $key => $item)
                            <tr colspan="5"></tr>
                                <tr class="transparent-border">
                                    <td>
                                       <b>{{$item['item']->name}}</b><br>
                                       <span class="sample_method">( {{$item['item']->sample->name}}, {{$item['item']->method->name}})</span>
                                    </td>
                                    <td>{{ $item['result']->result_value?:'-' }}</td>
                                    <td>{{ $item['item']->unit?:'-' }}</td>
                                    <td>{{ $flag_options[$item['result']->flag]?:'-' }}</td>
                                    @if($item['item']->is_special_reference)
                                        <td >{!! $item['item']->special_reference !!}</td>
                                    @else
                                        <td >{{ $item['item']->reference_from_value}} - {{$item['item']->reference_from_to }}</td>
                                    @endif
                                    {{-- <td >{{ $item['item_detail']->methodology }}</td> --}}
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
        {{-- {{ dd('d') }} --}}
        <!-- End of test section -->
        <!-- start oo notes section -->
        @if($lab_test_detail->comment != '')
            <div class="notes">
                <h5><u>Interpretation :</u></h5>
            </div>
            <div>
                {!! $lab_test_detail->comment !!}
            </div>
        @endif
        <!-- End oo notes section -->

        <div class="endnote">
            <p>****End of the Report****</p>
        </div>
        <!-- start of signatory section -->
        <div class="row signatory">
            <div class="col-4 sign">
                <img src="{{$tech_sign_encoded}}" alt="stamp" width="80px">
                <p class="notes">Test Processed By</p>
                @if(isset($lab_technican))
                <h5>{{ App\Models\HrMaster\HrMstEmployees::$salutation_options[$lab_technican->salutation_id].' '.$lab_technican->full_name}}</h5>
                <p>{{$lab_technican->qualification}}</p>
                @endif
            </div>
            <div class="col-4">
                @if($stamp_encoded)
                    <img src="{{$stamp_encoded}}" alt="stamp" width="100px">
                @endif
            </div>
            <div class="col-4 sign doc-sign">
                <img src="{{$doc_sign_encoded}}" alt="stamp" width="80px">
                <p class="notes">Approved By</p>
                @if (isset($doctor_detail))
                <h5>{{  App\Models\HrMaster\HrMstEmployees::$salutation_options[$doctor_detail->salutation_id].' '.$doctor_detail->full_name }}</h5>
                <p>{{$doctor_detail->qualification}}</p>
                @endif
            </div>
        </div>
        <!-- End of signatory section -->

        <hr>

        <div class="row note-section">
            <div class="col-1" style="margin-right: 3rem;">
                {{$qr_code}}
            </div>
            <div class="col-10 note-size">
                <h4>Notes :</h4>
                <ol>
                    <li>This report is system generated and does not require any stamp & Signature. Please scan the QR
                        to verify the report through BidhLab Digital Platform.</li>
                    <li>This information was printed on: {{dateTimeNow()}}</li>
                    <li>Read through the 2D barcode reader of the mobile camera and check the website via 3G / GPRS</li>
                </ol>
            </div>
        </div>


</body>

</html>
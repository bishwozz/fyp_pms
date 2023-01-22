<html lang="en">
<head>
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin-top: 10px;
                margin-left: 40px;
                margin-right: 40px;
            }
        }
        .report-heading {
            text-align: center;
            font-size:18px;
        }
        .report-data {
            font-size:13px;
            color:black;
            text-align: center;
        }
        .report-data-td{
            color:white;
            text-align: center;
        }
        tr>th{
            border-bottom: 1px solid white !important;
            border-right: 1px solid white !important;
            background-color:#3B72A0 !important;
            color:white;
        }
        tfoot.table-dark{
            background-color: black;
        }
        </style>
</head>
<body>
    <div class="row mt-2">
        <div class="col">
            <center><h2 class="font-weight-bold">{{ $report_name }}</h2></center>
            </div>
    </div>

     <table id="report_data_table" class="table table-bordered table-striped table-sm" style="width:100%;background-color:lightgrey;">
        <thead>
            <tr>
                @foreach ($columns as $key => $column)
                        <th class="report-heading">{{$column}}</th>
                    @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($output as $key => $data)
            <tr>
                @foreach ($data as $d)
                    <td class="report-data">{{$d}}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
        @php
            $check_no_of_columns = count($columns);
        @endphp
        @if ( $report_name == 'Purchase Report' || $report_name == 'Sales Report')
            <tfoot class="table-dark">
                <tr>
                    <td class="report-data-td">Total</td>
                    {{-- <td class="report-data-td" colspan="{{ ($check_no_of_columns-4) }}"></td> --}}
                    <td class="report-data-td" id="gross_amount">{{ isset($gross_amount)?$gross_amount:'-' }}</td> 
                    <td class="report-data-td" id="discount_amount">{{ isset($discount_amount)?$discount_amount:'-' }}</td> 
                    <td class="report-data-td" id="total_amount">{{ isset($total)?$total:'-' }}</td> 
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
            font-size:13px;
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
        
        .th_large{
            min-width:170px !important;
        }
        .th_large1{
            min-width:130px !important;
        }
        tfoot.table-dark{
            background-color: black;
        }
        </style>
</head>
<body>
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
        @if(in_array($report_name,['Cash Report', 'Collection Report', 'Overall Collection Details', 'Bill Report',
                        'Referral Report', 'Discount Report', 'Cancel Bill Report', 'Test Price According To Referral', 'Due Collection Report']))
            <tfoot class="table-dark">
                <tr>
                    <td class="report-data-td">Total</td>
                    <td class="report-data-td" colspan="{{ ($check_no_of_columns-2) }}"></td>
                    <td class="report-data-td" id="total_amount">{{ isset($total)?$total:'-' }}</td> 
                </tr>
            </tfoot>
        @elseif ( $report_name = 'Credit Report')
            <tfoot class="table-dark">
                <tr>
                    <td class="report-data-td">Total</td>
                    <td class="report-data-td" colspan="{{ ($check_no_of_columns-3) }}"></td>
                    <td class="report-data-td" id="paid_amount">{{ isset($paid_total)?$paid_total:'-' }}</td> 
                    <td class="report-data-td" id="total_amount">{{ isset($total)?$total:'-' }}</td> 
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
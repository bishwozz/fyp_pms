
@php
$check_no_of_columns = count($columns);
$all_reports = array('Cash Report', 'Collection Report', 'Overall Collection Details', 'Cancel Bill Report', 'Due Collection Report');
@endphp
<div class="card">
<div class="row mt-2">
<div class="col">
    <center><h5 class="font-weight-bold">{{ $report_name }}</h5></center>
    </div>
</div>
<div class="row">
    <div class="col" style="margin-top:-30px;">
        <a href="javascript:;" onclick="printExcel()" class="btn btn-sm btn-primary la la-file-excel float-right mr-2"> Export to Excel</a>
        <a href="javascript:;" onclick="printpdf()" class="btn btn-sm btn-success la la-file-pdf float-right mr-3"> Export to PDF</a>
    </div>
</div>
    <div class="row mt-0">
        <div class="col-md-12">
                <div class="col-md-12 p-2" style="overflow-x:auto;">
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
            </div>
        </div>
    </div>  
</div>   
</div>

<style>
.report-heading {
text-align: center;
font-size:13px;
}
.report-data {
font-size:12px;
color:black;
text-align: center;
width: auto;
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
</style>

<script>
$(document).ready(function () {
$('#report_data_table').DataTable({
    searching: false,
    paging: true,
    ordering:false,
    select: false,
    bInfo : true,
    lengthChange: false
});
});
</script>


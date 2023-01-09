

<div class="card">
    <div class="row mt-2">
        <div class="col">
            <center><h5 class="font-weight-bold">{{ $report_name }}</h5>
        </div>
    </div>
        {{-- <div class="row">
            <div class="col" style="margin-top:-30px;">
                <a href="javascript:;" class="btn btn-sm btn-primary la la-file-excel float-right mr-2"> Export to Excel</a>
                <a href="javascript:;" class="btn btn-sm btn-success la la-file-pdf float-right mr-3"> Export to PDF</a>
            </div>
        </div> --}}
            <div class="row mt-0">
                <div class="col-md-12">
                        <div class="col p-2" style="overflow-x:auto;">
                            <table id="report_data_table" class="table table-bordered table-striped table-sm" style="background-color:lightgrey;">
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
        font-size:13px;
        color:black;
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
    
    
<div class="card border h-25 mt-2">
    <div class="card-body">
        <table class="table table-borderless text-center">
            <td class="col-md-2">Bill Date</td>
            <td class="col-md-3">Department</td>
            <td class="col-md-2">Approved Date</td>
            <td class="col-md-2">Approved By</td>
            <td class="col-md-2">Referral</td>
            <td class="col-md-1">Action</td>
        </table>
    </div>
</div>
@foreach ($reports as $report)
<div class="card border h-25 mt-2">
    <div class="card-body">
        <table class="table table-borderless text-center">
                <th class="col-md-2">{{date("Y-m-d",strtotime($report->reported_datetime))}} <br/> 
                    {{date("l",strtotime($report->reported_datetime))}}
                </th>
                <th class="col-md-3">{{$report->category}}</th>
                <th class="mob-rmv col-md-2">{{ date("Y-m-d",strtotime($report->approved_datetime))}}</th>
                <th class="mob-rmv col-md-2">{{$report->doctor}}</th>
                <th class="mob-rmv col-md-2">{{$report->referral}}</th>
                <th class="mob-rmv col-md-1">
                    <a class="btn btn-primary btn-sm text-bold" target="_blank"
                href="/lab-patient-test-data/{{$report->id}}/print-test-report"> 
                <i class="fa-regular fa-file-lines"></i> View</a>
                </th>
        </table>
    </div>
</div>
@endforeach
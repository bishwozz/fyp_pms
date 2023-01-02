@foreach ($reports as $report)
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="border-radius: 35px;  box-shadow: 0 10px 10px rgb(0 0 0 / 0.2);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2 col-xs-12 ">
                            <div
                                class="counter-icon h-100 d-flex justify-content-center align-items-center">
                                <a href="/lab-patient-test-data/{{$report->id}}/printSampleCollectionReport" target="_blank">
                                    <i class="fa-regular fa-file-pdf fa-4x text-success"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-7 col-xs-12">

                            <h5 class="card-title text-success font-weight-bold">{{$report->order_no}}</h5>
                            <a href="#" class="text-success">&#8226; DR. {{$report->doctor}} </a>
                            <p class="card-text">
                            <p style="float:left ;">Referred by : </p>
                            <p>{{$report->referral}}</p>
                            </p>
                            <div class="last">
                                <p class="datetime">{{$report->reported_datetime}}</p>

                                <p>&#8226; Reported By : {{$report->lab_technician}}</p>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-12 ">
                            <div class="icon-btn">
                                <ul>
                                    <li> <a href="/lab-patient-test-data/{{$report->id}}/printSampleCollectionReport" type="btn" id="save-btn"
                                            class=" btn btn-sm btn-warning text-white" download="/lab-patient-test-data/{{$report->id}}/printSampleCollectionReport"> <i
                                                class="fa-solid fa-download"> </i> Save</a></li>
                                    <li> <a href="/lab-patient-test-data/{{$report->id}}/printSampleCollectionReport" target="_blank" type="btn" id="view-btn"
                                            class="btn btn-sm btn-info "><i class="fa-regular fa-eye">
                                            </i> View</a></li>
                                    <li>
                                        <a href="/lab-patient-test-data/{{$report->id}}/printSampleCollectionReport" type="btn" id="print-btn"
                                            class="btn btn-sm btn-danger"><i class="fa-solid fa-print">
                                            </i> Print</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
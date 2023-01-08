@extends(backpack_view('blank'))
@section('content') 
    <div class="card mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    
                    <div class="card-body">
                        <div class="px-3 py-1 mb-2">
                            <h3>Reports</h3>
                        </div>
                        <div class="container">
                            <ul class="list-group">
                                <div class="row col-md-12">

                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/patient_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Purchase Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/patient_reports.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/covid_test_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Sales Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/covid_blood_test_report.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    {{-- <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/cash_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Cash Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/cash_reports.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div> --}}
                                </div>
                              </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>     
      
@endsection

@push('after_styles')
    <style>
        .heading{
            margin-left: 3%;
        }
    </style>
@endpush
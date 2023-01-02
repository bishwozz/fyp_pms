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
                                    @if(backpack_user()->hasRole('referral'))
                                        <div class="col-xl-3 col-lg-6">
                                            <a href="{{ "/admin/reports/referral_report" }}" style="text-decoration: none;">
                                                <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                                <div class="card-body ">
                                                    <div class="row">
                                                    <div class="col d-flex align-items-center">
                                                        <p class="mb-0 reports-card-title">Bill Report by Referral</p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <img class="reports-card-icon" src="{{asset('images/icons/bill_by_referral.png')}}" alt="">
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </a>
                                        </div>
                                    @else

                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/patient_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Patient Reports</p>
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
                                                    <p class="mb-0 reports-card-title">Covid Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/covid_blood_test_report.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
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
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/overall_collection_details" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Overall Collection Details</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/overall_collection_summary.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/credit_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Credit/Due Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/credit_reports.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/bill_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Bill Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/bill_reports.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/referral_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Bill Report by Referral</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/bill_by_referral.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/discount_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Discount Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/discount_reports.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/{cancel_bill_report}" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Cancel Bill Reports</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/cancel_bill_reports.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/department_wise_test_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Total Test Report According To Department</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/total_test_report_according_to_department.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                   
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/test_price_according_to_referral" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Test Price according To Referral Hospital</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/test_price_according_to_referral.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    {{-- due collection report --}}
                                    <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/due_collection_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">Due collection report</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/test_price_according_to_referral.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div>
                                    {{-- collection report --}}
                                    {{-- <div class="col-xl-3 col-lg-6">
                                        <a href="{{ "/admin/reports/collection_report" }}" style="text-decoration: none;">
                                            <div class="card p-2 card-stats mb-4 mb-xl-0 reports-card">
                                            <div class="card-body ">
                                                <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <p class="mb-0 reports-card-title">collection report</p>
                                                </div>
                                                <div class="col-auto">
                                                    <img class="reports-card-icon" src="{{asset('images/icons/test_price_according_to_referral.png')}}" alt="">
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                        </a>
                                    </div> --}}
                                    @endif
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

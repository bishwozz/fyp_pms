@extends(backpack_view('blank'))
@section('content') 
<div class="card">
        <div class="card">
            <div class="card-header bg-primary p-1"><i class="la la-search"aria-hidden="true"></i>Search
              <small><a href="{{ backpack_url('reports') }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
              <button onclick="clearData()" class="btn float-right" style="background: Red !important; color:white !important; padding:2px !important;font-size:15px !important;"><i class="fa fa-close"></i> {{ trans('Clear') }}</button>
            </div>
            <div class="card-body p-0">
                <form id="filter-form">
                    <div class="form-row ml-2">
                        <input type="hidden" value="{{ $report_type }}" id="report_type">
                        <div class="form-group col-md-4">
                            <label for="from_date">From Date</label>
                            <input type="date" class="form-control" id="from_date" placeholder="From Date">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="to_date">To Date</label>
                            <input type="date" class="form-control" id="to_date" placeholder="To Date">
                        </div>
                        {{--  patient_report --}}
                        @if ($report_type == 'patient_report')
                            <div class="form-group col-md-4">
                                <label for="patient_no">Patient No</label>
                                <input type="text" class="form-control" id="patient_no" placeholder="Patient No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="blood_group">Blood Group</label>
                                <select class="form-control" id="blood_group">
                                    <option value="">-</option>
                                    @foreach ($blood_groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Patient Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Patient Name">
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="gender_id">Gender</label>
                                <select class="form-control" id="gender_id">
                                    <option value="">-</option>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        {{--  covid_test_report --}}
                        @elseif ($report_type == 'covid_test_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Patient Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Test Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="result_type">Result Type</label>
                                <select class="form-control" id="result_type">
                                    <option value="">-</option>
                                    <option value="Negative">Negative</option>
                                    <option value="Positive">Positive</option>
                                </select>
                            </div>
                        {{--  test_price_according_to_referral --}}
                        @elseif ($report_type == 'test_price_according_to_referral' )
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="NAME">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="referred_by">Referred By</label>
                                <select class="form-control" id="referred_by">
                                <option value="">-</option>
                                    @foreach ($referals as $referred_by)
                                        <option value="{{ $referred_by->id }}">{{ $referred_by->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                        {{--  department_wise_test_report --}}
                        @elseif ($report_type == 'department_wise_test_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="patient_name">Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="department">Department</label>
                                <select class="form-control" id="department">
                                    <option value="">-</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="item">Items</label>
                                <select class="form-control" id="item">
                                    <option value="">-</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        {{--  cancel_bill_report --}}
                        @elseif ($report_type == 'cancel_bill_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bill_cancel_date">Cancel Date</label>
                                <input type="date" class="form-control" id="bill_cancel_date" placeholder="Date">
                            </div>
                            {{--  discount_report --}}
                        @elseif ($report_type == 'discount_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dis_patient_name">Name</label>
                                <input type="text" class="form-control" id="dis_patient_name" placeholder="Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dis_approver">Discount Approver</label>
                                <select class="form-control" id="credit_approver">
                                    <option value="">-</option>
                                    @foreach ($dis_approvers as $dis_approver)
                                        <option value="{{ $dis_approver->full_name }}">{{ $dis_approver->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        {{--  overall_collection_details --}}
                        @elseif ($report_type == 'overall_collection_details')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="payment_mode">Payment Mode</label>
                                <select class="form-control" id="payment_mode">
                                    <option value="">-</option>
                                    @foreach ($payment_modes as $payment_mode)
                                        <option value="{{ $payment_mode->id }}">{{ $payment_mode->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="user">User</label>
                                <select class="form-control" id="user">
                                    <option value="">-</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            

                        {{--  credit_report --}}
                        @elseif ($report_type == 'credit_report' )
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="credit_approver">Credit Approver</label>
                                <select class="form-control" id="credit_approver">
                                    <option value="">-</option>
                                    @foreach ($credit_approvers as $credit_approver)
                                        <option value="{{ $credit_approver->full_name }}">{{ $credit_approver->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        {{--  bill_report --}}
                        @elseif ($report_type == 'bill_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="is_paid">Is Paid</label>
                                <select class="form-control" id="is_paid">
                                    <option value="">-</option>
                                    <option value="0">False</option>
                                    <option value="1">True</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Name">
                            </div>

                        {{--  referral_report --}}
                        @elseif ($report_type == 'referral_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="referals">Referal</label>
                                @if(backpack_user()->hasRole('referral'))
                                        <select class="form-control" id="referals" readonly>
                                            <option value="{{ $referals->id }}" selected>{{ $referals->name }}</option>
                                        </select>
                                    @else
                                        <select class="form-control" id="referals">
                                            <option value="">-</option>
                                            @foreach ($referals as $referred_by)
                                                <option value="{{ $referred_by->id }}">{{ $referred_by->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                            </div>
                        {{-- due_collection_report --}}
                        @elseif ($report_type == 'due_collection_report')
                            <div class="form-group col-md-4">
                                <label for="bill_no">Bill No</label>
                                <input type="text" class="form-control" id="bill_no" placeholder="Bill No">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patient_name">Patient Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Patient Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="due_collector">Due Collector</label>
                                <select class="form-control" id="due_collector">
                                    <option value="">-</option>
                                    @foreach ($due_collectors as $due_collector)
                                        <option value="{{ $due_collector->id }}">{{ $due_collector->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        {{-- collection_report --}}
                        @elseif ($report_type == 'collection_report')
                            <div class="form-group col-md-4">
                                <label for="patient_name">Patient Name</label>
                                <input type="text" class="form-control" id="patient_name" placeholder="Patient Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="payment_mode">Mode</label>
                                <select class="form-control" id="payment_mode">
                                    <option value="">-</option>
                                    @foreach ($payment_modes as $payment_mode)
                                        <option value="{{ $payment_mode->id }}">{{ $payment_mode->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                    </div>
                  </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card h-100">
                    <div id="lms_report_data"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('after_scripts')
<script>
    function clearData(){
        $('#filter-form')[0].reset();
        getReportData();
    }
    $(document).ready(function () {

        // GENERAL
        // BILL NO
            $('#bill_no').on('keyup',function(){
                getReportData();
            });
        // DATE
            $( "#from_date" ).change(function() {
                var to_date = $( "#to_date" ).val();
                if(to_date){
                    getReportData();
                }
            });
            $( "#to_date" ).change(function() {
                from_date = $( "#from_date" ).val();
                if(from_date){
                    getReportData();
                }
            });
        // PATIENT NAME
                $('#patient_name').on('keyup',function(){
                    getReportData();
                });

        // PATIENT REPORT
            $('#patient_no').on('keyup',function(){
                getReportData();
            });
            $( "#gender_id" ).change(function() {
                getReportData();
            });
            $( "#blood_group" ).change(function() {
                getReportData();
            });

        // COVID REPORT
            $( "#result_type" ).change(function() {
                getReportData();
            });
            
        // OVERALL COLLECTION DETAILS
            $( "#payment_mode" ).change(function() {
                getReportData();
            });
            $( "#user" ).change(function() {
                getReportData();
            });
            
            
        // CREDIT REPORT
            $( "#credit_approver" ).change(function() {
                getReportData();
            });

        // BILL REPORT
            $( "#is_paid" ).change(function() {
                getReportData();
            });

        // REFERAL REPORT
            $( "#referals" ).change(function() {
                getReportData();
            });

        // DISCOUNT REPORT
            $('#dis_patient_name').on('keyup',function(){
                getReportData();
            });
            $( "#dis_approver" ).change(function() {
                getReportData();
            });


        // CANCLE BILL REPORT;
            $( "#bill_cancel_date" ).change(function() {
                getReportData();
            });

        // DEPARTMENT WISE TEST REPORT
            $( "#department" ).change(function() {
                getReportData();
            });
            $( "#item" ).change(function() {
                getReportData();
            });


        // TEST PRICE ACCORDING TO REFERAL
            $( "#referred_by" ).change(function() {
                getReportData();
            });
        // DUE COLLECTION REPORT
            $( "#due_collector" ).change(function() {
                getReportData();
            });
        
        
        getReportData();

    });

    function printpdf(){
        let data = `is_print=${true}`;
        if($('#report_type').val() !== '') {
            data += '&report_type=' + $('#report_type').val();
        }
        if($('#from_date').val() !== '') {
            data += '&from_date=' + $('#from_date').val();
        }
        if($('#to_date').val() !== '') {
            data += '&to_date=' + $('#to_date').val();
        }
        if($('#patient_no').val() !== '') {
            data += '&patient_no=' + $('#patient_no').val();
        }
        if($('#patient_name').val() !== '') {
            data += '&patient_name=' + $('#patient_name').val();
        }
        if($('#gender_id').val() !== '') {
            data += '&gender_id=' + $('#gender_id').val();
        }
        if($('#bill_no').val() !== '') {
            data += '&bill_no=' + $('#bill_no').val();
        }
        if($('#blood_group').val() !== '') {
            data += '&blood_group=' + $('#blood_group').val();
        }
        if($('#is_paid').val() !== '') {
            data += '&is_paid=' + $('#is_paid').val();
        }
        if($('#referals').val() !== '') {
            data += '&referals=' + $('#referals').val();
        }
        if($('#payment_mode').val() !== '') {
        data += '&payment_mode=' + $('#payment_mode').val();
        }
        if($('#credit_approver').val() !== '') {
        data += '&credit_approver=' + $('#credit_approver').val();
        }
        if($('#dis_approver').val() !== '') {
        data += '&dis_approver=' + $('#dis_approver').val();
        }
        if($('#department').val() !== '') {
            data += '&department=' + $('#department').val();
        }
        if($('#item').val() !== '') {
            data += '&item=' + $('#item').val();
        }
        if($('#result_type').val() !== '') {
            data += '&result_type=' + $('#result_type').val();
        }
        if($('#bill_cancel_date').val() !== '') {
            data += '&bill_cancel_date=' + $('#bill_cancel_date').val();
        }
        if($('#referred_by').val() !== '') {
            data += '&referred_by=' + $('#referred_by').val();
        }
        window.open('/admin/getreportdata?' + data);
    }

    function printExcel(){
       let data = `is_excel=${true}`;
        if($('#report_type').val() !== '') {
            data += '&report_type=' + $('#report_type').val();
        }
        if($('#from_date').val() !== '') {
            data += '&from_date=' + $('#from_date').val();
        }
        if($('#to_date').val() !== '') {
            data += '&to_date=' + $('#to_date').val();
        }
        if($('#patient_no').val() !== '') {
            data += '&patient_no=' + $('#patient_no').val();
        }
        if($('#patient_name').val() !== '') {
            data += '&patient_name=' + $('#patient_name').val();
        }
        if($('#gender_id').val() !== '') {
            data += '&gender_id=' + $('#gender_id').val();
        }
        if($('#bill_no').val() !== '') {
            data += '&bill_no=' + $('#bill_no').val();
        }
        if($('#blood_group').val() !== '') {
            data += '&blood_group=' + $('#blood_group').val();
        }
        if($('#is_paid').val() !== '') {
            data += '&is_paid=' + $('#is_paid').val();
        }
        if($('#referals').val() !== '') {
            data += '&referals=' + $('#referals').val();
        }
        if($('#payment_mode').val() !== '') {
        data += '&payment_mode=' + $('#payment_mode').val();
        }
        if($('#credit_approver').val() !== '') {
        data += '&credit_approver=' + $('#credit_approver').val();
        }
        if($('#dis_approver').val() !== '') {
        data += '&dis_approver=' + $('#dis_approver').val();
        }
        if($('#department').val() !== '') {
            data += '&department=' + $('#department').val();
        }
        if($('#item').val() !== '') {
            data += '&item=' + $('#item').val();
        }
        if($('#result_type').val() !== '') {
            data += '&result_type=' + $('#result_type').val();
        }
        if($('#bill_cancel_date').val() !== '') {
            data += '&bill_cancel_date=' + $('#bill_cancel_date').val();
        }
        if($('#referred_by').val() !== '') {
            data += '&referred_by=' + $('#referred_by').val();
        }
        window.open('/admin/getexceldata?' + data);
    }

    function getReportData(){
            let data = {
            is_print : false,
            report_type : $('#report_type').val(),
            from_date : $('#from_date').val(),
            to_date : $('#to_date').val(),
            patient_no : $('#patient_no').val(),
            patient_name : $('#patient_name').val(),
            gender_id : $('#gender_id').val(),
            bill_no : $('#bill_no').val(),
            blood_group :  $("#blood_group" ).val(),
            is_paid :  $("#is_paid" ).val(),
            referals :  $("#referals" ).val(),
            payment_mode :  $("#payment_mode" ).val(),
            dis_patient_name :  $("#dis_patient_name" ).val(),
            credit_approver :  $("#credit_approver" ).val(),
            dis_approver :  $("#dis_approver" ).val(),
            department :  $("#department" ).val(),
            item :  $("#item" ).val(),
            result_type :  $( "#result_type" ).val(),
            bill_cancel_date :  $( "#bill_cancel_date" ).val(),
            referred_by :  $( "#referred_by" ).val(),
            due_collector :  $( "#due_collector" ).val(),
            user :  $( "#user" ).val(),
        }
        // debugger;
        $('#lms_report_data').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
        $.ajax({
            type: "POST",
            url: "/admin/getreportdata",
            data: data,
            success: function(response){
                $('#lms_report_data').html(response);
            }
        });
    }

</script>
@endpush
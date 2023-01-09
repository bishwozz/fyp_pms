@extends(backpack_view('blank'))
@section('content') 
<div class="card">
		<div class="card">
			<div class="card-header bg-primary p-1"><i class="la la-search"aria-hidden="true"></i>Search
              <small><a href="{{ backpack_url('reports') }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
            </div>
			<div class="card-body p-0">
				<form>
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


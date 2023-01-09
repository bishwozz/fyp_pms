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
$(document).ready(function () {
    getReportData();
});

function getReportData(){
		let data = {
            report_type : $('#report_type').val(),
            from_date : $('#from_date').val(),
            to_date : $('#to_date').val(),
		}
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


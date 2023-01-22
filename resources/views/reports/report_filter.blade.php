@extends(backpack_view('blank'))
@section('content') 
@php
    $years = range(2020, strftime("%Y", time()));
@endphp
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
                        <div class="form-group col-md-4">
                            <label for="month">Month</label>
                            <select id='month' class="form-control">
                                <option value=''>--Select Month--</option>
                                <option value='01'>Janaury</option>
                                <option value='02'>February</option>
                                <option value='03'>March</option>
                                <option value='04'>April</option>
                                <option value='05'>May</option>
                                <option value='06'>June</option>
                                <option value='07'>July</option>
                                <option value='08'>August</option>
                                <option value='09'>September</option>
                                <option value='10'>October</option>
                                <option value='11'>November</option>
                                <option value='12'>December</option>
                                </select> 
                        </div>

                    </div>
                    <div class="form-row ml-2">
                        <div class="form-group col-md-4">
                            <label for="month">Year</label>
                            <select id="year" class="form-control">
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                  <option value=" {{ $year}}"> {{ $year }}</option>
                                @endforeach
                              </select>
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

            $( "#year" ).change(function() {
                // var month = $( "#month" ).val();
                // if(month){
                    getReportData();
                // }
            });
            $( "#month" ).change(function() {
                var year = $( "#year" ).val();
                if(year){
                    getReportData();
                }
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
        if($('#year').val() !== '') {
            data += '&year=' + $('#year').val();
        }
        if($('#month').val() !== '') {
            data += '&month=' + $('#month').val();
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
        if($('#year').val() !== '') {
            data += '&year=' + $('#year').val();
        }
        if($('#month').val() !== '') {
            data += '&month=' + $('#month').val();
        }
        window.open('/admin/getexceldata?' + data);
    }

    function getReportData(){
            let data = {
            is_print : false,
            report_type : $('#report_type').val(),
            from_date : $('#from_date').val(),
            to_date : $('#to_date').val(),
            year : $('#year').val(),
            month : $('#month').val(),
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


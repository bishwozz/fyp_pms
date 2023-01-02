@extends(backpack_view('blank'))

@section('header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <section class="container-fluid">
        <div class="row p-2">
             <div class="col-md-7 page-header"> 
                 <h4><span class="text"></span><i class="la la-columns"></i> Result Entry
                    <small><a href="{{ url()->previous() }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
                </h4>
             </div>
        </div>
	</section>
@endsection

@section('content')

<style>
    #interpretations_table td{
        vertical-align: middle;
        /* padding: 20px 0 10px 10px; */
    }
    .btn:hover{
        cursor: pointer;
    }
</style>

    <div class="row">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header bg-blue p-1 text-white"></div>
            <form role="form" action="{{backpack_url('lab/store-result')}}" id="result-entry-form" method="POST" style="width: 100%">
                {!! csrf_field() !!}
                <input type="hidden" name="patient_test_data_id" value="{{$patient_test_data_id}}">
                <input type="hidden" name="approve_status" id="approve_status" value="">
                <div class="row lab-billing-content sample-custom-div">
                    <table class="table table-responsive-lg patient-info-header">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th><input class="form-control" type="text" name="patient_name"
                                        value="{{isset($patient) ? $patient->name:''}}" readonly></th>
                                <th>Patient No</th>
                                <th><input class="form-control" type="text" name="patient_no"
                                        value="{{isset($patient) ? $patient->patient_no:''}}" readonly></th>
                                <th>Age / Sex</th>
                                <th><input class="form-control" type="text" name="age_sex"
                                        value="{{isset($patient) ? $patient->gender_age():''}}" readonly></th>
                            </tr>
                            <tr>
                                <th>Order No</th>
                                <th><input class="form-control" type="text" name="order_no"
                                        value="{{isset($order_no) ? $order_no:''}}" readonly></th>
                                <th>Sample No</th>
                                <th><input class="form-control" type="text" name="sample_no"
                                        value="{{isset($entry->sample_no) ? $entry->sample_no:''}}" readonly></th>

                                    @if(backpack_user()->hasAnyRole('superadmin|clientadmin|admin'))
                                        @if (isset($entry->reported_datetime))
                                            <th>Reported Datetime</th>
                                            <th><input class="form-control" type="datetime-local" name="reported_datetime"
                                                    value="{{isset($entry->reported_datetime) ? $entry->reported_datetime:''}}"></th>
                                        @else
                                            <th>Reported Datetime</th>
                                            <th><input class="form-control" type="datetime-local" name="reported_datetime" value=""></th>
                                        @endif
                                    @else
                                        <th>Reported Datetime</th>
                                        <th><input class="form-control" type="text" name="reported_datetime"
                                                value="{{isset($entry->reported_datetime) ? $entry->reported_datetime:''}}" readonly></th>
                                    @endif
                            </tr>
                            <tr>
                                <th>Lab Technician</th>
                                <th>
                                    @hasanyrole('lab_admin|lab_technician|lab_technologist')
                                        <select class="form-control _required" name="lab_technician_id" readonly>
                                            @if(isset($entry->lab_technician_id))
                                                <option class="selected" value="{{$entry->lab_technician_id}}">{{$entry->labTechnicianEntity->full_name}}</option>
                                            @elseif (backpack_user()->employee_id)
                                                <option class="selected" value="{{backpack_user()->employee_id}}">{{backpack_user()->employeeEntity->full_name}}</option>
                                            @else
                                                <option class="selected" value="">-</option>
                                            @endif
                                        </select>
                                    @endhasanyrole

                                    @hasanyrole('clientadmin|admin|doctor')
                                        <select class="form-control _required" name="lab_technician_id">
                                            @foreach($lab_technicians as $lab_technician)
                                                <option {{$lab_technician->id==$entry->lab_technician_id?'selected':''}} value="{{$lab_technician->id}}">{{isset($entry->lab_technician_id) && ($entry->lab_technician_id == $lab_technician->id)? $entry->labTechnicianEntity->full_name : $lab_technician->full_name}}</option>
                                            @endforeach
                                        </select>
                                    @endhasanyrole

                                @if(backpack_user()->hasAnyRole('clientadmin|admin') || ( isset(backpack_user()->employee_id) ? backpack_user()->employeeEntity->is_result_approver : ''))
                                    <th>Approved By</th>
                                    <th>
                                        <select class="form-control _required" name="doctor_id" readonly>
                                            @if(isset($entry->doctor_id))
                                                @foreach($doctors as $doctor)
                                                    <option {{$doctor->id==$entry->doctor_id?'selected':''}} value="{{$doctor->id}}">{{$entry->v == $doctor->id ? $entry->doctorEntity->full_name : $doctor->full_name}}</option>
                                                @endforeach
                                            @else
                                                @foreach($doctors as $doctor)
                                                    <option {{$doctor->id==backpack_user()->employee_id?'selected':''}} value="{{$doctor->id}}">{{backpack_user()->employee_id == $doctor->id ? backpack_user()->employeeEntity->full_name : $doctor->full_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </th>
                                @else
                                    <th>Approved By</th>
                                    <th><input class="form-control" type="text" readonly value="{{isset($entry->doctor_id) ? $entry->doctorEntity->full_name:''}}"></th>
                                @endif    
                                @if(backpack_user()->hasAnyRole('superadmin|clientadmin'))
                                    @if (isset($entry->approved_datetime))
                                        <th>Approved Datetime</th>
                                        <th><input class="form-control" type="text" name="approved_datetime"
                                            value="{{isset($entry->approved_datetime) ? $entry->approved_datetime:''}}" readonly></th>
                                    @else
                                        <th>Approved Datetime</th>
                                        <th><input class="form-control" type="datetime-local" name="approved_datetime" value=""></th>
                                    @endif
                                @else
                                    <th>Approved Datetime</th>
                                    <th><input class="form-control" type="text" name="approved_datetime"
                                        value="{{isset($entry->approved_datetime) ? $entry->approved_datetime:''}}" readonly></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <div class="col-md-12">
                        <div class="card h-100 lab-billing-items-header">
                            <div class="card-header p-0 px-2">
                                <div class="form-row mb-2">
                                    <div class="col-md-1 ml-2 text-center">
                                        <label for="span_name">S.N.</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="span_name">Test</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_quantity">Result</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_rate">UOM</label>  
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_amount">Flag</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_amount">Reference Range</label>
                                    </div>
                                    {{-- <div class="col text-center">
                                        <label for="span_amount">Methodology</label>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="card-body p-0 m-2" id="lab-billing-item-main-content">
                                @php
                                 $i=1;   
                                 $encoded_panels=json_encode($panels);
                                 $encoded_items=json_encode($items);
                                 $color_options = [
                                    0=>'text-black',
                                    1=>'text-danger',
                                    2=>'text-success',
                                    3=>'text-danger',
                                 ];
                                @endphp
                            {{-- check if items_order array exists --}}
                                @if(count($items_order))
                                    @foreach($items_order as $key=>$items_array)
                                        <div class="form-row">
                                            <div class="col-md-1 ml-2 text-center">
                                                <label class="mt-1" for="span_name">{{$i++}}</label>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="border-bottom panel-name">{{$key}}</span>
                                            </div>
                                        </div>
                                        {{-- loop through items in particular panel --}}
                                        @foreach($items_array as $row_item)
                                        {{-- first,check if loop_item is item or group 
                                            if it is item, get all value directly
                                            if not loop again over group items --}}
                                            @if($row_item['type'] == 'item')
                                                <div class="form-row">
                                                    <div class="col-md-1 ml-2 text-center">
                                                        <label class="mt-1" for="span_name">&nbsp;</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control panel-item-name" value="{{$row_item['item']->name??''}}" readonly>
                                                    </div>
                                                    <div class="col text-center">
                                                        @if($row_item['item']->result_field_type==0)
                                                            <input name="results[{{$row_item['result']->id}}]" class="form-control" onkeyup="changeFlag(this,{{json_encode($row_item)}})" type="number" step="any" value={{ $row_item['result']->result_value??'' }}>
                                                        @elseif($row_item['item']->result_field_type==2)
                                                            <select name="results[{{$row_item['result']->id}}]" class="form-control select-class">
                                                                @foreach(json_decode($row_item['item']->result_field_options) as $key => $option)
                                                                    <option class="form-control" {{ $row_item['result']->result_value==$option->result_field_options?'selected':'' }} value="{{$option->result_field_options}}">{{$option->result_field_options}}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <input name="results[{{$row_item['result']->id}}]" class="form-control" onkeyup="changeFlag(this,{{json_encode($row_item)}})" type="text" value={{ $row_item['result']->result_value??'' }}>
                                                        @endif
                                                    </div>
                                                    <div class="col text-center">
                                                        <input class="form-control" type="text" value="{{ $row_item['item']->unit??'-' }}" readonly>
                                                    </div>
                                                    <div class="col text-center">
                                                        <select name="flags[{{$row_item['result']->id}}]" id="flag-{{$row_item['result']->id}}" data-itemid={{$row_item['result']->id}} class="form-control select-class flag">
                                                            @foreach($flag_options as $key => $value)
                                                                <option class="form-control" {{ $row_item['result']->flag==$key?'selected':'' }} value="{{$key}}">{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col text-center">
                                                        @if($row_item['item']->is_special_reference==1)
                                                            <div style="border:1px solid black;">{!! $row_item['item']->special_reference !!}</div>
                                                        @else
                                                            <input class="form-control" value="{{ $row_item['item']->reference_from_value}}-{{$row_item['item']->reference_from_to }}" type="text" readonly>
                                                        @endif
                                                    </div>
                                                    {{-- <div class="col text-center">
                                                        <input class="form-control" value="{{ $row_item['result']->methodology}}" type="text" name="methodologies[{{$row_item['result']->id}}]">
                                                    </div> --}}
                                                </div>
                                            @else
                                                <div class="form-row">
                                                    <div class="col-md-1 ml-2 text-center">
                                                        <label class="mt-1" for="span_name">&nbsp;</label>
                                                    </div>
                                                    <div class="col-md-4 my-2">
                                                        <span class="border-bottom group-name">{{$row_item['group_name']}}</span>
                                                    </div>
                                                </div>

                                                {{-- looping through group items --}}
                                                @foreach ($row_item['group_items'] as $key => $item)
                                                    <div class="form-row">
                                                        <div class="col-md-1 ml-2 text-center">
                                                            <label class="mt-1" for="span_name">&nbsp;</label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input class="form-control group-item-name" type="text" value="{{$item['item']->name??''}}" readonly>
                                                        </div>
                                                        <div class="col text-center">
                                                            @if($item['item']->result_field_type==0)
                                                                <input class="form-control" name="results[{{$item['result']->id}}]" type="number" onkeyup="changeFlag(this,{{json_encode($item)}})" step="any" value={{ $item['result']->result_value??'' }}>
                                                            @elseif($item['item']->result_field_type==2)
                                                                <select name="results[{{$item['result']->id}}]" class="form-control select-class">
                                                                    @foreach(json_decode($item['item']->result_field_options) as $key => $option)
                                                                        <option class="form-control" {{ $item['result']->result_value==$option->result_field_options?'selected':'' }} value="{{$option->result_field_options}}">{{$option->result_field_options}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <input class="form-control" name="results[{{$item['result']->id}}]" onkeyup="changeFlag(this,{{json_encode($item)}})" type="text" value={{ $item['result']->result_value??'' }}>
                                                            @endif
                                                        </div>
                                                        <div class="col text-center">
                                                            <input class="form-control" type="text" value="{{ $item['item']->unit??'-' }}" readonly>
                                                        </div>
                                                        <div class="col text-center">
                                                            <select name="flags[{{$item['result']->id}}]" id="flag-{{$item['result']->id}}" data-itemid={{$item['result']->id}} class="form-control select-class flag">
                                                                @foreach($flag_options as $key => $value)
                                                                    <option class="form-control" {{ $item['result']->flag==$key?'selected':'' }} value="{{$key}}">{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col text-center">
                                                            @if($item['item']->is_special_reference==1)
                                                                <div style="border:1px solid black;">{!! $item['item']->special_reference !!}</div>
                                                            @else
                                                                <input class="form-control" value="{{ $item['item']->reference_from_value}}-{{$item['item']->reference_from_to }}" type="text" readonly>
                                                            @endif
                                                        </div>
                                                        {{-- <div class="col text-center">
                                                            <input class="form-control" value="{{ $item['result']->methodology}}" type="text" name="methodologies[{{$item['result']->id}}]">
                                                        </div> --}}
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach

                                    @endforeach
                                @endif


                                @if(count($items))
                                    @foreach($items as $key => $item)
                                        <div class="form-row">
                                            <div class="col-md-1 ml-2 text-center">
                                                <label class="mt-1" for="span_name">{{$i++}}</label>
                                            </div>
                                            <div class="col-md-4">
                                                    <input type="text" class="form-control" value="{{$item['item']->name??''}}" readonly>
                                            </div>
                                            <div class="col text-center">
                                                @if($item['item']->result_field_type==0)
                                                    <input name="results[{{$item['result']->id}}]" class="form-control" onkeyup="changeFlag(this,{{json_encode($item)}})" type="number" step="any" value={{ $item['result']->result_value??'' }}>
                                                @elseif($item['item']->result_field_type==2)
                                                    <select name="results[{{$item['result']->id}}]" class="form-control select-class">
                                                        @foreach(json_decode($item['item']->result_field_options) as $key => $option)
                                                            <option class="form-control" {{ $item['result']->result_value==$option->result_field_options?'selected':'' }} value="{{$option->result_field_options}}">{{$option->result_field_options}}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input name="results[{{$item['result']->id}}]" class="form-control" onkeyup="changeFlag(this,{{json_encode($item)}})" type="text" value={{ $item['result']->result_value??'' }}>
                                                @endif
                                            </div>
                                            <div class="col text-center">
                                                <input class="form-control" type="text" value="{{ $item['item']->unit ?? '-' }}" readonly>
                                            </div>
                                            <div class="col text-center">
                                                <select name="flags[{{$item['result']->id}}]" id="flag-{{$item['result']->id}}" data-itemid={{$item['result']->id}} class="form-control select-class flag">
                                                    @foreach($flag_options as $key => $value)
                                                        <option class="form-control" {{ $item['result']->flag==$key?'selected':'' }} value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col text-center">
                                                @if($item['item']->is_special_reference==1)
                                                    <div style="border:1px solid black;">{!! $item['item']->special_reference !!}</div>
                                                @else
                                                    <input class="form-control" value="{{ $item['item']->reference_from_value}}-{{$item['item']->reference_from_to }}" type="text" readonly>
                                                @endif
                                            </div>
                                            {{-- <div class="col text-center">
                                                <input class="form-control" value="{{ $item['result']->methodology}}" type="text" name="methodologies[{{$item['result']->id}}]">
                                            </div> --}}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <hr class="hr-line">
                            <div class="card-footer">
                                <a class="btn btn-primary btn-sm fancybox" id="myfancybox_btn" data-fancybox href="#myfancybox">+ Add interpretation</a>
                            </div>

                            <div class="col-md-8" style="display: none;" id="myfancybox">
                                <div class="card-body table-responsive">
                                    <table class="table table-hover table-bordered" id="interpretations_table">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($interpretations as $interpretation)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="interpretation-{{$interpretation->id}}" onclick="changeInterpretation({{json_encode($interpretation)}})" class="interpretations" value="{{ $interpretation->description }}"/>
                                                    </td>
                                                    <td>{{$interpretation->name}}</td>
                                                    <td>{!! $interpretation->description !!}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td></td>
                                                    <td class="text-danger font-weight-bold">Sorry,No interpretations found</td>
                                                    <td></td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer float-right">
                                    <a class="btn btn-success text-white" onclick="submitInterpretation()" role="button">✔ Select</a>
                                    <a class="btn btn-primary" data-fancybox-close>❌Cancel</a>
                                </div>
                            </div>
                            <div class="row m-2">
                                <div class="col-md-6">
                                    <label for="remarks">Interpretation<sub class="text-muted text-small"
                                            style="font-family: cursive">(Optional)</sub></label>
                                    <textarea id="summernote" class="form-control" name="comment">{{isset($comment)?$comment:''}}</textarea>
                                </div>
                            </div>
                            <div class="row m-2">
                            </div>
                            <div class="row mb-4">
                                <div class="col text-right">
                                @if(backpack_user()->hasAnyRole('clientadmin|admin|doctor') || ( isset(backpack_user()->employee_id) ? backpack_user()->employeeEntity->is_result_approver : ''))
                                    <a href="javascript:;" class="btn btn-sm btn-success mr-5" role="button" onclick="setApproveStatus()"><i class="fa fa-floppy-o"></i>&nbsp; Approve </a>
                                    <button type="submit" class="btn btn-sm btn-blue mr-5" role="button"><i class="fa fa-floppy-o"></i>&nbsp; Save </button>
                                @elseif(backpack_user()->hasAnyRole('lab_admin|lab_technician|lab_technologist'))
                                    @if($entry->approve_status != 1)
                                        <button type="submit" class="btn btn-sm btn-blue mr-5" role="button"><i class="fa fa-floppy-o"></i>&nbsp; Save </button>
                                    @endif
                                @endif    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            
            $('#summernote').summernote();
            $('#interpretations_table').DataTable();

        });
        function changeInterpretation(interpretation){
            if($(`#interpretation-${interpretation.id}`).is(':checked')){
                $('.interpretations').prop('checked', false);
                $(`#interpretation-${interpretation.id}`).prop('checked','checked');
            }else{
                $('.interpretations').prop('checked', false);
            }
        }
        function submitInterpretation(){
            let checked_box = $('.interpretations:checkbox:checked');
            $.fancybox.close();
            $('#summernote').summernote('code',checked_box.val());
        }
        $('.fancybox').fancybox({
            closeClick:false,
            clickSlide: false, // disable close on outside click
            touch: false // disable close on swipe
        });
        highlightColor();

        function highlightColor()
        {
            $('.flag').each(function(index,ele){
            if(ele.value == 0){
                $('#flag-'+$(ele).data('itemid')).removeClass('high_low').removeClass('normal');
            }else if(ele.value == 1){
                $('#flag-'+$(ele).data('itemid')).removeClass('normal').addClass('high_low');
            }else if(ele.value == 2){
                $('#flag-'+$(ele).data('itemid')).removeClass('high_low').addClass('normal');
            }else if(ele.value == 3){
                $('#flag-'+$(ele).data('itemid')).removeClass('normal').addClass('high_low');
            }
        });
        }

        function changeFlag(e,item){
            // 0 => '-',
            // 1 => 'High',
            // 2 => 'Normal',
            // 3 => 'Low'
            if(!item.item.is_special_reference){
                let reference_from = item.item.reference_from_value;
                let reference_to = item.item.reference_from_to;
                let includes_greater_than = false;
                let includes_less_than = false;
                if(reference_from){
                    if(reference_from.includes('>')){
                        includes_greater_than=true;
                    }
                }
                if(reference_to){
                    if(reference_to.includes('<')){
                        includes_less_than=true;
                    }
                }
                if(includes_greater_than){
                    if(e.value>parseFloat(item.item.reference_from_value.replace('>', ''))){
                        $(`#flag-${item.result.id}`).val(2).change();
                    }else{
                        $(`#flag-${item.result.id}`).val(3).change();
                    }
                }else if(includes_less_than){
                    if(e.value<parseFloat(item.item.reference_from_to.replace('<', ''))){
                        $(`#flag-${item.result.id}`).val(2).change();
                    }else{
                        $(`#flag-${item.result.id}`).val(1).change();
                    }
                }else{
                    if(e.value>=parseFloat(item.item.reference_from_value) && e.value<=parseFloat(item.item.reference_from_to)){
                        $(`#flag-${item.result.id}`).val(2).change();
                    }else if(e.value<parseFloat(item.item.reference_from_value)){
                        $(`#flag-${item.result.id}`).val(3).change();
                    }else if(e.value>parseFloat(item.item.reference_from_to)){
                        $(`#flag-${item.result.id}`).val(1).change();
                    }else{
                        $(`#flag-${item.result.id}`).val(0).change();
                    }
                }
            }
            highlightColor();
        }

        function setApproveStatus()
        {
            $('#approve_status').val(1);
            $('form#result-entry-form').submit();

        }
        //js for saving form
        $('#result-entry-form').validate({
            submitHandler: function(form) {
                swal({
                    closeOnClickOutside: false,
                    title: "Confirm And Save !!",
                    text: "Store test results",
                    buttons: {
                        no: {
                            text: " No ",
                            value: false,
                            visible: true,
                            className: "btn btn-secondary px-5",
                            closeModal: true,
                        },
                        yes: {
                            text: " Yes ",
                            value: true,
                            visible: true,
                            className: "btn btn-success px-5",
                            closeModal: true,
                        }
                    },
                }).then((confirmResponse) => {
                    if (confirmResponse) {
                        LMS.lmsLoading(true,'Saving...');
                        let data = new FormData(form);
                        let url = form.action;
                        axios.post(url, data)
                        .then((response) => {
                            if(response.data.status == true){
                                document.location = response.data.url;
                            }else{
                                swal("Error !", response.data.message, "error")
                            }
                            LMS.lmsLoading(false);
                        }, (error) => {
                            swal("Error !", error.response.data.message, "error")
                            LMS.lmsLoading(false);
                        });
                    }else{
                        $('#approve_status').val('');
                    }
                });
            }
	    });
    </script>
@endpush
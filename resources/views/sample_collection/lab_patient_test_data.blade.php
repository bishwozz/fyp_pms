@extends(backpack_view('blank'))

@section('header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<section class="container-fluid">
    <div class="row p-2">
        <div class="col-md-7 page-header">
            <h4><span class="text"></span><i class="la la-columns"></i> Sample Collection
                <small><a href="{{url()->previous()}}" class="hidden-print back-btn"><i
                            class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
            </h4>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card h-100">
            <div class="card-header bg-blue p-1 text-white"></div>
            <form role="form" id="sample-collection-form" action="{{backpack_url('lab/sample-collect')}}" method="POST" style="width: 100%">
                {!! csrf_field() !!}
                <input type="hidden" name="patient_test_data_id" value="{{$patient_test_data_id}}">
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
                                        <th>Collection Datetime</th>
                                @if(backpack_user()->can('update labpatienttestdata'))
                                    @if (isset($entry->collection_datetime))
                                        <th><input class="form-control" type="datetime-local" name="collection_datetime"
                                            value="{{isset($entry->collection_datetime) ? $entry->collection_datetime:''}}"readonly ></th>
                                    @else
                                        <th><input class="form-control" type="datetime-local" value="{{ date("Y-m-d H:i") }}" name="collection_datetime"></th>
                                    @endif
                                @else
                                    <th><input class="form-control" type="datetime-local" name="collection_datetime"
                                            value="{{isset($entry->collection_datetime) ? $entry->collection_datetime:''}}"readonly ></th>
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
                                        <label for="span_name">Test Name</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_quantity">Sample</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_rate">Method</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_amount">Status</label>
                                    </div>
                                    <div class="col text-center">
                                        <label for="span_amount">Barcode</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0 m-2" id="lab-billing-item-main-content">
                                @php
                                $i=1;
                                @endphp
                                @if(count($panels))
                                    @foreach ($panels as $panel)
                                        <div class="form-row lab-billing-item-row mt-2">
                                            <div class="col-md-1 ml-2 text-center">
                                                {{$i++}}
                                            </div>
                                            <div class="col-md-4 panel_group_item">
                                                <input class="form-control bg-secondary btn-link" type="text"
                                                    value="{{$panel['panel']->name}}" readonly>
                                                <ul>
                                                    @if($panel['panel']->groups)
                                                        @foreach($panel['panel']->groups as $group)
                                                            <input class="form-control mt-1" type="text" value="{{$group->name}}"
                                                                readonly>
                                                        @endforeach
                                                    @endif
                                                    @if(count($panel['panel']->items))
                                                        @foreach($panel['panel']->items as $item)
                                                            <input class="form-control mt-1" type="text" value="{{$item->name}}"
                                                                readonly>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="col">
                                                @if(count($panel['panel']->items) && isset($panel['panel']->items[0]->sample))
                                                <input class="form-control text-center" type="text"
                                                    value="{{$panel['panel']->items[0]->sample->name}}" readonly>
                                                @endif
                                            </div>
                                            <div class="col">
                                                @if(count($panel['panel']->items) && isset($panel['panel']->items[0]->method))
                                                <input class="form-control text-center" type="text"
                                                    value="{{$panel['panel']->items[0]->method->name}}" readonly>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-center" type="text"
                                                    value="{{$collection_status}}" readonly>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-center" type="text" name="panel_barcodes[{{$panel['panel']->id}}]"
                                                    value="{{$panel['barcode']}}" >
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                @if(count($items))
                                    @foreach ($items as $item)
                                        <div class="form-row lab-billing-item-row mt-2" id="lab-billing-item-row">
                                            <div class="col-md-1 ml-2 text-center mt-2">
                                                {{$i++}}
                                            </div>
                                            <div class="col-md-4">
                                                <input class="form-control mt-1" type="text" value="{{$item['item']->name}}" readonly>
                                            </div>
                                            <div class="col">
                                                @if(isset($item['item']->sample))
                                                <input class="form-control text-center" type="text"
                                                    value="{{$item['item']->sample->name}}" readonly>
                                                @endif
                                            </div>
                                            <div class="col">
                                                @if(isset($item['item']->method))
                                                <input class="form-control text-center" type="text"
                                                    value="{{$item['item']->method->name}}" readonly>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-center" type="text"
                                                    value="{{$collection_status}}" readonly>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-center" type="text" name="item_barcodes[{{$item['item']->id}}]"
                                                    value="{{$item['barcode']}}">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <hr class="hr-line">
                            <div class="row mb-4">
                                <div class="col text-right">
                                    @if(!isset($entry->sample_no))
                                        <button type="submit" class="btn btn-sm btn-blue mr-5" role="button"><i
                                                class="fa fa-floppy-o"></i>&nbsp; Collect </button>
                                    @else
                                        <a href="{{ url()->previous() }}" class="btn btn-danger mr-5 mt-2"><span class="la la-arrow-left"></span> &nbsp;{{ trans('Back') }}</a>
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
    <script>
        //js for saving form
        $('#sample-collection-form').validate({
            submitHandler: function(form) {
                swal({
                    closeOnClickOutside: false,
                    title: "Confirm And Save !!",
                    text: "Once you update status, it can't be undone !!",
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
                    }
                });
            }
	    });
    </script>
@endpush
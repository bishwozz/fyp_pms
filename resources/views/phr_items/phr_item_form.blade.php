@extends(backpack_view('blank'))

@section('header')
	<section class="container-fluid">
	    <h4>
            @if ($crud->hasAccess('list'))
            <span class="text">Add new item</span>
            <small><a href="{{ url($crud->route) }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
            @endif
	    </h4>
	</section>
@endsection

@section('content') 
    <div class="card">
        <div class="row mt-1">
            <div class="col-md-12">
                @if(isset($entry))
                    <form method="post" action="{{ url($crud->route.'/'.$entry->getKey()) }}" id="item_registration">
                    {!! method_field('PUT') !!}
                @else
                    <form action="{{ url($crud->route) }}" role="form" method="POST" id="item_registration">
                @endif    
                    @csrf
                    <div class="card">
                        <div class="card-header bg-primary p-1">Item Details </div>
                        <div class="card-body p-2 m-2">
                            <div class="form-row">
                                <div class="col">
                                    <label class="_required"  for="code"><b>Code:</b></label>
                                    <input class="form-control" type="text" id="code"  name="code" value="{{ isset($entry)?$entry->code : ''}}" required/>
                                </div>
                    
                                <div class="col">
                                    <label for="supplier"><b>Supplier: </b></label>
                                    <select  class="form-control searchselect" name="supplier_id" id="supplier">
                                        <option value="">--Select Supplier--</option>
                                            @foreach ($suppliers as $option)
                                                @if(isset($entry) && $entry->supplier_id == $option->getKey())
                                                    <option value="{{ $entry->supplier_id }}" selected>{{ $entry->supplier->name }}</option>
                                                @else
                                                    <option value="{{ $option->getKey() }}">{{ $option->name }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="pharmaceutical"><b>Pharmaceutical:</b></label>
                                    <select  class="form-control searchselect" name="pharmaceutical_id" id="pharmaceutical">
                                        <option value="">--Select Pharmaceutical--</option>
                                            @foreach ($pharmaceuticals as $option)
                                                @if(isset($entry) && $entry->pharmaceutical_id == $option->getKey())
                                                    <option value="{{ $entry->pharmaceutical_id }}" selected>{{ $entry->pharmaceutical->name }}</option>
                                                @else
                                                    <option value="{{ $option->getKey() }}">{{ $option->name }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col">
                                    <label for="category"><b>Category: </b></label>
                                    <select  class="form-control searchselect" name="category_id" id="category">
                                    <option value="">--Select Category--</option>
                                            @foreach ($categories as $option)
                                            @if(isset($entry) && $entry->category_id == $option->getKey())
                                                <option value="{{ $entry->category_id }}" selected>{{ $entry->category->title_lc }}</option>
                                            @else
                                                <option value="{{ $option->getKey() }}">{{ $option->title_lc }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="generic_name"><b>Generic Name: </b></label>
                                    <select  class="form-control searchselect" name="generic_name_id" id="generic_name">
                                    <option value="">--Select Generic Name--</option>
                                            @foreach ($generic_names as $option)
                                            @if(isset($entry) && $entry->generic_name_id == $option->getKey())
                                                <option value="{{ $entry->generic_name_id }}" selected>{{ $entry->generic_name->name }}</option>
                                            @else
                                                <option value="{{ $option->getKey() }}">{{ $option->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label class="_required" for="brand_name"><b>Brand Name:</b></label>
                                    <input class="form-control" type="text" id="brand_name"  name="brand_name" value="{{ isset($entry) ? $entry->brand_name : ''}}" required/>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col">
                                    <label for="form"><b>Form:</b></label>
                                    <input class="form-control" type="text" id="form"  name="form" placeholder="Syrup, tabs, capsule..." value="{{ isset($entry) ? $entry->form : ''}}"/>
                                </div>

                                <div class="col">
                                    <label for="strength"><b>Strength:</b></label>
                                    <input class="form-control" type="text" id="strength"  name="strength" placeholder="gm, ml, mg, percentage..." value="{{ isset($entry) ? $entry->strength : ''}}"/>
                                </div>

                                <div class="col">
                                    <label for="disease_group_coverage"><b>Disease Group Coverage:</b></label>
                                    <input class="form-control" type="text" id="disease_group_coverage"  name="disease_group_coverage" placeholder="Antibiotic, Antiprotozonal..." value="{{ isset($entry) ? $entry->disease_group_coverage : ''}}"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-primary p-1">Stock Information </div>
                        <div class="card-body p-2 m-2">
                            <div class="form-row">
                                <div class="col">
                                    <label class="_required" for="current_stock"><b>Current Stock:</b></label>
                                    <input class="form-control" type="number" id="current_stock"  name="current_stock" min="0" value="{{ isset($entry)?$entry->current_stock : ''}}" required/>
                                </div>
                    
                                <div class="col">
                                    <label class="_required" for="stock_unit"><b>Smallest Stock Unit: </b></label>
                                    <select  class="form-control searchselect" name="stock_unit_id" id="stock_unit" required onchange="checkItemUnit()">
                                        <option value="">--Select Stock Unit--</option>
                                            @foreach ($stock_units->whereNull('dependent_unit_id') as $option)
                                                @if(isset($entry) && $entry->stock_unit_id == $option->getKey())
                                                    <option value="{{ $entry->stock_unit_id }}" selected>{{ $entry->stock_unit->code.'-'. $entry->stock_unit->name_en }}</option>
                                                @else
                                                    <option value="{{ $option->getKey() }}">{{ $option->code.' - '.$option->name_en }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label  class="_required" for="stock_alert_minimun"><b>Stock Alert Minimum:</b></label>
                                    <input class="form-control" type="number" id="stock_alert_minimun"  name="stock_alert_minimun" min="0" value="{{ isset($entry)?$entry->stock_alert_minimun : ''}}" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-primary p-1">Status </div>
                        <div class="card-body p-2 m-2">
                            <div class="form-row">
                                <div class="col">
                                    <span><b>Status:</b></span><br/>
                                    @if(isset($entry))
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="active" value="true" {{ $entry->is_active == true ? 'checked' : ''}}>
                                            <label class="form-check-label" for="active">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="inactive" value="false" {{ $entry->is_active == false ? 'checked' : ''}}>
                                            <label class="form-check-label" for="inactive">Inactive</label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="active" value="true" checked>
                                            <label class="form-check-label" for="active">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="inactive" value="false">
                                            <label class="form-check-label" for="inactive">Inactive</label>
                                        </div>
                                    @endif
                                </div>

                                <div class="col">
                                    <span><b>Is Free Item ?</b></span><br/>
                                    @if(isset($entry))
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_free" id="free_item_yes" value="true" {{ $entry->is_free == true ? 'checked' : ''}}>
                                            <label class="form-check-label" for="free_item_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_free" id="free_item_no" value="false" {{ $entry->is_free == false ? 'checked' : ''}}>
                                            <label class="form-check-label" for="free_item_no">No</label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_free" id="free_item_yes" value="true">
                                            <label class="form-check-label" for="free_item_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_free" id="free_item_no" value="false" checked>
                                            <label class="form-check-label" for="free_item_no">No</label>
                                        </div>
                                    @endif    
                                </div>
                                <div class="col">
                                    <span><b>Is Banned ?</b></span><br/>
                                    @if(isset($entry))
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_banned" id="banned_yes" value="true" {{ $entry->is_banned == true ? 'checked' : ''}}>
                                            <label class="form-check-label" for="banned_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_banned" id="banned_no" value="false" {{ $entry->is_banned == false ? 'checked' : ''}}>
                                            <label class="form-check-label" for="banned_no">No</label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_banned" id="banned_yes" value="true">
                                            <label class="form-check-label" for="banned_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_banned" id="banned_no" value="false" checked>
                                            <label class="form-check-label" for="banned_no">No</label>
                                        </div>
                                    @endif    
                                </div>
                                <div class="col">
                                    <span><b>Is Deprecated ?</b></span><br/>
                                    @if(isset($entry))
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_deprecated" id="deprecated_yes" value="true" {{ $entry->is_deprecated == true ? 'checked' : ''}}>
                                            <label class="form-check-label" for="deprecated_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_deprecated" id="deprecated_no" value="false" {{ $entry->is_deprecated == false ? 'checked' : ''}}>
                                            <label class="form-check-label" for="deprecated_no">No</label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_deprecated" id="deprecated_yes" value="true">
                                            <label class="form-check-label" for="deprecated_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_deprecated" id="deprecated_no" value="false" checked>
                                            <label class="form-check-label" for="deprecated_no">No</label>
                                        </div>
                                    @endif    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <div class="col ml-4">
                            <label for="description"><b>Description</b></label>
                            <textarea class="form-control" id="description" name="description">{{ isset($entry)? $entry->description : ''}}</textarea>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header bg-primary p-1">Item Units and Price </div>
                                <div class="card-body p-1 ml-2">
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="font-weight-bold">Unit</label>
                                        </div>
                                        <div class="col">
                                            <label class="font-weight-bold">Batch No</label>
                                        </div>
                                        <div class="col">
                                            <label class="font-weight-bold">Price</label>
                                        </div>
                                    </div>

                                   
                                        @foreach ($stock_units as $item)
                                            @if(isset($entry))  
                                                @php
                                                    $db_item_unit_ids = [];
                                                    $db_item_unit_batch = [];
                                                    $db_item_unit_price = [];
                                                    foreach($entry->itemunits as $db_item){
                                                        $db_item_unit_ids[] = $db_item->unit_id;
                                                        $db_item_unit_batch[$db_item->unit_id] = $db_item->batch_number;
                                                        $db_item_unit_price[$db_item->unit_id] = $db_item->price;
                                                    }
                                                @endphp  

                                                <div class="form-row" id="{{ 'item_unit_row-'.$item->id}}">
                                                    <div class="col">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" class="hmis-chk-bl" id="{{ 'unitcheck_'.$item->id}}" name="{{ 'unit_id['.$item->id.']' }}" onclick="enableDisablePriceField()" {{ in_array($item->id,$db_item_unit_ids) ? 'checked':''}} /><label class="ml-2" for="{{ 'unitcheck_'.$item->id}}">  {{$item->name_en}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                    <input type="text" class="form-control" id="{{ 'unitbatch_no_'.$item->id}}" name="{{ 'unit_batch_no['.$item->id.']'}}" value="{{ in_array($item->id,$db_item_unit_ids) ? $db_item_unit_batch[$item->id] : ''}}" {{ in_array($item->id,$db_item_unit_ids) ? '':'disabled'}}/>
                                                    </div>
                                                    <div class="col">
                                                        <input type="number" class="form-control" id ="{{ 'unitprice_'.$item->id}}" name="{{ 'unit_price['.$item->id.']' }}" value="{{ in_array($item->id,$db_item_unit_ids) ? $db_item_unit_price[$item->id] : ''}}" {{ in_array($item->id,$db_item_unit_ids) ? '':'disabled'}}/>
                                                    </div>
                                                </div> 
                                            @else
                                                <div class="form-row" id="{{ 'item_unit_row-'.$item->id}}">
                                                    <div class="col">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" class="hmis-chk-bl" id="{{ 'unitcheck_'.$item->id}}" name="{{ 'unit_id['.$item->id.']' }}" onclick="enableDisablePriceField()" /><label class="ml-2" for="{{ 'unitcheck_'.$item->id}}">  {{$item->name_en}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="{{ 'unitbatch_no_'.$item->id}}" name="{{ 'unit_batch_no['.$item->id.']'}}" disabled/>
                                                    </div>
                                                    <div class="col">
                                                        <input type="number" class="form-control" id ="{{ 'unitprice_'.$item->id}}" name="{{ 'unit_price['.$item->id.']' }}" disabled/>
                                                    </div>
                                                </div>
                                            @endif

                                            @php
                                            $used_ids[] = $item->id;   
                                            @endphp    
                                        @endforeach
                                </div>
                            </div>
                        </div>
                    </div>    

                    <div class="row mr-2 mb-3">
                        <div class="col-12 text-right">
                        <a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-danger mr-2"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> {{isset($entry) ? 'Update' : 'Save'}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>    
    </div>     
@endsection

@push('after_scripts')
<script>
    $(document).ready(function(){
        $('#item_registration').validate({
            submitHandler: function(form) {
                swal({
                    title: "Confirm Submission !!",
                    text: 'The information provided is correct and want to proceed towards registration.',
                    buttons: {
                        no: {
                            text: " No ",
                            value: false,
                            visible: true,
                            className: "btn btn-secondary",
                            closeModal: true,
                        },
                        yes: {
                            text: " Yes ",
                            value: true,
                            visible: true,
                            className: "btn btn-success",
                            closeModal: true,
                        }
                    },
                }).then((confirmResponse) => {
                    if (confirmResponse) {
                        HMIS.hmisLoading(true, $('#item_registration'));
                        let data = new FormData(form);
                        let url = form.action;
                        axios.post(url, data)
                        .then((response) => {
                            document.location = response.data.url;
                            HMIS.hmisLoading(false, $('#item_registration'));
                        }, (error) => {
                            swal("Error !", error.response.data.message, "error")
                            HMIS.hmisLoading(false, $('#item_registration'));
                        });
                    }
                });
            }
        });
    });
    function checkItemUnit(){
        //first uncheck all checked checkboxes
        $('input[type=checkbox]').prop('checked',false);
        $('input[type=checkbox]').prop('required',false);
        let item_unit_id = $('#stock_unit').val();

        //automatically check the selected unit 
        $('#unitcheck_'+item_unit_id).prop('checked',true);
        enableDisablePriceField(true);
    }

    function enableDisablePriceField(auto_triggered){
        let checkboxes = document.querySelectorAll('input[type=checkbox]');

        checkboxes.forEach(function(item, index) {
            let selected_unit_id = $('#stock_unit').val()
            let unit_id = item.id.split('_')[1];
            if(auto_triggered !== true){
                if(selected_unit_id === unit_id && !item.checked){
                    swal("Warning","This unit is selected as smallest stock unit.\n So, it can't be unchecked.");
                    $('#unitcheck_'+unit_id).prop('checked',true);
                }
            }

            if(item.checked){               
                $('#unitprice_'+unit_id).removeAttr('disabled');
                $('#unitbatch_no_'+unit_id).removeAttr('disabled');
                $('#item_unit_row-'+unit_id).find('input').prop('required', true);                
            }else{
                $('#unitprice_'+unit_id).val('').attr('disabled',true);
                $('#unitbatch_no_'+unit_id).val('').attr('disabled',true);
                $('#item-row-' +unit_id).find('input').prop('required', false);                

            }
        });
    }
</script>
@endpush
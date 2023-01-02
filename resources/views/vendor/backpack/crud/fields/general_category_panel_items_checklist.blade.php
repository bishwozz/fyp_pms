@include('crud::fields.inc.wrapper_start')
@php
        $categoryId = 1;
        $alreadySelectedItems = [];
        if(isset($crud->entry)) {
            if($crud->entry->id) {
                $alreadySelectedItems = App\Models\Lab\LabPanelItems::where('lab_panel_id', $crud->entry->id)->pluck('lab_item_id')->toArray();
            }
        }
        $lab_items = App\Models\Lab\LabMstItems::where([['lab_category_id',1],['is_active',true]])->get();
@endphp
<div class="col-md-12 ml-3">
    <div class="row">
        <div class="col-md-8">
            <label><span class="text-primary font-weight-bold">{!! $field['label'] !!}</span></label>
            <div class="form-row">
                @foreach($lab_items as $lab_item)
                    <div class="icheck-primary col-md-4">
                        <input type="checkbox" {{in_array($lab_item->id, $alreadySelectedItems) ? 'checked' : ''}} class="form-check-input" id ="general_item-{{$lab_item->id}}" name="general_category_items[]" value="{{$lab_item->id}}" item_name="{{$lab_item->name}}" item_price="{{$lab_item->price}}" onclick="lab_general_item(this)">
                        <label class="form-check-label" for="general_item-{{$lab_item->id}}">{{$lab_item->name}}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
                <div><span class="font-weight-bold text-primary">Selected General Item</span></div>
                <div class="item-selected">
                    <table class="table table-striped" id ="selected_general_item">
                        <tbody>
                        </tbody>
                    </table>
                    <div id="general_item_total_price"> </div>
                </div>
        </div>
    </div> 
</div>
@include('crud::fields.inc.wrapper_end')

<style>
.item-selected{
    border:2px solid lightgray;
    border-radius:7px;
    min-height:300px;
}

#selected_items tbody td,
#total_price,#groups_total_price{
    text-align:center;
}
</style>

@push('crud_fields_scripts')
<script>
    var general_item_total_price = 0;

    function lab_general_item(element){
            if($(element).prop('checked') === true){
                $("#general_item_total_price").empty();
                
                let general_item_name = element.getAttribute('item_name');
                let general_item_price = element.getAttribute('item_price');

                general_item_total_price += parseInt(general_item_price);

                $('#selected_general_item tbody').append('<tr id ="'+element.id+'_general_item"><td class="text-left">'+general_item_name+' (Rs.'+general_item_price+')</td></tr>');
                $('#general_item_total_price').append('<span><b>Total Group Price: Rs. '+general_item_total_price+'</b></span>');  
            }else{
                $("#general_item_total_price").empty();

                let id = element.id;
                let price = element.getAttribute('item_price');

                $('#'+id+'_general_item').remove();
                general_item_total_price -= parseInt(price);
                $('#general_item_total_price').append('<span><b>Total Group Price: '+general_item_total_price+'</b></span>');             
            };
    }
</script>  
@endpush


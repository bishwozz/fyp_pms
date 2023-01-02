@include('crud::fields.inc.wrapper_start')
<div class="col-md-12 ml-3">
    <div class="row">
        <div class="col-md-8">
            <label><span class="text-primary font-weight-bold">{!! $field['label'] !!}</span></label>
            <div class="form-row" id="checkbox_filtered">
            </div>
        </div>
        <div class="col-md-4">
                <div><span class="font-weight-bold text-primary">Selected Items</span></div>
                <div class="item-selected">
                    <table class="table table-striped table-hover" id ="selected_items">
                        <thead class="table-dark">
                            <tr>
                                <td>Items</td>
                                <td width="90px">Order No.</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="total_price"> </div>
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
#total_price{
    text-align:center;
}
</style>

@push('crud_fields_scripts')
<script>
    var total_price = 0;

    function lab_item(element){
            if($(element).prop('checked') === true){
                $("#total_price").empty();
                
                let id = $(element).data('id');
                let item_name = $(element).data('item_name');
                let item_price = $(element).data('item_price');

                total_price += parseInt(item_price);

                $('#selected_items tbody').append('<tr id ="'+$(element).data('id')+'_item"><td class="text-left">'+item_name+' (Rs.'+item_price+')</td><td><input type="number" value="0" name="display_order['+id+']" class="form-control" /></td></tr>');
                $('#total_price').append('<span><b>Total Item Price: Rs. '+total_price+'</b></span>');  
            }else{
                $("#total_price").empty();

                let id = $(element).data('id');
                let price = $(element).data('item_price');

                $('#'+id+'_item').remove();
                total_price -= parseInt(price);
                $('#total_price').append('<span><b>Total Group Price: '+total_price+'</b></span>');             
            };
    }
</script>  
@endpush


@include('crud::fields.inc.wrapper_start')
<div class="col-md-12 ml-3">
    <div class="row">
        <div class="col-md-8">
            <label><span class="text-primary font-weight-bold">{!! $field['label'] !!}</span></label>
            <div class="form-row" id="lab_group_checkbox_filtered">
            </div>
        </div>
        <div class="col-md-4">
                <div><span class="font-weight-bold text-primary">Selected Groups</span></div>
                <div class="item-selected">
                    <table class="table table-striped table-hover" id ="selected_groups">
                        <thead class="table-dark">
                            <tr>
                                <td>Groups</td>
                                <td width="90px">Order No.</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="groups_total_price"> </div>
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
    var groups_total_price = 0;

    function lab_group(element){
            if($(element).prop('checked') === true){
                $("#groups_total_price").empty();
                
                let group_name = $(element).data('group_name');
                let group_price = $(element).data('group_price');
                let id = $(element).data('id');
                let group_items = $(element).data('group_items');
                group_items = group_items.split(',');
                $.each(group_items,(item)=>{
                    $(`#item-${group_items[item]}`).attr('checked',true);
                    $(`#item-${group_items[item]}`).attr('disabled',true);
                });

                groups_total_price += parseInt(group_price);
                $('#selected_groups tbody').append('<tr id ="'+id+'_group"><td class="text-left">'+group_name+' (Rs.'+group_price+')</td><td><input type="number" value="0" name="display_order['+id+']" class="form-control" /></td></tr>');
                $('#groups_total_price').append('<span><b>Total Group Price: Rs. '+groups_total_price+'</b></span>');  
            }else{
                $("#groups_total_price").empty();

                let id = $(element).data('id');
                let price = $(element).data('group_price');
                let group_items = $(element).data('group_items');
                group_items = group_items.split(',');
                $.each(group_items,(item)=>{
                    $(`#item-${group_items[item]}`).attr('disabled',false);
                    $(`#item-${group_items[item]}`).attr('checked',false);
                });
                $('#'+id+'_group').remove();
                groups_total_price -= parseInt(price);
                $('#groups_total_price').append('<span><b>Total Group Price: '+groups_total_price+'</b></span>');             
            };
    }
</script>  
@endpush


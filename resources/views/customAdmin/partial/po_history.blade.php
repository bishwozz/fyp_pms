@php 
     $sum_of_qty=0;       
@endphp
<div class="table-responsive">
    <table class="table table-sm"  style="min-width: 1024px;">
    <thead>
        <tr class="text-white" style="background-color: #192840">
            <th scope="col">S.No</th>
            <th scope="col">Sup Name/ Req Store</th>
            <th scope="col">PO No</th>
            <th scope="col">PO Date</th>
            <th scope="col">PO Qty</th>
            <th scope="col">PO Price</th>
            <th scope="col">Discount Mode</th> 
            <th scope="col">Discount</th> 
            <th scope="col">Amount</th>
            <th scope="col">Created By</th>
        </tr>
    </thead>
    <tbody id="po_item_history">

    @foreach($data as $d)
        @php 
        $sum_of_qty= $sum_of_qty+$d->total_qty;
        @endphp
   
        <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{isset($d->supplier_id) ? $d->supplierEntity->name_en : $d->storeEntity->name_en}}</td>
            <td>{{$d->purchase_order_num}}</td>
            <td>{{$d->po_date}}</td>
            <td >{{$d->total_qty}}</td>
            <td>{{$d->purchase_price}}</td>

            <td>{{$d->discountModeEntity->name_en}}</td>
            <td>{{$d->discount}}</td>
            <td>{{$d->item_amount}}</td>
            <td>{{$d->createdByEntity->name}}</td>
        </tr>

        @endforeach
       
    </tbody>
</table>
</div>


<script>
    $('#total_qty_history').text('{{$sum_of_qty}}');
</script>
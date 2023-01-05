<div class="table-responsive">
    <table class="table table-sm" style="min-width: 1024px;">
        <thead>
        <tr class="text-white" style="background-color: #192840">
            <th scope="col">S.No</th>
            <th scope="col">Item</th>
            <th scope="col">Add Qty</th>
            <th scope="col">Free item</th>
            <th scope="col">Total Qty</th>
            <th scope="col">Entry Date</th>
            <th scope="col">Amount</th>
            <th scope="col">Entered By</th>
            <th scope="col">Approved By</th>
        </tr>
        </thead>
        <tbody>
        @foreach($historyData as $data)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$itemName}} </td>
                <td>{{$data->add_qty}}</td>
                <td>{{$data->free_item}}</td>
                <td>{{$data->total_qty}}</td>
                <td>{{dateToString($data->entry_date)}}</td>
                <td>{{$data->item_total}}</td>
                <td>{{backpack_user($data->created_by)->name}}</td>
                <td>{{$data->approved_by?backpack_user($data->approved_by)->name:'-'}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


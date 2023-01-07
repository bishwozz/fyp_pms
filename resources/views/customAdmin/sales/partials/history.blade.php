<table class="table table-sm">
    <thead>
        <tr class="bg-danger text-white">
            <th scope="col">Select</th>
            <th scope="col">Bill No</th>
            <th scope="col">Name</th>
            <th scope="col">Date</th>
            <th scope="col">Paid Amount</th>
            <th scope="col">Bill Amount</th>
            <th scope="col">Billed By</th>
        </tr>
    </thead>
    <tbody id="po_item_history">
        @foreach($data as $d)
        <tr>
            <td scope="row">
                <input class="bill_checked " type="checkbox">
            </td>
            <td>{{$d->bill_no}}</td>
            <td>{{isset($d->full_name) ? $d->full_name : '-'}}</td>
            <td>{{$d->bill_date_ad}}</td>
            <td>{{$d->paid_amt}}</td>
            <td>{{$d->receipt_amt}}</td>
            <td>{{$d->createdByEntity->name}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
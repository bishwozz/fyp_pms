<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales List</title>
    <style>
        @media print {
            @page {
                size: A4;
            }
        }

        .mainTable {
            width: 100%;
            text-align: center;
        }

        .mainTable,
        .mainTable>thead>tr>th,
        .mainTable>tbody>tr>td,
        .mainTable>tbody>tr>th>td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            padding: 5px 0;
        }

    </style>
</head>

<body>
	<div>
		<h2>
			List of Stock Entries
		</h2>
	</div>
    <table class="mainTable">
        <thead>
            <tr>
                <th  style="width: 5%">S. N.</th>
                <th  style="width: 10%">PO Type</th>
                <th  style="width: 15%">Supplier</th>
                <th  style="width: 15%">Req Store</th>
                <th  style="width: 10%">PO Date</th>
                <th  style="width: 15%">Expected Delivery</th>
                <th  style="width: 5%">PO Number</th>
                <th  style="width: 10%">Approved By</th>
                <th  style="width: 10%">Status</th>
                <th  style="width: 5%">Net Amt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($poDetails as $po)
			@php
				$poDate = strtotime($po->po_date);
				$po_date = date('d M Y', $poDate)
			@endphp
                <tr>
                    <td>
						{{ $loop->iteration }}
					</td>
                    <td>
						{{ isset($po->purchase_order_type_id) ? $po->PurchaseOrderEntity->name_en : '-' }}
					</td>
                    <td>
						{{ isset($po->supplier_id) ? $po->supplierEntity->name_en : '-' }}
					</td>
                    <td>
						{{ isset($po->requested_store_id) ? $po->requestedStoreEntity->name_en : '-' }}
					</td>
					<td>
						{{ isset($po->po_date) ? $po_date : '-' }}
					</td>
                    <td>
						{{ isset($po->expected_delivery) ? $po->expected_delivery : '-' }}
					</td>
					<td>
						{{ isset($po->purchase_order_num) ? $po->purchase_order_num : '-' }}
					</td>
					<td>
						{{ isset($po->approved_by) ? $po->approvedByEntity->name : '-' }}
					</td>
					<td>
						{{ isset($po->status_id) ? $po->statusEntity->name_en : '-' }}
					</td>
					<td>
						{{ isset($po->net_amt) ? $po->net_amt : '-' }}
					</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>

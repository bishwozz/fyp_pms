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
                <th  style="width: 20%">Store</th>
                <th  style="width: 20%">Supplier</th>
                <th  style="width: 25%">Purchase Order Date</th>
                <th  style="width: 10%">Purchase Order Id</th>
                <th  style="width: 10%">DC Date</th>
                <th  style="width: 10%">DC Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grns as $grn)
			@php
				$poDate = strtotime($grn->po_date);
				$po_date = date('d M Y', $poDate);
				$dcDate = strtotime($grn->dc_date);
				$dc_date = date('d M Y', $dcDate);
			@endphp
                <tr>
                    <td>
						{{ $loop->iteration }}
					</td>
                    <td>
						{{ isset($grn->store_id) ? $grn->storeEntity->name_en : '-' }}
					</td>
                    <td>
						{{ isset($grn->supplier_id) ? $grn->supplierEntity->name_en : '-' }}
					</td>
                    <td>
						{{ isset($grn->po_date) ? $po_date : '-' }}
					</td>
					<td>
						{{ isset($grn->purchase_order_id) ? $grn->purchase_order_id : '-' }}
					</td>
                    <td>
						{{ isset($grn->dc_date) ? $dc_date : '-' }}
					</td>
					<td>
						{{ isset($grn->dc_no) ? $grn->dc_no : '-' }}
					</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>

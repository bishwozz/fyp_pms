<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Returns</title>
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

			List of Purchase Returns
		</h2>
	</div>
    <table class="mainTable">
        <thead>
            <tr>
                <th style="width: 5%">S. N.</th>
                <th style="width: 10%">Purchase Order Id</th>
                <th style="width: 15%">Store</th>
                <th style="width: 15%">Return Reason</th>
                <th style="width: 10%">Supplier</th>
                <th style="width: 15%">Return Date</th>
                <th style="width: 5%">Grn Sequence</th>
                <th style="width: 10%">Requested Store</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($poReturns as $po)
			@php
				$poDate = strtotime($po->return_date);
				$po_date = date('d M Y', $poDate)
			@endphp
                <tr>
                    <td>
						{{ $loop->iteration }}
					</td>
                    <td>
						{{ isset($po->purchase_order_id) ? $po->purchase_order_id : '-' }}
					</td>
                    <td>
						{{ isset($po->store_id) ? $po->storeEntity->name_en : '-' }}
					</td>
                    <td>
						{{ isset($po->return_reason_id) ? $po->returnReasonEntity->name_en : '-' }}
					</td>
					<td>
						{{ isset($po->supplier_id) ? $po->supplierEntity->name_en : '-' }}
					</td>
                    <td>
						{{ isset($po->return_date) ? $po->return_date : '-' }}
					</td>
					<td>
						{{ isset($po->purchase_order_num) ? $po->purchase_order_num : '-' }}
					</td>
					<td>
						{{ isset($po->grn_sequences_id) ? $po->grnSequenceEntity->name : '-' }}
					</td>
					<td>
						{{ isset($po->requested_store_id) ? $po->storeEntity->name_en : '-' }}
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

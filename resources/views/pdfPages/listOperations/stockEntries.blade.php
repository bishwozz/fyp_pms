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
                <th  style="width: 10%">Stock Status</th>
                <th  style="width: 10%">Batch No.</th>
                <th  style="width: 10%">Adjustment No</th>
                <th  style="width: 15%">Entry Date(AD)</th>
                <th  style="width: 10%">Entry Date(BS)</th>
                <th  style="width: 10%">Stock Amount</th>
            </tr>
        </thead>
        <tbody>
			
            @foreach ($stocks as $stock)
			@php
				$entry_date = strtotime($stock->entry_date_ad);
				$entry_date_ad = date('d M Y', $entry_date)
			@endphp
                <tr>
                    <td>
						{{ $loop->iteration }}
					</td>
                    <td>
						{{ isset($stock->store_id) ? $stock->mstStore->name_en : '-' }}
					</td>
                    <td>
						{{ isset($stock->sup_status_id) ? $stock->supStatus->name_en : '-' }}
					</td>
                    <td>
						{{ isset($stock) ? $stock->getBatchNo() : '-' }}
					</td>
					<td>
						{{ isset($stock->adjustment_no) ? $stock->adjustment_no : '-' }}
					</td>
                    <td>
						{{ isset($stock->entry_date_ad) ? $entry_date_ad : '-' }}
					</td>
					<td>
						{{ isset($stock->entry_date_bs) ? $stock->getDateString() : '-' }}
					</td>
					<td>
						{{ isset($stock->gross_total) ? $stock->gross_total : '-' }}
					</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>

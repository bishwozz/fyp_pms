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
			List of Available Stock Status
		</h2>
	</div>
    <table class="mainTable">
        <thead>
            <tr>
                <th  style="width: 5%">S. N.</th>
                <th  style="width: 40%">Item Name</th>
                <th  style="width: 20%">Batch No</th>
                <th  style="width: 15%">Batch Qty</th>
                <th  style="width: 20%">Total Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>
						{{ $loop->iteration }}
					</td>
                    <td>
						{{ isset($stock->item_id) ? $stock->itemEntity->name : '-' }}
					</td>
                    <td>
						{{ isset($stock->batch_no) ? $stock->batch_no : '-' }}
					</td>
                    <td>
						{{ isset($stock->batch_qty) ? $stock->batch_qty : '-' }}
					</td>
					<td>
						{{ isset($stock->item_qty) ? $stock->item_qty : '-' }}
					</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>

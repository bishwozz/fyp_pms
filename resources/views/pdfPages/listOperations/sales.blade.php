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
			List of Sales Operations
		</h2>
	</div>
    <table class="mainTable">
        <thead>
            <tr>
                <th>S. N.</th>
                <th>Full Name</th>
                <th>Bill Number</th>
                <th>Billed Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>
						{{ $i++ }}
					</td>
                    <td>
						{{ isset($sale->full_name) ? $sale->full_name : '-' }}
					</td>
                    <td>
						{{ isset($sale->bill_no) ? $sale->bill_no : '-' }}
					</td>
                    <td>
						{{ isset($sale->bill_date_ad) ? $sale->bill_date_ad : '-' }}
					</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>

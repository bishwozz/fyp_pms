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
			List of Purchase Order Type
		</h2>
	</div>
    <table class="mainTable">
        <thead>
            <tr>
                <th style="width: 5%">S. N.</th>
                <th style="width: 7%">Code</th>
                <th style="width: 29%">Name</th>
                <th style="width: 29%">नाम</th>
                <th style="width: 20%">Description</th>
                <th style="width: 10%">Is Active?</th>
            </tr>
        </thead>
        <tbody>
			
            @foreach ($potypes as $potype)
			@php
				$entry_date = strtotime($potype->entry_date_ad);
				$entry_date_ad = date('d M Y', $entry_date)
			@endphp
                <tr>
                    <td>
						{{ $loop->iteration }}
					</td>
                    <td>
						{{ isset($potype->code) ? $potype->code : '-' }}
					</td>
                    <td>
						{{ isset($potype->name_en) ? $potype->name_en : '-' }}
					</td>
                    <td>
						{{ isset($potype->name_lc) ? $potype->name_lc : '-' }}
					</td>
					<td>
						{{ isset($potype->description) ? $potype->description : '-' }}
					</td>
					<td>
						{{ isset($potype->is_active) ? $potype->isActive() : '-' }}
					</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>

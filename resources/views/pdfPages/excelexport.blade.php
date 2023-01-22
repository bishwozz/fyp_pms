<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Status</title>
    
    <style>
        #table {
            font-family: "Kalimati";
            border-collapse: collapse;
            font-size: 12px;
            width: 100%;
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin-top: 40px;
                margin-left: 40px;
                margin-right: 40px;
                /* margin: 10mm; */
            }
        }

        th {
            font-weight: bold;
        }

    </style>
</head>

<body>
    <table id="table">
        <thead>
            <tr>
                <th>
                    S. N.
                </th>
                <th>
                    Item Name
                </th>
                <th>
                    Batch No
                </th>
                <th>
                    Batch Qty
                </th>
                <th>
                    Total Qty
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $stock->item_name }}
                    </td>
                    <td>
                        {{ $stock->batch_no }}
                    </td>
                    <td>
                        {{ $stock->batch_qty }}
                    </td>
                    <td>
                        {{ $stock->item_qty }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</body>

</html>

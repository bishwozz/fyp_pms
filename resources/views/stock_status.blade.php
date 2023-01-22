@extends(backpack_view('blank'))

@section('content')
    <title>Stock Status</title>

    <style>
        .heading {
            display: flex;
            justify-content: space-between;
        }
    </style>

    </head>

    <body>
        <div class="heading">
            <h1>Stock Status</h1>
            <div class="dropdown show">
                <a class="btn btn-sm print_export_button dropdown-toggle" href="#" role="button" id="ExportMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{-- <i class="fa fa-download" aria-hidden="true"></i> --}}
                    {{-- <i class="fa-solid fa-arrow-down-to-line"></i>
                    &nbsp; --}}
                    <i class="la la-download"></i>
                    Export
                </a>
                <div class="dropdown-menu" aria-labelledby="ExportMenuLink">
                    <a class="dropdown-item" href="{{ route('stock.exportExcel') }}" target="_blank">Excel</a>
                    <a class="dropdown-item" href="{{ route('stock.exportPdf') }}" target="_blank">PDF</a>
                </div>
            </div>
            {{-- <div>
                <a href="{{ route('stock.exportPdf') }}" class="btn btn-sm btn-success mr-5 mb-5" target="_blank">Export as
                    PDF</a>
                <a href="{{ route('stock.exportExcel') }}" class="btn btn-sm btn-primary mr-5 mb-5" target="_blank">Export as
                    Excel</a>
            </div> --}}
        </div>
        <table id="inventory_list_datatable" class="table table-striped" width="100%">
            <thead class="p-1">
                <tr>
                    <th>S.N.</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Supplier Name</th>
                    <th>Total Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="javascript:;" class="navbar-brand" style="color:blue !important;" data-toggle="modal"
                                data-target="#viewItemDetail-{{ $loop->iteration }}">
                                {{ $d['item']->name }}
                            </a>
                        </td>
                        <td>{{ $d['item']->mstBrandEntity->name_en }}</td>
                        <td>
                            {{ $d['item']->mstSupplierEntity->name_en  }}
                        </td>
                        <td>{{ isset($d['item']->itemQtyDetail->item_qty)?:0 }}</td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="viewItemDetail-{{ $loop->iteration }}" tabindex="-1" role="dialog"
                        aria-labelledby="viewItemDetailLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewItemDetailLabel">

                                        {{ $d['item']->name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-danger font-weight-bold" role="alert">
                                        {{ isset($d['item']->soldQty[0]->total_sold) ? $d['item']->soldQty[0]->total_sold : 0 }}
                                        {{ Str::plural('item', $d['item']->soldQty[0]->total_sold) }} Sold total
                                    </div>
                                    <div class="row">
                                        @php
                                            $unique_array = [];
                                            foreach ($d['item']['batchQty'] as $item) {
                                                if (!in_array($item, $unique_array)) {
                                                    $unique_array[] = $item;
                                                }
                                            }
                                            $d['item']['batchQty'] = $unique_array;
                                        @endphp
                                        @foreach ($d['item']['batchQty'] as $batchQty)

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="card text-white bg-primary">
                                                    <div class="card-header">
                                                        <strong>Batch :</strong> {{ $batchQty['batchNo'] }}
                                                    </div>
                                                    <ul class="list-group list-group-flush bg-white text-dark">
                                                        <li class="list-group-item">
                                                            <span class="font-weight-bold-h5 text-success">
                                                                Item Available Qty :
                                                            </span>
                                                            {{ $batchQty['qty'] }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach
            </tbody>
    </body>
@endsection

@push('after_scripts')
    <script>
        $(document).ready(function() {
            $('.modal').appendTo('body');
            $('#inventory_list_datatable').DataTable();
        });
    </script>
@endpush

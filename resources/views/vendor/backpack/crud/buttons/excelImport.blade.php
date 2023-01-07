<button type="button" class="btn btn-secondary btn-sm m-2" data-toggle="modal" data-target="#modalForBulkEntries"
    id="bulkUpload">
    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
    &nbsp;Bulk Upload
</button>

@if (request()->is('admin/stock-entries'))
    @include('bulkImportModel.stock')
@elseif(request()->is('admin/item'))
    @include('bulkImportModel.item')
@endif

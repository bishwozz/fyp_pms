{{--     Modal for adding item to the stock --}}
<div class="modal fade" id="add_stock_item_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog" role="document">
        <input type="hidden" class="barcode_item_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode to Add Qty in "<span id="barcodeItemName"></span>"</h5>
            </div>
            <form id="barcodeForm">

                <div class="modal-body">
                    <select id="barcodeScanner" class="barc_dtl" name="barcode_details[]" class="form-control"
                        multiple="multiple" style="width: 100%;height: auto;">
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="barcodeSave" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>
{{--     end of the modal content --}}

{{-- Modal for adding item to the stock --}}
<div class="modal fade bd-example-modal-lg" id="search_stock_item_modal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="container">
                    <div class="row">
                        <h3>Previous Stock of <span id="modalItemName"></span></h3>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="input-group">
                                <input type="date" class="form-control p-1"
                                    value="{{ generate_date_with_extra_days(dateToday(), 7) }}" id='itemFrom'
                                    placeholder="from date" size="1">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <input type="date" class="form-control p-1" value="{{ convert_ad_from_bs() }}"
                                    id="itemTo" placeholder="to date" size="1">
                            </div>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-primary" id="fetchHistory">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="modal_table_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{--                <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

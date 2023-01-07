<!-- Modal -->
<div class="modal fade" id="modalForBulkEntries" tabindex="-1" role="dialog"
    aria-labelledby="modalForBulkEntriesTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header text-center">
                <h5 class="modal-title text-center" id="exampleModalLongTitle">Item Entries via Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal_test">
                <form action="{{ route('item.importExcel') }}" method="POST" id="importItemViaExcelForm" files=true>
                    <div class="modal-body ">
                        <input type="file" id="itemExcelFileName" name="itemExcelFileName" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Discard</button>
                        <button class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('#modalForBulkEntries').appendTo('body');
    var itemForm = $('#importItemViaExcelForm')[0];


    $('#bulkUpload').click(function() {
        $('#modal_test').html(itemForm);
        itemForm.reset();

    })

    $('#importItemViaExcelForm').submit(function(e) {
        e.preventDefault();
        let url = $('#importItemViaExcelForm').attr('action');
        let formdata = new FormData(this);


        axios.post(url, formdata).then((response) => {
            if (response.data === 1) {
                document.location = 'mst-item';
            } else {
                $('#modal_test').html(response.data);
            }
        }, (error) => {
            Swal.fire("Error !", error.response.data.message, "error")
        });
    });
</script>

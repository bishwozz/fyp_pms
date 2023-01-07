
<!-- Modal -->
<div class="modal fade" id="modalForBulkEntries" tabindex="-1" role="dialog"
aria-labelledby="modalForBulkEntriesTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">

	<div class="modal-content">

			<div class="modal-header text-center">
				<h5 class="modal-title text-center" id="exampleModalLongTitle">Stock Entries via Excel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div id="modal_test">
				<form action="{{ route('stock.importExcel') }}" method="POST" id="importViaExcelForm" files=true>
					<div class="modal-body ">
						<input type="file" id="stockExcelFileName" name="stockExcelFileName" required>
						<div class="my-2">
							<label for="flatDiscount">Is flat Discount</label>
							<input type="checkbox" id="flatDiscount" name="flatDiscount" value="1">
							<input type="number" id="flatDiscountAmount" name="flatDiscountAmount" min=0 max=100
								disabled required>
						</div>
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

<script>
$('#modalForBulkEntries').appendTo('body');
var form = $('#importViaExcelForm')[0];


$('#flatDiscount').change(function() {
	if ($(this).is(':checked')) {
		$('#flatDiscountAmount').prop('disabled', false);
	} else {
		$('#flatDiscountAmount').prop('disabled', true);

	}
})

$('#bulkUpload').click(function() {
	$('#modal_test').html(form);
	form.reset();

})

$('#importViaExcelForm').submit(function(e) {
	e.preventDefault();
	let url = $('#importViaExcelForm').attr('action');
	let formdata = new FormData(this);

	axios.post(url, formdata).then((response) => {
		if (response.data === 1) {
			document.location = 'stock-entries';
		} else {
			$('#modal_test').html(response.data);
		}
	}, (error) => {
		Swal.fire("Error !", error.response.data.message, "error")
	});
});
</script>

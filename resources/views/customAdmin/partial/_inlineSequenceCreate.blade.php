{{-- Modal for createSequence inline  --}}
<div class="modal fade" id="createSequenceCodeModel" data-backdrop="static" data-keyboard="true" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered model-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create Sequence Code </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <form method="post" action="{{ route('sequence.inlineStore') }}" id="inlineSequenceCreateForm"> --}}
            <form id="inlineSequenceCreateForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Name :</label>
                                <input type="text" class="form-control " name="name_en" id="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="sequenceCode" class="col-form-label">Sequence Code :</label>
                                <input type="text" class="form-control @error('sequence_code') is-invalid @enderror"
                                    name="sequence_code" id="sequenceCode" onkeyup="checkSequenceCode()" required>
                                <span class="invalid-feedback" id="sequenceCodeError"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="sequence_type" id="hiddenSequenceType">
                                <label for="sequenceType" class="col-form-label">Sequence Type:</label>
                                <input type="text" class="form-control" id="sequenceType">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal();"><i
                            class="la la-ban"></i>&nbsp;Close</button>
                    <button type="button" id="btnInlineCreateFormSubmit" class="btn btn-success" onclick="saveSequenceCode();" disabled><i
                            ></i>&nbsp;Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('after_scripts')
    <script>
        var select = null;

        function loadModal(e, data) {
            select = $(e).siblings('select');
            showModal();
            let seqenceCodes = '<?php echo isset($sequenceCodes) ? json_encode($sequenceCodes) : '[]'; ?>';
            seqenceCodes = JSON.parse(seqenceCodes);
            const modalTitle = seqenceCodes[data];
            $('.modal-title').text(`Create Sequence Code for ${modalTitle}`);
            $('.modal-body #sequenceType').val(modalTitle);
            $('.modal-body #hiddenSequenceType').val(data);
            $('.modal-body #sequenceType').attr('readonly', true);
        }

        function closeModal() {
            $('#createSequenceCodeModel').modal('hide');
            $('#inlineSequenceCreateForm')[0].reset();
            $('#sequenceCode').removeClass('is-invalid');
            $('#sequenceCode').removeClass('is-valid');
            $('#btnInlineCreateFormSubmit').attr('disabled', true);
        }

        function showModal() {
            $('#createSequenceCodeModel').modal({
                show: true,
                keyboard: false,
                backdrop:'static',
            });
        }

        function checkSequenceCode() {
            const url = '{{ route('sequence.code-check') }}';
            const sequenceCode = $('#sequenceCode').val();
            const sequenceCodeType = $('#hiddenSequenceType').val();
            const data = {
                sequenceCode: sequenceCode,
                sequenceCodeType: sequenceCodeType
            };
            axios.get(url, {
                    params: data
                })
                .then((response) => {
                    if (response.data.status === 'success') {
                        $('#sequenceCode').removeClass('is-invalid');
                        $('#sequenceCode').addClass('is-valid');
                        $('#sequenceCodeError').html(response.data.message);
                        $('#btnInlineCreateFormSubmit').attr('disabled', false);
                    } else if (response.data.status === 'empty') {
                        $('#sequenceCode').removeClass('is-valid');
                        $('#sequenceCode').addClass('is-invalid');
                        $('#sequenceCodeError').html(response.data.message);
                        $('#btnInlineCreateFormSubmit').attr('disabled', true);
                    } else if (response.data.status === 'error') {
                        $('#sequenceCode').removeClass('is-valid');
                        $('#sequenceCode').addClass('is-invalid');
                        $('#sequenceCodeError').html(response.data.message);
                        $('#btnInlineCreateFormSubmit').attr('disabled', true);
                    }
                })
                .catch((error) => {
                    Swal.fire(error.toJSON().message);
                });
        }

        function saveSequenceCode() {
            const url = '{{ route('sequence.inlineStore') }}';
            const sequenceCode = $('#sequenceCode').val();
            const sequenceName = $('#name').val();
            const sequenceCodeType = $('#hiddenSequenceType').val();
            const axiosData = {
                sequence_code: sequenceCode,
                sequence_type: sequenceCodeType,
                name_en: sequenceName
            };
            axios.post(url, axiosData)
                .then((response) => {
                    if (response.data.status === 'success') {
                        closeModal();
                        Swal.fire(response.data.message);
                        select.append(`<option value=${response.data.sequenceId}>${response.data.sequenceCode}</option>`);
                        select.val(response.data.sequenceId).trigger('change');
                        $("#adjustment_no").load(" #adjustment_no > *");
                    }
                })
                .catch((error) => {
                    Swal.fire(error.toJSON().message);
                });
        }
    </script>
@endpush

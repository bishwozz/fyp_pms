@push('after_scripts')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="{{ asset('js/nepali.datepicker.v2.2.min.js') }}"></script>

<script>
    var batch_details = [];

    let listOfItems = [];

    $(document).on('show.bs.modal', '.modal', function() {
        $(this).appendTo('body');
    })

    function ceil(x) {
        return Number.parseFloat(x).toFixed(2);
    }

    function roundnum(x){
        return Number.round(parseFloat(x))
    }

    $(document).ready(function() {

        $('#menu-nav-link').click(function(){
            $('#drop-nav-link').toggleClass('show')
        })

        //if approved only in allow to cancel and view the bill
        @if(isset($sales->status_id))
            @if($sales->status_id == 2)
                $("#salesForm .disableSalesReturnInput").prop("disabled", true);
                // $("#salesForm :input").prop("disabled", true);
                // $("#salesForm #return_sequence").prop("disabled", false);
                $('.cancel_approved').prop("disabled",false);
                $('#return_remarks').prop("disabled",false);
                // $('.returnbarcodeScan').prop("disabled",false);
                $('#return_type').prop("disabled",false);
                $("#return_type").click(function() {
                if ($(this).is(":checked")) {
                    $('.returnbarcodeScan').each(function() {
                        $(this).prop('disabled', true);
                        let rowId = $(this).attr('data-cntr');
                        $('#salesAddQty-'+rowId).val(($('#sold_qty-'+rowId).val())).trigger('keyup')

                    })

                } else {

                    $('.returnbarcodeScan').each(function() {
                        $(this).prop('disabled', false);
                        let rowId = $(this).attr('data-cntr');
                        $('#salesAddQty-'+rowId).val('').trigger('keyup')
                    })

                }
                calcBillAmount()
                });



            //if bill is canceled update stock and don't allow anything
            @elseif($sales->status_id ==3)
                $("#salesForm :input").prop("disabled", true);
                Swal.fire(" This bill is canceled ");
            @endif
        @endif

        $('#company_field').hide();
        $('#pan_vat_field').hide();
        $('.cheque_enabled').hide();
        $('#due_field').hide();
        $('#sl_due_amt_field').hide();
        $('#bill_type').change(function() {
            if ($('#bill_type').val() == '2') {
                $('#company_field').show();
                $('#pan_vat_field').show();
            } else {
                $('#company_field').hide();
                $('#pan_vat_field').hide();
            }
        });

        if ($('#bill_type').find(":selected").val() === '2') {
            $('#pan_vat_field').show();
            $('#company_field').show();
        }

        $('#payment_type').change(() => {
            if($('#payment_type').val() == '2'){
                $('.cheque_enabled').show()
                $('#due_field').hide();
                $('#sl_refund_field').hide();
                $('#sl_due_amt_field').hide();
                $('#sl_paid_amount').val(0);
                $('#sl_refund_amount').val(0);
                $('#sl_due_amount').val(0);

            }
            else if( $('#payment_type').val() == '3'){
                $('#due_field').show();
                $('#sl_due_amt_field').show();
                $('.cheque_enabled').hide();
                $('#sl_refund_field').hide();
                $('#sl_paid_amount').val(0);
                $('#sl_due_amount').val(0);

            }
            else{
                $('#due_field').hide();
                $('.cheque_enabled').hide();
                $('#sl_due_amt_field').hide();
                $('#sl_refund_field').show();
                $('#sl_paid_amount').val(0);
                $('#sl_due_amount').val(0);
                $('#sl_refund_amount').val(0);
            }
        })

        if ($('#payment_type').find(":selected").val() === '2') {
            $('.cheque_enabled').show()
        }

        if ($('#payment_type').find(":selected").val() === '3') {
            $('#due_field').show();
            $('#sl_due_amt_field').show();
        }

        let repeaterCounter = 1;

        let counterArray = [1];

        let availableTags = [{
            'id': '',
            'text': 'Search an item'
        }];
        let all_items = '<?php echo isset($item_lists) ? json_encode($item_lists) : "[]" ?>';

        JSON.parse(all_items).forEach(function(item) {

            availableTags.push({
                'id': item.id,
                'label': item.code + ' : ' + item.name
            });

        });

        function getLastArrayData() {
            return counterArray[counterArray.length - 1];
        }

        @if(isset($sales))
            let totalItems = {{$sales->saleItems->count()}};
            counterArray = [];

            for (let i = 1; i <= totalItems; i++) {
                counterArray.push(i)
                $("#salesItemStock-" + i).autocomplete({
                    source: availableTags,
                    minLength: 1,
                    select: function(event, ui) {
                        let itemSales = $("#salesItemStock-" + i);
                        itemSales.next().attr('name', 'itemSalesHidden[' + i + ']').val(ui.item.id);
                        getStockItemDetails(ui.item.id, i);
                        let rowId = $(this).attr('data-cntr');
                        getBatchNo(ui.item.id, 1);
                        enableFields(rowId);
                        // enableFields(1);
                    },
                });
            }

            @else
            counterArray = [1];
        @endif

        function checkNan(val) {
            return !isNaN(val) ? val : 0;
        }

        function enableFields(rowId) {
            $('#salesBatchNo-' + rowId).prop("disabled", false);
            $('#salesAddQty-' + rowId).prop("disabled", false);
            $('#salesUnit-' + rowId).prop("disabled", false);
            $('#salesUnitPrice-' + rowId).prop("disabled", false);
            $('#salesDiscount-' + rowId).prop("disabled", false);

        }

        $("#discountCheckbox").click(function() {
            if ($(this).is(":checked")) {
                $('.salesDiscount').each(function() {
                    $(this).prop('disabled', false);
                })
                $('#flatDiscount').prop('disabled', true)

            } else {
                $('#flatDiscount').attr('disabled', false);
                $('.salesDiscount').each(function() {
                    $(this).prop('disabled', true);
                })
            }
            calcBillAmount()
        });

        function resetDiscount(rowId) {
            let purchaseQty = parseInt($('#salesAddQty-' + rowId).val());
            let purchasePrice = parseFloat($('#salesUnitPrice-' + rowId).val());
            let itemDiscount = 0;
            let salesDiscountMode = $("#salesDiscountMode").val();


            if (!$('#discountCheckbox').is(':checked')) {
                itemDiscount = calcItemDiscount(purchaseQty, purchasePrice);

            } else {
                let discount = parseFloat($('#salesDiscount-' + rowId).val());
                itemDiscount = calcItemDiscount(purchaseQty, purchasePrice, salesDiscountMode, discount);
            }

            let itemAmount = calcItemAmount(purchaseQty, purchasePrice, itemDiscount);

            //Everything setter
            $('#salesAmount-' + rowId).val(itemAmount);
        }

        function calcBillAmount() {
            let grossAmt = 0;
            let totalDiscAmt = 0;
            let totalTaxAmt = 0;
            let taxableAmnt = 0;
            let netAmt = 0;
            let taxableAmount =0;

            $(".salesAmount").each(function() {
                if ($(this).val()) {
                    let currRow = $(this).attr('data-cntr');
                    resetDiscount(currRow);
                    let currItemAmt = checkNan(parseFloat($(this).val()));
                    let taxVat = checkNan(parseFloat($('#salesTax-' + currRow).val()));
                    let purchaseQty = checkNan(parseInt($('#salesAddQty-' + currRow).val()));
                    let purchasePrice = checkNan(parseFloat($('#salesUnitPrice-' + currRow).val()));
                    let discount = checkNan(parseFloat($('#salesDiscount-' + currRow).val()));
                    let salesDiscountMode = $("#salesDiscountMode").val();
                    let itemWiswDiscount = calcItemDiscount(purchaseQty, purchasePrice, salesDiscountMode, discount);

                    // if (!$('#discountCheckbox').is(':checked')) {
                    //     grossAmt = grossAmt + currItemAmt;
                    // } else {
                        // grossAmt = grossAmt + parseInt($(this).val()) + itemWiswDiscount;
                        grossAmt +=  parseInt($(this).val()) ;

                    // }

                    totalDiscAmt +=  itemWiswDiscount;
                    if(taxVat!==0){
                        taxableAmount += currItemAmt-itemWiswDiscount;
                        totalTaxAmt +=  taxableAmount * taxVat / 100;
                    }

                }

            });
            if (!$('#discountCheckbox').is(':checked')) {
                totalDiscAmt = (checkNan(parseFloat($('#flatDiscount').val())) * grossAmt) / 100;
            }

            netAmt = grossAmt - totalDiscAmt + totalTaxAmt;
            taxableAmnt = grossAmt - totalDiscAmt;
            $('#sl_gross_total').val(ceil(grossAmt));
            $('#sl_discount_amount').val(ceil(totalDiscAmt));
            $('#sl_taxable_amnt').val(ceil(taxableAmount));
            $('#sl_tax_amount').val(ceil(totalTaxAmt));
            $('#sl_net_amount').val(ceil(netAmt));
            $('#sl_paid_amount').val(0);
            $('#sl_refund_amount').val(0);
            $('#sl_due_amount').val(0);
            if($('#sales_return_blade').val()==1){
                // $('#sl_paid_amount').val((Math.round(netAmt)));
                $('#sl_paid_amount').val(0);
                $('#receipt_amount').val(ceil(netAmt));
                }
        }

        function setAllThings(rowId) {
            let salesAddQty = parseInt($('#salesAddQty-' + rowId).val());
            let salesUnitPrice = parseFloat($('#salesUnitPrice-' + rowId).val());

            let salesDiscountMode = $("#salesDiscountMode").val();
            let salesDiscount = parseFloat($('#salesDiscount-' + rowId).val());
            let itemDiscount = calcItemDiscount(salesAddQty, salesUnitPrice, salesDiscountMode, salesDiscount);
            let salesAmount = calcItemAmount(salesAddQty, salesUnitPrice, itemDiscount);


            //Everything setter
            $('#salesAmount-' + rowId).val(salesAmount);
            calcBillAmount();
        }

        function calcItemDiscount(salesAddQty, salesUnitPrice, salesDiscountMode, salesDiscount) {
            if (!salesAddQty || !salesUnitPrice || salesDiscountMode === '0' || !salesDiscount) {
                return 0;
            }

            let itemAmount = salesAddQty * salesUnitPrice;
            if (salesDiscountMode === '1') {
                return salesDiscount * itemAmount / 100;
            }
            if (salesDiscountMode === '2') {
                return salesDiscount;
            }
        }

        function calcItemAmount(salesAddQty, salesUnitPrice, itemDiscount) {
            // console.log(salesAddQty, salesUnitPrice, itemDiscount);
            if (!salesAddQty || !salesUnitPrice) {
                return 0;
            }
            return salesAddQty * salesUnitPrice;
        }

        function checkIfItemExist(itemId) {
            let idOfItemSelected = itemId;
            let indexOfItemInArray = listOfItems.indexOf(idOfItemSelected);

            if (indexOfItemInArray !== -1) {
                // console.log("PRESENT")
                // console.log("Item already selected");
                return true
            }
            // console.log(" Not PRESENT")
            return false;
        }

        //Events
        $('.salesAddQty,.salesUnitPrice,.salesDiscount').keyup(function() {
            let rowId = $(this).attr('data-cntr');
            setAllThings(rowId);
        });

        $('.salesAddQty').change(function() {
            let rowId = $(this).attr('data-cntr');
            let batchQty = parseInt($('#salesBatchQty-' + rowId).val());
            let changeQty = parseInt($(this).val());
            if (changeQty > batchQty) {
                $(this).val(0);
                Swal.fire(" Greater than Batch Quantity ")
            }
        })

        $('#flatDiscount').keyup(function() {
            calcBillAmount();
        });

        $('#salesDiscountMode').change(function() {
            $('.salesDiscount').each(function() {
                let rowId = $(this).data('cntr');
                $('#salesDiscount-' + rowId).val(0).trigger('keyup');

            })
        });

        $('#sl_paid_amount').change(function() {
            let netAmount = $('#sl_net_amount').val();
            let paidAmount = $('#sl_paid_amount').val();
            let refundAmount = 0;
            let payment_mode = $('#payment_type').val();
            refundAmount = paidAmount - netAmount;
            if(refundAmount<0 ){
                if(payment_mode!=3){
                    Swal.fire("Set Payment type Due")
                    $('#sl_paid_amount').val(0);
                }
                $('#sl_due_amount').val(ceil(netAmount-paidAmount))
            }else{
                $('#sl_refund_amount').val(ceil(refundAmount));
            }
            $('#receipt_amount').val(ceil(netAmount));
        })

        function checkLen(event){
            let len = 0;
            if($("#return_type").is(':checked')!==true){
                $.each(counterArray, function (index, value) {
                    if($('#salesAddQty-' + value).val()!=''){
                        len++;
                    }})
                // $('#sl_paid_amount').prop("disabled",false)
                // return;

                if(len <1){
                    event.preventDefault()
                    Swal.fire("Add altleast one Quantity to return")
                    return;
                }
            }
        }

        $('#salesForm').on('submit', function(event) {

            $.each(counterArray, function (index, value) {
                    $('#salesAddQty-' + value).rules("add",
                        {
                            required: true,
                            messages: {
                                required: "Field  required",
                            }
                        });
            });
        });

        $("input").focus(function(){

            $('#sl_paid_amount').rules("add",{
                        required: true,
                        number:true,
                        messages: {
                            required: "Field  required",
                            number:"Please enter number"
                        }
                    });
        })

        $('#salesForm').validate({
            submitHandler: function(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085D6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((confirmResponse) => {
                    if (confirmResponse.isConfirmed) {
                    $('#receipt_amount').prop('disabled', false);
                    $("#salesForm :input").prop("disabled", false);
                        let data = $('#salesForm').serialize();
                        let url = form.action;
                        axios.post(url, data)
                            .then((response) => {
                                if (response.data.status === 'success') {
                                    Swal.fire("Success !", response.data.message, "success")
                                    window.location.href = response.data.route;
                                } else {
                                    Swal.fire("Error !", response.data.message, "error")
                                }
                            });
                    }
                });
            }
        });

        let barcodeList =  {!! getBarcodeJson(backpack_user()->sup_org_id) !!}
        // let returnBarcode =  '';

        function flushSession(key){
            let url = '{{ route("custom.stock-barcode-flush",":id" ) }}';
            url = url.replace(':id', key);
            axios.get(url)
                .then((response) => {
                    if (response.data.status === 'success') {
                    //    console.log('session flushed');
                    } else {
                        // console.log('failed to flush session');
                    }
                });
        }

        $("#barcodeScannerSalesReturn").select2({
            tags: true,
            dropdownCssClass: 'hide',
            tokenSeparators: [',', ' ']
        })



        $('#barcodeScannerSalesReturn').on("change",function (){

            let item_id = $('.barcode_item_id').val();
            let currentBarcode = $(this).val();
            $.each(currentBarcode,function (code){

                // console.log("iTEM iD : " + item_id, "bARCODE dETAILS:" + barcodeList[currentBarcode[code]], "aLL bARCODES : "+barcodeList[currentBarcode]);
                // if(returnval==1){
                    if((barcodeList[currentBarcode[code]]==item_id)){
                        $('#sl_paid_amount').prop("disabled",false)
                        return;
                    }
                    currentBarcode.splice(currentBarcode.indexOf(currentBarcode[code]),1);
                    Swal.fire("Barcode didn't match ");
                    return;
                // }
                if(barcodeList[currentBarcode[code]] === undefined || barcodeList[currentBarcode[code]]['item_id'] != item_id || barcodeList[currentBarcode[code]]['is_active'] == true ){
                    currentBarcode.splice(currentBarcode.indexOf(currentBarcode[code]),1);
                    Swal.fire('The scanned barcode does not exists.');
                    return;
                }
            })

            $(this).val(currentBarcode);

        });

        $('.returnbarcodeScan').on("click", function () {

            let currRow = $(this).attr('data-cntr');
            let item_name = $('#salesItemStock-' + currRow).val();
            let item_id = $('#salesItemStock-' + currRow).attr('item-id');

            let batch_no = $('#salesBatchNo-'+currRow).val();
            $("#add_stock_item_modal").attr('data-cntr', currRow);
            $('#barcodeItemName').html(item_name);
            $('#barcodeScannerSalesReturn').val('').trigger('change');
            $('.barcode_item_id').val(item_id);

            // console.log(currRow, item_name, item_id, $('.barcode_item_id').val());

            if (item_id === undefined) {
				Swal.fire('Please select an item before scanning barcode.')
            }
            else if(batch_no <=0){
				Swal.fire('select batch no');
            } else {
				$('#add_stock_item_modal').modal('show');
            }
        });

		let returnBarcode = [];

        $('#barcodeSaveSalesReturn').on('click',function (){
                let data = $('#barcodeForm').serialize();
                let currRow =  $("#add_stock_item_modal").attr('data-cntr');
                let url = '{{ route("custom.sale-barcode-return",":id" ) }}';
                let itemId =$('#salesItemStock-' + currRow).attr('item-id');
                url = url.replace(':id', itemId);
                axios.post(url, data)
                    .then((response) => {
                        if (response.data.status === 'success') {
                            let currentQtyRow =  $('#salesAddQty-'+currRow);
                            currentQtyRow.val(response.data.count);
                            currentQtyRow.trigger('keyup');
                            currentQtyRow.prop('readonly',true);
							returnBarcode = $.merge(returnBarcode, response.data.barcodeList);
							$("#returnModel").val(returnBarcode);
                            $('#add_stock_item_modal').modal('hide');
                        } else {
                            Swal.fire("Error !", response.data.message, "error")
                        }
                });
        });

        $('#approve_return').on('click', function(event) {
            checkLen(event);
        });

    });

</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
@endpush

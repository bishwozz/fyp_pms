@push('after_scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/nepali.datepicker.v2.2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var batch_details = [];

        let listOfItems = [];

        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        })

        function ceil(x) {
            return Number.parseFloat(x).toFixed(2);
        }

        function roundnum(x) {
            return Number.round(parseFloat(x))
        }

        $(document).ready(function() {

            $('#menu-nav-link').click(function() {
                $('#drop-nav-link').toggleClass('show')
            })

            $('.barcodeScan').click(function() {
                $('#add_stock_item_modal').on('shown.bs.modal', function() {
                    $('#barcodeScanner').focus();
                })
            });

            // select text
            $(function() {
                var focusedElement;
                $(document).on('focus', 'input', function() {
                    if (focusedElement == this)
                        return; //already focused, return so user can now place cursor at specific point in input.
                    focusedElement = this;
                    setTimeout(function() {
                            focusedElement.select();
                        },
                        100
                    ); //select all text in any field on focus for easy re-entry. Delay sightly to allow focus to "stick" before selecting.
                });
            });

            //if approved only in allow to cancel and view the bill
            @if (isset($sales->status_id))
                @if ($sales->status_id == 2)
                    $("#salesForm :input").prop("disabled", true);
                    $('.cancel_approved').prop("disabled", false);
                    $('#return_remarks').prop("disabled", false);
                    // $('.returnbarcodeScan').prop("disabled",false);
                    $('#return_type').prop("disabled", false);
                    $("#return_type").click(function() {
                        if ($(this).is(":checked")) {

                            $('.returnbarcodeScan').each(function() {

                                $(this).prop('disabled', true);
                                let rowId = $(this).attr('data-cntr');
                                $('#salesAddQty-' + rowId).val(($('#sold_qty-' + rowId).val()))
                                    .trigger('keyup')

                            })

                        } else {

                            $('.returnbarcodeScan').each(function() {
                                $(this).prop('disabled', false);
                                let rowId = $(this).attr('data-cntr');
                                $('#salesAddQty-' + rowId).val('').trigger('keyup')
                            })

                        }
                        // calcBillAmount()
                    });



                    //if bill is canceled update stock and don't allow anything
                @elseif ($sales->status_id == 3)
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
                if ($('#payment_type').val() == '2') {
                    $('.cheque_enabled').show()
                    $('#due_field').hide();
                    $('#sl_refund_field').hide();
                    $('#sl_due_amt_field').hide();
                    $('#sl_paid_amount').val(0);
                    $('#sl_refund_amount').val(0);
                    $('#sl_due_amount').val(0);

                } else if ($('#payment_type').val() == '3') {
                    $('#due_field').show();
                    $('#sl_due_amt_field').show();
                    $('.cheque_enabled').hide();
                    $('#sl_refund_field').hide();
                    $('#sl_paid_amount').val(0);
                    $('#sl_due_amount').val(0);

                } else {
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
            let all_items = '<?php echo isset($item_lists) ? json_encode($item_lists) : '[]'; ?>';

            JSON.parse(all_items).forEach(function(item) {

                availableTags.push({
                    'id': item.id,
                    'label': item.code + ' : ' + item.name
                });

            });

            function getLastArrayData() {
                return counterArray[counterArray.length - 1];
            }

            @if (isset($sales))
                let totalItems = {{ $sales->saleItems->count() }};
                counterArray = [];

                for (let i = 1; i <= totalItems; i++) {
                    counterArray.push(i)
                    $("#salesItemStock-" + i).autocomplete({
                        source: availableTags,
                        minLength: 1,
                        autoFocus: true,
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

                // for auto complete
                $("#salesItemStock-1").autocomplete({
                    source: availableTags,
                    minLength: 1,
                    autoFocus: true,
                    select: function(event, ui) {
                        debugger;
                        let dataCntr = this.getAttribute('data-cntr');
                        let itemSales = $("#salesItemStock-" + dataCntr);
                        let hidden_id = parseInt($('#salesItemStock-' + dataCntr).attr('item-id'))

                        if (ui.item.id === hidden_id) {
                            // console.log("Checkmate")
                            return;
                        } else {
                            let itemId = $("#salesItemStock-" + dataCntr).attr('item-id')
                            let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                            if (indxOfItem !== -1) {

                                listOfItems.splice(indxOfItem, 1);
                            }

                        }
                        let present = checkIfItemExist(ui.item.id);
                        if (present) {
                            Swal.fire({
                                title: 'Item Already Exits !',
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    $('#salesItemStock-' + dataCntr).val('');
                                    $('#salesAvailableQty-' + dataCntr).val('');
                                    return;
                                }
                            })
                        } else {
                            listOfItems.push(ui.item.id)
                            itemSales.next().attr('name', 'itemSalesHidden[' + dataCntr + ']').val(ui
                                .item.id);
                            $('#salesItemStock-' + dataCntr).attr('item-id', (ui.item.id));
                            // console.log("Current Items:", listOfItems);
                            getStockItemDetails(ui.item.id, dataCntr);
                            getBatchNo(ui.item.id, dataCntr);
                            enableFields(dataCntr);


                        }

                    },
                });
            @endif

            $(document).on('keydown', '.salesDiscount', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code == 78) {
                    repeater()
                    console.log(code)
                    var index = $('.salesDiscount').index(this) + 1;
                    $('.salesItemStock').eq(index).focus();
                }
            });



            $(document).on('keydown', '.fireRepeater', function(e) {
                if (e.keyCode != 13) return;
                repeater();
            });

            $(document).on('click', '.fireRepeaterClick', function(e) {
                repeater();
            });


            $(".destroyRepeater").click(function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let tr = this.parentNode.parentNode;
                        let dataCntr = this.getAttribute('data-cntr');
                        let itemId = $('#salesItemStock-' + dataCntr).attr('item-id');

                        let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                        listOfItems.splice(indxOfItem, 1);

                        indexCntr = counterArray.indexOf(parseInt(dataCntr));
                        tr.remove();
                        flushSession('barcode-' + itemId);
                        counterArray.splice(indexCntr, 1);
                        if (counterArray.length == 1) {
                            $('#itemDestroyer-' + counterArray[0]).addClass('d-none');
                        }
                        calcBillAmount();

                    }
                })
            });

            function repeater() {
                let tr = $('#repeater').clone(true);
                tr.removeAttr('id');
                tr.removeAttr('class');
                tr.children(':first').children(':first').children(':first').addClass('customSelect2');
                setIdToRepeater(getLastArrayData() + 1, tr);
                $('#sales-table').append(tr);
                counterArray.push(getLastArrayData() + 1);
                $("#salesItemStock-" + getLastArrayData()).autocomplete({
                    source: availableTags,
                    minLength: 1,
                    select: function(event, ui) {
                        let dataCntr = this.getAttribute('data-cntr');
                        let itemSales = $("#salesItemStock-" + dataCntr);
                        let hidden_id = parseInt($('#salesItemStock-' + dataCntr).attr('item-id'))

                        if (ui.item.id === hidden_id) {
                            // console.log("Checkmate")
                            return;
                        } else {
                            let itemId = $("#salesItemStock-" + dataCntr).attr('item-id')
                            let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                            if (indxOfItem !== -1) {

                                listOfItems.splice(indxOfItem, 1);
                                // console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                            }

                        }
                        let present = checkIfItemExist(ui.item.id);
                        if (present) {
                            Swal.fire({
                                title: 'Item Already Exits !',
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    $('#salesItemStock-' + dataCntr).val('');
                                    $('#salesAvailableQty-' + dataCntr).val('');
                                    return;
                                }
                            })
                        } else {
                            listOfItems.push(ui.item.id)
                            itemSales.next().attr('name', 'itemSalesHidden[' + dataCntr + ']').val(ui
                                .item.id);
                            $('#salesItemStock-' + dataCntr).attr('item-id', (ui.item.id));
                            // console.log("Current Items:", listOfItems);
                            getStockItemDetails(ui.item.id, dataCntr);
                            getBatchNo(ui.item.id, dataCntr);
                            enableFields(dataCntr);


                        }
                        // getStockItemDetails(ui.item.id, dataCntr);
                        // getBatchNo(ui.item.id, dataCntr);
                        // enableFields(dataCntr);
                    },
                });

                if (counterArray > 1) {
                    if ($('#itemDestroyer-1').hasClass('d-none')) {
                        $('#itemDestroyer-1').removeClass('d-none')
                    }
                }
                if (counterArray.length == 2) {
                    $('#itemDestroyer-' + counterArray[0]).removeClass('d-none')
                }
            }

            $('.salesItemStock,.salesAvailableQty,.salesUnit,.salesDiscount,.salesUnitPrice').keyup(function(e) {
                if (e.ctrlKey && e.which == 13) {
                    repeater()
                }
            })

            function setIdToRepeater(cntr, cloneTr) {
                let classArr = ['salesItemStock', 'salesAvailableQty', 'salesBatchNo', 'salesBatchQty',
                    'salesAddQty', 'salesUnit', 'salesUnitPrice', 'salesDiscount', 'salesTax', 'salesAmount',
                    'itemDestroyer'
                ];
                let nameArr = ['item_id', 'sales_availableQty', 'batch_no', 'batch_qty', 'total_qty', 'unit_id',
                    'unit_cost_price', 'item_discount', 'tax_vat', 'item_total', 'itemDestroyer'
                ];
                cloneTr.children(':last').children('.destroyRepeater').attr('id', 'itemDestroyer-' + cntr).attr(
                    'data-cntr', cntr);
                cloneTr.children(':first').find('input').attr('id', 'salesItemStock-' + cntr).attr('data-cntr',
                    cntr).attr('name', 'item_id[' + cntr + ']');

                for (let i = 1; i < 11; i++) {
                    let n = i + 1;
                    attr = cloneTr.children(':nth-child(' + n + ')').attr('class');
                    if (attr == undefined) {
                        if (classArr[i] == 'salesAddQty') {}
                        cloneTr.children(':nth-child(' + n + ')').children('.input-group').children('.barcodeScan')
                            .attr('id', 'barcodeScan-' + cntr).attr('data-cntr', cntr);
                        cloneTr.children(':nth-child(' + n + ')').children('.input-group').children('.' + classArr[
                            i]).attr('id', classArr[i] + '-' + cntr).attr('data-cntr', cntr).attr('name',
                            nameArr[i] + '[' + cntr + ']');
                    } else {
                        cloneTr.children(':nth-child(' + n + ')').attr('id', classArr[i] + '-' + cntr).attr(
                            'data-cntr', cntr);
                    }
                }
            }

            function getStockItemDetails(itemId, cntr) {
                let url = '{{ route('custom.get-total', ':id') }}';
                url = url.replace(':id', itemId);
                $.get(url).then(function(response) {
                    if (response.status == 'failed') {
                        Swal.fire(response.message)
                    }
                    debugger;
                    $('#salesAvailableQty-' + cntr).val(response.availableQty);
                    $('#salesBatchNo-' + cntr).val(response.batch_detail.batch_id);
                    $('#salesTax-' + cntr).val(response.taxRate);
                    $('#salesUnit-' + cntr).val(response.unit);
                    if (response.is_price_editable === true) {
                        $('#salesUnitPrice-' + cntr).attr('readonly', false)
                    }
                })
            }

            function getBatchNo(itemId, cntr) {
                $("#salesBatchNo-" + cntr).empty();
                $("#salesBatchNo-" + cntr).append($("<option>").val("").html("--Select--"));

                let url = '{{ route('custom.get-batch', ':id') }}';
                url = url.replace(':id', itemId);
                $.get(url).then(function(response) {
                    let responseData = response.batchNumber;

                    for (let i = 0; i < responseData.length; i++) {
                        $("#salesBatchNo-" + cntr).append("<option value=" + responseData[i] + ">" +
                            responseData[i] + "</option>");

                    }
                })
            }

            $(".salesBatchNo").change(function() {

                let cntr = $(this).data('cntr');
                let itemId = $('#salesItemStock-' + cntr).attr('item-id');
                let batchId = $('#salesBatchNo-' + cntr).val();
                $('#salesAddQty-' + cntr).val(0);
                calcBillAmount();
                if (batchId) {
                    let url = '{{ route('custom.get-batch-detail', [':itemId', ':batchId']) }}';
                    url = url.replace(':batchId', batchId);
                    url = url.replace(':itemId', itemId);

                    $.get(url).then(function(response) {
                        // debugger;
                        $('#salesBatchQty-' + cntr).val(response.batch_qty);
                        $('#salesUnitPrice-' + cntr).val(response.batch_price);
                    })
                    $('#salesAddQty-' + cntr).prop("disabled", false);
                } else {
                    $('#salesBatchQty-' + cntr).val(0);
                    $('#salesUnitPrice-' + cntr).val(0);
                    $('#salesAddQty-' + cntr).prop("disabled", true);
                }
            })

            function setBatchDetails(id) {}

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
                let taxableAmount = 0;

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
                        let itemWiswDiscount = calcItemDiscount(purchaseQty, purchasePrice,
                            salesDiscountMode, discount);

                        // if (!$('#discountCheckbox').is(':checked')) {
                        //     grossAmt = grossAmt + currItemAmt;
                        // } else {
                        // grossAmt = grossAmt + parseInt($(this).val()) + itemWiswDiscount;
                        grossAmt += parseInt($(this).val());

                        // }

                        totalDiscAmt += itemWiswDiscount;
                        if (taxVat !== 0) {
                            taxableAmount += currItemAmt - itemWiswDiscount;
                            totalTaxAmt += taxableAmount * taxVat / 100;
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
                if (refundAmount < 0) {
                    if (payment_mode != 3) {
                        Swal.fire("Set Payment type Due")
                        $('#sl_paid_amount').val(0);
                    }
                    $('#sl_due_amount').val(ceil(netAmount - paidAmount))
                } else {
                    $('#sl_refund_amount').val(ceil(refundAmount));
                }
                $('#receipt_amount').val(ceil(netAmount));
            })

            function checkLen(event) {
                let len = 0;
                if ($("#return_type").is(':checked') !== true) {
                    $.each(counterArray, function(index, value) {
                        if ($('#salesAddQty-' + value).val() != '') {
                            len++;
                        }
                    })

                    if (len < 1) {
                        event.preventDefault()
                        Swal.fire("Add altleast one Quantity to return")
                        return;
                    }
                }
            }

            $('#salesForm').on('submit', function(event) {

                $.each(counterArray, function(index, value) {
                    $('#salesItemStock-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#salesBatchNo-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#salesBatchQty-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#salesAddQty-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#unit_id-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#salesUnitPrice-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                });

            });

            $("input").focus(function() {
                $('#contact_number').rules("add", {
                    required: true,
                    number: true,
                    minlength: 7,
                    maxlength: 10,
                    messages: {
                        required: "Field  required",
                        minlength: "Please enter a valid phone number"
                    }
                });
                $('#full_name').rules("add", {
                    required: true,
                    messages: {
                        required: "Field  required",
                    }
                });
                $('#sl_paid_amount').rules("add", {
                    required: true,
                    number: true,
                    messages: {
                        required: "Field  required",
                        number: "Please enter number"
                    }
                });
            })

            $('#save').on('click', function() {
                $('#status').val({{ \App\Models\Pms\SupStatus::CREATED }});
            });

            $('#approve').on('click', function(e) {
                debugger
                let paid_amount = parseInt($('#sl_paid_amount').val());
                $.each(counterArray, function(index, value) {
                    debugger
                    let currQty = $('#salesBatchQty-' + value).val()
                    let selectedQty = $('#salesAddQty-' + value).val()
                    let itemName = $('#salesItemStock-' + value).val()
                    if (currQty - selectedQty < 0) {
                        swal.fire(`${itemName} is out of stock`)
                        $('#salesAddQty-' + value).val(0)
                        calcBillAmount();
                        e.preventDefault();
                    }
                    if (selectedQty <= 0) {
                        swal.fire(`Select Qty for ${itemName}`)
                        $('#salesAddQty-' + value).val(0)
                        calcBillAmount();
                        e.preventDefault();
                    }
                })

                if ($('#payment_type').val() == 2) {
                    $('#bank_name').rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#cheque_number').rules("add", {
                        required: true,
                        number: true,
                        messages: {
                            required: "Field  required",
                            number: "Cheque must be number"
                        }
                    });
                    $('#ac_holder_name').rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#branch_name').rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#cheque_date').rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                }

                if (paid_amount <= 0) {
                    Swal.fire("Enter paid amount to approve");
                    e.preventDefault();
                } else {
                    $('#status').val({{ \App\Models\Pms\SupStatus::APPROVED }});
                }
            });

            $('#cancel').on('click', function() {
                $('#status').val({{ \App\Models\Pms\SupStatus::CANCELLED }});

            });

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
                                    debugger;
                                    if (response.data.status === 'success') {
                                        Swal.fire("Success !", response.data.message,
                                            "success")
                                        window.location.href = response.data.route;
                                    } else {
                                        Swal.fire("Error !", response.data.message, "error")
                                    }
                                });
                        }
                    });
                }
            });

            let barcodeList = [''];

            $("#barcodeScanner").select2({
                tags: true,
                dropdownCssClass: 'hide',
                tokenSeparators: [',', ' ']
            })

            function flushSession(key) {
                let url = '{{ route('custom.stock-barcode-flush', ':id') }}';
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

            // $("#barcodeScanner").on("keypress", function (e) {
            //   if (e.keyCode == 13) {
            //     alert("asdasd");
            //   }
            // });

            $("#barcodeScanner").keyup(function(e) {
                if (e.keyCode == 13) {
                    alert("asdasd");
                }
            });



            $('#barcodeScanner').on("change", function() {


                let item_id = $('.barcode_item_id').val();
                let currentBarcode = $(this).val();
                var final_barcode_array = []
                let fba = []
                $.each(currentBarcode, function(code) {
                    var multiple_barcode = currentBarcode[code].toString()
                    const first_four_char = multiple_barcode.substring(0, 4)

                    function countOcurrences(str, value) {
                        var regExp = new RegExp(value, "gi");
                        return (str.match(regExp) || []).length;
                    }

                    var ocurrance_count = countOcurrences(multiple_barcode, first_four_char)
                    let withoutFirst = []
                    if (ocurrance_count > 3) {
                        console.log(ocurrance_count)

                        var myarray = multiple_barcode.split(first_four_char).map(function(
                            first_four_charrr) {
                            return multiple_barcode.substring(0, 4) + first_four_charrr
                        });
                        withoutFirst = myarray.slice(1);

                        final_barcode_array += withoutFirst
                        check_exist_or_not = final_barcode_array.split(",")

                        console.log(check_exist_or_not)
                        $.each(check_exist_or_not, function(code) {
                            if (barcodeList[check_exist_or_not[code]] === undefined ||
                                barcodeList[check_exist_or_not[code]]['item_id'] !=
                                item_id || barcodeList[check_exist_or_not[code]][
                                    'is_active'
                                ] == false) {
                                currentBarcode.splice(currentBarcode.indexOf(currentBarcode[
                                    code]), 1);
                                let timerInterval
                                // debugger;
                                Swal.fire({
                                    title: 'The scanned barcode does not exists!',
                                    timer: 1000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()
                                        const b = Swal.getHtmlContainer()
                                            .querySelector('b')
                                        timerInterval = setInterval(() => {},
                                            100)
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval)
                                    }
                                })
                                return;
                            }
                        })
                    } else {
                        // debugger;
                        final_barcode_array = currentBarcode[code]
                        // debugger;
                        if (barcodeList[currentBarcode[code]] === undefined || barcodeList[
                                currentBarcode[code]]['item_id'] != item_id || barcodeList[
                                currentBarcode[code]]['is_active'] == false) {
                                    // debugger;
                            currentBarcode.splice(currentBarcode.indexOf(currentBarcode[code]), 1);
                            let timerInterval
                            // debugger;
                            Swal.fire({
                                title: 'The scanned barcode does not exists!',
                                timer: 1000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {}, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                            })
                            return;
                        }
                    }
                    fba = final_barcode_array.split(',')
                    $.each(fba, function(code) {
                        if (barcodeList[fba[code]] === undefined || barcodeList[fba[code]][
                                'item_id'
                            ] != item_id || barcodeList[fba[code]]['is_active'] == false) {
                            fba.splice(fba.indexOf(fba[code]), 1);
                            let timerInterval
                            // debugger;
                            Swal.fire({
                                title: 'The scanned barcode does not exists!',
                                timer: 1000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer()
                                        .querySelector('b')
                                    timerInterval = setInterval(() => {}, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                            })
                            return;
                        }
                    })
                })
                $(this).val(currentBarcode);
            });

            $('.barcodeScan').on("click", function() {
                let currRow = $(this).attr('data-cntr');
                let item_name = $('#salesItemStock-' + currRow).val();
                let item_id = $('#salesItemStock-' + currRow).attr('item-id');
                let batch_no = $('#salesBatchNo-' + currRow).val();
                $("#add_stock_item_modal").attr('data-cntr', currRow);
                $('#barcodeItemName').html(item_name);
                $('#barcodeScanner').val('').trigger('change');
                $('.barcode_item_id').val(item_id);
                if (item_id === undefined) {
                    Swal.fire('Please select an item before scanning barcode.')
                } else if (batch_no <= 0) {
                    Swal.fire('select batch no');

                } else {
                    $('#add_stock_item_modal').modal('show');
                }
            });

            $('#barcodeSave').on('click', function() {
                let data = $('#barcodeForm').serialize();
                let currRow = $("#add_stock_item_modal").attr('data-cntr');
                let url = '{{ route('custom.sale-barcode', [':itemId', ':batchId']) }}';
                let itemId = $('#salesItemStock-' + currRow).attr('item-id');
                let batchId = $('#salesBatchNo-' + currRow).val();
                url = url.replace(':itemId', itemId);
                url = url.replace(':batchId', batchId);
                axios.post(url, data)
                    .then((response) => {
                        if (response.data.status === 'success') {
                            let currentQtyRow = $('#salesAddQty-' + currRow);
                            currentQtyRow.val(response.data.count);
                            currentQtyRow.trigger('keyup');
                            currentQtyRow.prop('readonly', true);
                            barcodeList = JSON.parse(response.data.barcodeList);
                            $('#add_stock_item_modal').modal('hide');
                        } else {
                            Swal.fire("Error !", response.data.message, "error")
                        }
                    });
            });
        });

        $(document).ready(function() {
            // $('#customerType').modal('show');

            $('[data-toggle="tooltip"]').tooltip();

            $('#buyerSelect').select2({
                dropdownParent: $('#customerModal'),
                width: 'resolve',
            });

            $('#customerName').select2({
                dropdownParent: $('#coorporateCustomerModal'),
                width: 'resolve',
            });

            $('#companyName').select2({
                dropdownParent: $('#coorporateCustomerModal'),
                width: 'resolve',
            });
        });

        // $('#customer-div').on('click', function(params) {
        //     $('#customerModal').modal('show');
        // });

        $('#customer-div').on('click', function(params) {
            $('#customerType').modal('show');
        });

        $('#individualCustomer').click(function(e) {
            e.preventDefault();
            $('#customerType').modal('hide');
            $('#customerModal').modal('show');
        });

        $('#coorporateCustomer').click(function(e) {
            e.preventDefault();
            $('#customerType').modal('hide');
            $('#coorporateCustomerModal').modal('show');
        });

        //On choosing customer from individual customer model
        $('#indCustomerSave').on('click', function(params) {
            var val = $('#buyerSelect').val();
            var text = $("#buyerSelect option:selected").text();

            if(val != null){
                $('#hidden_customer').val(val);
                let url = '{{ route('api.customerDetail', ':id') }}';
                url = url.replace(':id', val);
                axios.get(url)
                    .then((response) => {
                        if (response.data.status === 'success') {
                            if (response.data.customer.is_coorporate == false) {
                                var bill_type = 1;
                            } else {
                                var bill_type = 2;
                            }
                            $('#full_name').val(response.data.customer.name_en);
                            $('select[name="bill_type"]').val(bill_type).change();
                            $('#hidden-bill-type').val(bill_type);
                            $('#contact_number').val(response.data.customer.contact_number);
                            $('#address').val(response.data.customer.address);
                            $('#company_name').val(response.data.customer.company_name);
                            $('#pan_vat').val(response.data.customer.pan_no);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Try Again',
                            });
                        }
                    });
                $('#customerModal').modal('hide');
                $("#buyerSelect").val("");
            }
            else{
                swal("Oops!", "Customer Name must be sleected.", "error");
            }
        });

        //On choosing customer from individual customer model via create new btn
        $('#createIndividualCustomer').click(function(e) {
            e.preventDefault();
            var name = $('#customerNameInput').val();
            if (name.length != 0) {
                $('#full_name').val($('#customerNameInput').val());
                $('#customerModal').modal('hide');
                $('#customerNameInput').val('');
            }else{
                swal("Oops!", "Customer Name is required before creating new customer", "error");
                $('#customerModal').modal('show');
            }
        });

        //On choosing company for customer from coorporate customer model
        $('#companyName').on('change', function(e) {
            var companyName = $("#companyName option:selected").text();
            let url = '/admin/api/customer/company/' + companyName;
            axios.get(url)
                .then((response) => {
                    if (response.data.status === 'success') {
                        $('#customerName').empty();
                        $('#customerName').append('<option selected disabled> Select Customer </option>');
                        $.each(response.data.customer, function(index, customer) {
                            $('#customerName').append('<option value="' + customer.id + '">' + customer
                                .name_en + '</option>');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! Try Again',
                        });
                    }
                });
        });

        //On choosing customer from coorporate customer model
        $('#companySave').on('click', function(e) {
            var val = $('#customerName').val();
            var text = $("#customerName option:selected").text();
            if(val != null){
                $('#hidden_customer').val(val);
                let url = '{{ route('api.customerDetail', ':id') }}';
                url = url.replace(':id', val);
                axios.get(url)
                    .then((response) => {
                        if (response.data.status === 'success') {
                            if (response.data.customer.is_coorporate == false) {
                                var bill_type = 1;
                            } else {
                                var bill_type = 2;
                            }
                            $('#full_name').val(response.data.customer.name_en);
                            $('select[name="bill_type"]').val(bill_type).change();
                            $('#hidden-bill-type').val(bill_type);
                            $('#contact_number').val(response.data.customer.contact_number);
                            $('#address').val(response.data.customer.address);
                            $('#company_name').val(response.data.customer.company_name);
                            $('#pan_vat').val(response.data.customer.pan_no);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Try Again',
                            });
                        }
                    });
                $('#coorporateCustomerModal').modal('hide');
                $("#companyName").val("");
                $("#customerName").val("");
            }
            else{
                swal("Oops!", "Customer Name must be sleected.", "error");
            }
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
@endpush

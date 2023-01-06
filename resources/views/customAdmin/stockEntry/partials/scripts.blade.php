@push('after_scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('js/nepali.datepicker.v2.2.min.js') }}"></script>

    @isset($stock)
        <script type="text/javascript">
            $(document).ready(function () {
                var itemId = $("#itemStock-1").val();
                $('#availableQty-1').val(ui.item.qty);
            });
        </script>
    @endisset

    <script>

        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        })
        document.getElementById('itemStock-1').focus();
        $(document).ready(function () {
                $('#menu-nav-link').click(function(){
                $('#drop-nav-link').toggleClass('show')
            })
            $('.barcodeScan').click(function () {
            $('#add_stock_item_modal').on('shown.bs.modal', function () {
                $('#barcodeScanner').focus();
                })
            });
            function ceil(x) {
                return Number.parseFloat(x).toFixed(2);
            }

            // select text
            $(function () {
                var focusedElement;
                $(document).on('focus', 'input', function () {
                    if (focusedElement == this) return; //already focused, return so user can now place cursor at specific point in input.
                    focusedElement = this;
                    setTimeout(function () { focusedElement.select(); }, 100); //select all text in any field on focus for easy re-entry. Delay sightly to allow focus to "stick" before selecting.
                });
            });



            function setAllThings(rowId) {
                // =====
                let purchaseQty = parseInt($('#addQty-' + rowId).val());
                let freeQty = parseInt($('#freeQty-' + rowId).val());
                let purchasePrice = parseFloat($('#unitPrice-' + rowId).val());
                let availableQty = parseFloat($('#availableQty-' + rowId).val());
                let qty_number = parseFloat($('#custom_Qty-' + rowId).val());
                if(!qty_number){
                    let totalQty = calcTotalQty(purchaseQty, freeQty, availableQty);
                    // let discountMode = $("#discount_mode-" + rowId).val();
                    let discount = parseFloat($('#discount-' + rowId).val());
                    let itemDiscount = calcItemDiscount(purchaseQty, purchasePrice, discount);
                    let itemAmount = calcItemAmount(purchaseQty, purchasePrice, itemDiscount);
                    //Everything setter
                    $("#totalQty-" + rowId).val(totalQty);
                    $('#totalAmnt-' + rowId).val(itemAmount);
                    calcBillAmount();
                }else{

                    let totalQty = calcTotalQtyOfNumber(qty_number, freeQty, availableQty);
                    let discount = parseFloat($('#discount-' + rowId).val());
                    let itemDiscount = calcItemDiscount(qty_number, purchasePrice, discount);
                    let itemAmount = calcItemAmountOfQtyNumber(qty_number, purchasePrice, itemDiscount);
                    //Everything setter
                    $("#totalQty-" + rowId).val(totalQty);
                    $('#totalAmnt-' + rowId).val(itemAmount);
                    calcBillAmount();
                }
            }

            function resetDiscount(rowId) {
                let purchasePrice = parseFloat($('#unitPrice-' + rowId).val());
                let qty_number = parseFloat($('#custom_Qty-' + rowId).val());
                let purchaseQty = parseInt($('#addQty-' + rowId).val());

                let itemDiscount = 0;
                // ==========
                if(qty_number){
                    // let discountMode = $("#discount_mode-" + rowId).val();
                    if (!$('#discountCheckbox').is(':checked')) {
                        itemDiscount = calcItemDiscount(purchaseQty, purchasePrice);

                    } else {
                        let discount = parseFloat($('#discount-' + rowId).val());
                        itemDiscount = calcItemDiscount(qty_number, purchasePrice, discount);
                    }

                    let itemAmount = calcItemAmount(qty_number, purchasePrice, itemDiscount);

                    //Everything setter
                    $('#totalAmnt-' + rowId).val(itemAmount);

                }else{
                    // let discountMode = $("#discount_mode-" + rowId).val();
                    if (!$('#discountCheckbox').is(':checked')) {
                        itemDiscount = calcItemDiscount(purchaseQty, purchasePrice);

                    } else {
                        let discount = parseFloat($('#discount-' + rowId).val());
                        itemDiscount = calcItemDiscount(purchaseQty, purchasePrice, discount);
                    }
                    let itemAmount = calcItemAmount(purchaseQty, purchasePrice, itemDiscount);
                    //Everything setter
                    $('#totalAmnt-' + rowId).val(itemAmount);
                }


            }

            function EnterToNext(){

            }

            function calcBillAmount() {
                let grossAmt = 0;
                let totalDiscAmt = 0;
                let totalTaxAmt = 0;
                let taxableAmnt = 0;
                let netAmt = 0;

                $(".totalAmnt").each(function() {
                    if ($(this).val()) {
                        let currRow = $(this).attr('data-cntr');
                        resetDiscount(currRow);
                        let currItemAmt = checkNan(parseFloat($(this).val()));
                        let taxVat = checkNan(parseFloat($('#itemTax-' + currRow).val()));

                        let purchaseQty = checkNan(parseInt($('#addQty-' + currRow).val()));
                        let purchasePrice = checkNan(parseFloat($('#unitPrice-' + currRow).val()));
                        let discount = checkNan(parseFloat($('#discount-' + currRow).val()));
                        let itemWiswDiscount = calcItemDiscount(purchaseQty, purchasePrice, discount);

                        if (!$('#discountCheckbox').is(':checked')) {
                            grossAmt = grossAmt + currItemAmt;
                        } else {
                            grossAmt = grossAmt + currItemAmt + itemWiswDiscount;
                        }

                        totalDiscAmt = totalDiscAmt + itemWiswDiscount;
                        totalTaxAmt = totalTaxAmt + currItemAmt * taxVat / 100;

                    }
                });

                if (!$('#discountCheckbox').is(':checked')) {
                    totalDiscAmt = (checkNan(parseFloat($('#flatDiscount').val())) * grossAmt) / 100;
                }

                netAmt = grossAmt - totalDiscAmt + totalTaxAmt;

                taxableAmnt = grossAmt - totalDiscAmt;
                $('#st_gross_total').val(ceil(grossAmt));
                $('#st_discount_amount').val(ceil(totalDiscAmt));

                $('#st_taxable_amnt').val(ceil(taxableAmnt));
                $('#st_tax_amount').val(ceil(totalTaxAmt));
                $('#st_net_amount').val(ceil(netAmt));
            }

            function checkNan(val) {
                return !isNaN(val) ? val : 0;
            }


            function calcTotalQty(purchaseQty, freeQty, availableQty) {

                if (!freeQty) {
                    freeQty = 0;
                }
                if (!purchaseQty) {
                    purchaseQty = 0;
                }
                if (!availableQty) {
                    availableQty = 0;
                }
                return purchaseQty + freeQty + availableQty;

            }
            function calcTotalQtyOfNumber(qty_number, freeQty, availableQty) {

                if (!freeQty) {
                    freeQty = 0;
                }
                if (!qty_number) {
                    qty_number = 0;
                }
                if (!availableQty) {
                    availableQty = 0;
                }
                return qty_number + freeQty + availableQty;

            }

            function calcItemDiscount(purchaseQty, purchasePrice, discount = 0) {
                if (!purchaseQty || !purchasePrice || !discount) {
                    return 0;
                }

                let itemAmount = purchaseQty * purchasePrice;
                return discount * itemAmount / 100;
            }

            function calcItemAmount(purchaseQty=null, purchasePrice, itemDiscount) {

                if (!purchaseQty || !purchasePrice) {
                    return 0;
                }
                if (!$('#discountCheckbox').is(':checked')) {
                    return purchaseQty * purchasePrice;
                }
                return purchaseQty * purchasePrice - itemDiscount;

            }
            function calcItemAmountOfQtyNumber(qty_number, purchasePrice, itemDiscount) {

                if (!qty_number || !purchasePrice) {
                    return 0;
                }
                if (!$('#discountCheckbox').is(':checked')) {
                    return qty_number * purchasePrice;
                }
                return qty_number * purchasePrice - itemDiscount;

            }


            function checkIfItemExist(rowId) {
                let idOfItemSelected = parseInt($("#itemHistory-" + rowId).attr('item-id'));
                let indexOfItemInArray = listOfItems.includes(idOfItemSelected);

                if (indexOfItemInArray) {
                    return true;
                }
                return false;
            }

            $('.itemstock').keyup(function(e){
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            })

            //Events
            $('.itemStock').keyup(function (e) {
                let rowId = $(this).attr('data-cntr');
                setAllThings(rowId);
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            });
            $('.unitPrice').keyup(function (e) {
                let rowId = $(this).attr('data-cntr');
                setAllThings(rowId);
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            });
            $('.custom_Qty').keyup(function (e) {
                let rowId = $(this).attr('data-cntr');
                setAllThings(rowId);
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            });
            $('.salesPrice').keyup(function (e) {
                let rowId = $(this).attr('data-cntr');
                setAllThings(rowId);
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            });
            $('.discount').keyup(function (e) {
                let rowId = $(this).attr('data-cntr');
                setAllThings(rowId);
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            });
            $('#flatDiscount').keyup(function() {
                calcBillAmount();
                if(e.ctrlKey && e.which == 13){
                    repeater()
                }
            });

            let counterArray = [];
            let listOfItems = [];
            let availableTags = [{
                'id': '',
                'text': 'Search an item'
            }];
            let all_items = '<?php echo isset($item_lists) ? json_encode($item_lists) : '[]'; ?>';

            JSON.parse(all_items).forEach(function(item) {
                availableTags.push({
                    'id': item.id,
                    'label': item.code + ' : ' + item.name,
                    'qty': item.qty
                });
            });
            //For autocomplete item search

            @if (isset($stock))
                let totalItems = {{ $stock->items->count() }};
                let selectedItems = {{ $stock->items->pluck('id') }};
                for (let i = 1; i <= totalItems; i++) {
                    counterArray.push(i)
                    listOfItems.push(selectedItems[i-1]);
                    $("#itemStock-" + i).autocomplete({
                        source: availableTags,
                        autoFocus: true,
                        minLength: 1,
                        select: function (event, ui) {
                            let itemStock = $("#itemStock-" + i);
                            itemStock.next().attr('name', 'itemStockHidden[' + i + ']').val(ui.item.id);
                            $('#itemHistory-' + i).attr('item-id', ui.item.id)
                            getStockItemDetails(ui.item.id, i);
                        },
                    });
                }
            @else
                counterArray = [1];
                $("#itemStock-1").autocomplete({
                    source: availableTags,
                    minLength: 0,
                    autoFocus: true,
                    select: function (event, ui) {
                            let itemStock = $("#itemStock-1");
                            $('#itemHistory-1').attr('item-id', ui.item.id)
                            itemStock.next().attr('name', 'itemStockHidden[1]').val(ui.item.id);
                            getStockItemDetails(ui.item.id, 1);
                            $('#availableQty-1').val(ui.item.qty);
                            if (checkIfItemExist(1)) {
                                Swal.fire({
                                    title: 'Item Already Exits !',
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (result.isConfirmed) {
                                        $("#itemStock-1").val('');
                                        return;
                                    }
                                })
                            } else {
                                listOfItems.push(ui.item.id)
                            }
                    },
                }).focus(function() { $(this).keydown(); })
            @endif

            $(document).on('keydown', '.discount', function (e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code == 78) {
                    repeater()
                    var index = $('.discount').index(this) + 1;
                    $('.itemStock').eq(index).focus();
                }
            });

            $(document).on('click', '.fireRepeaterClick', function(e) {
                repeater();
                var index = $('.discount').index(this) + 1;
                $('.itemStock').eq(index+1).focus();
            });

            function getLastArrayData() {
                return counterArray[counterArray.length - 1];
            }

            function repeater() {
                let tr = $('#repeater').clone(true);
                tr.removeAttr('id');
                tr.removeAttr('class');
                tr.children(':first').children(':first').children(':first').addClass('customSelect2');

                setIdToRepeater(getLastArrayData() + 1, tr);
                $('#stock-table').append(tr);
                counterArray.push(getLastArrayData() + 1);
                let i = counterArray.length;

                $(document).on('keydown', '.discount', function(e) {
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if (code == 13) {
                        var index = $('.discount').index(this) + 1;
                        $('.itemStock').eq(index).focus();
                    }
                });

                $("#itemStock-" + getLastArrayData()).autocomplete({
                    source: availableTags,
                    minLength: 1,
                    select: function(event, ui) {
                        let dataCntr = this.getAttribute('data-cntr');
                        let itemStock = $("#itemStock-" + dataCntr);
                        $('#itemHistory-' + dataCntr).attr('item-id', ui.item.id)
                        itemStock.next().attr('name', 'itemStockHidden[' + dataCntr + ']').val(ui.item.id);
                        getStockItemDetails(ui.item.id, dataCntr);
                        $('#availableQty-' + getLastArrayData()).val(ui.item.qty);
                        if (checkIfItemExist(dataCntr)) {
                            Swal.fire({
                                title: 'Item Already Exits !',
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    $("#itemStock-" + dataCntr).val('');
                                    return;
                                }
                            })
                        } else {
                            listOfItems.push(ui.item.id)
                        }
                    },
                });

                if (counterArray.length > 1) {
                    if ($('#itemDestroyer-1').hasClass('d-none')) {
                        $('#itemDestroyer-1').removeClass('d-none')
                    }
                }
                if (counterArray.length == 2) {
                    $('#itemDestroyer-' + counterArray[0]).removeClass('d-none')
                }
            }

            function setIdToRepeater(cntr, cloneTr) {
                let classArr = ['itemStock', 'availableQty','custom_Qty', 'totalQty', 'itemExpiry',
                    'unitPrice', 'salesPrice', 'discount', 'itemTax', 'totalAmnt', 'itemDestroyer'
                ];
                let nameArr = ['mst_item_id', 'available_total_qty','custom_Qty', 'total_qty',
                    'expiry_date', 'unit_cost_price', 'unit_sales_price', 'discount', 'tax_vat', 'item_total',
                    'itemDestroyer'
                ];
                cloneTr.children(':last').children('.destroyRepeater').attr('id', 'itemDestroyer-' + cntr).attr(
                    'data-cntr', cntr);
                cloneTr.children(':last').children('.itemHistory').attr('id', 'itemHistory-' + cntr).attr(
                    'data-cntr', cntr);
                cloneTr.children(':first').find('input').attr('id', 'itemStock-' + cntr).attr('data-cntr', cntr)
                    .attr('name', 'mst_item_id[' + cntr + ']');

                for (let i = 1; i < (nameArr.length -1); i++) {
                    let n = i + 1;

                    attr = cloneTr.children(':nth-child(' + n + ')').attr('class');
                    if (attr == undefined) {
                        cloneTr.children(':nth-child(' + n + ')').children('.input-group').children('.custom_Qty')
                            .attr('id', 'custom_Qty-' + cntr).attr('data-cntr', cntr).attr('name', 'custom_Qty' + '[' + cntr + ']');

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
                let url = '{{ route('custom.stock-item', ':id') }}';
                url = url.replace(':id', itemId);
                $.get(url).then(function(response) {
                    $('#itemTax-' + cntr).val(response.taxRate);
                })
            }

            $("#discountCheckbox").click(function() {
                if ($(this).is(":checked")) {
                    $('.discount').each(function() {
                        $(this).prop('disabled', false);
                    })
                    $('#flatDiscount').prop('disabled', true)
                } else {
                    $('#flatDiscount').attr('disabled', false);
                    $('.discount').each(function() {
                        $(this).prop('disabled', true);
                    })
                }
                calcBillAmount()
            });
            $('#stockEntryForm').on('submit', function(event) {
                $('#client_id').rules("add", {
                    required: true,
                    messages: {
                        required: "Field  required",
                    }
                });
                $.each(counterArray, function(index, value) {
                    $('#itemStock-' + value).rules("add", {
                        required: true,
                        messages: {
                            required: "Field  required",
                        }
                    });
                    $('#custom_Qty-' + value).rules("add", {
                        required: true,
                        number: true,
                        messages: {
                            required: "Field Required",
                            number: 'Field must be a number'
                        }
                    });
                    $('#discount-' + value).rules("add", {
                        number: true,
                        messages: {
                            number: "Field must be a number",
                        }
                    });

                });
            });

            var form = $('#stockEntryForm')[0];

            $('#save').on('click', function() {
                $('#status').val({{ \App\Models\Pms\SupStatus::CREATED }});
            });

            $('#approve').on('click', function() {
                $('#status').val({{ \App\Models\Pms\SupStatus::APPROVED }});
            });

            $('#stockEntryForm').validate({
                submitHandler: function(form) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085D6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                    }).then((response) => {
                        if (response.isConfirmed) {
                            let data = $('#stockEntryForm').serialize();
                            let url = form.action;
                            axios.post(url, data)
                                .then((response) => {
                                    if (response.data.status === 'success') {
                                        Swal.fire("Success !", response.data.message, "success")
                                        window.location.href = response.data.route;
                                    } else {
                                        Swal.fire("Error !", response.data.message, "error");
                                    }
                                });
                        }
                    });
                }
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
                        let itemId = $('#itemHistory-' + dataCntr).attr('item-id');

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

        });

        // History
        $(document).on("click", ".itemHistory", function() {
            let currRow = $(this).attr('data-cntr');
            let item_name = $('#itemStock-' + currRow).val();
            let item_id = $('#itemHistory-' + currRow).attr('item-id');

            $("#search_stock_item_modal").attr('data-cntr', item_id);
            $('#modalItemName').html(item_name);
            let history_from = $('#itemFrom').val();
            let history_to = $('#itemTo').val();
            if (item_id === undefined) {
                Swal.fire('Please select an item before searching history')
            } else {
                getStockHistory(item_id, history_from, history_to);
                $('#search_stock_item_modal').modal('show');
            }
        });
        $("#fetchHistory").click(function() {
            let itemId = $('#search_stock_item_modal').attr("data-cntr");
            let history_from = $('#itemFrom').val();
            let history_to = $('#itemTo').val();
            getStockHistory(itemId, history_from, history_to);
        })

        function getStockHistory(itemId, history_from, history_to) {
            let url = '{{ route('custom.stock-item-search', [':id', ':to', ':from']) }}'
            url = url.replace(':id', itemId);
            url = url.replace(':to', history_from);
            url = url.replace(':from', history_to);

            $.get(url).then(function(response) {
                $("#modal_table_content").html(response);
            })
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
@endpush

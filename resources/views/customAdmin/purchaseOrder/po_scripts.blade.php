
<!-- validation cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
    $(document).on('show.bs.modal', '.modal', function() {
        $(this).appendTo('body');
    })
</script>


<script>
    $(document).ready(function() {
        $('#menu-nav-link').click(function(){
                $('#drop-nav-link').toggleClass('show')
            })

        if ($('#po_type').find(":selected").val() === '1') {
            $('#supplier').prop('disabled', false)
        }
        if ($('#po_type').find(":selected").val() === '2') {
            $('#requested_store').prop('disabled', false)
        }

        $('.po_discount_mode').each(function() {
            if ($(this).find(":selected").val() === '2') {
                let currRow = $(this).attr('tr-id');
                $('#po_discount-' + currRow).removeAttr("max");
            }
        });

        $('#save').on('click', function() {
            $('#status').val({{\App\Models\Pms\ SupStatus::CREATED}});
            console.log($('#status').val())
            // debugger;
        });
        $('#approve').on('click', function() {
            $('#status').val({{\App\ Models\ Pms\ SupStatus::APPROVED}});
            console.log("helllllllllllllllll", $('#status').val())
            // debugger;
        });
        $('#cancelBtn').on('click', function() {
            $('#status').val({{\App\ Models\ Pms\ SupStatus::CANCELLED}});
            console.log("helllllllllllllllll", $('#status').val())
            // debugger;
        });


        function setAllThings(rowId) {
            let purchaseQty = parseInt($('#po_purchase_qty-' + rowId).val());
            let freeQty = parseInt($('#po_free_qty-' + rowId).val());
            let totalQty = calcTotalQty(purchaseQty, freeQty)
            let purchasePrice = parseFloat($('#po_purchase_price-' + rowId).val());

            let discountMode = $("#po_discount_mode-" + rowId).val();
            let discount = parseFloat($('#po_discount-' + rowId).val());
            let itemDiscount = calcItemDiscount(purchaseQty, purchasePrice, discountMode, discount);
            let itemAmount = calcItemAmount(purchaseQty, purchasePrice, itemDiscount);

            console.log("itemAMMAT", purchaseQty, freeQty, totalQty, purchasePrice, discountMode, discount);

            //Everything setter
            $("#po_total_qty-" + rowId).val(totalQty);
            $('#po_item_amount-' + rowId).val(itemAmount);
            calcBillAmount();
        }
        function checkNan(val) {
                return !isNaN(val) ? val : 0;
            }
        function calcBillAmount() {
            let grossAmt = 0;
            let totalDiscAmt = 0;
            let totalTaxAmt = 0;
            let otherCharges = parseFloat($("#po_other_charges").val());
            let netAmt = 0;

            $(".po_item_amount").each(function() {
                if ($(this).val()) {
                    let currRow = $(this).attr('tr-id');
                    let currItemAmt = checkNan(parseFloat($(this).val()));
                    let taxVat =checkNan( parseFloat($('#po_tax_vat-' + currRow).val()));

                    let purchaseQty = checkNan(parseInt($('#po_purchase_qty-' + currRow).val()));
                    let purchasePrice = checkNan(parseFloat($('#po_purchase_price-' + currRow).val()));
                    let discountMode = $("#po_discount_mode-" + currRow).val();
                    let discount = checkNan(parseFloat($('#po_discount-' + currRow).val()));
                    let itemWiswDiscount = calcItemDiscount(purchaseQty, purchasePrice, discountMode, discount);


                    grossAmt = grossAmt + parseInt($(this).val()) + itemWiswDiscount;
                    totalDiscAmt = totalDiscAmt + itemWiswDiscount;
                    totalTaxAmt = totalTaxAmt + currItemAmt * taxVat / 100;

                }
            });

            if (!otherCharges) {
                otherCharges = 0;
            }
            netAmt = grossAmt - totalDiscAmt + totalTaxAmt + otherCharges;

            $('#po_gross_amount').val(grossAmt);
            $('#po_discount_amount').val(totalDiscAmt);
            $('#po_tax_amount').val(totalTaxAmt);
            $('#po_net_amount').val(netAmt);
        }


        function calcTotalQty(purchaseQty, freeQty) {
            if (!freeQty) {
                freeQty = 0;
            }
            if (!purchaseQty) {
                purchaseQty = 0;
            }
            return purchaseQty + freeQty;
        }

        function calcItemDiscount(purchaseQty, purchasePrice, discountMode, discount) {

            if (!purchaseQty || !purchasePrice || discountMode === '0' || !discount) {
                return 0;
            }

            let itemAmount = purchaseQty * purchasePrice;
            if (discountMode === '1') {
                return discount * itemAmount / 100;
            }
            if (discountMode === '2') {
                return discount;
            }
        }

        function calcItemAmount(purchaseQty, purchasePrice, itemDiscount) {
            if (!purchaseQty || !purchasePrice) {
                return 0;
            }

            return purchaseQty * purchasePrice - itemDiscount;
        }


        //Events
        $('.po_purchase_qty').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $('.po_free_qty').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $('.po_purchase_price').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $('.po_discount_mode').change(function() {
            let rowId = $(this).attr('tr-id');

            // let discountMode = $("#po_discount_mode-" + rowId).val();

            let discountMode = $("#po_discount_mode-" + rowId).val();




            if (discountMode === '2') {
                $("#po_discount-" + rowId).removeAttr("max");
                console.log("NRS is selected")
                // $("#po_discount-" + rowId).val();

            } else {
                console.log("% is selected")
                $("#po_discount-" + rowId).attr({
                    "max": 100,
                });
            }
            setAllThings(rowId);
        });
        $('.po_discount').keyup(function() {
            let rowId = $(this).attr('tr-id');
            setAllThings(rowId);
        });
        $("#po_other_charges").keyup(function() {
            calcBillAmount();
        });

        //Other Scripts
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

                        let rowId = $(this).attr('tr-id');
                        let itemId = $("#po_item_name-" + rowId).attr('item-id')
                        let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                        listOfItems.splice(indxOfItem, 1);
                        console.log("Current Item:", listOfItems);



                        tr.remove();

                        console.log("Current Items:", listOfItems);
                        indexCntr = counterArray.indexOf(parseInt(this.getAttribute('tr-id')));
                        // console.log(indexCntr);
                        console.log(parseInt(this.getAttribute('tr-id')), "###########destroyed")

                        counterArray.splice(indexCntr, 1);
                        // repeaterCounter--;
                        if (counterArray.length == 1) {
                            console.log('1 item in array', counterArray);
                            console.log(counterArray[0], "counter0")
                            $('#itemDestroyer-' + counterArray[0]).addClass('d-none');
                        }
                        calcBillAmount();

                }
            })
        });





    });
</script>

<!-- new script -->
<script>


    let repeaterCounter = 1;
    let listOfItems = [];
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

    //For autocomplete item search
    @if(isset($po))
    let totalItems = {{$po->purchase_items->count()}};
    let tempItems = {!!$po->purchase_items->pluck('id')!!};
  

    counterArray = [];

    for (let i = 1; i <= totalItems; i++) {
    //    let itemId= $("#po_history_icon-"+(i+1)).attr('item-id');
   
        listOfItems.push(tempItems[i-1]);
        console.log("curr---",listOfItems)
        counterArray.push(i)
        $("#po_item_name-" + i).autocomplete({
            source: availableTags,
            minLength: 1,
            select: function(event, ui) {
    //  console.log("lkalallalallalalaala")

                let itemStock = $("#po_item_name-" + i);
                itemStock.next().attr('name', 'po_item_name_hidden[' + i + ']').val(ui.item.id);
              
                $("#po_history_icon-1").attr('item-id', ui.item.id);

                getStockItemDetails(ui.item.id, i);

                let rowId = $(this).attr('tr-id');
              

                enableFields(rowId);
            },
        });
    }
    @else
    counterArray = [1];



    $("#po_item_name-1").autocomplete({
        source: availableTags,
            minLength: 1,
            select: function(event, ui) {
                let dataCntr = $(this).attr('tr-id');

                let hidden_id=parseInt($('#po_item_name-'+dataCntr).attr('item-id'))
                    console.log(hidden_id,ui.item.id,$('#po_item_name-'+dataCntr))
                    if(ui.item.id===hidden_id){
                        console.log("Checkmate")
                        return;
                    }
                    else{
                        console.log("Inside else")
                        let rowId = $(this).attr('tr-id');
                        let itemId = $("#po_item_name-" + rowId).attr('item-id')
                        let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                        if(indxOfItem !==-1){

                            listOfItems.splice(indxOfItem, 1);
                            console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                        }
                       
                    }
                    console.log("Before::",ui.item.id)
                let present = checkIfItemExist(ui.item.id);
                if (present) {
                    Swal.fire({
                        title: 'Item Already Exits !',
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            $("#po_item_name-" + dataCntr).val('');
                            console.log("exists already")
                            return;
                        }
                    })
                } else {
                    let itemStock = $("#po_item_name-1");
                    itemStock.next().attr('name', 'po_item_name_hidden[1]').val(ui.item.id);
                    $("#po_history_icon-1").attr('item-id', ui.item.id);
                    $("#po_item_name-1").attr('item-id', ui.item.id);
                    listOfItems.push(ui.item.id)
                    console.log("Current Items:", listOfItems);
                    getStockItemDetails(ui.item.id, 1);
                    enableFields(dataCntr);
                }
            },
        });
    @endif


  

    $(document).on('keydown', '.fireRepeater', function(e) {
        if (e.keyCode != 13) return;
        repeater();
    });

    $(document).on('click', '.fireRepeaterClick', function(e) {
        repeater();
    });
    function checkIfItemExist(itemId) {
        console.log("ID Check",itemId)
            let idOfItemSelected = itemId;
            let indexOfItemInArray = listOfItems.indexOf(idOfItemSelected);

            if (indexOfItemInArray !== -1) {
                console.log("PRESENT")
                console.log("Item already selected");
                return true
            }
            console.log(" Not PRESENT")
            return false;
        }

    function getLastArrayData() {
        return counterArray[counterArray.length - 1];
    }

    function repeater() {
            let tr = $('#repeater').clone(true);
            tr.removeAttr('id');
            tr.removeAttr('class');
            tr.children(':first').children(':first').children(':first').addClass('customSelect2');
            setIdToRepeater(getLastArrayData() + 1, tr);
            $('#po-table').append(tr);

            counterArray.push(getLastArrayData() + 1);
            console.log("ROW ARRAY:", counterArray);


        $("#po_item_name-" + getLastArrayData()).autocomplete({
            source: availableTags,
            minLength: 1,
            select: function(event, ui) {
                let dataCntr = this.getAttribute('tr-id');
                    let itemStock = $("#po_item_name-" + dataCntr)
                    let hidden_id=parseInt($('#po_item_name-'+dataCntr).attr('item-id'))
                    console.log(hidden_id,ui.item.id,$('#po_item_name-'+dataCntr))
                    if(ui.item.id===hidden_id){
                        // console.log("Checkmate")
                        return;
                    }
                    else{
                        let rowId = $(this).attr('tr-id');
                        let itemId = $("#po_item_name-" + rowId).attr('item-id')
                        let indxOfItem = listOfItems.indexOf(parseInt(itemId));
                        if(indxOfItem !==-1){

                            listOfItems.splice(indxOfItem, 1);
                            // console.log("INS:",indxOfItem,"LISSST:",listOfItems)
                        }
                       
                    }
                
                    // console.log(item_id_hidden,ui.item.id,"condition:",item_id_hidden ===ui.item.id)
                   
                        let present = checkIfItemExist(ui.item.id,dataCntr);
                        if (present) {
                            Swal.fire({
                                title: 'Item Already Exits !',
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    $("#po_item_name-" + dataCntr).val('');
                                    // console.log("exists already")
                                    return;
                                }
                            })
                        } else {
                            listOfItems.push(ui.item.id)
                        console.log("LISSST:",listOfItems)

                            itemStock.next().attr('name', 'po_item_name_hidden[' + dataCntr + ']').val(ui.item.id);
                            $("#po_history_icon-" + dataCntr).attr('item-id', ui.item.id);
                            $("#po_item_name-" + dataCntr).attr('item-id', ui.item.id);
                            console.log("Ok Current Items:", listOfItems);
                            getStockItemDetails(ui.item.id, dataCntr);
                            enableFields(dataCntr);

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
            let classArr = ['po_item_name', 'po_purchase_qty', 'po_free_qty', 'po_total_qty', 'po_discount_mode', 'po_discount', 'po_tax_vat', 'po_purchase_price', 'po_sales_price', 'po_item_amount', 'destroyRepeater'];
            let trDBfields = ['items_id', 'purchase_qty', 'free_qty', 'total_qty', 'discount_mode_id', 'discount', 'tax_vat', 'purchase_price', 'sales_price', 'item_amount'];
            cloneTr.children(':last').children('.destroyRepeater').attr('id', 'itemDestroyer-' + cntr).attr('tr-id', cntr);
            cloneTr.children(':last').children('.po_history_icon').attr('id', 'po_history_icon-' + cntr).attr('tr-id', cntr);
            cloneTr.children(':first').find('input').attr('id', 'po_item_name-' + cntr).attr('tr-id', cntr).attr('name', 'items_id[' + [cntr] + ']');

            for (let i = 1; i < 11; i++) {
                let n = i + 1;
                attr = cloneTr.children(':nth-child(' + n + ')').attr('class');
                if (attr == undefined) {
                    cloneTr.children(':nth-child(' + n + ')').children('.input-group').children('.' + classArr[i]).attr('id', classArr[i] + '-' + cntr).attr('tr-id', cntr).attr('name', trDBfields[i] + '[' + cntr + ']').prop("disabled", true).prop('min', '0');
                } else {
                    cloneTr.children(':nth-child(' + n + ')').attr('id', classArr[i] + '-' + cntr).attr('tr-id', cntr);
                }
            }
        }


    $("#supplier").change(function() {
        let supplier_id = $(this).find(":selected").val();
        if (supplier_id) {
            // get contact details of requested store[make api]
            getContactDetails(supplier_id, flag = "supplier");
        }

    });
    $("#requested_store").change(function() {
        let requested_store_id = $(this).find(":selected").val();
        if (requested_store_id) {
            // get contact details of requested store[make api]
            getContactDetails(requested_store_id, flag = "store");

        }
    });


    function getContactDetails(id, flag) {

        let url = '{{ route("custom.contact-details",":id") }}'
        url = url.replace(':id', id);
        $.get(url, {
            flag: flag
        }).then(function(response) {

            // console.log("CONTACT: ", response);
            $("#phone").val(response.phone)
            $("#email").val(response.email)
        })
    }

    function getStockItemDetails(itemId, cntr) {
        let url = '{{ route("custom.po-details", ":id") }}'
        url = url.replace(':id', itemId);
        $.get(url).then(function(response) {
            // console.log("taxxx", cntr)
            $('#po_tax_vat-' + cntr).val(response.taxRate);
        })
    }

    // validation script
    $('#po_form').on('submit', function() {
        // console.log("jhsgjsdj")
        $('.po_item_amount').prop("disabled", false);
        $('.po_tax_vat').prop("disabled", false);
        $('.po_total_qty').prop("disabled", false);
        $('.po_purchase_qty').prop("disabled", false);
        $('.po_free_qty').prop("disabled", false);
        $('.po_discount_mode').prop("disabled", false);
        $('.po_discount').prop("disabled", false);
        $('.po_purchase_price').prop("disabled", false);
        $('.po_sales_price ').prop("disabled", false);
        // debugger;
        $('.po_item_name').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Field  required",
                }
            });
        });

    });

    $('#po_form').on('submit', function(event) {
        $.each(counterArray, function(index, value) {
            $('#po_item_name-' + value).rules("add", {
                required: true,
                messages: {
                    required: "Field  required",
                }
            });
            $('#po_purchase_qty-' + value).rules("add", {
                required: true,
                number: true,
                messages: {
                    required: "Field Required",
                    number: 'Field must be a number'
                }
            });
            $('#po_type').rules("add", {
                required: true,
                messages: {
                    required: "Field Required",
                }
            });
            if ($("#po_type").find(":selected").val() === '1') {
                // console.log("Regular")
                $('#supplier').rules("add", {
                    required: true,
                    messages: {
                        required: "Field Required",
                    }
                });
            };
            if ($("#po_type").find(":selected").val() === '2') {
                // console.log("STOCk transfer")
                $('#requested_store').rules("add", {
                    required: true,
                    messages: {
                        required: "Field Required",
                    }
                });
            };



        });

    });


    $('#po_form').validate({
        submitHandler: function(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((confirmResponse) => {
                if (confirmResponse.isConfirmed) {
                    // INVENTORY.inventoryLoading(true, $('#po_form'));
                    let data = $('#po_form').serialize();
                    let url = form.action;
                    axios.post(url, data).then((response) => {
                        if(response.data.status==='success'){

                            document.location = response.data.route;
                        }
                        else{
                        Swal.fire(response.data.message)
                        }
                        // console.log()
                        // console.log("edididieie",response.data.route);
                        // INVENTORY.inventoryLoading(false, $('#po_form'));
                    }, (error) => {
                        Swal.fire(response.data.message)
                        // INVENTORY.inventoryLoading(false, $('#po_form'));
                    });


                }
            });
        }
    });

    // $('#po_cancel_btn').click(function(){

    // })

    function enableFields(rowId) {
        $('#po_purchase_qty-' + rowId).prop("disabled", false);
        $('#po_free_qty-' + rowId).prop("disabled", false);
        $('#po_discount_mode-' + rowId).prop("disabled", false);
        $('#po_discount-' + rowId).prop("disabled", false);
        $('#po_purchase_price-' + rowId).prop("disabled", false);
        $('#po_sales_price-' + rowId).prop("disabled", false);
        $('#po_tax_vat-1').prop("disabled", false);

    }



    $('#po_type').change(function() {
        let val = $(this).find(":selected").val();

        // clear requested store and supplier
        $("#supplier").val('');
        if (val === '1') {
            $("#supplier").attr("disabled", false)
        }
        if (val === '2') {
            $("#supplier").attr("disabled", true)
        }
    });
    

    // end of validation script
</script>

<!-- script for modal -->
<script>
    $(document).on("click", ".po_history_icon", function() {
        let currRow = $(this).attr('tr-id');
        let item_name = $('#po_item_name-' + currRow).val();
        let item_id = $('#po_history_icon-' + currRow).attr('item-id');

        $("#purchase_order_modal").attr('item-id', item_id);
        $('#item_name_modal').html(item_name);

        let po_history_from = $('#po_history_from').val();
        let po_history_to = $('#po_history_to').val();
        // console.log("history:::", item_id, po_history_from, ?)
        getPurchaseItemHistoryDetails(item_id, po_history_from, po_history_to);
        // console.log("history clicked")

        $("#purchase_order_modal").modal('show');

    });
    $("#po_history_fetch_btn").click(function() {
        let itemId = $('#purchase_order_modal').attr("item-id");
        let po_history_from = $('#po_history_from').val();
        let po_history_to = $('#po_history_to').val();

        getPurchaseItemHistoryDetails(itemId, po_history_from, po_history_to);


    })

    function getPurchaseItemHistoryDetails(itemId, po_history_from, po_history_to) {
        let url = '{{ route("custom.poh-details", [":id",":to",":from"] ) }}'
        url = url.replace(':id', itemId);
        url = url.replace(':to', po_history_from);
        url = url.replace(':from', po_history_to);

        // console.log(url, "Test URL");

        $.get(url).then(function(response) {
            $("#modal_table_content").html(response);
        })
    }
</script>

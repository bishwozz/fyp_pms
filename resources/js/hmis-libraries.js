
let LMS = {
    _formSaveGroupKey: 83, // S
    _formNewFormKey:78,

    validate: (wrapperElement) => {
        let valid = true;
        $(wrapperElement).find('input, select, textarea,number,time').each(function () {
            /**
             * Validate if element has required attribute and no value/input given
             */
            if ($(this).attr('required') !== undefined && $(this).val() === "") {
                valid = false;
                $(this).addClass('is-invalid');
                if ($(this).next().hasClass('select2')) {
                    $(this).next().addClass('is-invalid');
                }
            }
            else {
                $(this).removeClass('is-invalid');
            }
        });

        return valid;
    },
    buildPreview: () => {
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
        let father_name = $('#father_name').val();
        let mother_name = $('#mother_name').val();
        let gender = $("#gender :selected").text();

        let street_address = $("#street_address").val();
        let address = street_address;// + local_level + "-" + ward_no + "," + district + "," + province;
        let cell_phone = $('#cell_phone').val();
        let home_phone = $('#home_phone').val();
        let contact_number = 'मोबाइल :' + cell_phone + '<br> फोन : ' + home_phone;
        let age = $('#age').val();
        let age_gender = age + "/" + gender;
        let full_name = first_name + " " + last_name;

        $('#full-name').html(full_name).show();
        $('#father-name').html(father_name).show();
        $('#mother-name').html(mother_name).show();
        $('#age-gender').html(age_gender).show();
        $('#address').html(address).show();
        $('#contact_number').html(contact_number).show();
    },

    /**
     * Print price
     */
    printPrice: (amount, currency = false) => {
        return (currency === true ? 'Rs. ' : '') + parseFloat(amount).toFixed(2);
    },

    calculateAge: () => {
        let birth_date = $('#dob-ad').val();
        let birth_year = birth_date.slice(0, 4);
        let current_year = new Date().getFullYear();
        let age = current_year - birth_year;
        $('#age').val(age);
    },

    lmsLoading: (bool,text='') => {
        let status = bool === true ? 'show' : 'hide';
        $.LoadingOverlay(status, { text: text });
    },

    printPreview: (item) => {
        let href = item.getAttribute('url');
        printJS({ printable: href, type: 'pdf', showModal: true });
    },

    setIsTaxableField: () => {
        if (!$('#is_taxable').is(':checked')) {
            $('#tax_vat').prop("readonly", true);
            $('#tax_vat').val(0);
        }else{
            $('#tax_vat').prop("readonly", false);
            $('#tax_vat').val('');
        };
    },
}

window.LMS = LMS;
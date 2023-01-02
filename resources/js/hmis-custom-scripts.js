$(function () {
    $('input, select, textarea').on('keyup change focusout', function (e) {
        if ($(this).val() !== "") {
            $(this).removeClass('is-invalid');

            /**
             * Handle select 2 validation class removal on change
             */
            if ($(this).next().hasClass('select2')) {
                $(this).next().removeClass('is-invalid');
            }
        }
        else {
            if ($(this).attr('required') !== undefined) {
                $(this).addClass('is-invalid');

                /**
                 * Handle select 2 validation class removal on change
                 */
                if ($(this).next().hasClass('select2')) {
                    $(this).next().addClass('is-invalid');
                }
            }
        }
    });

    $('body').on('change', 'input[type="checkbox"][class^=select-all-]', function (event) {
        let className = this.className.substring(11);
        let callBackFunc = eval($(this).data('callback'));

        $('input.hmis-chk-' + className).prop('checked', $(this).is(":checked"));

        if (typeof callBackFunc === 'function') {
            setTimeout(function () {
                callBackFunc();
            }, 500);
        }
    });

    $('body').on('focusout', 'input.age', function (event) {
        let age = parseInt($(this).val());

        if (age > 0) {
            let totalDays = parseInt(age) * 365;
            let today = new Date();
            today.setDate(today.getDate() - totalDays);
            let dateAd = today.toISOString().split('T')[0];
            let dateBs = AD2BS(dateAd)

            $('#dob-ad').val(dateAd);
            $('#dob-bs').val(dateBs);
        }
    });

    $('.nav-tabs a').on('shown.bs.tab', function (event) {
        $(event.target).removeClass('bg-primary text-white');
        $(event.relatedTarget).addClass('bg-primary text-white');
    });

});

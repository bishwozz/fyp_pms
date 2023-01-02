$(document).ready(function () {
    //validate fields on next button
    $('#gender, input[name="first_name"],input[name="last_name"], input[name="date_of_birth"], input[name="date_of_birth_bs"]').on('keyup change', function (e) {
        if ($(this).val().length > 0) {
            $(this).removeClass('is-invalid');
            if ($(this).next().hasClass('select2')) {
                $(this).next().removeClass('is-invalid');
            }
        }
    });

    // $('#btn-next1').click(function () {
    //     let next_step = LMS.validate('#demographic_section');

    //     if (next_step === true) {
    //         $('#contact').removeClass('disabled');
    //         $('#contact').trigger('click');
    //     }
    // });

    // $('#btn-next2').click(function () {
    //     let next_step = LMS.validate('#contact_section');;

    //     if (next_step === true) {
    //         $('#preview_confirm').removeClass('disabled');
    //         $('#preview_confirm').trigger('click');
    //         LMS.buildPreview();
    //     }
    // });

    // $('#btn-prev1').click(function () {
    //     $('#demographic').trigger('click');
    // });
    // $('#btn-prev2').click(function () {
    //     $('#contact').trigger('click');
    // });

    // //call buildPreiew function to fetch data 
    // $('#preview_confirm').click(function () {
    //     LMS.buildPreview();
    // });

    $('#confirm_cancel_btn').click(function () {
        swal({
            title: "Are you sure to cancel the registration?",
            text: '',
            buttons: {
                no: {
                    text: "No",
                    value: 'no',
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                },
                yes: {
                    text: " Yes",
                    value: 'yes',
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                }
            },
        }).then((value) => {
            if (value === 'yes') {
                var url = '/admin/patient';
                if(window.location.toString().includes('emergency-patient')){
                    window.location.href = '/admin/emergency-patient';
                }else{
                    window.location.href = url;
                }

            }
            if (value === 'no') {
            }
        });
    });

    //search with select
    $('.searchselect').select2();

    $('#dob-bs').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            $('#dob-ad').val(BS2AD($('#dob-bs').val()));
            LMS.calculateAge();
        }
    });

    // $('#dob-bs').focusout(function(e) {
    //     hideCalendarBox();
    // })

    $('#dob-bs').change(function () {
        $('#dob-ad').val(BS2AD($('#dob-bs').val()));
        LMS.calculateAge();
    });

    $('#dob-ad').change(function () {
        $('#dob-bs').val(AD2BS($('#dob-ad').val()));
        LMS.calculateAge();
    });


});

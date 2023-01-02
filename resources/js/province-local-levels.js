$(document).ready(function () {
    var district;
    var local_level;
    
    if ($('#patient_id').length && $('#patient_id').val() !== "" && $('#province').val() !== "") {
        var patient_id = $('#patient_id').val();
        
        $.ajax({
            url: '/getdistrictlocallevel',
            type: "GET",
            data: { patientId: patient_id },
            success: function (data) {
                district = data.district.id;
                local_level = data.local_level.id;
            }
        });
    }

    $.urlParam = function (name) {
        try {
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results[1] || 0;
        } catch {
            return null;
        }
    }
    $('#district').append('<option value="">--Select District--</option>');
    $('#local_level').append('<option value="">--Select Local Level--</option>');

    $('#province').on('change', function () {
        var stateID = $(this).val();

        $('#district').append('<option value="">-- Loading...  --</option>');

        if (stateID) {
            $.ajax({
                url: '/district/' + stateID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (data) {

                    if (data) {
                        $('#district').empty();
                        $('#local_level').empty();
                        $('#district').focus;
                        $('#district').append('<option value="">-- select district  --</option>');
                        var selected_id = district;
                        $.each(data, function (key, value) {
                            var selected = "";
                            if (selected_id == value.id) {
                                // console.log('ok');
                                selected = "SELECTED";
                            }


                            $('select[name="district_id"]').append('<option class="form-control" value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                            if (selected == "") {
                                $("#district").trigger("change");
                                $("#local_level").trigger("change");
                            }
                        });
                        $('.searchselect_1').select2();
                    } else {
                        $('#district').empty();
                        $('#local_level').empty();
                    }
                }
            });
        } else {
            $('#district').empty();
            $('#local_level').empty();
        }
    });

    $('#district').on('change', function () {
        var districtID = $(this).val();
        if (districtID) {
            $('#local_level').append('<option value="">-- Loading...  --</option>');
            $.ajax({
                url: '/local_level/' + districtID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },

                dataType: "json",
                success: function (data) {
                    if (data) {
                        $('#local_level').empty();
                        $('#local_level').focus;
                        $('#local_level').append('<option value="">-- select local level  --</option>');
                        var selected_id = local_level;
                        $.each(data, function (key, value) {
                            var selected = "";
                            if (selected_id == value.id) {
                                // console.log('ok');
                                selected = "SELECTED";
                            }
                            $('select[name="local_level_id"]').append('<option class="form-control" value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                            if (selected == "") {
                                $("#local_level").trigger("change");
                            }
                        });
                        // $('.searchselect_l').select2();
                    } else {
                        $('#local_level').empty();
                    }
                }
            });
        } else {
            $('#local_level').empty();
        }
    });
    $("#province").trigger("change");
});



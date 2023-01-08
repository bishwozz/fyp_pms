try {
    require('./bootstrap');
    require('./hmis-libraries');
    require('./province-local-levels');
    require('./hmis-registration');
    require('./hmis-custom-scripts');

    Echo.channel('stockMinimumAlert').notification((e) => {
        $.ajax({
            url: 'stock-entries/notifications',
            method: "GET",
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function (response) {
                debugger;
                if (response.status == 'success') {
                    if(response.stockNotificationCount != 0){
                        $('#stockalertminimum').html(response.stockNotificationCount);
                    }
                }
        }});
    });
} catch (e) {}

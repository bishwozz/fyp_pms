<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LMS</title>
    @yield('before_styles')
    @stack('before_styles')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/patient_end/card.css')}}">
    <link rel="stylesheet" href="{{asset('css/patient_end/menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/patient_end/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/patient_end/sidecard.css')}}">
    <link rel="stylesheet" href="{{asset('css/patient_end/sidebar.css')}}">

    @yield('after_styles')
    @stack('after_styles')
</head>
<body>

    @yield('content')

    @yield('before_scripts')
    @stack('before_scripts')

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // $('.date_time').innerHTML = new Date().toLocaleString();
        $('.date_time').each(function (index, item) {
            $(item)[0].innerHTML = new Date().toLocaleString();
        })

    </script>
    <script>
        const wrapper = $('#wrapper');

        $('#sidebar-toggle').click( e => {
            e.preventDefault();
            wrapper.toggleClass('toggled');
            const window_width = $(window).width();
            $('#logo').toggleClass('hidden');
            if(window_width<=767){
                $('.sidetoggle').hide();
                $('.toptoggle').show();
            }
        });
        $( document ).ready(function() {
            $('#header-sidebar-toggle').click( e => {
                wrapper.toggleClass('toggled');
                $('#logo').show();
                $('.toptoggle').hide();
                $('.sidetoggle').show();
            });
        });
    </script>

    @yield('after_scripts')
    @stack('after_scripts')
</body>
</html>
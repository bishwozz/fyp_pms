<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <link rel="stylesheet" href="{{asset('css/patient_end/dashboard-style.css')}}">
        <link rel="stylesheet" href="{{asset('css/jquery.fancybox.min.css')}}">
        <title>Dashboard</title>
        @yield('before_styles')
        @stack('before_styles')
        <style>

        </style>
        @yield('after_styles')
        @stack('after_styles')
    </head>

    <body>
        @yield('content')
        
        @yield('before_scripts')
        @stack('before_scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/fancybox.v3.5.7.min.js')}}" type="text/javascript"></script>
        <script>

        </script>

        @yield('after_scripts')
        @stack('after_scripts')
    </body>

</html>
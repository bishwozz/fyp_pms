<!-- =================================================== -->
<!-- ========== Top menu items (ordered left) ========== -->
<!-- =================================================== -->
<ul class="nav navbar-nav d-md-down-none">

    @if (backpack_auth()->check())
        <!-- Topbar. Contains the left part -->
        @include(backpack_view('inc.topbar_left_content'))
    @endif

</ul>
<!-- ========== End of top menu left items ========== -->



<!-- ========================================================= -->
<!-- ========= Top menu right items (ordered right) ========== -->
<!-- ========================================================= -->
<ul class="nav navbar-nav ml-auto @if(config('backpack.base.html_direction') == 'rtl') mr-0 @endif">
    @if (backpack_auth()->guest())
        <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>
        </li>
        @if (config('backpack.base.registration_open'))
            <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
        @endif
    @else
        <!-- Topbar. Contains the right part -->
        @include(backpack_view('inc.topbar_right_content'))
        <li class="nav-item dropdown pr-4" id="stockentries-li">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false" onclick="showStockNotification();" id="stocMinimumAlertButton" title="Stock Minimum Alerts">
                <span class="badge badge-danger badge-counter" style="position: absolute;top:40%;left:30%;z-index:9999;" id="stockalertminimum">
                </span>
                <i class="la la-exclamation-triangle text-white-50 la-2x" style="position: relative;"></i>
            </a>
            <div class="dropdown-menu {{ config('backpack.base.html_direction') == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right' }} mr-4 pb-1 pt-1" id="unReadStockNotificationList">
            </div>
        </li>
        @include(backpack_view('inc.menu_user_dropdown'))
    @endif
</ul>
<!-- ========== End of top menu right items ========== -->

<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        loadInitialStockNotification();
    });

    function loadInitialStockNotification() {
        $.ajax({
            url: '{{ route('stock.notification.check') }}',
            method: "GET",
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#stockalertminimum').html(response.countUnreadNotifications);
                let counStockAlert = $('#stockalertminimum').html().trim().replace(',', '');
                if (response.countUnreadNotifications == 0) {
                    $('#unReadStockNotificationList').hide();
                }
            }
        });
    }

    function showStockNotification() {
        $.ajax({
            url: '{{ route('stock.notification.show') }}',
            method: "GET",
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#unReadStockNotificationList').html(response);
            }
        });
    }
</script>

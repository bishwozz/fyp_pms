@if (config('backpack.base.scripts') && count(config('backpack.base.scripts')))
    @foreach (config('backpack.base.scripts') as $path)
    <script type="text/javascript" src="{{ asset($path).'?v='.config('backpack.base.cachebusting_string') }}"></script>
    @endforeach
@endif

@if (config('backpack.base.mix_scripts') && count(config('backpack.base.mix_scripts')))
    @foreach (config('backpack.base.mix_scripts') as $path => $manifest)
    <script type="text/javascript" src="{{ mix($path, $manifest) }}"></script>
    @endforeach
@endif

@include('backpack::inc.alerts')

{{-- Send in $this->data['load_scripts'] as array to load in view --}}
@if(!empty($load_scripts))
    @foreach ($load_scripts as $script)
        <script type="text/javascript" src={{ $script }}></script>
    @endforeach
@endif

{{-- Specific style in $this->data['script_js'] --}}
@if(!empty($script_js))
    <script type="text/javascript">
        {{!! html_entity_decode($script_js) !!}}
    </script>
@endif



<!-- JavaScript Libraries -->
    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
    {{-- <script src="{{asset('homepage/js/jquery.js')}}"></script> --}}

    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{asset('js/bootstrap.min.js')}}"></script>

    <script src="{{asset('js/easing.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>

    <!-- Contact Javascript File -->
    <script src="{{asset('js/jqBootstrapValidation.min.js')}}"></script>
    <script src="{{asset('js/contact.js')}}"></script>
    <!-- Template Javascript -->
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/fancybox.v3.5.7.min.js')}}"></script>
    <script src="{{asset('homepage/js/chart.min.js')}}"></script>

    <script src="{{asset('homepage/js/jquery.parallax.js')}}"></script>
    <script src="{{asset('homepage/js/moment.min.js')}}"></script>
    
    <script src="{{asset('homepage/js/currencyFormatter.min.js')}}"></script>


<!-- page script -->
<script type="text/javascript">
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function() { Pace.restart(); });

    // polyfill for `startsWith` from https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/startsWith
    if (!String.prototype.startsWith) {
    Object.defineProperty(String.prototype, 'startsWith', {
        value: function(search, rawPos) {
            var pos = rawPos > 0 ? rawPos|0 : 0;
            return this.substring(pos, pos + search.length) === search;
        }
    });
    }



    // polyfill for entries and keys from https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/entries#polyfill
    if (!Object.keys) Object.keys = function(o) {
        if (o !== Object(o))
            throw new TypeError('Object.keys called on a non-object');
        var k=[],p;
        for (p in o) if (Object.prototype.hasOwnProperty.call(o,p)) k.push(p);
        return k;
    }

    if (!Object.entries) {
        Object.entries = function( obj ){
            var ownProps = Object.keys( obj ),
                i = ownProps.length,
                resArray = new Array(i); // preallocate the Array
            while (i--)
            resArray[i] = [ownProps[i], obj[ownProps[i]]];
            return resArray;
        };
    }

    // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    {{-- Enable deep link to tab --}}
    var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
    location.hash && activeTab && activeTab.tab('show');
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        location.hash = e.target.hash.replace("#tab_", "#");
    });
</script>

@if(config('app.debug'))
    @include('crud::inc.ajax_error_frame')
@endif
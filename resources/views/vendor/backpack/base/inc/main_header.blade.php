@php
  $logo_path = App\Models\CoreMaster\AppSetting::where('client_id',backpack_user()->client_id)->first();
  // dd($logo_path->client_logo);
@endphp
<header class="{{ config('backpack.base.header_class') }}">
  <!-- Logo -->
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto ml-3" type="button" data-toggle="sidebar-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div style="width: 210px;display:flex;justify-content:center">
    @if ($logo_path)
      <img src="{{ '/storage/uploads/'.$logo_path->client_logo }}" width="90%" height="60px" alt="logo">
    @else
      <a class="navbar-brand" href="{{ url(config('backpack.base.home_link')) }}" title="{{ config('backpack.base.project_name') }}">
       {!! config('backpack.base.project_logo') !!} 
    @endif
    </a>
  </div>
  
  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="date-time">
    <span class="text-white mr-3 p-2" href="javascript:;"><i class="la la-calendar"> </i> {{current_nepali_date_formatted()}} </span></br>
    <i class="la la-clock-o pl-2 pr-2"> </i><span class="text-white" id="txt"> Loading...</span>
  </div>
  @include(backpack_view('inc.menu'))
  <style>
      @font-face {
      font-family: "Kalimati";
      src: url("/fonts/Kalimati.ttf") format("truetype");
  }
    .class-lc{
      font-family: Kalimati;
    }
    .active_lang {
            background: rgb(154, 154, 214);
            border-radius: 5px;
            margin: 2px;
            padding:5px 7px 10px 7px;
        }
        .date-time {
            color: white !important;
            font-weight: 600;
            font-family: inherit;
            text-transform: uppercase;
            width: 400px;
            padding: 0 2.25rem;
            position: sticky;
            top: 0;
            left: 100%;
        }
  </style>

<script>
  function startTime() {
      var today = new Date();
      var h = today.getHours();
      var ampm = h >= 12 ? 'PM' : 'AM';
      h = h % 12;
      h = h ? h : 12;
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m);
      s = checkTime(s);
      document.getElementById('txt').innerHTML =
          h + ":" + m + ":" + s + " " + ampm;
      var t = setTimeout(startTime, 500);
  }

  function checkTime(i) {
      if (i < 10) {
          i = "0" + i
      }; // add zero in front of numbers <script 10
      return i;
  }

  window.onload = startTime();
</script>
</header>

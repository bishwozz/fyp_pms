@extends('patient_end.base')
@section('content')
    <div id="wrapper">
        
        <aside class="sidebar-wrapper" id="sidebar-wrapper">
            <div class="sidetoggle" id="navbar-wrapper">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                        <a href="#" id="sidebar-toggle"><i class="fa fa-bars"></i></a>
                    </div>
                </nav>
            </div>
            <div class="sidebar-brand">
                <img id="logo" src="{{asset('images/patient_end/lab management system.png')}}" alt="logo">
            </div>
            <ul class="sidebar-nav mt-5">
                <li class="active">
                    <a href="report.html"><i class="fa fa-address-card"></i>Report</a>
                </li>
            </ul>
        </aside>

        <div class="toptoggle">
                <a href="#" class="navbar-brand" id="header-sidebar-toggle"><i class="fa fa-bars"></i></a>
        </div>

        <section id="content-wrapper">
            <div class="row">
                <div class="col-lg-9">
                    <div class="banner">
                        <div class="top-img">
                            <img src="{{asset('images/patient_end/hospital_management.png')}}" class=" img-fluid img-thumbnail" alt="">
                        </div>
                        <!-- line -->
                        <div class="row mt-2 bar-line">
                            {{-- <div class="col-sm-3 col-xs-6 text-center " id="searchInput">
                                <form class="example" action="/action_page.php">
                                    <input type="text" placeholder="Search.." onkeyup="loadReports({{$patient->id}})" id="search"
                                        style=" border-radius:12px; border-color:white ;  box-shadow: 0 10px 10px rgba(105, 160, 224, 0.2);">
                                </form>
                            </div> --}}
                            <div class="col-sm-6 col-xs-12 offset-xs-6 text-center" id="pagination">
                                <p><i class="fa-sharp fa-solid fa-backward"></i> ALL BILLS <i
                                        class="fa-solid fa-forward"></i>
                                </p>
                            </div>

                            <!-- date -->
                            {{-- <div class="col-sm-3 col-xs-6 text-center" id="dateInput">
                                <div class="">
                                    <input class="form-group" type="date" onchange="loadReports({{$patient->id}})" id="date">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="content-area" id="content_area">
                    </div>
                </div>
                <div class="col-lg-3" style="border-left: solid  3px rgb(214, 208, 208);">
                    <div class="personal-info-head text-center">
                        <img id="photo" class=" text-bg" src="{{ asset('storage').'/'.$patient->photo_name}}" alt="">
                        <p class="name text-uppercase text-center mt-4 mb-0  ">{{$patient->name}}</p>
                        @if($patient->email)
                            <p class="email text-sm-center">{{$patient->email}}</p>
                        @endif
                        <p class="text-center" style="font-size:14px ;">PT.ID. {{$patient->patient_no}}</p>
                    </div>
                    <div class="contact-info">
                        <div class="row">
                            <div class="col-md-2 horizontally-center">
                                <i class="fa-solid fa-phone text-warning bg-white fa-1x shadow p-2 rounded-circle"></i>
                            </div>
                            <div class="col-md-10 ">
                                <p class="head text-muted">contact no.</p>
                                <p class="info">{{$patient->cell_phone}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 horizontally-center">
                                <i
                                    class="fa-solid fa-map-location text-primary bg-white fa-1x shadow p-2 rounded-circle mr-2"></i>
                            </div>
                            <div class="col-md-10">
                                <div class="ml-2">
                                    <p class="head text-muted ">Address</p>
                                    <p class="info">{{$patient->street_address}}</p>
                                </div>
                            </div>
                        </div>
                        <a href="" type="btn" id="print-btn"
                            class="mt-5 px-3 btn text-success bg-white border rounded-pill shadow ">
                            <i class="fa-solid fa-pen"></i>&nbsp;&nbsp;Edit Details
                        </a>
                        <a href="{{route('patient-logout')}}" type="btn" id="logout-btn"
                            class="px-3 btn text-success bg-white border rounded-pill shadow ">
                            <i class="fa-solid fa-sign-out"></i>&nbsp;&nbsp;Logout
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('after_scripts')
<script>
    function loadReports(patient_id){
        // const search = $('#search').val();
        // const date = $('#date').val();
        $.ajax({
            url:'/patient/get-report-list',
            type:'get',
            data:{
                'patient_id':patient_id,
                // 'search':search,
                // 'date':date
            },
            success:response=>{
                $('#content_area').html(response);
            }
        });
    }
    $(document).ready(()=>{
        const patient_id = <?php echo $patient->id ?>;
        loadReports(patient_id);
    });
</script>
@endpush
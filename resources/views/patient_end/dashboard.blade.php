@extends('patient_end.base')
@section('content')
    <div class="container project pt-4">
        <div class="header">
            <div class="main-nav">
                <div class="topic d-flex  justify-content-between  ">
                    <img src="{{asset('images/patient_end/assets-01.png')}}" alt="logo">
                    <div class="snl">
                        {{-- <input  style="font-family:Arial, FontAwesome" type="text" placeholder=" &#Xf002; Search"
                            name="search"> --}}
                        <a href="{{route('patient-logout')}}" class="btn btn-danger text-white">Logout <i class="fa-solid fa-sm fa-right-from-bracket mr"></i></a>
                    </div>
                </div>
                <div class="nav1 mt-3">
                    <hr>
                    <div class=" nav d-flex ">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item font-weight-bold">Dashboard</li>
                              <li class="breadcrumb-item font-weight-bold"><a href="#">Reports</a></li>

                            </ol>
                          </nav>
                    </div>
                    <div class="hr">
                        <hr>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-12 ">
                    <div class="card   user text-center ">
                        <img class="card-img-top mt-2" src="{{ asset('storage').'/'.$patient->photo_name}}" alt="cap">
                        <p class="card-title mt-2">{{$patient->name}}</p>
                        <p class="patient-id">PATIENT ID : {{$patient->patient_no}}</p>


                    </div>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div class="card details text-center">
                        <div class="row details p-2">
                            <div class="col-sm-12 col-xs-12 t-one">
                                <table class="table table-borderless">
                                    <tr >
                                        <th>Gender</th>
                                        <th>Date of Birth</th>
                                        <th>Phone</th>
                                    </tr>
                                    <tr>
                                        <td>{{$patient->gender->name}}</td>
                                        <td>{{$patient->date_of_birth}}</td>
                                        <td>{{$patient->cell_phone}}</td>
                                    </tr>
                                </table>
                                <hr>
                                <table class="table  table-borderless">
                                    <tr>
                                        <th>Address</th>
                                        <th>Date of Registration</th>

                                    </tr>
                                    <tr>
                                        <td>{{$patient->street_address}}</td>
                                        <td>{{date("Y-m-d",strtotime($patient->created_at))}}</td>

                                    </tr>
                                </table>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" card  mt-2">
            <div class="container-fluid">

            <div style="height: 65vh;" class=" card-body">
                <div class="main row bg-white mx-0">
                    <div class="col-lg-6 go-to">
                        <div class="row">
                            <div class="col-sm-6">
                                <a class="btn tab-btn font-weight-bold" data-toggle="tab" href="">Laboratory Reports</a>
                            </div>
                            {{-- <div class="col-sm-6">
                                <a class="btn" data-toggle="tab" href="#">Bill Reports</a>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="billing mt-2 ">
                    <div class="container-fluid bills">
                        <div class="title row align-items-center">
                            <p class="col">Laboratory Reports</p>
                            <button type="button" class=" mt-2 mx-2 col-2 btn  float-end" ><i
                                    class="fa-sharp fa-solid fa-chevron-up"></i> Earliest First</button>
                        </div>

                        <hr class="mt-2">
                        <div class="content-area" id="content_area">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
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
@extends(backpack_view('blank'))

@section('content')
@php
$count = 0;

  $notifications = DB::table('notifications')->get();
  foreach($notifications as $notification){
    $get_app_id = json_decode($notification->data);
    if($get_app_id->appointment_id){
      $id = $get_app_id->appointment_id;
      $notfy = App\Models\PatientAppointment::find($id);
      if($notfy){
        $status = $notfy->appointment_status;
        if($status == 0){
          $count+=1;
        }
      }
    }
  }
@endphp
<link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard/dashboard.css') }}">
    <div class="container">
            <div class="card main-content">
              <div class="header my-4">
                <div class="container-fluid">
                  <h2 class="mb-3 dash">Dashboard</h2>
                  @if(backpack_user()->hasAnyRole('superadmin|clientadmin|admin|lab_admin|reception'))
                    <div class="header-body">
                      <div class="row">
                        <div class="col-xl-3 col-lg-6">
                          <a href="{{backpack_url('patient')}}">
                            <div class="card card-stats mb-4 mb-xl-0">
                              <div class="card-body">
                                <div class="row">
                                  <div class="col">
                                    <span class="card-title text-uppercase text-dark mb-0">Registration</span>
                                  </div>
                                  <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                      <i class="fas fa-chart-bar"></i>
                                    </div>
                                  </div>
                                </div>
                                <p class="mt-3 mb-0 text-dark text-sm">
                                  <span class="text-danger mr-2"><i class="fa fa-arrow-right"></i></span>
                                  <span class="text-nowrap"><a href="{{ backpack_url('patient') }}">Visit</a></span>
                                </p>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                          <a href="{{backpack_url('emergency-patient')}}">
                            <div class="card card-stats mb-4 mb-xl-0">
                              <div class="card-body">
                                <div class="row">
                                  <div class="col">
                                    <h5 class="card-title text-uppercase text-dark mb-0">Emergency Registration</h5>
                                  </div>
                                  <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                      <i class="fas fa-chart-pie"></i>
                                    </div>
                                  </div>
                                </div>
                                <p class="mt-3 mb-0 text-dark text-sm">
                                  <span class="text-warning mr-2"><i class="fas fa-arrow-right"></i></span>
                                  <span class="text-nowrap"><a href="{{ backpack_url('emergency-patient') }}">Visit</a></span>
                                </p>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                          <a href="{{backpack_url('billing/patient-billing')}}">
                            <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                              <div class="row">
                                <div class="col">
                                  <h5 class="card-title text-uppercase text-dark mb-0">Lab Billing</h5>
                                </div>
                                <div class="col-auto">
                                  <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                  </div>
                                </div>
                              </div>
                              <p class="mt-3 mb-0 text-dark text-sm">
                                <span class="text-primary mr-2"><i class="fas fa-arrow-right"></i></span>
                                <span class="text-nowrap"><a href="{{ backpack_url('billing/patient-billing') }}">Visit</a></span>
                              </p>
                            </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                          <a href="{{backpack_url('reports')}}">
                            <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                              <div class="row">
                                <div class="col">
                                  <h5 class="card-title text-uppercase text-dark mb-0">Reports</h5>
                                </div>
                                <div class="col-auto">
                                  <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                    <i class="fas fa-id-card"></i>
                                  </div>
                                </div>
                              </div>
                              <p class="mt-3 mb-0 text-dark text-sm">
                                <span class="text-success mr-2"><i class="fas fa-arrow-right"></i></span>
                                <span class="text-nowrap"><a href="{{ backpack_url('reports') }}">Visit</a></span>
                              </p>
                            </div>
                            </div>
                          </a>
                        </div>
                        <div class="col-xl-3 col-lg-6 mt-4">
                          <a href="{{backpack_url('patient-appointment')}}">
                            <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                              <div class="row">
                                <div class="col">
                                  <h5 class="card-title text-uppercase text-dark mb-0">Appointment</h5>
                                </div>
                                <div class="col-auto">
                                  <div class="rounded-circle" style="box-shadow: 2px 4px 5px 2px rgb(0 0 0 / 50%);">
                                    @include('notification.notification')
                                    {{-- <i class="fas fa-users"></i> --}}
                                  </div>
                                </div>
                              </div>
                              <p class="mt-3 mb-0 text-dark text-sm">
                                <span class="text-warning mr-2"><i class="fas fa-arrow-right"></i></span>
                                <span class="text-nowrap"><a href="{{ backpack_url('patient-appointment') }}">Visit</a></span>
                              </p>
                            </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
    </div>
@endsection
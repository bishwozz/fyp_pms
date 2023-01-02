<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laboratory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('/css/patient_end/patient-login-style.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />       
  </head>
  <body>
      <div class="container-fluid" style="height: 100vh">
        <div
          style="height:100vh"
          class="row d-flex align-items-center justify-content-center"
        >
          <div  class="col-md-7 col-sm-12  content p-4 ">
            <div class="row">
              <span>
              <a href="https://bidhlab.com.np" target="_blank" class="arrow-return "><i class="fa-solid fa-arrow-left"></i>  Bidh Lab Website </a>
              </span>
            </div>
            <div class="row  d-flex  justify-content-center">
                          
              <div class=" col-lg-6 col-md-8 col-sm-8 content-middle">
                <div class="bg-image d-flex justify-content-center">
                  <img src="{{asset('images/patient_end/assets-05.png')}}" alt="" width="300" />
                </div>
                <div class="row box-1 login-box pt-3">
                  <h4 class="px-1 d-flex align-items-center justify-content-center">PATIENT LOGIN</h4>
                  <div class="box-2 px-4 pt-3">
                    <form  class="" role="form" method="POST" action="{{ route('patient-login') }}">
                      {!! csrf_field() !!}
                      <div class="form-group b-0">
                        <label for="{{$username}}">Username</label>
                        <input type="text" class="form-control" id="{{$username}}" name="{{$username}}" aria-describedby="emailHelp" placeholder="username">
                        <small id="emailHelp" class="form-text text-muted"></small>
                      </div>
                      <div class="form-group pt-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="********">
                      </div>
                      {{-- <div class="form-check pt-4 ">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label " for="exampleCheck1">Remember Password</label>
                      </div> --}}
                      <div class="d-flex justify-content-center mt-5 pb-4">
                        <button type="submit" class="btn rounded-pill">Log In</button>
                      </div>                    
                    </form>
                  </div>
                </div>
                <div class="governance py-2 d-flex justify-content-center pt-3">
                <a target="_blank" href="https://gasnepal.com.np">  &copy; Governance Automation Pvt. Ltd.</a>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
  </body>
</html>


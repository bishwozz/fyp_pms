<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('/css/patient-style.css')}}">

  <title>Laboratory Management System</title>
</head>
<body>
  <div class="container">
    <div class="row content pt-5 align-self-center">
      <div class="col"></div>
      <div class="col-md-8 ">
        <div class="row">
          <div class="col-md-5  right    ">
            <div class="row right-content text-center ">
              <h3>Hello, there !!!</h3>
              <p class="pt-4">Welcome to the Log in web portal of Laboratory Management System </p>
            </div>
          </div>
          <div class="col-md-7  order-md-2  left   py-5 ">
            <div class="row ">
              <div class="col "></div>
              <div class="col-10">
                <div class="row">
                  <img src="{{asset('/images/patient-login-02.png')}}" alt="" width="250">
                  
                  <div class="pt-5 "><h5 class="color">
                    Please Sign In To Continue.
                  </h5>
                  </div>
                </div>
                <div class="row">
                  <form class="" role="form" method="POST" action="{{ route('patient-login') }}">
                    {!! csrf_field() !!}

                    <div class="form-group">
                      <label for="{{$username}}"></label>
                      <input type="text" class="form-control" name="{{$username}}"  placeholder="User name">
                    </div>
                    <div class="form-group">
                      <label for="password"></label>
                      <input type="password" class="form-control" name="password" placeholder="Password">
                    </div> 
                    <div class="text-center " >
                    <button type="submit" class="btn btn-primary mt-4  ">Sign In</button>
                    </div>                
                  </form>
                </div>
                <div class="row mt-5">
                  <div class="col-sm-12">
                    <p>Governance Automation Pvt.Ltd. All rights reserved.</p>
                  </div>
                </div>
                  
                
              </div>
              <div class="col "></div>
            </div>
          </div>
          
        </div>
      </div>
      <div class="col "></div>
    </div>

  </div>

  
</body>
</html>
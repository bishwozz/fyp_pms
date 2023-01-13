@extends(backpack_view('layouts.plain'))

@section('after_styles')
<link rel="stylesheet" type="text/css" href="{{asset('/css/login.css')}}" />
@endsection
@section('content')

<div class="row box-form">
	<div class="col-md-12 col-lg-6 left d-flex justify-content-center align-items-center">
        <div class="py-5">
            <div class="row d-flex justify-content-center align-items-center">

                <img src="{{asset('images/logo_text.png')}}" alt="" width="50%">
            </div>
            <div class="row left-img d-flex justify-content-center align-items-center">

                <img src="{{asset('images/microscope1.png')}}" alt="" width="80%">
            </div>
            <div class="row d-flex justify-content-center align-items-center">

                <span>Pharmacy Pvt. Ltd.</span>
            </div>
        </div>
        
	</div>
	
	
	<div class="col-md-12 col-lg-6 right">
        <div class="wrapper d-flex align-items-center">
            <div style="width: 100%;">
                <div style="background: #e8eef7;border-radius:25px;width:100%;">
                    <div style="padding: 20px 20px 10px 20px;">
                        <span style="font-weight:bold;color:#3e3b75;border-bottom:5px solid #867ee6;padding-bottom:10px;font-size:20px;">Login</span>
                    </div>
                    <div style="background:#fff;border-radius:20px;padding:20px;">
                        <div class="inputs">
                            <form class="" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                                {!! csrf_field() !!}
                
                                <div class="form-group">
                                    <label class="control-label" for="{{ $username }}">{{ config('backpack.base.authentication_column_name') }}</label>
                
                                    <div>
                                        <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}" id="{{ $username }}" placeholder="Enter Email">
                
                                        @if ($errors->has($username))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first($username) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                
                                <div class="form-group">
                                    <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>
                                    <div>
                                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Enter Password">
                
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
                                    <div class="text-center mb-3"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
                                @endif
                
                                <div class="form-group pb-5">
                                    <div class="text-center">
                                        <button type="submit" class="btn">
                                            {{ trans('backpack::base.login') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
    
                    </div>
                    
                </div>
                <div class="col-md-12 d-flex justify-content-between pt-3" style="color: #867ee6;">
                    <div class="p-2">
                        <span><i class="fa fa-mobile" aria-hidden="true"></i> 00000000</span> 
                    </div>
                    <div class="p-2">
                        <span><i class="fa fa-envelope" aria-hidden="true"></i> info@pharmacy.com</span> 
                    </div>
                </div>
            </div>
            
            
            
        </div>
        
	</div>
	
</div>


<!-- partial -->
@endsection
  

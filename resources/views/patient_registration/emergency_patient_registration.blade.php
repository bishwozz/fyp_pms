@extends(backpack_view('blank'))
@section('header')
<div class="heading" style="margin: 1em;margin-left: 30px;">
    <h4>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small><a href="{{ backpack_url('emergency-patient') }}" class="hidden-print back-btn"><i class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a></small>
    </h4>
</div>
@endsection      

@section('content')
    <div class="card">
        @if(isset($patient->id) )
        <form role="form" action="{{ url($crud->route.'/'.$entry->getKey()) }}" method="POST" name="formname" id="registration_form">
            {!! method_field('PUT') !!}
        @else
        <form role="form" action="{{ url($crud->route) }}" method="POST" name="formname" id="registration_form" enctype="multipart/form-data">
        @endif
            {!! csrf_field() !!}
            <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id ?? ''}}">
            <input type="hidden" name="has_insurance" value="0">
            <input type="hidden" name="patient_no" value="{{ isset($patient->id) ? $patient->patient_no : '' }}">
            <input type="hidden" name="redirect" value="{{ request()->get('redirect') }}">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary p-1" style="padding: 1em !important;"><i class="fa fa-info"></i> Basic Information</div>
                        <div class="card-body p-2">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="image-previwe">
                                            @if(isset($patient))
                                                @if($patient->photo_name)
                                                    <img data-check=1 class="profile_image" src="{{ '/storage/'.$patient->photo_name }}" alt="{{ $patient->name ?? '' }}" id="profile_image">
                                                @else
                                                    <img data-check=0 class="profile_image" src="{{ gender_image($patient->gender_id==1?'Male':'Female') }}" alt="{{ $patient->name ?? '' }}" id="profile_image">
                                                @endif
                                            @else
                                                <img data-check=0 class="profile_image" src="{{ gender_image('Male') }}" alt="{{ $patient->name ?? '' }}" id="profile_image">
                                            @endif
                                        </div>
                                    </div>
                                    <div style="margin-left:22px;margin-top:10px;margin-right: 22px;" class="row">
                                        <input type="file" id="photo_name" name="photo_name">
                                        <input type="hidden" name="image" class="image-tag">
                                        <div class="col-3">
                                            <label for="photo_name"><i class="fa fa-upload profile-icon"></i></label>
                                        </div>
                                        <div class="col-3">
                                            <i class="fa fa-times profile-icon clear-img" onclick="clearImage()"></i> 
                                         </div>
                                        <div class="col-3">
                                            <i class="fa fa-camera profile-icon" onclick="openWebCam()"></i>
                                        </div>
                                    </div>
                                </div>
                                {{-- ============================================================================================================ --}}
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="_required" for="patient_type">{{trans('patientRegistration.patient_type')}}</label>
                                            <div class="form-group check_validation_p">
                                                <select class="form-control form-control-sm" style="width: 100%;" name="patient_type" id="patient_type" required>
                                                    <option value="">{{trans('patientRegistration.select_patient_type')}}</option>
                                                    @foreach ($patient_types as $key => $patient_type)
                                                        @if(isset($patient->patient_type) && $key== $patient->patient_type)
                                                            <option selected value="{{ $key}}">{{ $patient_type }}</option>
                                                        @else
                                                            <option value="{{ $key}}" {{$key==1?'selected':''}}>{{ $patient_type }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="_required" for="salutation_id">{{trans('patientRegistration.salutation')}}</label>
                                            <div class="form-group check_validation_p">
                                                <select class="form-control form-control-sm" style="width: 100%;" name="salutation_id" id="salutation_id" required>
                                                    <option value="">{{trans('patientRegistration.select_salutation')}}</option>
                                                    @foreach ($salutation_ids as $key => $salutation_id)
                                                        @if(isset($patient->salutation_id) && $key== $patient->salutation_id)
                                                            <option selected value="{{ $key}}">{{ $salutation_id }}</option>
                                                        @else
                                                            <option value="{{ $key}}" {{$key==1?'selected':''}}>{{ $salutation_id }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="_required" for="patient_name">{{ trans('patientRegistration.full_name') }}</label>
                                            <input required type="text" name="name" id="patient_name" value="{{ $patient->name ?? '' }}" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label class="_required" for="age">{{trans('patientRegistration.age')}}</label>
                                            <input required class="form-control form-control-sm age " type="text" id="age"  name="age" value="{{ $patient->age ?? ''}}" >
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="age_unit">{{trans('patientRegistration.age_unit')}}</label>
                                            <div class="form-group check_validation_p">
                                                <select class="form-control form-control-sm" style="width: 100%;" name="age_unit" id="age_unit" required>
                                                    <option value="">{{trans('patientRegistration.select_age_unit')}}</option>
                                                    @foreach ($age_units as $key => $age_unit)
                                                        @if(isset($patient->age_unit) && $key== $patient->age_unit)
                                                            <option selected value="{{ $key}}">{{ $age_unit }}</option>
                                                        @else
                                                            <option value="{{ $key}}" {{$key==1?'selected':''}}>{{ $age_unit }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="dob-bs">{{trans('patientRegistration.dob_bs')}}</label>
                                            <input placeholder="YYYY-MM-DD" class="form-control form-control-sm " id="dob-bs" type="text" value="{{ $patient->date_of_birth_bs ?? ''}}" name="date_of_birth_bs" >
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label for="dob-ad">{{trans('patientRegistration.dob_ad')}}</label>
                                            <input class="form-control form-control-sm" id="dob-ad" type="date" value="{{ $patient->date_of_birth ?? ''}}" name="date_of_birth">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="_required" for="gender">{{trans('patientRegistration.gender')}}</label>
                                            <select required class="form-control form-control-sm" name="gender_id" id="gender" >
                                            <option value="">{{trans('patientRegistration.select_gender')}}</option>
                                                @foreach ($genders as $option)
                                                    @if(isset($patient->gender_id) && $option->getKey() == $patient->gender_id)
                                                        <option tion selected value="{{ $option->getKey() }}">{{ $option->name }}</option>
                                                    @else
                                                        <option value="{{ $option->getKey() }}">{{ $option->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="marital_status">{{trans('patientRegistration.marital_status')}}</label>
                                            <div class="form-group check_validation_p">
                                                <select class="form-control form-control-sm" style="width: 100%;" name="marital_status" id="marital_status" required>
                                                    <option value="">{{trans('patientRegistration.select_marital_status')}}</option>
                                                    @foreach ($marital_status as $key => $marital_stat)
                                                        @if(isset($patient->marital_status) && $key== $patient->marital_status)
                                                            <option selected value="{{ $key}}">{{ $marital_stat }}</option>
                                                        @else
                                                            <option value="{{ $key}}" {{$key==0?'selected':''}}>{{ $marital_stat }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="national_id_no">{{trans('patientRegistration.national_id_no')}}</label>
                                            <input class="form-control form-control-sm" type="text" value="{{  $patient->national_id_no ?? ''}}" name="national_id_no">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="passport_no">Passport No</label>
                                            <input class="form-control form-control-sm mr-2" id="passport_no" type="text" value="{{ $patient->passport_no ?? ''}}" name="passport_no">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <label class="_required" for="cell_phone">{{trans('patientRegistration.mobile')}}</label>
                                            <input class="form-control form-control-sm" id="cell_phone" type="tel" value="{{ $patient->cell_phone ?? '' }}" name="cell_phone" required maxlength="10">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="_required" for="street_address">{{trans('patientRegistration.street_address')}}</label>
                                            <input required class="form-control form-control-sm mr-2" id="street_address" type="text" value="{{ $patient->street_address ?? ''}}" name="street_address">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="icmr_no">ICMR no</label>
                                            <input class="form-control form-control-sm mr-2" id="icmr_no" type="text" value="{{ $patient->icmr_no ?? ''}}" name="icmr_no">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <div class="form-row">
                <div class="col-md-12 text-right d-flex flex-column">
                    <div class="mt-auto">
                        @if(isset($patient->id))
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i> Update</button>
                            <a href="{{ backpack_url('emergency-patient') }}" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Cancel</a>
                        @else
                            <a href="javascript:;" id="confirm_cancel_btn"  class="btn btn-sm btn-danger ml-2"><i class="fa fa-ban"></i> Cancel</a>
                            <button title="Press Alt+S to save the form." type="submit" class="btn btn-sm btn-primary ml-2 mr-3"><i class="fa fa-floppy-o"></i> Confirm And Register</button>
                        @endif
                            <div class="small text-muted">Press <strong>Alt+S</strong> to save the form.</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<div class="modal" id="WebCameraModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Take Photo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="my_camera"></div>
            <input type=button value="Take Snapshot" onClick="take_snapshot()">
            <div id="results" ></div>
            <input type="hidden" id="data_url" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" onclick="savePhoto()" class="btn btn-primary">Done</button>
        </div>
      </div>
    </div>
  </div>

@section('after_styles')
<style>
      #registration_form{
        color: black;
    }
    .profile_image{
        width:150px; 
        margin-left:50px;
        margin-top:15px;
    }
    #photo_name{
        display: none;
    }
    .profile-icon{
        padding: 5px 12px;
        border: 2px solid dodgerblue;
        border-radius: 10px;
    }
    .profile-icon:hover{
        background: rgb(102, 201, 102);
        color: white;
        cursor: pointer;
    }
    /* .profile-icon.fa-camera{
        margin-left: 15px;
        margin-bottom: 7px;
    }
    .profile-icon.fa-times{
        margin-left: 15px;
    } */
    h4{
        margin:0px;
        padding: 0px;
    }
    .accordion {
        background-color: #eee;
        color: #000000;
        cursor: pointer;
        padding: 10px;
        width: 100%;
        text-align: center;
        border: none;
        outline: none;
        transition: 0.4s;
        font-size:17px;
    }
    .accordion:hover {
        text-decoration-color: #000000;
        /* color:black; */
        background-color:lightgray; 
    }
    .heading{
        margin-left: 3%;
    }

    #my_camera{
        width: 280px;
        height: 220px;
        border: 1px solid black;
    }
</style>
@endsection

<script  type="text/javascript" src="{{asset('js/webcam.min.js')}}"></script>
@section('after_scripts')
<script>
$('.clear-img').hide();
var action_method = {!! json_encode($crud->getActionMethod()) !!};
if(action_method == 'edit' | 'update'){
    $('.clear-img').show();
}
$('body').on('keyup', function(e) {
    let keyPressed = e.which ? e.which : e.keyCode;

    if ((e.altKey && keyPressed === LMS._formSaveGroupKey) || keyPressed === LMS._formSaveSingleKey) { // If Alt + S(83) or F2 (113)
        let forms = document.querySelectorAll('form')
        if(forms.length === 0) {
            return false;
        }
        $('#registration_form').submit();
    }
});

function showHideIdNumber(){
    const id_type = $('#id_type').val();
    $('.id_number').hide();
    $(`#id_type-${id_type}`).show();
}
function showHideReferrerInfo()
    {
        const is_referred=$('#is_referred').val()
        if(is_referred==1){
            $('.referrer_info').show();
            $('#hospital_id, #referrer_doctor_name').addClass('required');
        }else{
            $('#hospital_id, #referrer_doctor_name').removeClass('required');
            $('.referrer_info').hide();
        }
    }



// For clearing image
function clearImage(){
    $(".image-previwe > img").remove()
    gender=$('#gender').val();
    if(gender==1){
        var picture = '<img data-check=0 src="/images/user-image.png" class="profile_image"  id="profile_image">'
    }else{
        var picture = '<img data-check=0 src="/images/user-default-female.png" class="profile_image"  id="profile_image">'
    }
    $(".image-previwe").empty().append(picture);
    $("#photo_name").val("");
    $(".image-tag").val("");
    $(".clear-img").hide();
}

  // For Web Camera  Start
function openWebCam(){
    $('#WebCameraModal').modal('show');
    Webcam.set({
			width: 280,
			height: 220,
			image_format: 'jpeg',
			jpeg_quality: 60
		});
	Webcam.attach( '#my_camera' );
}

function take_snapshot() {
	// take snapshot and get image data
    Webcam.snap( function(data_uri) {
        // display results in page
        document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        document.getElementById('data_url').value = data_uri;
    } );
}

function savePhoto()
{
    let image = document.getElementById("data_url").value;
    document.getElementById("profile_image").src = image;
    $('#WebCameraModal').modal('hide');
    const video = document.querySelector('video');
    // A video's MediaStream object is available through its srcObject attribute
    const mediaStream = video.srcObject;
    // Through the MediaStream, you can get the MediaStreamTracks with getTracks():
    const tracks = mediaStream.getTracks();
    // Tracks are returned as an array, so if you know you only have one, you can stop it with: 
    tracks[0].stop();
}

///END

$(document).ready(function(){
    changeGenderImage();
    $("#gender").change(function() {
        changeGenderImage();
    });
    $("#photo_name").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });

    function changeGenderImage() {
        const imageCheck =$('#profile_image').data('check');
        if(imageCheck==1){
            return;
        }
        gender=$('#gender').val();
        if(gender==1){
            var picture = '<img data-check=0 src="/images/user-image.png" class="profile_image"  id="profile_image">'
        }else{
            var picture = '<img data-check=0 src="/images/user-default-female.png" class="profile_image"  id="profile_image">'
        }
        $(".image-previwe").empty().append(picture);
    }
    
    function imageIsLoaded(e) {
        var picture = '<img data-check=1 src="' + e.target.result + '" class="profile_image"  id="profile_image">'
        $(".image-previwe").empty().append(picture);
        $(".clear-img").show();
    }
    $('input#patient_name').focus();
    showHideReferrerInfo();
    showHideIdNumber();
    $('#id_type').change(function(){
        showHideIdNumber();
    });
    $('#is_referred').change(function(){
        showHideReferrerInfo();
    });
    $('#register_and_admit_btn').click(function(){
        $('#is_ipd').val(true);
        $('form#registration_form').submit();
    });

    $('#registration_form').validate({
        submitHandler: function(form) {
            swal({
                closeOnClickOutside: false,
                title: "Confirm And Register !!",
                text: 'The information provided is correct and want to proceed towards registration.',
                buttons: {
                    no: {
                        text: " No ",
                        value: false,
                        visible: true,
                        className: "btn btn-secondary",
                        closeModal: true,
                    },
                    yes: {
                        text: " Yes ",
                        value: true,
                        visible: true,
                        className: "btn btn-success",
                        closeModal: true,
                    }
                },
            }).then((confirmResponse) => {
                if (confirmResponse) {
                    LMS.lmsLoading(true, 'Saving...');
                    let data = new FormData(form);
                    let url = form.action;
                    axios.post(url, data)
                    .then((response) => {
                        document.location = response.data.url;
                        LMS.lmsLoading(false);
                    }, (error) => {
                        swal("Error !", error.response.data.message, "error")
                        LMS.lmsLoading(false);
                    });
                }
            });
        }
    });
}); 
</script>
@endsection

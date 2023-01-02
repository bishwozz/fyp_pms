@extends(backpack_view('blank'))
@section('content') 
<link href="{{ asset('css/excel_upload.css') }}" rel="stylesheet" type="text/css" />
  <!-- Filepond stylesheet -->
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" integrity="sha512-WvVX1YO12zmsvTpUQV8s7ZU98DnkaAokcciMZJfnNWyNzm7//QRV61t4aEr0WdIa4pe854QHLTV302vH92FSMw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

<div class="card mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card h-100">
                
                <div class="card-body">
                    <div class="px-3 py-1 mb-2">
                        <h3>Excel Report</h3>
                    </div>
                    <div class="container">

                        <!--teste dropzone com preview-->
                        <div class="row">
                            <div class="col">
                                <!-- Uploader Dropzone -->
                                <form action="{{ route('excel-upload') }}" id="zdrop" class="fileuploader center-align">
                                    @csrf
                                <div id="upload-label" style="width: 200px;">
                                    <i class="material-icons">upload</i>
                                </div>
                                <span class="tittle">Click the Button or Drop Files Here</span>
                                </form>
            
                                <!-- Preview collection of uploaded documents -->
                                <div class="preview-container">
                                <div class="collection card" id="previews">
                                    <div class="collection-item clearhack valign-wrapper item-template" id="zdrop-template">
                                    <div class="left pv zdrop-info" data-dz-thumbnail>
                                        <div>
                                        <span data-dz-name></span> <span data-dz-size></span>
                                        </div>
                                        <div class="progress">
                                        <div class="determinate" style="width:0" data-dz-uploadprogress></div>
                                        </div>
                                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                    </div>
            
                                    <div class="secondary-content actions">
                                        <a href="#!" data-dz-remove class="btn-floating ph red white-text waves-effect waves-light"><i class="material-icons white-text">clear</i></a>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>						    
                        </div> 
                    </div>
    

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<!-- Load FilePond library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/excel_upload.js') }}">
</script>
@endsection
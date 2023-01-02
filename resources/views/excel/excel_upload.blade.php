@extends(backpack_view('blank'))
@section('content')
<link href="{{ asset('css/excel_upload.css') }}" rel="stylesheet" type="text/css" />

<div class="card mt-3">
  <div class="row">

    <div class="col-md-12">

      <div class="card h-100">

        <div class="card-body">
          <div class="px-3 py-1">
            <h3>Excel Report</h3>
          </div>
          <section>
            <form action="{{ route('excel-upload') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label">Upload File</label>
                      <div class="preview-zone hidden">
                        <div class="box box-solid">
                          <div class="box-header with-border">
                            <div><b>Preview</b></div>
                            <div class="box-tools pull-right">
                              <button type="button" class="btn btn-danger btn-xs remove-preview">
                                <i class="fa fa-times"></i> Reset This Form
                              </button>
                            </div>
                          </div>
                          <div class="box-body"></div>
                        </div>
                      </div>
                      <div class="dropzone-wrapper">
                        <div class="dropzone-desc">
                          <i class="glyphicon glyphicon-download-alt"></i>
                          <p>Choose an Excel file or drag it here.</p>
                        </div>
                        <input type="file" name="excel-upload" class="dropzone"
                          accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Upload</button>
                  </div>
                </div>
              </div>
            </form>
          </section>

        </div>

      </div>

    </div>

  </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/excel_upload.js') }}">
</script>
@endsection
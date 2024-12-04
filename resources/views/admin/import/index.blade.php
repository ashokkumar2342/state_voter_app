@extends('admin.layout.base') 
@section('body')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3>Import Data From Excel Files</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right"> 
        </ol>
      </div>
    </div> 
    <div class="card card-info"> 
      <div class="card-body">
        <form action="{{ route('admin.import.store') }}" method="post" class="add_form" success-content-id="import_data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-lg-6 form-group">
              <label>Data Type</label>
              <select name="data_type" class="form-control" onchange="callAjax(this,'{{ route('admin.import.sample.help.file') }}','sample_help_file');callAjax(this,'{{ route('admin.import.show.previous.upload') }}','import_data')" required>
                <option selected disabled>Select Data Type</option>
                @foreach ($dataTypes as $dataType)
                <option value="{{$dataType->id}}">{{$dataType->data_type}}</option>
                @endforeach
              </select> 
            </div>
            <div class="col-lg-6" id="sample_help_file">
              
            </div>
            <div class="col-lg-6 form-group"> 
              <label>Excel File</label>
              <div class="custom-file">
                  <input type="file" class="custom-file-input" name="excel_file" id="customFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                  <label class="custom-file-label" for="customFile">Choose file</label>
              </div> 
            </div>
            <div class="col-lg-6 form-group"> 
              <input type="submit" class="btn btn-success form-control" value="Import" style="margin-top: 30px">
            </div> 
          </div> 
        </form>
        <div id="import_data">
          
        </div>
      </div>
    </div>
  </div> 
</section>
@endsection



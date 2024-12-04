@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Polling Day Time</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">    
                        <form action="{{ route('admin.Master.pollingDayTimeStore') }}" method="post" no-reset="true" class="add_form" select-triger="block_select_box" reset-input-text="polling_day_time_english,polling_day_time_local,signature">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">  
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                    <label for="exampleInputEmail1">Block / MC's</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.pollingDayTimeList') }}','polling_day_time_list')" required>
                                        <option selected disabled>Select Block / MC's</option> 
                                    </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                    <label>Polling Day Time (In English)</label>
                                    <span class="fa fa-asterisk"></span>
                                    <input type="text" name="polling_day_time_english" id="polling_day_time_english" class="form-control" required maxlength="500"> 
                                    </div>
                                    <div class="col-lg-4 form-group">
                                    <label>Polling Day Time (In Hindi)</label>
                                    <span class="fa fa-asterisk"></span>
                                    <input type="text" name="polling_day_time_local" class="form-control" id="polling_day_time_local" required maxlength="500"> 
                                    </div>
                                    <div class="col-lg-4 form-group">
                                    <label>Sign Stamp (in jpg)</label>
                                    <span class="fa fa-asterisk"></span>
                                    <input type="file" name="signature" id="signature" class="form-control" required accept=".jpg" /> 
                                    </div> 
                                   <div class="col-lg-12 form-group">
                                       <input type="submit" class="btn btn-primary form-control">
                                   </div> 
                                    </div>
                                </div> 
                        </form>
                    </div>
                </div>
                <div id="polling_day_time_list">
                     
                 </div> 
            </div>
      </div>
  </div>
</section>
@endsection
@push('scripts')
 
@endpush
 


@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Add Voter Slip Notes.</h3>
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
                        <form action="{{ route('admin.Master.voter.slip.notes.store') }}" method="post" no-reset="true" class="add_form" select-triger="district_select_box" reset-input-text="notes,srno">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">  
                                    <div class="col-lg-12 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.voter.slip.notes.show') }}','voter_slip_notes')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Sr. No.</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="srno" id="srno" class="form-control"maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                                    </div>
                                    <div class="col-lg-9 form-group">
                                    <label for="exampleInputEmail1">Notes</label>
                                    <textarea name="notes" class="form-control" id="notes" style="height: 250px" maxlength="500" required></textarea> 
                                    </div> 
                                   <div class="col-lg-12 form-group">
                                       <input type="submit" class="btn btn-primary form-control">
                                   </div> 
                                    </div>
                                </div> 
                        </form>
                    </div>
                </div>
                <div id="voter_slip_notes">
                     
                 </div> 
            </div>
      </div>
  </div>
</section>
@endsection
@push('scripts')
 
@endpush
 


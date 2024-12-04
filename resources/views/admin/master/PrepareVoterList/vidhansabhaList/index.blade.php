@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Prepare Vidhansabha Supplement List</h3>
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
                        <form action="{{ route('admin.prepare.vidhansabha.List.submit') }}" method="post" no-reset="true" class="add_form">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">  
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.mapping.district.wise.ac') }}','block_select_box')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                    <label for="exampleInputEmail1">Assembly</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="assembly" class="form-control select2" id="block_select_box">
                                        <option selected disabled>Select Assembly</option> 
                                    </select>
                                    </div> 
                                    <div class="col-lg-12 form-group" style="margin-top: 33px">
                                       <input type="submit" class="btn btn-success form-control">
                                    </div>
                                    
                                    </div>
                                </div> 
                        </form>
                    </div> 
                </div>
            </div>
      </div>
  </div>
</section>
@endsection
@push('scripts')
 
@endpush
 


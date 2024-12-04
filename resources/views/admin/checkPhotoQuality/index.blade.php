@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Check Photo Quality</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.check.photo.quality.store') }}" no-reset="true" class="add_form">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-3 form-group">
                        <label>Assembly</label>
                        <select class="form-control select2" name="assembly" onchange="callAjax(this,'{{ route('admin.voter.AssemblyWisePartNo') }}','select_part_no')">
                        <option selected disabled>Select Assembly</option>
                        @foreach ($assemblys as $assembly)
                        <option value="{{$assembly->id}}">{{$assembly->code}}-{{$assembly->name_e}}</option> 
                        @endforeach 
                        </select>    
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>Part No.</label>
                        <select class="form-control select2" name="part_no" id="select_part_no">
                            <option>Select Part No.</option>
                        </select>    
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>From Sr.No.</label>
                        <input type="text" name="from_sr_no" class="form-control">     
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>To Sr.No.</label>
                        <input type="text" name="to_sr_no" class="form-control">     
                    </div>
                    <div class="col-lg-12 form-group"> 
                    <input type="submit" class="form-control btn btn-success">     
                    </div>  
                </div>
            </form>
            </div> 
        </div> 
    </section>
    @endsection
<script type="text/javascript">
    function disablewardNo() {
    document.getElementById("ward_select_box").disabled = false;
}

</script>
   
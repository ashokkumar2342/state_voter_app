@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Import Assembly Data</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                </ol>
            </div>
        </div> 
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.database.conection.tableRecordStore') }}" method="post" class="add_form" no-reset="true">
                    {{ csrf_field() }} 
                    <div class="row"> 
                        <div class="col-lg-6 form-group">
                            <label>District</label>
                            <select name="district_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','asembly_no_div')" select-triger="asembly_no_div">
                                <option selected disabled>Select District</option> 
                                @foreach ($rs_district as $val_rec)
                                <option value="{{ Crypt::encrypt($val_rec->opt_id) }}">{{ $val_rec->opt_text }}</option> 
                                @endforeach 
                            </select> 
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Assembly Code</label>
                            <select name="ac_code" class="form-control select2" id="asembly_no_div" onchange="callAjax(this,'{{ route('admin.database.conection.assemblyWisePartNo') }}','part_no_div')">
                                <option selected disabled>Select Assembly Code</option>
                            </select> 
                        </div>
                        <div class="col-lg-12">
                            <fieldset class="fieldset_border">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <td class="bg-dark">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="all_check" class="checked_all">
                                                        <label for="all_check" class="checked_all">All Check</label>
                                                    </div>
                                                </td>
                                                <th>Part No.</th>
                                                <th>Total Import</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="part_no_div">
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="btn btn-primary form-group form-control" >Submit</button>
                        </div>
                    </div>
                </form> 
            </div>
        </div>  
    </div>
</section>
@endsection
@push('scripts')
<script>
    $("#all_check").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script> 
@endpush




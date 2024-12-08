<div class="col-lg-12"> 
    <div class="card card-info">
        <div class="card-body">
            <div class="row"> 
                <div class="col-lg-6 form-group">
                    <label>Assembly</label>
                    <select name="assembly" class="form-control select2" id="assembly_select_box" onchange="callAjax(this,'{{ route('admin.Master.MappingAssemblyWisePartNo') }}'+'?village_id='+$('#village_select_box').val(),'part_no_select_box')">
                        <option selected disabled>Select Assembly</option> 
                        @foreach ($rs_assemblys as $rs_val)
                        <option value="{{ Crypt::encrypt($rs_val->id) }}">{{ $rs_val->code }}-{{ $rs_val->name_e }}</option> 
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-6 form-group">
                    <label>Part No.</label>
                    <select name="part_no" class="form-control select2" id="part_no_select_box">
                        <option selected disabled>Select Part</option>
                    </select> 
                </div>
                <div class="col-lg-12 form-group text-center">
                    <input type="submit" class="btn btn-success form-control" value="Save">
                </div>
            </div> 
        </div>
    </div>
</div>

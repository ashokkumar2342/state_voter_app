<div class="col-lg-4">     
    <div class="form-group">
        <label for="exampleInputEmail1">District</label>
        <span class="fa fa-asterisk"></span>
        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','assembly_select_box');" required>
            <option selected disabled>Select District</option>
            @foreach ($rs_district as $rs_val)
                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
            @endforeach
        </select> 
    </div>
</div>
<div class="col-lg-4">
    <div class="form-group">
        <label for="exampleInputEmail1">Assembly</label>
        <span class="fa fa-asterisk"></span>
        <select name="assembly" class="form-control select2" id="assembly_select_box" onchange="callAjax(this,'{{ route('admin.Master.AssemblyWiseAllPartNo') }}','ap_select_box');" required>
            <option selected disabled>Select Assembly</option>                         
        </select>
    </div> 
</div>

<div class="col-lg-4">
    <div class="form-group">
        <label for="exampleInputEmail1">Part No.</label>
        <span class="fa fa-asterisk"></span>
        <select name="ac_part" class="form-control select2" id="ap_select_box" required>
            <option selected disabled>Select Part No.</option>                         
        </select>
    </div> 
</div>

<div class="col-lg-12 form-group" style="padding-top: 24px;">
    <input  type="submit" class="btn btn-success form-control" id="btn_show" value="Show">
</div>
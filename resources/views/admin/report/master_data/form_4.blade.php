<div class="col-lg-3">     
    <div class="form-group">
        <label for="exampleInputEmail1">District</label>
        <span class="fa fa-asterisk"></span>
        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')" required>
            <option selected disabled>Select District</option>
            @foreach ($rs_district as $rs_val)
                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
            @endforeach
        </select> 
    </div>
</div>
<div class="col-lg-3">
    <div class="form-group">
        <label for="exampleInputEmail1">Block / MC's</label>
        <span class="fa fa-asterisk"></span>
        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" required>
            <option selected disabled>Select Block / MC's</option> 
        </select>
    </div> 
</div>
<div class="col-lg-3">
    <div class="form-group">
        <label for="exampleInputEmail1">Panchayat MC's</label>
        <span class="fa fa-asterisk"></span>
        <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table="ward_datatable" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWardAll') }}','ward_select_box')">
            <option selected disabled>Select Panchayat MC's</option>
        </select>
    </div> 
</div>
<div class="col-lg-3">
    <div class="form-group">
        <label for="exampleInputEmail1">Ward</label>
        <span class="fa fa-asterisk"></span>
        <select name="ward" class="form-control select2" id="ward_select_box">
            <option selected disabled>Select Ward</option>
        </select>
    </div> 
</div>
<div class="col-lg-12 form-group" style="padding-top: 24px;">
    <input  type="submit" class="btn btn-success form-control" id="btn_show" value="Show">
</div>
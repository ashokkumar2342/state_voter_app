<div class="row"> 
<div class="col-lg-4 form-group">
<label for="exampleInputEmail1">States</label>
<span class="fa fa-asterisk"></span>
<select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')">
<option selected disabled>Select States</option>
@foreach ($States as $State)
<option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
@endforeach
</select>
</div>
<div class="col-lg-4 form-group">
<label for="exampleInputEmail1">District</label>
<span class="fa fa-asterisk"></span>
<select name="district" class="form-control select2" id="district_select_box">
<option selected disabled>Select District</option>
</select>
</div>
<div class="col-lg-4 form-group">
 <input type="submit" class="form-control btn btn-primary" value="Save" style="margin-top: 30px">
</div>
<div class="col-lg-12" style="margin-top: 20px"> 
<table class="table  table-bordered table-striped" id="class_section_list">
    <thead>
        <tr>
            
            <th>District</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($DistrictBlockAssigns as $DistrictBlockAssign)
                    <tr>
                        <td>{{ $DistrictBlockAssign->name_e or ''}}</td> 
                        <td>
                            <a title="Delete" class="btn btn-xs btn-danger" select-triger="user_id" onclick="if (confirm('Are you Sure delete')){callAjax(this,'{{ route('admin.Master.DistrictsAssignDelete',Crypt::encrypt($DistrictBlockAssign->id)) }}') } else{console_Log('cancel') }"  ><i class="fa fa-trash"></i></a>
                        </td> 
                    </tr> 
        @endforeach
    </tbody>
</table> 
 </div>
</div>
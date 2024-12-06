<div class="row"> 
    <div class="col-lg-4 form-group">
        <label for="exampleInputEmail1">District</label>
        <span class="fa fa-asterisk"></span>
        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
            <option selected disabled>Select District</option>
            @foreach ($rs_district as $val_rec)
                <option value="{{ Crypt::encrypt($val_rec->opt_id) }}">{{ $val_rec->opt_text }}</option>    
            @endforeach
        </select>
    </div>
    <div class="col-lg-4 form-group">
        <label for="exampleInputEmail1">Block/MC's</label>
        <span class="fa fa-asterisk"></span>
        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
            <option selected disabled>Select Block/MC's</option> 
        </select>
    </div>
    <div class="col-lg-4 form-group">
        <label for="exampleInputEmail1">Panchayat / MC's</label>
        <span class="fa fa-asterisk"></span>
        <select name="village" class="form-control select2" id="village_select_box" select2="true">
            <option selected disabled>Select Panchayat</option>
        </select>
    </div>
    <div class="col-lg-12 form-group">
        <input type="submit" class="form-control btn btn-primary" value="Save" style="margin-top: 30px">
    </div>
    <div class="col-lg-12" style="margin-top: 20px"> 
        <table class="table  table-bordered table-striped" id="class_section_list">
            <thead>
                <tr>
                    <th>Block / MC's</th>
                    <th>Panchayat / MC's</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($DistrictBlockAssigns as $DistrictBlockAssign)
                            <tr>
                                <td>{{ $DistrictBlockAssign->block_name}}</td> 
                                <td>{{ $DistrictBlockAssign->vil_name}}</td> 
                                <td>
                                    <a title="Delete" class="btn btn-xs btn-danger" select-triger="user_id" onclick="if (confirm('Are you Sure To Delete This Record')){callAjax(this,'{{ route('admin.Master.DistrictBlockVillageAssignDelete',Crypt::encrypt($DistrictBlockAssign->id)) }}') } else{console_Log('cancel') }"  ><i class="fa fa-trash"></i></a>
                                </td> 
                            </tr> 
                @endforeach
            </tbody>
        </table> 
    </div>
</div>
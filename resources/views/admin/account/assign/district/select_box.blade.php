<div class="row"> 
    <div class="col-lg-6 form-group">
        <label for="exampleInputEmail1">District</label>
        <span class="fa fa-asterisk"></span>
        <select name="district" class="form-control select2" id="district_select_box" required>
            @foreach ($rs_district as $val_rec)
                <option value="{{ Crypt::encrypt($val_rec->opt_id) }}">{{ $val_rec->opt_text }}</option>    
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 form-group">
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
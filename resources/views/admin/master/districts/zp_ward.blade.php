<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Create Z.P. Wards</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.districts.zpWardStore', Crypt::encrypt($district_id)) }}" method="post" class="add_form" select-triger="state_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <h3>{{ @$DistrictName[0]->name_e }}</h3> 
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Zila Parishad Ward To Create</label>
                        <span class="fa fa-asterisk"></span> 
                        <input type="text" name="zp_ward" class="form-control" placeholder="" maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                    </div>               
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Submit</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


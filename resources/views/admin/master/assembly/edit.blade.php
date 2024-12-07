<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.Assembly.store', Crypt::encrypt($assembly[0]->id)) }}" method="post" class="add_form" select-triger="district_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Assembly Code</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="5" value="{{ $assembly[0]->code }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Assembly Name (English)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_english" class="form-control" placeholder="Enter Name (In English)" maxlength="50" value="{{ $assembly[0]->name_e }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Assembly Name (Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" maxlength="100" value="{{ $assembly[0]->name_l }}" required>
                    </div> 
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Update</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


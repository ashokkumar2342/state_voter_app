<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.village.store', Crypt::encrypt($rec_id)) }}" method="post" class="add_form" select-triger="block_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Panchayat / MC's Code</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="5" value="{{ $village[0]->code }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Name (English)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required value="{{ $village[0]->name_e }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Name (Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_local_language" class="form-control" placeholder="Enter Name (Hindi)" maxlength="100" required value="{{ $village[0]->name_l }}">
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


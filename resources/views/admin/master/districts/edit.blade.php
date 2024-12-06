<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $rec_id>0? 'Edit' : 'Add'}}</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.master.districts.store', Crypt::encrypt($rec_id)) }}" method="post" class="add_form" select-triger="state_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body"> 
                    <div class="form-group">
                        <label for="exampleInputEmail1">District Code</label>
                          <span class="fa fa-asterisk"></span>
                          <input type="text" name="code" class="form-control" placeholder="Enter Code" value="{{ $Districts[0]->code }}" maxlength="5" required> 
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">District Name (English)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_english" class="form-control" placeholder="Enter Name (English)" value="{{ $Districts[0]->name_e }}" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">District Name (Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" value="{{ $Districts[0]->name_l }}" maxlength="100" required>
                    </div>                  
                </div>

                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">{{ $rec_id>0? 'Update' : 'Submit' }}</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


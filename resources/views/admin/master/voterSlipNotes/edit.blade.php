<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.voter.slip.notes.store', Crypt::encrypt($rs_edit[0]->id)) }}" method="post" class="add_form" select-triger="district_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="card-body">
                    <input type="text" name="district" class="form-control" placeholder="" hidden maxlength="5" value="{{ Crypt::encrypt($rs_edit[0]->district_id) }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sr. No.</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="srno" id="srno" class="form-control"maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required value="{{ $rs_edit[0]->note_srno }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Notes</label>
                        <textarea name="notes" class="form-control" id="notes" style="height: 250px" maxlength="500" required >{{ $rs_edit[0]->note_text }}</textarea> 
                    </div>
                    <div class="modal-footer card-footer justify-content-between">
                        <button type="submit" class="btn btn-success form-control">Update</button>
                        <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


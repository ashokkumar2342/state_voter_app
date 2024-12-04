<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.ZilaParishadUpdate',$ZilaParishad[0]->id) }}" method="post" class="add_form" no-reset="true" select-triger="district_select_box" button-click="btn_close">
        {{ csrf_field() }}
        <div class="card-body">
          <div class="form-group">
            <label>Ward No.</label>
            <input type="text" class="form-control" name="zp_ward_no" value="{{ $ZilaParishad[0]->ward_no }}" required maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
          </div>
          <div class="form-group">
            <label>Name (in English)</label>
            <input type="text" class="form-control" name="zp_ward_name_english" value="{{ $ZilaParishad[0]->name_e }}" required maxlength="50"> 
          </div>
          <div class="form-group">
            <label>Name (in Hindi)</label>
            <input type="text" class="form-control" name="zp_ward_name_local_language" value="{{ $ZilaParishad[0]->name_l }}" required maxlength="50"> 
          </div>
        </div> 
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


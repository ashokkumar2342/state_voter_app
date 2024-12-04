<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.gender.update',$gender[0]->id) }}" method="post" class="add_form" content-refresh="gender_table" button-click="btn_close">
        {{ csrf_field() }}  
          <div class="form-group">
            <label for="exampleInputPassword1">Gender (English)</label>
            <span class="fa fa-asterisk"></span>
            <input type="text" name="gender_english" class="form-control" placeholder="Enter Gender (English)" maxlength="20" value="{{ $gender[0]->genders }}" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Gender (Hindi)</label>
            <span class="fa fa-asterisk"></span>
            <input type="text" name="gender_local_language" class="form-control" placeholder="Enter Gender (Local Language)" maxlength="50" value="{{ $gender[0]->genders_l }}" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Code (English)</label>
            <span class="fa fa-asterisk"></span>
            <input type="text" name="code_english" class="form-control" placeholder="Enter Code" maxlength="5" value="{{ $gender[0]->code }}" required>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Code (Hindi)</label>
            <span class="fa fa-asterisk"></span>
            <input type="text" name="code_local_language" class="form-control" placeholder="Enter Code (in Hindi)" maxlength="20" value="{{ $gender[0]->code_l }}" required>
          </div>  
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


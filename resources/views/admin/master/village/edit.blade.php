<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.village.store',$village[0]->id) }}" method="post" class="add_form" select-triger="block_select_box" button-click="btn_close">
      {{ csrf_field() }}
      <div class="card-body">
      <input type="text" name="states" class="form-control" placeholder="" hidden maxlength="5" value="{{ $village[0]->states_id }}">
            <input type="text" name="district" class="form-control" placeholder="" hidden maxlength="5" value="{{ $village[0]->districts_id }}">
            <input type="text" name="block_mcs" class="form-control" placeholder="" hidden maxlength="5" value="{{ $village[0]->blocks_id }}">
             
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
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-primary form-control">Update</button>
           
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.districtsStore',$Districts[0]->id) }}" method="post" class="add_form" select-triger="state_select_box" button-click="btn_close">
          {{ csrf_field() }}
          <div class="card-body">
              <div class="form-group">
                  <label for="exampleInputEmail1">States</label>
                  <span class="fa fa-asterisk"></span>
                  <select name="states" class="form-control">
                      <option selected disabled>Select States</option>
                      @foreach ($States as $State)
                      <option value="{{ $State->id }}"{{ $Districts[0]->state_id==$State->id?'selected' :'' }}>{{ $State->code }}--{{ $State->name_e }}</option>  
                      @endforeach
                  </select>
              </div>
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
          <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


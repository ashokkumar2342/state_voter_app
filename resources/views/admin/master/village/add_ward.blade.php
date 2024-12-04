<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Add Ward</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.ward.store') }}" method="post" class="add_form" select-triger="block_select_box" button-click="btn_close">
        {{ csrf_field() }}
        <div class="card-body row">
          <div class="col-lg-6 form-group">
            <h3>{{ $Village[0]->name_e }}</h3> 
          </div>
          <div class="col-lg-12 form-group">
            <label for="exampleInputEmail1">Ward To Be Created</label>
            <span class="fa fa-asterisk"></span>
            <input type="text" name="states" class="form-control" placeholder="" hidden maxlength="5" value="{{ $Village[0]->states_id }}">
            <input type="text" name="district" class="form-control" placeholder="" hidden maxlength="5" value="{{ $Village[0]->districts_id }}">
            <input type="text" name="block" class="form-control" placeholder="" hidden maxlength="5" value="{{ $Village[0]->blocks_id }}">
            <input type="text" name="village" class="form-control" placeholder="" hidden maxlength="5" value="{{ $Village[0]->id }}">
            <input type="text" name="ward" class="form-control" placeholder="" maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
        </div>
        </div> 
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success form-control">Save</button>
          
        </div>
      </form>
    </div>
  </div>
</div>


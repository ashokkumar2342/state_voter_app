<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
       <form action="{{ route('admin.Voter.import.type.store',$VoterimportType[0]->id) }}" method="post" no-reset="true" class="add_form" no-reset="true" content-refresh="voter_import_type" button-click="btn_close">
                {{ csrf_field() }} 
                <div class="row"> 
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Import Description</label>
                          <input type="text" name="description" class="
                          form-control" maxlength="200" required value="{{$VoterimportType[0]->description}}">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Import Date</label>
                          <input type="date" name="date" class="
                          form-control" maxlength="200" required value="{{$VoterimportType[0]->date}}">
                    </div>
                     <input type="hidden" name="status"  value="{{$VoterimportType[0]->status}}">
                    
                  </div> 
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


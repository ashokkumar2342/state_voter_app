<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Add / Update Assembly Parts</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.AssemblyPart.store') }}" method="post" class="add_form" select-triger="district_select_box" button-click="btn_close">
                                {{ csrf_field() }}
                                <div class="card-body row"> 
                                     <input type="hidden" name="assembly" value="{{ $assembly[0]->id}}">
                                     <div class="col-lg-6 form-group">
                                       <h3>{{ $assembly[0]->name_e }}</h3>
                                     </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="exampleInputEmail1">Parts To Be Created </label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="part_no" class="form-control" placeholder="Enter Total Parts" maxlength="3" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div> 
                                    
                                </div>

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                                </div>
                            </form>
    </div>
  </div>
</div>


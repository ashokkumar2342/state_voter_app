<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Search</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
     <div class="col-lg-12 form-group">
        <label for="exampleInputEmail1">Search</label> 
        <input type="text" name="search" class="form-control" placeholder="Search" onkeyup="callAjax(this,'{{ route('admin.voter.DeteleAndRestoreSearchFilter') }}','search_table')">
    </div>
    <div class="col-lg-12 form-group" id="search_table">
         
    </div>
    </div>
  </div>
</div>


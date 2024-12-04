<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Import</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.Master.AssemblyImportStore') }}" method="post"  content-refresh="district_table" button-click="btn_close" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="card-body">
          <div class="row">  
          <div class="col-lg-12 form-group">
              <label for="exampleInputFile">Import File</label>
              <div class="input-group">
                  <div class="custom-file">
                      <input type="file" class="custom-file-input" id="exampleInputFile" name="import_file">
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                  </div> 
              </div>
          </div> 
          </div> 
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


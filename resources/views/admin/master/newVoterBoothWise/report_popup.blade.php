<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Report Type</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <form action="{{ route('admin.new.voter.booth.wise.report.generate') }}" method="post" target="blank">
        {{ csrf_field() }} 
        <div class="row"> 

          <input type="hidden" name="village_id" value="{{ $village_id }}">
          <input type="hidden" name="ward_id" value="{{ $ward_id }}">
          <input type="hidden" name="assembly_part" value="{{ $assembly_part }}">
          <input type="hidden" name="data_list" value="{{ $data_list }}">
          <input type="hidden" name="booth" value="{{ $booth }}">
           
          <div class="col-lg-4 form-group"> 
            <div class="icheck-primary d-inline">
              <input type="radio" id="radioPrimary1" name="report" value="1" checked>
              <label for="radioPrimary1">Voter Not Mapped</label>  
            </div>
          </div>
          <div class="col-lg-4 form-group"> 
            <div class="icheck-primary d-inline">
              <input type="radio" id="radioPrimary2" name="report" value="2">
              <label for="radioPrimary2">Ward Check List</label>  
            </div>
          </div>
          <div class="col-lg-4 form-group">  
            <div class="icheck-primary d-inline">
              <input type="radio" id="radioPrimary3" name="report" value="3">
              <label for="radioPrimary3">Village/MC Voter Check List</label>  
            </div>
          </div>
          <div class="col-lg-6 form-group">  
            <div class="icheck-primary d-inline">
              <input type="radio" id="radioPrimary4" name="report" value="4">
              <label for="radioPrimary4">Assembly Part Check List</label>  
            </div>
          </div>
          <div class="col-lg-6 form-group">  
            <div class="icheck-primary d-inline">
              <input type="radio" id="radioPrimary5" name="report" value="5">
              <label for="radioPrimary5">Polling Booth Check List</label>  
            </div>
          </div> 
        </div> 
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" style="width: 360px">Generate</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: 360px">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Report</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <form action="{{ route('admin.Master.change.voter.with.ward.acpart.report.pdf') }}" method="post" target="blank">
        {{ csrf_field() }} 
        <div class="row"> 
          <div class="col-lg-6 form-group">
          <label>Report Type</label>
          <select class="form-control" name="report_type" required="required">
            <option value="0" selected disabled="">Select Report Type</option>
            <option value="1">Deleted (From Ward)</option>
            <option value="2">Added (To Ward)</option> 
          </select> 
          </div>
          <div class="col-lg-6 form-group">
          <label>Ward</label>
          <select name="ward" id="to_ward_select_box" class="form-control select2" required="required">
            <option value = "0" selected disabled>Select Ward</option> 
            @foreach ($WardVillages as $WardVillage) 
            <option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no or '' }}</option> 
            @endforeach 
          </select> 
          </div>
          <div class="col-lg-6 form-group">
            <button type="submit" class="btn btn-success form-control">Generate</button> 
          </div>
          <div class="col-lg-6 form-group">
            <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button> 
          </div>
        </div> 
      </form>
    </div>
  </div>
</div>


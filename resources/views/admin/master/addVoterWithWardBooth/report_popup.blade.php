<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Report</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <form action="{{ route('admin.Master.add.voter.with.ward.booth.report.pdf') }}" method="post" target="blank">
        {{ csrf_field() }} 
        <div class="row"> 
          <div class="col-lg-6 form-group">
          <label>Ward</label>
          <select name="ward" id="ward_select_box" required="required" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')">
            <option selected disabled>Select Ward</option> 
            @foreach ($WardVillages as $WardVillage) 
            <option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no or '' }}</option> 
            @endforeach 
          </select> 
          </div>
          <div class="col-lg-6">
          <label>Polling Booth</label>
          <select name="booth" class="form-control"  id="booth_select_box" required="required">
          <option selected disabled>Select Booth No.</option> 
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


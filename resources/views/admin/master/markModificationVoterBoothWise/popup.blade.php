<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Update</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <form action="{{ route('admin.mark.modification.voter.booth.store') }}" method="post" class="add_form" button-click="btn_close" select-triger="data_list">
        {{ csrf_field() }} 
        <div class="row">
          <div class="col-lg-4">
            <label>Ward No.</label>
            <select class="form-control" name="ward_id" required>
              <option selected disabled>Select Ward No.</option>
              @foreach ($WardVillages as $WardVillage)
                <option value="{{$WardVillage->id}}">{{$WardVillage->ward_no}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-4">
            <label>Booth No.</label>
            <select class="form-control" name="booth_id" required>
              <option selected disabled>Select Booth No.</option>
              @foreach ($booths as $booth)
                <option value="{{$booth->id}}">{{$booth->c_booth_no}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-4">
            <label>Sr.No.</label>
            <input type="text" name="sr_no" class="form-control" maxlength="5" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
          </div> 
          <input type="hidden" name="voter_id" value="{{$voter_id}}"> 
          <div class="modal-footer" style="margin-top: 10px">
            <button type="submit" class="btn btn-success" style="width: 360px">Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: 360px">Close</button>
          </div>
        </div> 
      </form>
    </div>
  </div>
</div>


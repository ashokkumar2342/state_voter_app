<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Report</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.change.voter.ward.with.booth.report.pdf') }}" method="post" target="blank">
                {{ csrf_field() }} 
                <div class="box-body"> 
                    <div class="form-group">
                        <label>Report Type</label>
                        <span class="fa fa-asterisk"></span>
                        <select class="form-control select2" name="report_type" required="required">
                            <option selected disabled>Select Report Type</option>
                            <option value="{{Crypt::encrypt(1)}}">Deleted (From Ward Booth)</option>
                            <option value="{{Crypt::encrypt(2)}}">Added (To Ward Booth)</option> 
                        </select> 
                    </div>
                    <div class="form-group">
                        <label>Ward</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="ward" id="ward_select_box" required="required" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')">
                            <option selected disabled>Select Ward</option> 
                            @foreach ($WardVillages as $WardVillage) 
                            <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no }}</option> 
                            @endforeach 
                        </select> 
                    </div>
                    <div class="form-group">
                        <label>Polling Booth</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="booth" class="form-control select2"  id="booth_select_box" required="required">
                            <option selected disabled>Select Booth No.</option> 
                        </select>
                    </div>
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Generate</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


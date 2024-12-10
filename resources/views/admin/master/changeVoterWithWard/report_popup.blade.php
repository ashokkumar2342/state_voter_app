<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Report</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.change.voter.with.ward.report.pdf') }}" method="post" target="blank">
                {{ csrf_field() }} 
                <div class="card-body"> 
                    <div class="form-group">
                        <label>Report Type</label>
                        <span class="fa fa-asterisk"></span>
                        <select class="form-control select2" name="report_type" required="required">
                            <option selected disabled="">Select Report Type</option>
                            <option value="{{Crypt::encrypt(1)}}">Deleted (From Ward)</option>
                            <option value="{{Crypt::encrypt(2)}}">Added (To Ward)</option> 
                        </select> 
                    </div>
                    <div class="form-group">
                        <label>Ward</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="ward" id="to_ward_select_box" class="form-control select2" required="required">
                            <option value = "0" selected disabled>Select Ward</option> 
                            @foreach ($WardVillages as $WardVillage) 
                            <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no or '' }}</option> 
                            @endforeach 
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


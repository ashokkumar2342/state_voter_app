<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.MappingWardBoothStore') }}" method="post" class="add_form"  select-triger="ward_select_box" button-click="btn_close">
                {{ csrf_field() }} 
                <div class="box-body">
                    <input type="hidden" name="rec_id" value="{{ Crypt::encrypt($BoothWardVoterMapping[0]->id) }}"> 
                    <input type="hidden" name="ward" value="{{ Crypt::encrypt($BoothWardVoterMapping[0]->wardId) }}"> 
                    <input type="hidden" name="booth" value="{{ Crypt::encrypt($BoothWardVoterMapping[0]->boothid) }}"> 
                    <div class="form-group">
                        <label>From Sr. No.</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="from_sr_no" id="from_sr_no" class="form-control" required onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="5" >   
                    </div>
                    <div class="form-group">
                        <label>To Sr. No.</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="to_sr_no" id="to_sr_no" class="form-control" required onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="5" >
                    </div>
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Update</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>  
        </div>
    </div>
</div>


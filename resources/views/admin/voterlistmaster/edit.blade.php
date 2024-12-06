<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.VoterListMaster.store', Crypt::encrypt($VoterListMaster[0]->id)) }}" method="post" class="add_form" content-refresh="voter_list_master" button-click="btn_close" select-triger="block_select_box">
                {{ csrf_field() }} 
                <div class="row">
                    <input type="text" name="block" class="form-control" placeholder="" hidden maxlength="5" value="{{ Crypt::encrypt($VoterListMaster[0]->block_id) }}">
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Voter List Name</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="voter_list_name" class="
                        form-control" maxlength="200" value="{{ $VoterListMaster[0]->voter_list_name }}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Voter List Type</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="voter_list_type" class="
                        form-control" maxlength="200" value="{{ $VoterListMaster[0]->voter_list_type }}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Publication Year</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="publication_year" class="form-control" maxlength="4" value="{{ $VoterListMaster[0]->year_publication }}" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Date of Publication</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="date_of_publication" class="form-control" maxlength="20" value="{{ $VoterListMaster[0]->date_publication }}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Base Year</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="base_year" class="form-control" maxlength="4" value="{{ $VoterListMaster[0]->year_base }}" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Base Date</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="base_date" class="form-control" maxlength="20" value="{{ $VoterListMaster[0]->date_base }}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">पुनरिक्षण का विवरण</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="remarks1" class="form-control" maxlength="100" value="{{ $VoterListMaster[0]->remarks1 }}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Remarks 1</label>
                        <input type="text" name="remarks2" class="form-control" maxlength="100" value="{{ $VoterListMaster[0]->remarks2 }}">
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Remarks 2</label>
                        <input type="text" name="remarks3" class="form-control" maxlength="100" value="{{ $VoterListMaster[0]->remarks3 }}">
                    </div>
                    @php
                    if ($VoterListMaster[0]->is_supplement==1) {
                        $checked='checked';
                    }else{
                        $checked='';
                    }
                    @endphp
                    <div class="col-lg-4 form-group">  
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="radioPrimary4" name="is_supplement" value="1" {{ $checked }}>
                            <label for="radioPrimary4">Is Supplement</label>  
                        </div>
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



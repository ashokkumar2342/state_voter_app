<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Create P.S. Ward</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.master.block.mcs.psWard.store', Crypt::encrypt($block_id)) }}" method="post" class="add_form" select-triger="district_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <h3>{{ @$Block_Name[0]->name_e }}</h3> 
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Panchyat Samiti Ward To Be Created</label>
                        <span class="fa fa-asterisk"></span> 
                        <input type="text" name="ps_ward" class="form-control" placeholder="" maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>               
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Submit</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


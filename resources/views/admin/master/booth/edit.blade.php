<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Polling Booth Update</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.boothStore',$booth[0]->id) }}" method="post" class="add_form" no-reset="true"  select-triger="village_select_box" button-click="btn_close">
                {{ csrf_field() }} 
                <div class="row"> 
                <input type="hidden" name="states" value="{{ $booth[0]->states_id }}"> 
                <input type="hidden" name="district" value="{{ $booth[0]->districts_id }}"> 
                <input type="hidden" name="block" value="{{ $booth[0]->blocks_id }}"> 
                <input type="hidden" name="village" value="{{ $booth[0]->village_id }}"> 
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Booth No.</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_no" id="booth_no" class="form-control" placeholder="" maxlength="5" value="{{ $booth[0]->booth_no }}" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Booth No. (Auxiliary)</label>
                         
                        <input type="text" name="booth_no_c" id="booth_no_c" class="form-control" placeholder="" maxlength="1" value="{{ $booth[0]->booth_no_c }}">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Booth Name (English)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_name_english" id="booth_name_english" class="form-control" placeholder="" maxlength="100" value="{{ $booth[0]->name_e }}" required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Booth Name (Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_name_local" id="booth_name_local" class="form-control" placeholder="" maxlength="100" value="{{ $booth[0]->name_l }}" required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input type="submit" class="form-control btn btn-primary" value="Update" style="margin-top: 30px">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>


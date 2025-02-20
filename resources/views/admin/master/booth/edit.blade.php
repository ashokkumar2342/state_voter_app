<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.booth.store', Crypt::encrypt($booth[0]->id)) }}" method="post" class="add_form" select-triger="village_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Booth No.</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_no" id="booth_no" class="form-control" placeholder="" maxlength="5" value="{{ $booth[0]->booth_no }}" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Booth No. (Auxiliary)</label>
                         
                        <input type="text" name="booth_no_c" id="booth_no_c" class="form-control" placeholder="" maxlength="1" value="{{ $booth[0]->booth_no_c }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Booth Name (English)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_name_english" id="booth_name_english" class="form-control" placeholder="" maxlength="100" value="{{ $booth[0]->name_e }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Booth Name (Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_name_local" id="booth_name_local" class="form-control" placeholder="" maxlength="100" value="{{ $booth[0]->name_l }}" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Booth Area (English) </label>
                        <input type="text" name="booth_area_english" id="booth_name_english" class="form-control" placeholder="" maxlength="250" value="{{ $booth[0]->booth_area_e }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Booth Area (Hindi)</label>
                        <input type="text" name="booth_area_local" id="booth_name_local" class="form-control" placeholder="" maxlength="250" value="{{ $booth[0]->booth_area_l }}">
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


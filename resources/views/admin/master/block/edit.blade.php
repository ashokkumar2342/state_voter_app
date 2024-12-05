<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.master.block.mcs.store', Crypt::encrypt($rec_id)) }}" method="post" class="add_form" select-triger="district_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                        <input type="hidden" name="states" value="{{ Crypt::encrypt($BlocksMcs[0]->states_id)}}">
                        <input type="hidden" name="district" value="{{ Crypt::encrypt($BlocksMcs[0]->districts_id) }}"> 
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="exampleInputEmail1">Block / MC's Code</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="code" class="form-control" placeholder="Enter Code" value="{{ $BlocksMcs[0]->code }}" maxlength="5" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="exampleInputPassword1">Block / MC's Name(English)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="name_english" class="form-control" placeholder="Enter Name (English)" value="{{ $BlocksMcs[0]->name_e }}" maxlength="50" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="exampleInputPassword1">Block / MC's Name(Hindi)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="name_local_language" class="form-control" placeholder="Enter Block Name (In Hindi)" value="{{ $BlocksMcs[0]->name_l }}" maxlength="100" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="exampleInputPassword1">Block / MC's Type</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="block_mc_type_id" id="block_mc_type" class="form-control select2">
                                <option selected disabled>Select Block / MC's Type</option>
                                @foreach ($BlockMCTypes as $BlockMCType)
                                <option value="{{ Crypt::encrypt($BlockMCType->id) }}"{{ $BlockMCType->id==$BlocksMcs[0]->block_mc_type_id?'selected': '' }}>{{ $BlockMCType->block_mc_type_e }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputPassword1">Stamp Line 1</label>

                            <input type="text" name="stamp_l1" id="stamp_l1" class="form-control" maxlength="100" value="{{ $BlocksMcs[0]->stamp_l1 }}" >
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputPassword1">Stamp Line 2</label>

                            <input type="text" name="stamp_l2" id="stamp_l2" class="form-control" maxlength="100" value="{{ $BlocksMcs[0]->stamp_l2 }}" >
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


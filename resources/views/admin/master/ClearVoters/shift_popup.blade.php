<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Shift Votes In Other Booth</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.clear.voters.shift.store') }}" method="post" class="add_form" select-triger="village_select_box" button-click="btn_close">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <input type="hidden" name="booth_ward_id" value="{{$booth_ward_id}}">
                    </div>

                    <div class="form-group">
                        <label>From Ward No. :: {{$ward_no}}</label>
                    </div>

                    <div class="form-group">
                        <label>From Booth No. :: {{$booth_no}}</label>
                    </div>

                    <div class="form-group">
                        <label>From Assembly-Part</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" required>
                            <option selected disabled >Select Assembly-Part</option>
                            @foreach ($assemblyParts as $assemblyPart)
                                <option value="{{ Crypt::encrypt($assemblyPart->id) }}">{{ $assemblyPart->opt_text }} - Voter Mapped :: {{ $assemblyPart->t_voters }}</option> 
                            @endforeach 
                        </select>
                    </div>

                    <div class="form-group">
                        <label>To Ward No.</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="ward" id="ward_select_box" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')" required>
                            <option selected disabled>Select Ward</option> 
                            @foreach ($WardVillages as $WardVillage)
                                @if ($WardVillage->lock==1)
                                @else  
                                    <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no or '' }}</option> 
                                @endif
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label>To Booth No.</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="booth" class="form-control select2" id="booth_select_box" required>
                            <option selected disabled>Select Booth No.</option> 
                        </select> 
                    </div>                                     
                </div>
                <div class="modal-footer card-footer justify-content-between">
                    <button type="submit" class="btn btn-success form-control">Shift</button>
                    <button type="button" class="btn btn-danger form-control" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


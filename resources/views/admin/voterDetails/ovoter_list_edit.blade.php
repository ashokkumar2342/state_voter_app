<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit</h4>
      <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="{{ route('admin.voter.voterUpdate',$voterlist->id) }}" method="post" class="add_form">
                {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                             <div class="col-lg-6 form-group">
                                 <label for="exampleInputEmail1">Name (English)</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="name_english" id="name_english" class="form-control" value="{{ $voterlist->name_e }}">
                             </div>
                             <div class="col-lg-6 form-group" id="name_local_language">
                                 <label for="exampleInputEmail1">Name (Local Language)</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="name_local_language"  class="form-control" value="{{ $voterlist->name_l }}">
                             </div>
                             <div class="col-lg-4 form-group">
                                 <label for="exampleInputEmail1">Relation</label>
                                 <span class="fa fa-asterisk"></span>
                                 <select name="relation" id="relation" class="form-control">
                                     <option selected disabled>Select Relation</option>
                                     @foreach ($Relations as $Relation)
                                     <option value="{{ $Relation->id }}"{{ $Relation->id==$voterlist->relation?'selected':'' }}>{{ $Relation->relation_e }}-{{ $Relation->relation_l }}</option> 
                                     @endforeach 
                                 </select>
                             </div>
                             <div class="col-lg-4 form-group">
                                 <label for="exampleInputEmail1">F/H Name (English)</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="f_h_name_english" id="f_h_name_english" class="form-control" value="{{ $voterlist->father_name_e }}">
                             </div>
                             <div class="col-lg-4 form-group" id="f_h_name_local_language">
                                 <label for="exampleInputEmail1">F/H Name (Local Language)</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="f_h_name_local_language" class="form-control" value="{{ $voterlist->father_name_l }}">
                             </div> 
                             <div class="col-lg-6 form-group">
                                 <label for="exampleInputEmail1">House No.(English)</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="house_no_english" id="house_no_english" class="form-control" value="{{ $voterlist->house_no_e }}">
                             </div>
                             <div class="col-lg-6 form-group" id="house_no_local_language">
                                 <label for="exampleInputEmail1">House No.(Local Language)</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="house_no_local_language" class="form-control" value="{{ $voterlist->house_no_l }}">
                             </div>
                             <div class="col-lg-3 form-group">
                                 <label for="exampleInputEmail1">Gender</label>
                                 <span class="fa fa-asterisk"></span>
                                 <select name="gender" class="form-control" id="gender">
                                     <option selected disabled>Select Gender</option>
                                     @foreach ($genders as $gender)
                                     <option value="{{ $gender->id }}"{{ $gender->id==$voterlist->gender_id?'selected':'' }}>{{ $gender->genders }}-{{ $gender->genders_l }}</option>  
                                     @endforeach
                                 </select>
                             </div> 
                             <div class="col-lg-3 form-group" id="age_value_div">
                                 <label for="exampleInputEmail1">Age</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="age" id="age" class="form-control" value="{{ $voterlist->age }}">
                             </div>
                             <div class="col-lg-3 form-group">
                                 <label for="exampleInputEmail1">Voter ID No.</label>
                                 <span class="fa fa-asterisk"></span>
                                 <input type="text" name="voter_id_no" id="voter_id_no" class="form-control" value="{{ $voterlist->voter_card_no }}">
                             </div>
                             <div class="col-lg-3 form-group">
                                 <label for="exampleInputEmail1">Mobile No.</label> 
                                 <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{ $voterlist->mobile_no }}">
                             </div>
                             
                        </div>                     
                                        
                    <!-- /.box-body -->
                    <div class="box-footer text-center" style="margin-top: 30px">
                      <button type="submit" class="btn btn-primary form-control">Update</button>
                    </div>
                </form>
    </div>
  </div>
</div>


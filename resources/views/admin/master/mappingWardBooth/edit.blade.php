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
            <div class="row">
      <input type="hidden" name="id" value="{{ $BoothWardVoterMapping[0]->id }}"> 
      <input type="hidden" name="ward" value="{{ $BoothWardVoterMapping[0]->wardId }}"> 
      <input type="hidden" name="booth" value="{{ $BoothWardVoterMapping[0]->boothid }}"> 
               <div class="col-lg-6">
                 <label>From Sr.No.</label>
                 <input type="text" name="from_sr_no" id="from_sr_no" class="form-control">   
               </div>
               <div class="col-lg-6">
                 <label>To Sr.No.</label>
                 <input type="text" name="to_sr_no" id="to_sr_no" class="form-control">
               </div>
            </div>   
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-success form-control">Update</button>
           
        </div>
      
    </div>
  </div>
</div>


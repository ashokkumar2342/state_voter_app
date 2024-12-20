<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Report Type</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.Master.WardBandiReportGenerate') }}" method="post" target="blank">
                {{ csrf_field() }} 
                <div class="card-body">
                    <input type="hidden" name="village" value="{{ $village }}">
                    <input type="hidden" name="assembly_part" value="{{ $assembly_part }}">
                    <input type="hidden" name="ward" value="{{ $ward }}">
                    <div class="row"> 
                        <div class="col-lg-4 form-group"> 
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPrimary1" name="report" value="1" checked>
                                <label for="radioPrimary1">Booth Check List</label>  
                            </div>
                        </div>
                        <div class="col-lg-4 form-group"> 
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPrimary2" name="report" value="2">
                                <label for="radioPrimary2">Voter Not Mapped</label>  
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">  
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPrimary3" name="report" value="3">
                                <label for="radioPrimary3">Village/Voter Check List</label>  
                            </div>
                        </div>
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


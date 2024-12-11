<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                       	<th>Note Sr.No.</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($voterSlipNotes as $voterSlipNote)                    
                        <tr>
                            <td>{{ $voterSlipNote->note_srno }}</td>
                            <td>{{ $voterSlipNote->note_text }}</td>                            
                            <td>
                                <button type="button" onclick="callPopupLarge(this,'{{ route('admin.Master.voter.slip.notes.edit', Crypt::encrypt($voterSlipNote->id)) }}')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                                             
                                <button type="button" class="btn btn-sm btn-danger" select-triger="district_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.voter.slip.notes.delete', Crypt::encrypt($voterSlipNote->id)) }}')"><i class="fa fa-trash"></i> Delete</button>
                            </td>                            
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>
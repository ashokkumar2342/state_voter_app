<div class="col-lg-12"> 
<div class="card card-info">
  <div class="card-header">
     <h3 class="card-title"></h3>
    </div> 
    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped" id="voter_list_table">
        <thead>
          <tr>
            <th>Sr.No</th>
            <th>EPIC No. </th>
            <th>Name </th>
            <th>F/H Name</th>
            <th>Village</th>
            <th>Ward</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($voterLists as $voterList)
            <tr>
              <td>{{ $voterList->sr_no }}</td>
              <td>{{ $voterList->voter_card_no }}</td>
              <td>{{ $voterList->name_e }}</td>
              <td>{{ $voterList->father_name_l }}</td>
              <td>{{ $voterList->vil_name }}</td>
              <td>{{ $voterList->ward_no }}</td>
              <td>
                @if($voterList->village_id>0)
                <a href="#" onclick="callAjax(this,'{{ route('admin.Master.removeVoter_wardbandi',$voterList->id) }}')" 
                   @if ($refreshdata == 1)
                                select-triger="assembly_part_select_box"
                            @endif
                            success-popup="true" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                @endif
              </td>
            </tr> 
          @endforeach
        </tbody>
      </table>
        
    </div>
  </div>
</div>
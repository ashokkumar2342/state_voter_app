<div class="card card-primary table-responsive"> 
<div class="row">
<div class="col-lg-6"> 
    <table id="voter_datatable" class="table table-striped table-hover control-label">
        <thead>
            <tr> 
                <th>Sr.No.</th>
                <th>Name</th>
                <th>F/H Name</th>
                <th>Voter Card No.</th> 
                <th>Action</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($voters as $voter)
            @if ($voter->status!=2) 
            <tr> 
                <td>{{ $voter->sr_no }}</td>
                <td>{{ $voter->name_e }}</td>
                <td>{{ $voter->father_name_e }}</td>
                <td>{{ $voter->voter_card_no }}</td> 
                <td>
                    <a success-popup="true" button-click="btn_show" onclick="callAjax(this,'{{ route('admin.voter.DeteleAndRestoreDetele',$voter->id) }}')" title="" class="btn btn-xs btn-danger"style="color:white">Delete</a> 
                </td> 
            </tr>
            @endif 
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-lg-6"> 
    <table id="voter_datatable2" class="table table-striped table-hover control-label">
        <thead>
            <tr> 
                <th>Sr.No.</th>
                <th>Name</th>
                <th>F/H Name</th>
                <th>Voter Card No.</th> 
                <th>Action</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($voters as $voter)
            @if ($voter->status==2) 
            <tr> 
                <td>{{ $voter->sr_no }}</td>
                <td>{{ $voter->name_e }}</td>
                <td>{{ $voter->father_name_e }}</td>
                <td>{{ $voter->voter_card_no }}</td> 
                <td>
                     
                    <a success-popup="true" button-click="btn_show" onclick="callAjax(this,'{{ route('admin.voter.DeteleAndRestoreRestore',$voter->id) }}')" title="" class="btn btn-xs btn-primary" style="color:white">Restore</a>
                     
                </td> 
            </tr>
            @endif 
            @endforeach
        </tbody>
    </table>
</div> 
</div>
</div> 

<div class="row">  
    <div class="col-lg-12">
        <button type="button" style="width: 100px; margin-bottom: 10px;background-color: #c3cad1" class="btn btn-block pull-right" onclick="callAjax(this,'{{ route('admin.prepare.voter.slip.download.result') }}'+'?block_id='+$('#block_select_box').val(),'download_table')"><i class="fa fa-refresh"></i> <b>Refresh</b></button>
        <table class="table-striped table-bordered table">
            <thead>
                <tr>
                    
                    <th>Village</th>
                    <th>Ward</th> 
                    <th>Booth</th> 
                    <th>Submit Time</th>
                    <th>Expected Time to Process</th>
                    <th class="text-nowrap">Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voterlistprocesseds as $voterlistprocessed)
                <tr> 
                    <td>{{ $voterlistprocessed->name_e}}</td>
                    <td>{{ $voterlistprocessed->ward_no}}</td> 
                    <td>{{ $voterlistprocessed->booth_no}}</td> 
                    <td>{{ $voterlistprocessed->submit_time}}</td>
                    <td>{{ $voterlistprocessed->expected_time_start}}</td>
                    @if ($voterlistprocessed->status==1)
                        <td><a target="_blank" href="{{ route('admin.prepare.voter.slip.result.download',[$voterlistprocessed->id]) }}" title="">Download</a></td>
                    @elseif ($voterlistprocessed->status==2)
                        <td>Processing</td>
                    @else
                        <td>Pending</td>
                    @endif
                </tr>                                     
                @endforeach
            </tbody>
        </table>
    </div>
</div>    
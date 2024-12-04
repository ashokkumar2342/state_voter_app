
<div class="row">  
    <div class="col-lg-12"> 
        <table class="table-striped table-bordered table">
            <thead>
                <tr class="thead-dark">
                    
                    <th>Village</th>
                    <th>Ward</th> 
                    <th>Booth</th> 
                    <th>Report Type</th>
                    <th>Submit Time</th>
                    <th>Expected Time to Process</th>
                    <th class="text-nowrap">Download With Photo</th>
                    <th class="text-nowrap">Download Without Photo</th>
                    <th class="text-nowrap">Download Annexure-A</th>  
                </tr>
            </thead>
            <tbody>
                @foreach ($voterlistprocesseds as $voterlistprocessed)
                <tr> 
                    <td>{{ $voterlistprocessed->name_e}}</td>
                    <td>{{ $voterlistprocessed->ward_no}}</td> 
                    <td>{{ $voterlistprocessed->booth_no}}</td> 
                    <td>{{ $voterlistprocessed->report_type}}</td>
                    <td>{{ $voterlistprocessed->submit_time}}</td>
                    <td>{{ $voterlistprocessed->expected_time_start}}</td>
                    @if ($voterlistprocessed->status==1)
                        <td><a target="_blank" href="{{ route('front.download',[$voterlistprocessed->id,'p']) }}" title="">Download</a></td>
                        <td><a target="_blank" href="{{ route('front.download',[$voterlistprocessed->id,'w']) }}" title="">Download</a></td>
                        <td><a target="_blank" href="{{ route('front.download',[$voterlistprocessed->id,'h']) }}" title="">Download</a></td>
                    @elseif ($voterlistprocessed->status==2)
                        <td>Processing</td>
                        <td>Processing</td>
                        <td>Processing</td>
                    @else
                        <td>Pending</td>
                        <td>Pending</td>
                        <td>Pending</td>
                    @endif
                </tr>                                     
                @endforeach
            </tbody>
        </table>
    </div>
</div>    
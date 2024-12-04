
<div class="row">  
    <div class="col-lg-12">
        <table class="table-striped table-bordered table">
            <thead>
                <tr>
                    
                    <th>Assembly</th>
                    <th class="text-nowrap">Download Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voterlistprocesseds as $voterlistprocessed)
                <tr> 
                    <td>{{ $voterlistprocessed->code}} - {{ $voterlistprocessed->name_e}}</td>
                    @if ($voterlistprocessed->status==1)
                        <td><a target="_blank" href="{{ route('admin.voter.VidhanSabhaListDownloadPDF',[$voterlistprocessed->id]) }}" title="">Download</a></td>
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
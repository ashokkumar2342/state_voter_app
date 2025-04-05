
<div class="row">  
    <div class="col-lg-12"> 
        <table class="table-striped table-bordered table">
            <thead>
                <tr class="thead-dark">
                    
                    <th>Village</th>
                    <th>Ward</th> 
                    <th>Booth</th> 
                    <th class="text-nowrap">Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voterlistprocesseds as $voterlistprocessed)
                <tr> 
                    <td>{{ $voterlistprocessed->name_e}}</td>
                    <td>{{ $voterlistprocessed->ward_no}}</td> 
                    <td>{{ $voterlistprocessed->booth_no}}</td> 
                    <td><a target="_blank" href="{{ route('front.download',Crypt::encrypt($voterlistprocessed->id)) }}" title="">Download</a></td>
                </tr>                                     
                @endforeach
            </tbody>
        </table>
    </div>
</div>    
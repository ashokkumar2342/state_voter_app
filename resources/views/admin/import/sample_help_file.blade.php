<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table table-striped table-bordered" id="district_sample_table">
        <thead>
            <tr>
                <th>Sample File</th>
                <th>Help File</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($sampleHelpFiles as $sampleHelpFile) 
            <tr>
                <td>
                <a href="{{ asset($sampleHelpFile->sample_file) }}"><i class="fa fa-download"></i>{{$sampleHelpFile->sample_file}}</a>
                </td>
                <td>
                <a href="{{ asset($sampleHelpFile->help_file) }}"><i class="fa fa-download"></i>{{$sampleHelpFile->help_file}}</a>
                </td> 
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div> 
<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive"> 
            <table class="table table-bordered table-striped table-hover" id="example1" width = "100%">
                <thead style="background-color: #6c757d;color: #fff">
                    <tr>
                        @php
                        $counter = 0;
                        while ($counter < $tcols ){
                            @endphp
                            <th width = "{{ $qcols[$counter][1] }}%">{{ $qcols[$counter][0] }}</th>
                            @php
                            $counter = $counter+1;
                        }
                        @endphp
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rs_result as $rs_row)
                    <tr>
                        @foreach ($rs_row as $value)
                        <td>{!! $value !!}</td>  
                        @endforeach
                    </tr> 
                    @endforeach
                </tbody>

            </table>
        </div>
    </fieldset>
</div>
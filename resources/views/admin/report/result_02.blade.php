<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive"> 
            <table class="table table-bordered table-striped table-hover" id="example1" width = "100%">
                <thead style="background-color: #6c757d;color: #fff">
                    <tr>
                        <th width = "5%">Sr. No.</th>
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
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($rs_result as $rs_row)
                        <tr>
                            <td style="text-align:center;">{{$counter++}}</td>
                            @php
                                $col_counter = 0;
                            @endphp
                            @foreach ($rs_row as $value)
                                <td style="text-align:{{$qcols[$col_counter++][5]}};">{!! $value !!}</td>  
                            @endforeach
                        </tr>
                    @endforeach
                    @if ($show_total_row == 1)
                        <tr>
                            <td style="text-align:center;">{{$counter++}}</td>
                            @php
                                $counter = 0;
                                while ($counter < $tcols ){
                            @endphp
                            <td style="text-align:{{$qcols[$counter][5]}};">{{ $qcols[$counter][4] }}</td>
                            @php
                                $counter = $counter+1;
                                }
                            @endphp
                        </tr>
                    @endif
                        
                </tbody>

            </table>
        </div>
    </fieldset>
</div>
<div class="col-lg-12 table-responsive"> 
    <table class="table table-bordered table-striped table-hover">
        <thead class="bg-gray">
            <tr>
                <th>District</th>
                <th>Assembly</th>
                <th>Part No.</th>
                <th>Sr. No. In Part</th>
                <th>Epic No.</th>
                <th>House No.</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rs_result as $rs_value)
            <tr>
                <td>{{$rs_value->d_name}}</td>
                <td>{{$rs_value->ac_name}}</td>
                <td>{{$rs_value->ac_name}}</td>
                <td>{{$rs_value->sr_no}}</td>
                <td>{{$rs_value->voter_card_no}}</td>
                <td>{{$rs_value->house_no_e}}</td>
                <td>
                    @php
                        $image_path = 'vimage/'.$rs_value->data_list_id.'/'.$rs_value->assembly_id.'/'.$rs_value->assembly_part_id.'/'.$rs_value->sr_no.'.jpg';
                    @endphp
                    <img src="{{ route('admin.Master.pollingDayTimesignature',Crypt::encrypt($image_path)) }}" class="img-circle elevation-2" alt="User Image" style="height: 110px;width: 150;">                    
                </td>
            </tr> 
            @endforeach
        </tbody>
    </table>
</div>
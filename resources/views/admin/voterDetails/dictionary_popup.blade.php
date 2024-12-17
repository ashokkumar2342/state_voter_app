<div class="modal-dialog_1">
    <div class="modal-content level2">
        <div class="modal-header">
            <h4 class="modal-title">Dictionary</h4>
            <button type="button" id="btn_close" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    @if ($condition_type == 1)
                        Name (English) :: {{$name_english}}
                    @elseif($condition_type == 2)
                       F/H Name (English) :: {{$name_english}}
                    @elseif($condition_type == 3)
                       House No. (English):: {{$name_english}}
                    @endif
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-lg-12 table-responsive"> 
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-gray">
                            <tr>
                                <th>
                                    @if ($condition_type == 1)
                                        Name (Hindi)
                                    @elseif($condition_type == 2)
                                       F/H Name (Hindi)
                                    @elseif($condition_type == 3)
                                       House No. (Hindi)
                                    @endif
                                </th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_result as $value)
                            <tr>
                                <td>{{$value->name_l}}</td>  
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" success-popup="true" onclick="EmpNameFill('{{$name_english}}', '{{$condition_type}}')">Apply</button>
                                </td>  
                            </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<table id="block_datatable" class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name (English)</th>
            <th>Name (Hindi)</th>
            <th>Block / MC's Type</th>
            <th>Total P.S. Ward</th>
            <th>Action</th>
             
        </tr>
    </thead>
    <tbody>
        @foreach ($BlocksMcs as $BlockMc)
        <tr>
            <td>{{ $BlockMc->code }}</td>
            <td>{{ $BlockMc->name_e }}</td>
            <td>{{ $BlockMc->name_l }}</td>
            <td>{{ $BlockMc->block_mc_type_e}}</td>
            <td>{{ $BlockMc->pscount}}</td>
            <td class="text-nowrap">
                <a onclick="callPopupLarge(this,'{{ route('admin.Master.BlockMCSpsWard',$BlockMc->id) }}')" title="" class="btn btn-primary btn-xs" style="color: #fff">Add P.S Ward</a>
                <a onclick="callPopupLarge(this,'{{ route('admin.Master.BlockMCSEdit',$BlockMc->id) }}')" title="" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                <a href="{{ route('admin.Master.BlockMCSDelete',Crypt::encrypt($BlockMc->id)) }}" onclick="return confirm('Are you sure you want to delete this item?');"  title="" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
            </td>
        </tr> 
       @endforeach
    </tbody>
</table>
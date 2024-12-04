<table class="table table-striped table-bordered" id="voter_list_master">
               <thead>
                 <tr>
                   <th class="text-nowrap">Voter List Name</th>
                   <th class="text-nowrap">Voter List Type</th>
                   <th class="text-nowrap">Year Publication</th>
                   <th class="text-nowrap">Date Publication</th>
                   <th class="text-nowrap">Year Base</th>
                   <th class="text-nowrap">Date Base</th>
                   <th class="text-nowrap">Remarks</th>
                   <th class="text-nowrap">Action</th>
                 </tr>
               </thead>
               <tbody>
                @foreach ($VoterListMasters as $VoterListMaster)
                 <tr style="{{ $VoterListMaster->status==1?'background-color: #95e49b':'' }}">
                   <td>{{ $VoterListMaster->voter_list_name }}</td>
                   <td>{{ $VoterListMaster->voter_list_type }}</td>
                   <td>{{ $VoterListMaster->year_publication }}</td>
                   <td>{{ $VoterListMaster->date_publication }}</td>
                   <td>{{ $VoterListMaster->year_base }}</td>
                   <td>{{ $VoterListMaster->date_base }}</td>
                   <td>{{ $VoterListMaster->remarks1 }}</td>
                   <td class="text-nowrap">
                    @if ($VoterListMaster->status==1)
                     <a href="#" select-triger="block_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.VoterListMaster.default',$VoterListMaster->id) }}')" title="" class="btn btn-success btn-xs">Default</a>
                     @else
                     <a href="#" select-triger="block_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.VoterListMaster.default',$VoterListMaster->id) }}')" title="" class="btn btn-default btn-xs">Default</a> 
                    @endif
                    <a href="#" title="Edit" class="btn btn-xs btn-info" onclick="callPopupLarge(this,'{{ route('admin.VoterListMaster.edit',$VoterListMaster->id) }}')"><i class="fa fa-edit"></i></a>
                   </td>
                 </tr>
                 @endforeach
               </tbody>
             </table>
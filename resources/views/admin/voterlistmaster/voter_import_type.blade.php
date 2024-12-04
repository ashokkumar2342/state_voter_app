@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Voter Import Type</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.Voter.import.type.store') }}" method="post" no-reset="true" class="add_form" no-reset="true" content-refresh="voter_import_type">
                {{ csrf_field() }} 
                <div class="row"> 
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Import Description</label>
                          <input type="text" name="description" class="
                          form-control" maxlength="200" required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Import Date</label>
                          <input type="date" name="date" class="
                          form-control" maxlength="200" required>
                    </div>
                    
                    <div class="col-lg-12 form-group">                        
                          <input type="submit" class="form-control btn-success" style="margin-top: 30px">
                    </div>
                  </div> 
                
            </form>
            <table class="table table-striped table-bordered" id="voter_import_type">
               <thead>
                 <tr>
                   <th class="text-nowrap">Import Description</th>
                   <th class="text-nowrap">Import Date</th> 
                   <th class="text-nowrap">Action</th>
                 </tr>
               </thead>
               <tbody>
                @foreach ($importTypes as $importTypes)
                 <tr style="{{ $importTypes->status==1?'background-color: #95e49b':'' }}">
                   <td>{{ $importTypes->description }}</td>
                   <td>{{ date('d-m-Y' , strtotime($importTypes->date)) }}</td>
                   
                   <td class="text-nowrap">
                    @if ($importTypes->status==1)
                     <a href="{{ route('admin.voterImportType.default',$importTypes->id) }}" select-triger="block_select_box" success-popup="true" {{-- onclick="callAjax(this,'{{ route('admin.voterImportType.default',$importTypes->id) }}')" --}} title="" class="btn btn-success btn-xs">Default</a>
                     @else
                     <a href="{{ route('admin.voterImportType.default',$importTypes->id) }}" select-triger="block_select_box" success-popup="true" {{-- onclick="callAjax(this,'{{ route('admin.voterImportType.default',$importTypes->id) }}')" --}} title="" class="btn btn-default btn-xs">Default</a> 
                    @endif
                    <a href="#" title="Edit" class="btn btn-xs btn-info" onclick="callPopupLarge(this,'{{ route('admin.voterImportType.edit',$importTypes->id) }}')"><i class="fa fa-edit"></i></a>
                   </td>
                 </tr>
                 @endforeach
               </tbody>
             </table>
          </div> 
        </div>
    </div> 
</section>
@endsection 
@push('scripts')  
 

@endpush
  




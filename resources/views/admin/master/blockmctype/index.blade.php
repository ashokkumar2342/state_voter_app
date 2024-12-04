@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Block / MC's Types</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row"> 
                    <div class="col-lg-12">
                        <div class="card card-primary table-responsive"> 
                             <table id="gender_table" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr> 
                                         <th>Block / MC's Type (English)</th>
                                         <th>Block / MC's Type (Hindi)</th> 
                                         <th>Action</th>
                                          
                                     </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($BlockMCTypes as $BlockMCType)
                                     <tr>                                     
                                         
                                         <td>{{ $BlockMCType->block_mc_type_e }}</td>
                                         <td>{{ $BlockMCType->block_mc_type_l }}</td> 
                                         <td>
                                             <button type="button" class="btn-info btn-xs btn" onclick="callPopupLarge(this,'{{ route('admin.Master.BlockMCSTypeEdit',$BlockMCType->id) }}')">Edit</button>
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
    </section>
    @endsection
     

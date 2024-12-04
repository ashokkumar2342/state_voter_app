@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Update Gender</h3>
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
                                         <th>Name (English)</th>
                                         <th>Name (Hindi)</th>
                                         <th>Code (English)</th>
                                         <th>Code (Hindi)</th>
                                         <th>Action</th>
                                          
                                     </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($genders as $gender)
                                     <tr>                                     
                                         
                                         <td>{{ $gender->genders }}</td>
                                         <td>{{ $gender->genders_l }}</td>
                                         <td>{{ $gender->code }}</td>
                                         <td>{{ $gender->code_l }}</td>
                                         <td>
                                             <button type="button" class="btn-info btn-xs btn" onclick="callPopupLarge(this,'{{ route('admin.Master.gender.edit',$gender->id) }}')">Edit</button>
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
     

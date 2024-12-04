@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Role Quick Link</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div>
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.roleAccess.quick.role.menu.store') }}" select-triger="role_select_box" method="post" class="add_form form-horizontal" no-reset="true" >
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('role','Role',['class'=>' control-label']) }}                         
                              <div class="form-group">  
                                     <select class="form-control" id="role_select_box"  data-table-without-pagination-disable-sorting="menu_role_table" multiselect-form="true"  name="role"  onchange="callAjax(this,'{{route('admin.account.role.default.quickMenu')}}'+'?id='+this.value,'menu_list')" > 
                                      <option value="" disabled selected>Select User</option>
                                     @foreach ($roles as $role)
                                          <option value="{{ $role->id }}">{{ $role->name }}</option> 
                                      @endforeach  
                                     </select>  
                                </div>
                            </div> 
                        </div>
                        <div class="col-lg-12" id="menu_list">
                             
                        </div>               
                    </div>
                </form>  
            </div> 
        </div>
    </div> 
</section>
@endsection 

 
         
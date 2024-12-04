@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Role Permission</h3>
            </div> 
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.roleAccess.subMenu.save') }}" call-back="triggerSelectBox" method="post" class="add_form form-horizontal" no-reset="true" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label>Role</label>
                        <select class="form-control" id="role_select_box" data-table-without-pagination-disable-sorting="menu_role_table" multiselect-form="true"  name="role"  onchange="callAjax(this,'{{route('admin.account.roleMenuTable')}}'+'?id='+this.value,'menu_list')" > 
                          <option value="" disabled selected>Select User</option>
                         @foreach ($roles as $role)
                              <option value="{{ $role->id }}">{{ $role->name }}</option> 
                          @endforeach  
                         </select> 
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


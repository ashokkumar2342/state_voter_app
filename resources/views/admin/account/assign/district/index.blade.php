@extends('admin.layout.base')
@section('body')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3>District Assign</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right"> 
        </ol>
      </div>
    </div> 
    <div class="card card-info"> 
      <div class="card-body">
        <form action="{{ route('admin.Master.DistrictsAssignStore') }}" no-reset="true" method="post" class="add_form" select-triger="user_id,">
          {{ csrf_field() }} 
          <div class="row"> 
            <div class="col-md-12"> 
              {{ Form::label('User','Users',['class'=>' control-label']) }}
              <select class="form-control select2"  duallistbox="true" data-table-all-record="class_section_list" name="user" id="user_id"  onchange="callAjax(this,'{{route('admin.account.StateDistrictsSelect')}}'+'?id='+this.value,'state_select_box')" > 
                <option value="" disabled selected>Select User</option>
                @foreach ($users as $user)
               
                <option value="{{ $user->id }}">{{ $user->email }} &nbsp;&nbsp;&nbsp;&nbsp;( {{ $user->first_name }} )</option>
              
                @endforeach  
              </select> 
              <p class="text-danger">{{ $errors->first('user') }}</p>
            </div> 
            <div class="col-lg-12" id="state_select_box"> 
            </div> 
          </form>           
        </div> 
      </div>
    </div>
  </section>
  @endsection 


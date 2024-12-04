@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Quick Links</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.userAccess.hotMenuAdd') }}" method="post" class="add_form form-horizontal" no-reset="true" select-triger="user_select_box" accept-charset="utf-8"> 
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-12">
                            {{ Form::label('User','Users',['class'=>' control-label']) }}                         
                            <div class="form-group">  
                                <select class="form-control select2" id="user_select_box"  multiselect-form="true"  name="user"  onchange="callAjax(this,'{{route('admin.account.access.hotmenuTable')}}'+'?id='+this.value,'menu_list')" > 
                                    <option value="" disabled selected>Select User</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->email }} &nbsp;&nbsp;&nbsp;&nbsp;( {{ $user->first_name }} )</option> 
                                    @endforeach  
                                </select> 
                            </div>
                        </div> 
                        <div class="col-lg-12" id="menu_list">
                        </div>   
                    </form>
                </div>
            </div>
        </div>


    </section>
    @endsection 


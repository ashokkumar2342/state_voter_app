@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Voter List Default Value</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.VoterListMaster.VoterListDefaultValueStore',@$VoterListDefaultValue->id) }}" method="post" class="add_form" no-reset="true">
                    {{ csrf_field() }} 
                    <div class="row">
                        @php
                        if (@$VoterListDefaultValue->page_break==1){ 
                         $checked='checked';
                        }else{
                          $checked='';  
                        }
                        if (@$VoterListDefaultValue->page_blank==1){ 
                         $checkedBlank='checked';
                        }else{
                          $checkedBlank='';  
                        }
                        @endphp
                        <div class="col-lg-2 text-center" style="margin-top: 30px">  
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="radioPrimary3" name="page_break" value="1" {{ $checked }}>
                          <label for="radioPrimary3">Page Break</label>  
                        </div>
                       </div>
                        <div class="col-lg-2 text-center" style="margin-top: 30px">  
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="radioPrimary4" name="page_blank" value="1" {{ $checkedBlank }}>
                          <label for="radioPrimary4">Page Blank</label>  
                        </div>
                      </div>    
                    </div>
                    <div class="col-lg-12 form-group">                        
                        <input type="submit" class="form-control btn-success" style="margin-top: 30px">
                    </div>
                </form> 
            </div> 
        </div>
    </div> 
</section>
@endsection 
@push('scripts') 
@endpush





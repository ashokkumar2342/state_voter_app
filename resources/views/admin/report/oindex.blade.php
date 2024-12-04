@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Report</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.report.PrintVoterListGenerate') }}" method="post" target="blank">
                {{ csrf_field() }} 
                <div class="row">
                    <div class="col-lg-3 form-group"> 
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary1" name="report" value="1" checked>
                        <label for="radioPrimary1">Part Wise--Voter Mapped</label> 
                      </div>
                    </div><div class="col-lg-3 form-group"> 
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" name="report" value="2">
                        <label for="radioPrimary2">Part Wise--Village Mapped</label> 
                      </div>
                    </div><div class="col-lg-3 form-group"> 
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary3" name="report" value="3">
                        <label for="radioPrimary3">Village--Part Wise Mapped</label> 
                      </div>
                    </div><div class="col-lg-3 form-group"> 
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary4" name="report" value="4">
                        <label for="radioPrimary4">Village Ward Wise--Voter</label> 
                      </div>
                    </div>
                    <div class="col-lg-12 text-center form-group">
                     <input type="submit" class="btn btn-primary form-control" style="margin-top: 20px" value="Report Generate">  
                    </div>
                </div>
            </form>
            </div> 
        </div>
    </div> 
</section>
@endsection




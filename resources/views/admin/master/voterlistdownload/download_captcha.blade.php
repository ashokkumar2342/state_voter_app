@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Validate Captcha</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.voter.VoterListDownloadPDF',[$rec_id, $condition]) }}" method="post">
                {{ csrf_field() }}                   
                    <div class="row">
                        <div class="col-lg-3 form-group captcha">                            
                            <span>{!! captcha_img('math') !!}</span>
                            <button type="button" class="btn btn-default" onclick="window.location.reload();"> <i class="fas fa-1x fa-sync-alt" ></i> </button>
                        </div>
                        <div class="col-lg-3">
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"> 
                            <p class="text-danger">{{ $errors->first('captcha') }}</p>
                        </div>
                        <div class="col-lg-6 form-group">
                            <input type="submit" class="btn btn-primary form-control" value="Download">
                        </div>
                    </div>
                </form>  
            </div>
        </div>   
    </div>
</section>
@endsection



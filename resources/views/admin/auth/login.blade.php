
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Voter List Management | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome --> 
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin_asset/dist/css/AdminLTE.min.css')}}"> 
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<style>
  .site-logo img {

    width: 100%; 
    border-bottom: 5px groove #6495ED;
}
.login-page1 {
    -ms-flex-align: center;
    align-items: center;
    background: #e9ecef;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column; 
    -ms-flex-pack: center;
    justify-content: center;
}
</style>
<body class="hold-transition login-page1">
  <div class="header-top">
            <div class="row">
                <div class="site-logo img-responsive">
                    <img src="{{ asset('images/voter_managenet_banner.png') }}" alt="voter_managenet_banner"/>
                </div>
            </div>
            <br />
          </div>
<div class="login-box">
  <div class="login-logo">
   <b style="color: red;font-size: 30px"></b>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="{{ route('admin.login') }}" method="post" class="add_form">
        {{ csrf_field() }}
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p class="text-danger">{{ $errors->first('email') }}</p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <p class="text-danger">{{ $errors->first('password') }}</p>
        <div class="captcha">
         <span>{!! captcha_img('math') !!}</span>
         <button type="button" class="btn btn-success" id="refresh"><i class="fas fa-1x fa-sync-alt" ></i></button>
       </div>
       <div class="input-group mb-3" style="margin-top: 5px">
          <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"> 
          <div class="input-group-append">
            <div class="input-group-text">
               
            </div>
          </div>
        </div>
        <p class="text-danger">{{ $errors->first('captcha') }}</p>
        <div class="row"> 
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <div class="col-12">
            <a href="{{ url('/') }}">Home</a>
          </div>
          <!-- /.col -->
        </div>
      </form> 
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery --> 
<script src="{{ asset('admin_asset/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('admin_asset/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
<script type="text/javascript">
$('#refresh').click(function(){
  $.ajax({
     type:'GET',
     url:'{{ route('admin.refresh.captcha') }}',
     success:function(data){
        $(".captcha span").html(data);
     }
  });
});
</script>

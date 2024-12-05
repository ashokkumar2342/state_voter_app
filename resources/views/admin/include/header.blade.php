 
  <nav class="main-header navbar navbar-expand navbar-light" style="background-color:#001f3f">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" id="bars_btn" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color:#fff"></i></a>
      </li>
      @includeIf('admin.include.hot_menu_top')
          
    </ul>
    @php
       $admin = Auth::guard('admin')->user();
       $rs_fetch = Illuminate\Support\Facades\DB::select(DB::raw("SELECT `name` from `roles` where `id` = $admin->role_id limit 1;"));
     @endphp 
    <ul class="navbar-nav ml-auto">       
      <li class="nav-item">
        <strong style="color:#fff;margin-top: 10px">Welcome : <span style="color:#28a745">{{$admin->first_name}}</span> :: <span style="color:#28a745">{{@$rs_fetch[0]->name}}</span></strong>
        <a class="btn btn-lg" title="Logout" id="btn_logout" href="{{ route('admin.logout.get') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
          <i class="fa fa-power-off" style="color:#dc3545"> <strong style="color:#fff">Logout</strong></i>
        </a>
        <form id="logout-form" action="{{ route('admin.logout.get') }}" method="POST" style="display: none;">
           {{ csrf_field() }}
        </form>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

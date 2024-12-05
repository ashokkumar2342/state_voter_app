<aside class="main-sidebar sidebar-dark-primary elevation-4" style="font-size: 13px;font-weight">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link"> 
      <span class="brand-text font-weight-light" style="margin-left: 47px"><b>Dashboard</b></span>
    </a> 
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) --> 
      @php
            $menuTypes=App\Helper\MyFuncs::userHasMinu();
           
         @endphp

         @foreach ($menuTypes as $menuType)
         @php
           $subMenus = App\Helper\MyFuncs::mainMenu($menuType->id);
         @endphp 
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa {{ $menuType->icon }}"></i>
              <p>
                {{ $menuType->name }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @foreach ($subMenus as $subMenu)
              <li class="nav-item">
                <a href="{{ route(''.$subMenu->url) }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ $subMenu->name }}</p>
                </a>
              </li>
              @endforeach 
            </ul>
          </li>
        @endforeach
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
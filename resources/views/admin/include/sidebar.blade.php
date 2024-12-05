<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#001f3f ">
  <a href="{{ route('admin.dashboard') }}" class="brand-link logo-switch text-center" style="background-color:#fff;border:solid black;">
  <img src="{{ asset('images/nic_logo.png') }}" alt="" style="text-align: center;width: 220px;height: 38px"> 
  </a>
  <div class="sidebar">
    <div class="mt-2">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p><strong>Dashboard</strong></p>
            </a> 
          </li> 
        </ul>
      </nav>

    </div>
      @php
       $menuTypes=App\Helper\MyFuncs::userHasMinu();
      @endphp
      @foreach ($menuTypes as $menuType)
      @php
      $subMenus = App\Helper\MyFuncs::mainMenu($menuType->id);
      @endphp
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="{{ $menuType->icon }}"></i>
              <p>
                {{ $menuType->name }}
                <i class="fas fa-angle-left right"></i> 
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
  </div>
</aside>


      

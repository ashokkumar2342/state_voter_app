 
  {{-- @php
   $hotMenus =App\Helper\MyFuncs::hotMenu(); 
  @endphp
  @foreach($hotMenus as $hotMenu)

  	<li class="nav-item d-none d-sm-inline-block">
    	<a class="nav-link" style="color:#fff" href="{{ route(''.$hotMenu->url) }}">{{ $hotMenu->name }} </a>
    </li>
   
  @endforeach  --}}
    <li class="nav-item d-none d-sm-inline-block hidden-xs hidden-sm">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color:#fff;"><strong>VOTER LIST MANAGEMENT SYSTEM</strong></a>
    </li>
 
 


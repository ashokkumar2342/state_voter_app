 
<div class="col-lg-12" style="margin-top:10px;">
  <table class="table table-condensed "id="menu_role_table" style="width: 100%">
    <thead>
    
      <tr>
        <th>Sub Menu Name</th>
        <th>Main Menu Name</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($menus as $menu)
      <tr>
        <td></td>
        <td>{{ $menu->name }}</td>
        <td></td>
      </tr>
      @php
        $subMenus = App\Helper\MyFuncs::role_menuwise_submenu_permission($menu->id, $id, $link_option);
      @endphp
      @foreach ($subMenus as $subMenu)
         
      <tr style="{{ $subMenu->permission==1?'background-color: #28a745':'background-color: #dc3545' }}">
        <td>{{ $subMenu->name }}</td>
        <td></td>
            
         <td>@if ( $subMenu->permission==1) Yes @else  No @endif  </td> 
    
      </tr>
       @endforeach  
       @endforeach 
    </tbody>
  </table>
</div>
<div class="row"> 
    <div class="col-md-4">
        <label for="exampleInputEmail1">Menu</label></br>
        <select class="selectpicker multiselect" multiple data-live-search="true" name="sub_menu[]">
            @foreach ($menus as $menu) 
            <optgroup label="{{ $menu->name }}"> 
                @foreach ($subMenus as $subMenu)
                @if ($menu->id==$subMenu->menu_type_id )
                <option value="{{ $subMenu->id }}" {{ in_array($subMenu->id, $usersmenus)?'selected':'' }} >{{ $subMenu->name }}</option> 
                @endif 
                @endforeach 
            </optgroup>
            @endforeach                                     
        </select> 
    </div>
    <div class="col-md-4" style="margin-top: 30px"> 
        <button type="submit"  class="btn btn-success form-control">Save</button>  
    </div>  
    <div class="col-md-4" style="margin-top: 30px"> 
        <a href="{{ route('admin.account.user.menu.assign.report',Crypt::encrypt($id)) }}" class="btn btn-primary form-control" target="blank" title="">PDF</a> 
    </div>
</div> 


@include('admin.account.report.user_menu_assign_repot') 




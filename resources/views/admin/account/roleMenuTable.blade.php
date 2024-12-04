<div class="row"> 
  <div class="col-lg-6"> 
    {{ Form::label('sub_menu','Menu',['class'=>' control-label']) }} <br>
    <select class="selectpicker multiselect" multiple data-live-search="true" name="sub_menu[]">
      @foreach ($menus as $menu) 
      <optgroup label="{{ $menu->name }}">
        @php
           $subMenus = App\Helper\MyFuncs::role_menuwise_submenu_permission($menu->id, $id, $link_option);
         @endphp 
        @foreach ($subMenus as $subMenu)
          <option value="{{ $subMenu->id }}" {{ $subMenu->permission==1?'selected':'' }} >{{ $subMenu->name }}</option> 
        @endforeach 
      </optgroup>
      @endforeach                                    
    </select> 
  </div>
  <div class="col-md-6" style="margin-top: 30px"> 
    <button type="submit"  class="btn btn-success form-control">Save</button> 
  </div>
</form>
</div>
 </div>

@include('admin.account.report.result')


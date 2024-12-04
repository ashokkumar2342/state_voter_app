<?php

namespace App\Helper;

// use App\Model\Assembly;
// use App\Model\AssemblyPart;
// use App\Model\HotMenu;
// use App\Model\Minu;
// use App\Model\MinuType;
// use App\Model\SubMenu;
use Illuminate\Support\Facades\Auth;
use Route;
use Illuminate\Support\Facades\DB;



class MyFuncs {

  public static function removeSpacialChr($strValue)
  {
    $newString = trim(str_replace('\'', '', $strValue));
    $newString = trim(str_replace('\\', '', $newString));
    return $newString;
  }
  
  // all permission check
  public static function isPermission(){ 
    $user =Auth::guard('admin')->user();
    $routeName= Route::currentRouteName();
    return true;

    // $subMenu =SubMenu::where('url',$routeName)->first(); 
    // if (empty($subMenu)){
    //   return true;
    // }else{
    //   $menu= Minu::where('admin_id',$user->id)->where('status',1)->where('sub_menu_id',$subMenu->id)->first();
    //   if (empty($menu)) {
    //     return false;
    //   }else{
    //     return true;
    //   }
    // }          

  }

  // main menu 
  public static function mainMenu($menu_type_id){ 
    // $mainMenus = Minu::where('admin_id',Auth::guard('admin')->user()->id)
    //                       ->where('minu_id',$menu_type_id)
    //                       ->where('status',1)
    //                       ->get(['sub_menu_id']); 
        
    // return $subMenus = SubMenu::whereIn('id',$mainMenus)->orderBy('sorting_id','ASC')
    //                       ->get();

    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;

    return $subMenus = DB::select(DB::raw("select `sm`.`id`, `sm`.`name`, `sm`.`status`, `sm`.`url` from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1 and `sm`.`menu_type_id` = $menu_type_id order by `sm`.`sorting_id` ;"));
  }


  // public static function showMenu(){
  //   // $menu='';
  //   $subMenus=array();
  //   // $menuTypes = MinuType::orderBy('sorting_id','asc')->get();
  //   $menuTypes = DB::select(DB::raw("select `id` from `minu_types` order by `sorting_id`;"));
  //   foreach ($menuTypes as  $menuType) {
  //     $menus=MyFuncs::mainMenu($menuType->id);
  //     foreach ($menus as $subMenu) {
  //       $subMenus[]=$subMenu->id;
  //     }
  //   }
  //   return $subMenus;
  // }

  // read write delete permission check
  public static function userHasMinu(){ 
    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;
    return $menuTypes = DB::select(DB::raw("select * from `minu_types` where `id` in (select Distinct `sm`.`menu_type_id` from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1) order by `sorting_id` ;"));

    // return array_pluck(Minu::where('admin_id',Auth::guard('admin')->user()->id)->where('status',1)->distinct()->get(['minu_id'])->toArray(), 'minu_id');

  } 

  // hot menu 
  public static function hotMenu(){ 
    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;
    return $subMenus = DB::select(DB::raw("select `sm`.`id`, `sm`.`name`, `sm`.`status`, `sm`.`url` from `default_role_quick_menu` `drqm` inner join `sub_menus` `sm` on `sm`.`id` = `drqm`.`sub_menu_id` where `drqm`.`role_id` = $user_role and `drqm`.`status` = 1;"));

    // $hotMenus = HotMenu::where('status',1)->where('admin_id',Auth::guard('admin')->user()->id)
    //   ->get(['sub_menu_id']); 
    // return $subMenus = SubMenu::whereIn('id',$hotMenus)->take('7') 
    //                       ->get(); 
    
  }


  // Count Zila Parishad Wards 
  public static function ZPWard_Count($district_id){ 
    $zp_rs = DB::select(DB::raw("select count(*) as `tcount` from `ward_zp` where `districts_id` = $district_id;"));
    return $zp_rs[0]->tcount;     
  } 

  //Role Menuwise submenu permission
  public static function role_menuwise_submenu_permission($menu_type_id, $roleid, $link_option){ 
    return $subMenus = DB::select(DB::raw("select `sm`.`id`, `sm`.`name`, `uf_check_role_permission`($roleid, `sm`.`id`, $link_option) as `permission` from `sub_menus` `sm` where `sm`.`menu_type_id` = $menu_type_id order by `sm`.`sorting_id`;"));
  }


  public static function Refresh_data_voterEntry(){ 
    $rs_check = DB::select(DB::raw("select `entry_refresh_data` from `app_default_value`;"));
    return $rs_check[0]->entry_refresh_data;     
  }

   



  //--------------End-----------------
//     public static function getUser(){
//        return $user = Auth::guard('admin')->user();  
//     }

//     public static function getUserId(){
//        return $user = Auth::guard('admin')->user()->id;  
//     } 
    

//      // read write delete permission check
//   public static function menuPermission(){ 
//     $user_id =Auth::guard('admin')->user()->id;
//     $routeName= Route::currentRouteName();
//     $previousRoute= app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
//     $subMenu =SubMenu::where('url',$routeName)->first(); 
//     $previoussubMenu =SubMenu::where('url',$previousRoute)->first(); 
//     if (empty($subMenu)) {
//       return Minu::where('admin_id',$user_id)->where('status',1)->where('sub_menu_id',$previoussubMenu->id)->first();   
//      } 
//     return Minu::where('admin_id',$user_id)->where('status',1)->where('sub_menu_id',$subMenu->id)->first();
              

//   }

  


//   public static function getAssemblyIdByTableName($table_name)
//     {     
//        $assemblyCode=substr($table_name, -7, 3); 
//        $assembly=Assembly::where('code',$assemblyCode)->first(); 
//        return $assembly;
//     }
//     public static function getAssemblyPartIdByTableName($table_name)
//     {     
//        $assemblyCode=substr($table_name, -7, 3); 
//        $assemblyPartCode=substr($table_name,4);
//        $assembly=Assembly::where('code',$assemblyCode)->first();  
//        $assemblyPart=AssemblyPart::where('part_no',$assemblyPartCode)->where('assembly_id',$assembly->id)->first(); 
//        return $assemblyPart;
//     }
   


}
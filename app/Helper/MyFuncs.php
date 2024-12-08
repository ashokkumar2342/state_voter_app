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

  public static function Exception_error_handler($controller, $method, $error_message) 
  {
    $user_id = MyFuncs::getUserId();
    $from_ip = MyFuncs::getIp();

    $error_message = MyFuncs::removeSpacialChr($error_message);

    $user_detail = "";
    $rs_fetch = DB::select(DB::raw("SELECT `first_name`, `email`, `mobile` from `admins` where `id` = $user_id limit 1;"));
    if(count($rs_fetch)>0){
      $user_detail = $rs_fetch[0]->first_name.' - '.$rs_fetch[0]->email.' ('.$rs_fetch[0]->mobile.')';
    }
    
    $rs_insert = DB::select(DB::raw("INSERT into `gehs` (`controller_name`, `method_function_name`, `error_detail`, `user_id`, `from_ip`, `date_time`, `status`, `remarks`, `user_detail`) values ('$controller', '$method', '$error_message', '$user_id', '$from_ip', now(), 0, '', '$user_detail');"));
  }

  public static function generateId() 
  {
    return rand(1111111111,100000000); 
  }

  public static function generateRandomIV() 
  {
    return substr(uniqid(), 1, 8); 
  }

  public static function getIp()
  {

    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
      foreach ($matches[0] AS $xip) {
        if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
          $ip = $xip;
          break;
        }
      }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
      $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
      $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
  }

  public static function isPermission_route($sub_menu_id)
  { 
    $user_rs = Auth::guard('admin')->user(); 
    if(empty($user_rs)){
      return false;
    }

    // $rs_fetch = DB::select(DB::raw("SELECT `web_url` from `schoolinfo` limit 1;"));
    // $web_url = $rs_fetch[0]->web_url;
    $web_url = "10.145.41.196";

    $http_host =  $_SERVER['HTTP_HOST'];
    if(($http_host != $web_url) && ($http_host != 'localhost:80') && ($http_host != 'localhost:81') && ($http_host != 'localhost')){
      return false;
      return Redirect::route('logout')->with(['error_msg' => 'Unauthorised Access to Application !!']);
    }

    $user_role = $user_rs->role_id;
    $user_id = $user_rs->id;
    
    $sub_menu_id = "(".$sub_menu_id.")";
    $result_rs = DB::select(DB::raw("SELECT * from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1 and `sm`.`id` in $sub_menu_id limit 1;"));
    
    if(count($result_rs)>0){
      return true;  
    }

    return false;
  }

  public static function getUserId()
  {
    return $user = Auth::guard('admin')->user()->id;  
  }

  public static function getUserRoleId()
  {
    return $role_id = Auth::guard('admin')->user()->role_id;  
  }

  public static function check_password_strength($password, $user_id) 
  {
    $passwordError = "";
    if (strlen($password) <= 8) {
      $passwordError .= "Password must have 8 characters at least.<br>";
    }
    if (!preg_match("#[0-9]+#", $password)) {
      $passwordError .= "Password must have 1 Number at least.<br>";
    }
    if (!preg_match("#[A-Z]+#", $password)) {
      $passwordError .= "Password must have 1 uppercase letter at least.<br>";
    }
    if (!preg_match("#[a-z]+#", $password)) {
      $passwordError .= "Password must have 1 lowercase letter at least.<br>";
    }
    if (!preg_match('@[^\w]@', $password)) {
      $passwordError .= "Password must have 1 special character letter at least.<br>";
    }

    
    $rs_fetch = DB::select(DB::raw("SELECT * from `password_change_history` where `user_id` = $user_id order by `id` desc limit 3;"));
    $found = 0;
    foreach ($rs_fetch as $key => $value) {
      if(password_verify($password,$value->new_password)){
        if($found == 0){
          $passwordError .= "You used this password recently, please choose a different password.";
          $found = 1;
        }
      }
    }
    

    return $passwordError;
    
  }

  public static function check_emailid_user($user_id, $emailid)
  {
    $rs_fetch = DB::select(DB::raw("SELECT `id`  from `admins` where `email` = '$emailid' and `id` <> $user_id limit 1;"));
    if(count($rs_fetch)>0){
      return 0;
    }else{
      return 1;
    }
  }

  public static function check_mobile_user($user_id, $mobile)
  {
    $rs_fetch = DB::select(DB::raw("SELECT `id`  from `admins` where `mobile` = '$mobile' and `id` <> $user_id limit 1;"));
    if(count($rs_fetch)>0){
      return 0;
    }else{
      return 1;
    }
  }

  public static function removeSpacialChr($strValue)
  {
    $newString = trim(str_replace('\'', '', $strValue));
    $newString = trim(str_replace('\\', '', $newString));
    // $newString = trim(strip_tags($newString, "<b><u><i><div><p><h>"));
    // $newString = trim(strip_tags($newString));
    // $newString = trim(htmlspecialchars($newString));

    $newString = trim(str_replace('&lt;script&gt;', '', $newString));
    $newString = trim(str_replace('&lt;/script&gt;', '', $newString));
    
    $newString = trim(preg_replace('/script/i', '', $newString));
    $newString = trim(preg_replace('/php/i', '', $newString));
    
    return $newString;
  }

  public static function check_district_access($d_id)
  { 
    $user_id = MyFuncs::getUserId();
    $role_id = MyFuncs::getUserRoleId();
    $result = 0;

    if($role_id == 1){
      $result = 1;
    }elseif($role_id == 2){
      $result_rs = DB::select(DB::raw("SELECT `id` from `user_district_assigns` where `user_id` = $user_id and `district_id` = $d_id and `status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }elseif($role_id == 3){
      $result_rs = DB::select(DB::raw("SELECT `id` from `user_block_assigns` where `user_id` = $user_id and `district_id` = $d_id and `status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }elseif($role_id == 4){
      $result_rs = DB::select(DB::raw("SELECT `id` from `user_village_assigns` where `user_id` = $user_id and `district_id` = $d_id and `status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }
    return $result;
  }

  public static function check_block_access($bl_id)
  { 
    $user_id = MyFuncs::getUserId();
    $role_id = MyFuncs::getUserRoleId();
    $result = 0;

    if($role_id == 1){
      $result = 1;
    }elseif($role_id == 2){
      $result_rs = DB::select(DB::raw("SELECT `uda`.`id` from `user_district_assigns` `uda` inner join `blocks_mcs` `bl` on `bl`.`districts_id` = `uda`.`district_id` and `bl`.`id` = $bl_id where `uda`.`user_id` = $user_id and `uda`.`status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }elseif($role_id == 3){
      $result_rs = DB::select(DB::raw("SELECT `id` from `user_block_assigns` where `user_id` = $user_id and `block_id` = $bl_id and `status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }elseif($role_id == 4){
      $result_rs = DB::select(DB::raw("SELECT `id` from `user_village_assigns` where `user_id` = $user_id and `block_id` = $bl_id and `status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }
    return $result;
  }

  public static function check_village_access($vil_id)
  { 
    $user_id = MyFuncs::getUserId();
    $role_id = MyFuncs::getUserRoleId();
    $result = 0;

    if($role_id == 1){
      $result = 1;
    }elseif($role_id == 2){
      $result_rs = DB::select(DB::raw("SELECT `uda`.`id` from `user_district_assigns` `uda` inner join `villages` `vil` on `vil`.`districts_id` = `uda`.`district_id` and `vil`.`id` = $vil_id where `uda`.`user_id` = $user_id and `uda`.`status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }elseif($role_id == 3){
      $result_rs = DB::select(DB::raw("SELECT `uda`.`id` from `user_block_assigns` `uda` inner join `villages` `vil` on `vil`.`blocks_id` = `uda`.`block_id` and `vil`.`id` = $vil_id where `uda`.`user_id` = $user_id and `uda`.`status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }elseif($role_id == 4){
      $result_rs = DB::select(DB::raw("SELECT `id` from `user_village_assigns` where `user_id` = $user_id and `village_id` = $vil_id and `status` = 1 limit 1;"));
      if(count($result_rs) > 0){
        $result = 1;
      }
    }
    return $result;
  }

  public static function isPermission_reports($role_id, $report_id)
  { 
    $rs_fetch = DB::select(DB::raw("SELECT * from `report_types` where `report_id` = $report_id and `report_for` = $role_id limit 1;"));
    
    if(count($rs_fetch)>0){
      return true;  
    }
    return false;
  }

  // main menu 
  public static function mainMenu($menu_type_id)
  { 

    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;
    $user_id = $user_rs->id;

    $rs_fetch = DB::select(DB::raw("SELECT `id` from `admins` where `id` = $user_id and `password_expire_on` <= curdate();"));
    if(count($rs_fetch)>0){
      $subMenus = DB::select(DB::raw("SELECT `sm`.`id`, `sm`.`name`, `sm`.`status`, `sm`.`url` from `sub_menus` `sm` where `sm`.`id` = 8 order by `sm`.`sorting_id` ;"));
    }else{
      $subMenus = DB::select(DB::raw("SELECT `sm`.`id`, `sm`.`name`, `sm`.`status`, `sm`.`url` from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1 and `sm`.`menu_type_id` = $menu_type_id order by `sm`.`sorting_id` ;"));
    }

    return $subMenus;
  }

  public static function userHasMinu()
  { 

    $user_rs=Auth::guard('admin')->user();  
    $user_role = $user_rs->role_id;
    $user_id = $user_rs->id;
    $rs_fetch = DB::select(DB::raw("SELECT `id` from `admins` where `id` = $user_id and `password_expire_on` <= curdate();"));
    if(count($rs_fetch)>0){
      $menuTypes = DB::select(DB::raw("SELECT * from `minu_types` where `id` = 1 order by `sorting_id` ;"));
    }else{
      $menuTypes = DB::select(DB::raw("SELECT * from `minu_types` where `id` in (select Distinct `sm`.`menu_type_id` from `default_role_menu` `drm` inner join `sub_menus` `sm` on `sm`.`id` = `drm`.`sub_menu_id` where `drm`.`role_id` = $user_role and `drm`.`status` = 1) order by `sorting_id` ;"));
    }

    return $menuTypes;
  }

  // all permission check
  public static function isPermission()
  {
    $user = Auth::guard('admin')->user();
    if(!empty($user)){ 
      $role_id = $user->role_id;
      $routeName = Route::currentRouteName();
      $rs_fetch = DB::select(DB::raw("SELECT `id` from `sub_menus` where `url` = '$routeName' and `status` = 1;"));
      if (count($rs_fetch)>0){
        $menu_id = $rs_fetch[0]->id;
        $rs_fetch = DB::select(DB::raw("SELECT `id` from `default_role_menu` where `role_id` = $role_id and `status` = 1 and `sub_menu_id` = $menu_id;"));
        if(count($rs_fetch) == 0){
          return false;    
        }
      }  
    }else{
      // return false;  
    }
    $http_host =  $_SERVER['HTTP_HOST'];
    if(($http_host != '10.145.41.196') && ($http_host != 'localhost') && ($http_host != 'localhost:81')){
      return false;
      return Redirect::route('logout')->with(['error_msg' => 'Unauthorised Access to Application !!']);
    }
    return true;
  }

  // ----------------------- End -------------------------

  

  


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
<?php

namespace App\Helper;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Route;
use Illuminate\Support\Facades\DB;
use App\Helper\MyFuncs;

class SelectBox {

  public static function get_user_list_v1($role_id, $created_by)
  { 
    $condition = "";
    if($role_id > 0){
      $condition = $condition." and `role_id` = $role_id ";
    }
    if($created_by > 0){
      $condition = $condition." and `created_by` = $created_by ";
    }
    $result_rs = DB::select(DB::raw("SELECT `id` as `opt_id`, concat(`first_name`, ' - ', `email`, ' - ', `mobile`) as `opt_text` from `admins`where `status` = 1 $condition Order By `first_name`;"));
    return $result_rs;
  }

  public static function get_district_access_list_v1()
  { 
    $user_id = MyFuncs::getUserId();
    $role_id = MyFuncs::getUserRoleId();

    if($role_id == 1){
      $result_rs = DB::select(DB::raw("SELECT `id` as `opt_id`, concat(`code`, ' - ', `name_e`) as `opt_text` from `districts` order by `code`;"));  
    }elseif($role_id == 2){
      $result_rs = DB::select(DB::raw("SELECT `dst`.`id` as `opt_id`, concat(`dst`.`code`, ' - ', `dst`.`name_e`) as `opt_text` from `user_district_assigns` `uda` inner join `districts` `dst` on `dst`.`id` = `uda`.`district_id` where `uda`.`status` = 1 and `uda`.`user_id` = $user_id order by `dst`.`code`;"));
    }elseif($role_id == 3){
      $result_rs = DB::select(DB::raw("SELECT `dst`.`id` as `opt_id`, concat(`dst`.`code`, ' - ', `dst`.`name_e`) as `opt_text` from `user_block_assigns` `uda` inner join `districts` `dst` on `dst`.`id` = `uda`.`district_id` where `uda`.`status` = 1 and `uda`.`user_id` = $user_id order by `dst`.`code`;"));
    }elseif($role_id == 4){
      $result_rs = DB::select(DB::raw("SELECT `dst`.`id` as `opt_id`, concat(`dst`.`code`, ' - ', `dst`.`name_e`) as `opt_text` from `user_village_assigns` `uda` inner join `districts` `dst` on `dst`.`id` = `uda`.`district_id` where `uda`.`status` = 1 and `uda`.`user_id` = $user_id order by `dst`.`code`;"));
    }
    return $result_rs;
  }

  public static function get_block_access_list_v1($d_id)
  { 
    $user_id = MyFuncs::getUserId();
    $role_id = MyFuncs::getUserRoleId();

    if($role_id == 1){
      $result_rs = DB::select(DB::raw("SELECT `bl`.`id` as `opt_id`, concat(`bl`.`code`, ' - ', `bl`.`name_e`) as `opt_text` from `blocks_mcs` `bl` where `bl`.`districts_id` = $d_id order by `bl`.`code`;"));  
    }elseif($role_id == 2){
      $result_rs = DB::select(DB::raw("SELECT `bl`.`id` as `opt_id`, concat(`bl`.`code`, ' - ', `bl`.`name_e`) as `opt_text` from `blocks_mcs` `bl` where `bl`.`districts_id` = $d_id order by `bl`.`code`;"));
    }elseif($role_id == 3){
      $result_rs = DB::select(DB::raw("SELECT `bl`.`id` as `opt_id`, concat(`bl`.`code`, ' - ', `bl`.`name_e`) as `opt_text` from `user_block_assigns` `uba` inner join `blocks_mcs` `bl` on `uba`.`block_id` = `bl`.`id` where `uba`.`district_id` = $d_id and `uba`.`user_id` = $user_id and `status` = 1 order by `bl`.`code`;"));
    }elseif($role_id == 4){
      $result_rs = DB::select(DB::raw("SELECT `bl`.`id` as `opt_id`, concat(`bl`.`code`, ' - ', `bl`.`name_e`) as `opt_text` from `user_village_assigns` `uba` inner join `blocks_mcs` `bl` on `uba`.`block_id` = `bl`.`id` where `uba`.`district_id` = $d_id and `uba`.`user_id` = $user_id and `status` = 1 order by `bl`.`code`;"));
    }
    return $result_rs;
  }

}
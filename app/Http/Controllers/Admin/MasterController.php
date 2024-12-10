<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;
use PDF;
use Response;

class MasterController extends Controller
{
  protected $e_controller = "MasterController";

  public function districts(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(21);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $States = DB::select(DB::raw("SELECT * from `states` order by `name_e`;"));     
      return view('admin.master.districts.index',compact('States'));
    } catch (\Exception $e) {
      $e_method = "districts";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function DistrictsTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(21);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $state_id = intval(Crypt::decrypt($request->id));
      $rs_district = DB::select(DB::raw("SELECT * from `districts` where `state_id` = $state_id order by `name_e`;"));
      return view('admin.master.districts.district_table',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "DistrictsTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function DistrictsStore(Request $request, $id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(21);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rec_id = intval(Crypt::decrypt($id));
      $rules=[
        'code' => 'required|max:5', 
        'name_english' => 'required|max:50', 
        'name_local_language' => 'required|max:50', 
      ];

      $customMessages = [
        'code.required'=> 'Please Enter District Code',                
        'code.max'=> 'District Code Should Be Maximum of 5 Character',

        'name_english.required'=> 'Please Enter District Name English',                
        'name_english.max'=> 'District Name English Should Be Maximum of 50 Character',

        'name_local_language.required'=> 'Please Enter District Name (H)',                
        'name_local_language.max'=> 'District Name (H) Should Be Maximum of 50 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $code = substr(MyFuncs::removeSpacialChr($request->code), 0, 5);
      $name_e = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
      $name_l = MyFuncs::removeSpacialChr($request->name_local_language);

      $user_id = MyFuncs::getUserId();

      if ($rec_id > 0) {
        $state_id = 0;
        $zpward = 0;
      }else{
        $state_id = intval(Crypt::decrypt($request->states));
        $zpward = intval(substr(MyFuncs::removeSpacialChr($request->zp_ward), 0, 2));
        if($state_id == 0){
          $response=['status'=>0,'msg'=>"Please Select State"];
          return response()->json($response);
        }
      }

      $rs_update = DB::select(DB::raw("call `up_save_district` ($rec_id, $user_id, $state_id, '$code', '$name_e', '$name_l', $zpward);"));
      $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "DistrictsStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }  
  }

  public function DistrictsEdit($rec_id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(21);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $rec_id = intval(Crypt::decrypt($rec_id));
      $Districts = DB::select(DB::raw("SELECT * from `districts` where `id` = $rec_id limit 1;"));
      if(count($Districts) == 0){
        return view('admin.common.error_popup');  
      } 
      return view('admin.master.districts.edit',compact('Districts', 'rec_id'));
    } catch (\Exception $e) {
      $e_method = "DistrictsEdit";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  // public function DistrictsDelete($id)
  // {
  //   try{
  //     $permission_flag = MyFuncs::isPermission_route(21);
  //     if(!$permission_flag){
  //       return view('admin.common.error');
  //     }
  //     $decrp_id = intval(Crypt::decrypt($id));
  //     $rs_delete = DB::select(DB::raw("DELETE from `districts` where `id` = $decrp_id limit 1;"));
  //     $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
  //     return response()->json($response);  
  //   } catch (\Exception $e) {
  //     $e_method = "DistrictsDelete";
  //     return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
  //   }
  // }

  public function DistrictsZpWard($d_id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(21);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $district_id = intval(Crypt::decrypt($d_id));
      $DistrictName = DB::select(DB::raw("SELECT `name_e` from `districts` where `id` = $district_id;")); 
      if(count($DistrictName) == 0){
        return view('admin.common.error_popup');  
      }
      return view('admin.master.districts.zp_ward',compact('DistrictName', 'district_id')); 
    } catch (\Exception $e) {
      $e_method = "DistrictsZpWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function DistrictsZpWardStore(Request $request, $d_id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(21);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[ 
        'zp_ward' => 'required|max:2',
      ]; 
      $customMessages = [
        'zp_ward.required'=> 'Please Enter Zila Parishad Ward',                
        'zp_ward.max'=> 'Zila Parishad Ward Should Be Maximum of 2 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $district_id = intval(Crypt::decrypt($d_id));
      if($district_id == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);  
      }
      $zpward = intval(substr(MyFuncs::removeSpacialChr($request->zp_ward), 0, 2));
      $rs_save = DB::select(DB::raw("call up_create_zp_ward ($district_id, $zpward, 0);")); 
      $response=['status'=>1,'msg'=>'ZP Wards Created Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "DistrictsZpWardStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function blockMCS(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();    
      $rs_block_mc_type = DB::select(DB::raw("SELECT * from `block_mc_type` order by `block_mc_type_e`;"));    
      return view('admin.master.block.index',compact('rs_district', 'rs_block_mc_type'));
    } catch (\Exception $e) {
      $e_method = "blockMCS";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function blockMCSTable(Request $request)
  {  
    try {
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $role_id = MyFuncs::getUserRoleId();
      $d_id = intval(Crypt::decrypt($request->id));

      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }

      $rs_blocks = DB::select(DB::raw("SELECT `bl`.`id`, `bl`.`code`, `bl`.`name_e`, `bl`.`name_l`, `blt`.`block_mc_type_e`, (select Count(*) from `ward_ps` where `blocks_id` = `bl`.`id`) as `pscount`, `bl`.`block_mc_type_id` from `blocks_mcs` `bl` inner join `block_mc_type` `blt` on `blt`.`id` = `bl`.`block_mc_type_id` where `bl`.`districts_id` = $d_id order by `bl`.`name_e`;"));
      return view('admin.master.block.block_table',compact('rs_blocks', 'role_id'));
    } catch (Exception $e) {
      $e_method = "blockMCSTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function blockMCSStore(Request $request, $id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'code' => 'required|max:5', 
        'name_english' => 'required', 
        'name_local_language' => 'required', 
        'block_mc_type_id' => 'required', 
      ];

      $customMessages = [
        'code.required'=> 'Please Enter Block Code',                
        'code.max'=> 'Block Code Should Be Maximum of 5 Character',

        'name_english.required'=> 'Please Enter Block Name (E)',                
        'name_english.max'=> 'Block Name (E) Should Be Maximum of 50 Character',

        'name_local_language.required'=> 'Please Enter Block Name (H)',                
        'name_local_language.max'=> 'Block Name (H) Should Be Maximum of 100 Character',

        'block_mc_type_id.required'=> 'Please Select Block / MC\'s Type',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $rec_id = intval(Crypt::decrypt($id));
      $b_type_id = intval(Crypt::decrypt($request->block_mc_type_id));

      $bcode = substr(MyFuncs::removeSpacialChr($request->code), 0, 5);
      $name_e = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
      $name_l = MyFuncs::removeSpacialChr($request->name_local_language);

      $stamp1 = substr(MyFuncs::removeSpacialChr($request->stamp_l1), 0, 100);
      $stamp2 = substr(MyFuncs::removeSpacialChr($request->stamp_l2), 0, 100);
      
      $user_id = MyFuncs::getUserId();
      if ($rec_id == 0) { 
        $d_id = intval(Crypt::decrypt($request->district));
        if($d_id == 0){
          $response=['status'=>0,'msg'=>"Please Select District"];
          return response()->json($response);
        }
        $permission_flag = MyFuncs::check_district_access($d_id);
        if($permission_flag == 0){
          $response=['status'=>0,'msg'=>"Something Went Wrong"];
          return response()->json($response);
        }
        $pswards = intval(substr(MyFuncs::removeSpacialChr($request->ps_ward), 0, 2));
        $block_id = 0;  
      }else{
        $d_id = 0;
        $pswards = 0;
        $block_id = $rec_id;
        $permission_flag = MyFuncs::check_block_access($block_id);
        if($permission_flag == 0){
          $response=['status'=>0,'msg'=>"Something Went Wrong"];
          return response()->json($response);
        }
      }
      $rs_update = DB::select(DB::raw("call `up_save_block` ($block_id, $user_id, $d_id, '$bcode', '$name_e', '$name_l', $pswards, $b_type_id, '$stamp1', '$stamp2');"));
      $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->remarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "blockMCSStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }  
  }

  public function blockMCSEdit($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $permission_flag = MyFuncs::check_block_access($rec_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }
      $BlocksMcs = DB::select(DB::raw("SELECT * from `blocks_mcs` where `id` = $rec_id limit 1;"));
      $BlockMCTypes = DB::select(DB::raw("SELECT * from `block_mc_type` order by `block_mc_type_e`;"));
      return view('admin.master.block.edit',compact('BlocksMcs','BlockMCTypes', 'rec_id'));
    } catch (\Exception $e) {
      $e_method = "blockMCSEdit";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function blockMCSDelete($id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $block_id = intval(Crypt::decrypt($id));
      $role_id = MyFuncs::getUserRoleId();
      if($role_id != 1){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rs_delete = DB::select(DB::raw("DELETE from `blocks_mcs` where `id` = $block_id limit 1;"));
      $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "blockMCSDelete";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }  
  }

  public function blockMCSpsWard($b_id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $block_id = intval(Crypt::decrypt($b_id));
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }

      $Block_Name = DB::select(DB::raw("SELECT `name_e`, `block_mc_type_id` from `blocks_mcs` where `id` = $block_id limit 1;"));
      if($Block_Name[0]->block_mc_type_id != 1){
        return view('admin.common.error_popup');  
      }
      return view('admin.master.block.ps_ward',compact('Block_Name', 'block_id'));
    } catch (\Exception $e) {
      $e_method = "blockMCSpsWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }  
  }

  public function blockMCSpsWardStore(Request $request, $b_id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(17);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[ 
        'ps_ward' => 'required|max:2',  
      ]; 
      $customMessages = [
        'ps_ward.required'=> 'Please Enter Panchyat Samiti Ward',                
        'ps_ward.max'=> 'Panchyat Samiti Ward Should Be Maximum of 2 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $block_id = intval(Crypt::decrypt($b_id));
      $pswards = intval(substr(MyFuncs::removeSpacialChr($request->ps_ward), 0, 2));

      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $Block_Name = DB::select(DB::raw("SELECT `name_e`, `block_mc_type_id` from `blocks_mcs` where `id` = $block_id limit 1;"));
      if($Block_Name[0]->block_mc_type_id != 1){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rs_save = DB::select(DB::raw("call up_create_ps_ward ($block_id, $pswards, 0);")); 
      $response=['status'=>1,'msg'=>'Ward Created Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "blockMCSpsWardStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function village(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(14);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.village.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "village";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(14);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $role_id = MyFuncs::getUserRoleId();
      $block_id = intval(Crypt::decrypt($request->id));
      
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }

      $rs_village = DB::select(DB::raw("SELECT `vil`.`id`, `vil`.`code`, `vil`.`name_e`, `vil`.`name_l`, (select count(*) from `ward_villages` where `village_id` = `vil`.`id`) as `tcount` from `villages` `vil` where `blocks_id` = $block_id order by `vil`.`name_e`;"));
      return view('admin.master.village.village_table',compact('rs_village', 'role_id')); 
    } catch (\Exception $e) {
      $e_method = "villageTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageStore(Request $request, $id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(14);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'code' => 'required|max:5', 
        'name_english' => 'required|max:50', 
        'name_local_language' => 'required|max:100', 
      ];
      $customMessages = [
        'code.required'=> 'Please Enter Panchayat / MC\'s Code',                
        'code.max'=> 'Panchayat / MC\'s Code Should Be Maximum of 5 Character',

        'name_english.required'=> 'Please Enter Panchayat / MC\'s Name English',                
        'name_english.max'=> 'Panchayat / MC\'s Name English Should Be Maximum of 50 Character',

        'name_local_language.required'=> 'Please Enter Panchayat / MC\'s Name Local Language',                
        'name_local_language.max'=> 'Panchayat / MC\'s Name Local Language Should Be Maximum of 100 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $rec_id = intval(Crypt::decrypt($id));
      $user_id = MyFuncs::getUserId();
      $v_id = 0;
      $wards = 0;
      $b_id = 0;
      
      $vcode = substr(MyFuncs::removeSpacialChr($request->code), 0, 5);
      $name_e = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
      $name_l = MyFuncs::removeSpacialChr($request->name_local_language);

      if ($rec_id == 0) {
        $d_id = intval(Crypt::decrypt($request->district));
        $b_id = intval(Crypt::decrypt($request->block_mcs));
        $wards = intval(substr(MyFuncs::removeSpacialChr($request->ward), 0, 2));

        $permission_flag = MyFuncs::check_district_access($d_id);
        if($permission_flag == 0){
          $response=['status'=>0,'msg'=>'Something Went Wrong'];
          return response()->json($response);
        }

        $permission_flag = MyFuncs::check_block_access($b_id);
        if($permission_flag == 0){
          $response=['status'=>0,'msg'=>'Something Went Wrong'];
          return response()->json($response);
        }
      }else{
        $v_id = $rec_id;
        $permission_flag = MyFuncs::check_village_access($v_id);
        if($permission_flag == 0){
          $response=['status'=>0,'msg'=>'Something Went Wrong'];
          return response()->json($response);
        }
      }
      $rs_update = DB::select(DB::raw("call `up_save_village` ($v_id, $user_id, $b_id, '$vcode', '$name_e', '$name_l', $wards);")); 
      $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->remarks];
      return response()->json($response);  
    } catch (\Exception $e) {
      $e_method = "villageStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageEdit($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(14);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $permission_flag = MyFuncs::check_village_access($rec_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }

      $village = DB::select(DB::raw("SELECT * from villages where `id` = $rec_id limit 1;"));
      return view('admin.master.village.edit',compact('village', 'rec_id'));
    } catch (\Exception $e) {
      $e_method = "villageEdit";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageDelete($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(14);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $role_id = MyFuncs::getUserRoleId();
      if($role_id >= 3){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rec_id = intval(Crypt::decrypt($id));
      $permission_flag = MyFuncs::check_village_access($rec_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $village = DB::select(DB::raw("DELETE from villages where `id` = $rec_id limit 1;"));
      $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "villageDelete";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageWardAdd($v_id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(14);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $village_id = intval(Crypt::decrypt($v_id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }

      $Village_Name = DB::select(DB::raw("SELECT `name_e` from villages where `id` = $village_id limit 1;")); 
      return view('admin.master.village.add_ward',compact('Village_Name', 'village_id'));
    } catch (\Exception $e) {
      $e_method = "villageWardAdd";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageWard(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(13);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.wards.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "villageWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageWardTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(13);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $role_id = MyFuncs::getUserRoleId();
      $village_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }

      $rs_wards = DB::select(DB::raw("SELECT * from `ward_villages` where `village_id` = $village_id order by `ward_no`;"));
      return view('admin.master.wards.ward_table',compact('rs_wards', 'role_id'));
    } catch (\Exception $e) {
      $e_method = "villageWardTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardStore(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route("13, 14");
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'village' => 'required', 
        'ward' => 'required', 
      ];
      $validator = Validator::make($request->all(),$rules);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $village_id = intval(Crypt::decrypt($request->village));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $wards = intval(substr(MyFuncs::removeSpacialChr($request->ward), 0, 2));
      $rs_update = DB::select(DB::raw("call up_create_village_ward ($village_id, $wards, 0);"));
      $response=['status'=>1,'msg'=>'Ward Created Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "wardStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageWardDelete($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(13);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rec_id = intval(Crypt::decrypt($id));

      $village_id = 0;
      $rs_fetch = DB::select(DB::raw("SELECT `village_id` from `ward_villages` where `id` = $rec_id limit 1;"));
      if(count($rs_fetch)>0){
        $village_id = $rs_fetch[0]->village_id;
      }
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rs_delete = DB::select(DB::raw("DELETE from `ward_villages` where `id` = $rec_id limit 1;"));
      $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "villageWardDelete";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function Assembly(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(19);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();  
      return view('admin.master.assembly.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "Assembly";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(19);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $role_id = MyFuncs::getUserRoleId();
      $d_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }

      $rs_assemblys = DB::select(DB::raw("SELECT `asm`.`id`, `asm`.`code`, `asm`.`name_e`, `asm`.`name_l`, (select count(*) from `assembly_parts` where `assembly_id` = `asm`.`id`) as `tcount` from `assemblys` `asm` where `asm`.`district_id` = $d_id order by `asm`.`code`;"));  
      return view('admin.master.assembly.assembly_table',compact('rs_assemblys', 'role_id')); 
    } catch (\Exception $e) {
      $e_method = "AssemblyTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyStore(Request $request, $id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(19);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[        
        'code' => 'required|max:5', 
        'name_english' => 'required|max:50', 
        'name_local_language' => 'required|max:100', 
      ];
      $customMessages = [
        'code.required'=> 'Please Enter Assembly Code',                
        'code.max'=> 'Assembly Code Should Be Maximum of 5 Character',

        'name_english.required'=> 'Please Enter Assembly Name English',                
        'name_english.max'=> 'Assembly Name English Should Be Maximum of 50 Character',

        'name_local_language.required'=> 'Please Enter Assembly Name Local Language',                
        'name_local_language.max'=> 'Assembly Name Local Language Should Be Maximum of 100 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $rec_id = intval(Crypt::decrypt($id));
      $userid = MyFuncs::getUserId();
      
      $code = substr(MyFuncs::removeSpacialChr($request->code), 0, 5);
      $name_e = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
      $name_l = MyFuncs::removeSpacialChr($request->name_local_language);
      $d_id = 0;
      $asmb_id = 0;
      $asmb_parts = 0;
      if ($rec_id == 0){
        $d_id = intval(Crypt::decrypt($request->district));
        $asmb_parts = intval(substr(MyFuncs::removeSpacialChr($request->part_no), 0, 3));

        $permission_flag = MyFuncs::check_district_access($d_id);
        if($permission_flag == 0){
          $response=['status'=>0,'msg'=>'Something Went Wrong'];
          return response()->json($response);
        }
      }else{
        $asmb_id = $rec_id;
      }
      $rs_save = DB::select(DB::raw("call `up_save_assembly` ($asmb_id, $userid, $d_id, '$code', '$name_e', '$name_l', $asmb_parts);"));
      
      $response=['status'=>$rs_save[0]->save_status,'msg'=>$rs_save[0]->remarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "AssemblyStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }     
  }

  public function AssemblyEdit($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(19);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $userid = MyFuncs::getUserId();  
      $assembly = DB::select(DB::raw("SELECT * from `assemblys` where `id` = $rec_id limit 1;"));

      $d_id = 0;
      if(count($assembly) > 0){
        $d_id = $assembly[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }
      return view('admin.master.assembly.edit',compact('assembly'));
    } catch (Exception $e) {
      $e_method = "AssemblyEdit";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyDelete($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(19);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $rs_fetch = DB::select(DB::raw("SELECT `district_id` from `assemblys` where `id` = $rec_id limit 1;"));

      $d_id = 0;
      if(count($rs_fetch) > 0){
        $d_id = $rs_fetch[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rs_delete = DB::select(DB::raw("DELETE from `assemblys` where `id` = $rec_id limit 1;"));
      $response=['status'=>1,'msg'=>'Assembly Deleted Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "AssemblyDelete";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }    
  }

  public function AssemblyPartAdd($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(19);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $ac_id = intval(Crypt::decrypt($id));
      $assembly = DB::select(DB::raw("SELECT * from `assemblys` where `id` = $ac_id limit 1;"));

      $d_id = 0;
      if(count($assembly) > 0){
        $d_id = $assembly[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }

      return view('admin.master.assemblypart.add_form',compact('assembly'));
    } catch (\Exception $e) {
      $e_method = "AssemblyPartAdd";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyPart(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(18);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.assemblypart.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "AssemblyPart";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyPartTable(Request $request)
  {  
    try {
      $permission_flag = MyFuncs::isPermission_route(18);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $role_id = MyFuncs::getUserRoleId();
      $ac_id = intval(Crypt::decrypt($request->id));

      $rs_ftch = DB::select(DB::raw("SELECT `district_id` from `assemblys` where `id` = $ac_id limit 1;"));

      $d_id = 0;
      if(count($rs_ftch) > 0){
        $d_id = $rs_ftch[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }

      $rs_assemblys_part = DB::select(DB::raw("SELECT * from `assembly_parts` where `assembly_id` = $ac_id order by `part_no`;"));
      return view('admin.master.assemblypart.part_table',compact('rs_assemblys_part', 'role_id'));
    } catch (\Exception $e) {
      $e_method = "AssemblyPartTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyPartStore(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route("18, 19");
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[      
        'assembly' => 'required', 
        'part_no' => 'required',
      ];
      $validator = Validator::make($request->all(),$rules);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $ac_id = intval(Crypt::decrypt($request->assembly));

      $rs_fetch = DB::select(DB::raw("SELECT `district_id` from `assemblys` where `id` = $ac_id limit 1;"));

      $d_id = 0;
      if(count($rs_fetch) > 0){
        $d_id = $rs_fetch[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $asmb_parts = intval(substr(MyFuncs::removeSpacialChr($request->part_no), 0, 3));
      $rs_save = DB::select(DB::raw("call up_create_assembly_part ($ac_id, $asmb_parts, 0);"));
      $response=['status'=>1,'msg'=>'Submit Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "AssemblyPartStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyPartDelete($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(18);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $role_id = MyFuncs::getUserRoleId();
      if($role_id >= 3){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rec_id = intval(Crypt::decrypt($id));


      $rs_fetch = DB::select(DB::raw("SELECT `district_id` from `assemblys` where `id` = (select `assembly_id` from `assembly_parts` where `id` = $rec_id limit 1);"));
      $d_id = 0;
      if(count($rs_fetch) > 0){
        $d_id = $rs_fetch[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      
      $rs_delete = DB::select(DB::raw("DELETE from `assembly_parts` where `id` = $rec_id limit 1;"));
      $response=['status'=>1,'msg'=>'Part Deleted Successfully'];
      return response()->json($response);  
    } catch (\Exception $e) {
      $e_method = "AssemblyPartDelete";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function booth_form()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(25);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.booth.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "booth_form";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function boothTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(25);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $role_id = MyFuncs::getUserRoleId();
      $village_id = intval(Crypt::decrypt($request->id));

      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }
      $rs_booths = DB::select(DB::raw("SELECT * from `polling_booths` where `village_id` = $village_id order by `booth_no`, `booth_no_c`;"));
      return view('admin.master.booth.table',compact('rs_booths', 'role_id')); 
    } catch (\Exception $e) {
      $e_method = "boothTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }    
  }

  public function boothStore(Request $request, $id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(25);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'booth_no' => 'required|max:5',     
        'booth_name_english' => 'required|max:100', 
        'booth_name_local' => 'required|max:100', 
      ];
      $customMessages = [
        'booth_no.required'=> 'Please Enter Polling Booth No.',                
        'booth_no.max'=> 'Polling Booth No. Should Be Maximum of 5 Character',

        'booth_name_english.required'=> 'Please Enter Booth Name English',                
        'booth_name_english.max'=> 'Booth Name English Should Be Maximum of 100 Character',

        'booth_name_local.required'=> 'Please Enter Booth Name Local Language',                
        'booth_name_local.max'=> 'Booth Name Local Language Should Be Maximum of 100 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);
      }
      $rec_id = intval(Crypt::decrypt($id));
      
      $booth_no = intval(substr(MyFuncs::removeSpacialChr($request->booth_no), 0, 5));
      $booth_a = substr(MyFuncs::removeSpacialChr($request->booth_no_c), 0, 1);
      $booth_e = substr(MyFuncs::removeSpacialChr($request->booth_name_english), 0, 100);
      $booth_h = MyFuncs::removeSpacialChr($request->booth_name_local);

      $v_id = 0;
      if($rec_id == 0){
        $d_id = intval(Crypt::decrypt($request->district));
        $b_id = intval(Crypt::decrypt($request->block));
        $v_id = intval(Crypt::decrypt($request->village));
      }else{
        $rs_fetch = DB::select(DB::raw("SELECT `village_id` from `polling_booths` where `id` = $rec_id limit 1;"));
        if(count($rs_fetch)>0){
          $v_id = $rs_fetch[0]->village_id;
        }
      }

      $permission_flag = MyFuncs::check_village_access($v_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rs_update = DB::select(DB::raw("call `up_save_polling_booths`($rec_id, $v_id, '$booth_no', '$booth_e', '$booth_h', '$booth_a');"));
      $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->remarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "boothStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function boothEdit($id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(25);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $rec_id = intval(Crypt::decrypt($id));

      $v_id = 0;
      $rs_fetch = DB::select(DB::raw("SELECT `village_id` from `polling_booths` where `id` = $rec_id limit 1;"));
      if(count($rs_fetch)>0){
        $v_id = $rs_fetch[0]->village_id;
      }
      $permission_flag = MyFuncs::check_village_access($v_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }

      $booth = DB::select(DB::raw("SELECT * from `polling_booths` where `id` = $rec_id limit 1;"));
      return view('admin.master.booth.edit',compact('booth'));     
    } catch (\Exception $e) {
      $e_method = "boothEdit";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function boothDelete($id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(25);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $role_id = MyFuncs::getUserRoleId();
      if($role_id >= 3){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rec_id = intval(Crypt::decrypt($id));

      $v_id = 0;
      $rs_fetch = DB::select(DB::raw("SELECT `village_id` from `polling_booths` where `id` = $rec_id limit 1;"));
      if(count($rs_fetch)>0){
        $v_id = $rs_fetch[0]->village_id;
      }
      $permission_flag = MyFuncs::check_village_access($v_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      
      $booth = DB::select(DB::raw("DELETE from `polling_booths` where `id` = $rec_id limit 1;"));
      $response=['status'=>1,'msg'=>'Polling Booth Deleted Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "boothDelete";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }     
  }

  public function voterListIndex()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(15);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.voterlistmaster.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "voterListIndex";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function voterListTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(15);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $block_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }
      
      $rs_voter_list_master = DB::select(DB::raw("SELECT * from `voter_list_master` where `block_id` = $block_id order by `id` desc;"));
        
      return view('admin.voterlistmaster.table',compact('rs_voter_list_master'));
    } catch (\Exception $e) {
      $e_method = "voterListTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function storeVoterListType(Request $request, $id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(15);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'voter_list_name' => 'required|max:200',
        'voter_list_type' => 'required|max:200',
        'publication_year' => 'required|max:4',
        'date_of_publication' => 'required|max:20',
        'base_year' => 'required|max:4',
        'base_date' => 'required|max:20',
        'remarks1' => 'required|max:100',
      ];
      $customMessages = [
        'voter_list_name.required'=> 'Please Enter Voter List Name',                
        'voter_list_name.max'=> 'Voter List Name Should Be Maximum of 200 Character',

        'voter_list_type.required'=> 'Please Enter Voter List Type',                
        'voter_list_type.max'=> 'Voter List Type Should Be Maximum of 200 Character',

        'publication_year.required'=> 'Please Enter Publication Year',                
        'publication_year.max'=> 'Publication Year Should Be Maximum of 4 Character',

        'date_of_publication.required'=> 'Please Enter Date of Publication',                
        'date_of_publication.max'=> 'Date of Publication Should Be Maximum of 20 Character',

        'base_year.required'=> 'Please Enter Base Year',                
        'base_year.max'=> 'Base Year Should Be Maximum of 4 Character',

        'base_date.required'=> 'Please Enter Base Date',                
        'base_date.max'=> 'Base Date Should Be Maximum of 20 Character',

        'remarks1.required'=> 'Please Enter पुनरिक्षण का विवरण',                
        'remarks1.max'=> 'पुनरिक्षण का विवरण Should Be Maximum of 100 Character',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }

      $block_id = 0;
      $rec_id = intval(Crypt::decrypt($id));
      if($rec_id == 0){
        $block_id = intval(Crypt::decrypt($request->block));  
      }else{
        $rs_fetch = DB::select(DB::raw("SELECT `block_id` from `voter_list_master` where `id` = $rec_id limit 1;"));
        if(count($rs_fetch) > 0){
          $block_id = $rs_fetch[0]->block_id;
        }
      }
      
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $list_name = substr(MyFuncs::removeSpacialChr($request->voter_list_name), 0, 200);
      $list_type = substr(MyFuncs::removeSpacialChr($request->voter_list_type), 0, 200);
      $year_pub = intval(substr(MyFuncs::removeSpacialChr($request->publication_year), 0, 4));
      $pub_date = substr(MyFuncs::removeSpacialChr($request->date_of_publication), 0, 20);
      $year_base = intval(substr(MyFuncs::removeSpacialChr($request->base_year), 0, 4));
      $base_date = substr(MyFuncs::removeSpacialChr($request->base_date), 0, 20);
      $rem1 = substr(MyFuncs::removeSpacialChr($request->remarks1), 0, 100);
      $rem2 = substr(MyFuncs::removeSpacialChr($request->remarks2), 0, 100);
      $rem3 = substr(MyFuncs::removeSpacialChr($request->remarks3), 0, 100);

      if (empty($request->is_supplement)) {
        $supliment_flag = 0;
      }
      else{
        $supliment_flag = 1;
      }
      
      if($rec_id == 0){
        $rs_update = DB::select(DB::raw("INSERT into `voter_list_master` (`block_id`, `voter_list_name`, `voter_list_type`, `year_publication`, `year_base`, `date_base`, `date_publication`, `remarks1`, `remarks2`, `remarks3`, `is_supplement`) values ($block_id, '$list_name', '$list_type', '$year_pub', '$year_base', '$base_date', '$pub_date', '$rem1', '$rem2', '$rem3', $supliment_flag);"));  
      }else{
        $rs_update = DB::select(DB::raw("UPDATE `voter_list_master` set `voter_list_name` = '$list_name', `voter_list_type` = '$list_type', `year_publication` = '$year_pub', `year_base` = '$year_base', `date_base` = '$base_date', `date_publication` = '$pub_date', `remarks1` = '$rem1', `remarks2` = '$rem2', `remarks3` = '$rem3', `is_supplement` = $supliment_flag where `id` = $rec_id limit 1;"));
      }

      $response=['status'=>1,'msg'=>'Record Saved Successfully'];
      return response()->json($response);
    } catch (Exception $e) {}
  }

  public function show_votermaster_editform($id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(15);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $block_id = 0;
      $rs_fetch = DB::select(DB::raw("SELECT `block_id` from `voter_list_master` where `id` = $rec_id limit 1;"));
      if(count($rs_fetch) > 0){
        $block_id = $rs_fetch[0]->block_id;
      }
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        return view('admin.common.error_popup');
      }

      $VoterListMaster = DB::select(DB::raw("SELECT * from `voter_list_master` where `id` = $rec_id limit 1;"));
      return view('admin.voterlistmaster.edit',compact('VoterListMaster'));
    } catch (\Exception $e) {
      $e_method = "show_votermaster_editform";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function setVoterListTypeDefault($id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(15);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rec_id = intval(Crypt::decrypt($id));
      $block_id = 0;
      $rs_fetch = DB::select(DB::raw("SELECT `block_id` from `voter_list_master` where `id` = $rec_id limit 1;"));
      if(count($rs_fetch) > 0){
        $block_id = $rs_fetch[0]->block_id;
      }
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }

      $rs_update = DB::select(DB::raw("call `up_set_voterListType_Default` ($rec_id);"));
      $response=['status'=>1,'msg'=>'Default Value Set Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "setVoterListTypeDefault";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

//wardBandi
  public function wardBandi()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(41);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      return view('admin.master.wardbandi.index',compact('rs_district', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "wardBandi";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardBandiFrom(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(41);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->id));
      
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }
      $assemblyParts = DB::select(DB::raw("SELECT `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $village_id order by `ac`.`code`, `ap`.`part_no`;"));
      $WardVillages = DB::select(DB::raw("call up_fetch_ward_village_access ($village_id, 0)")); 
      $rs_dataList = DB::select(DB::raw("SELECT * from `import_type` order by `id`;")); 
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      return view('admin.master.wardbandi.form',compact('assemblyParts','WardVillages', 'rs_dataList', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "wardBandiFrom";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardBandiFilterAssemblyPart(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(41);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $data_list_id = 0;
      $part_id = 0;
      if ($request->data_list_id!='null'){
        $data_list_id = intval(Crypt::decrypt($request->data_list_id));  
      }
      if ($request->part_id!='null'){
        $part_id = intval(Crypt::decrypt($request->part_id));  
      }
      $rs_voterLists = DB::select(DB::raw("SELECT `v`.`id`, `v`.`sr_no`, `v`.`voter_card_no`, `v`.`name_e`, `v`.`name_l`, `v`.`father_name_l`, `vil`.`name_l` as `vil_name`, `wv`.`ward_no`, `v`.`village_id` from `voters` `v`Left Join `villages` `vil` on `vil`.`id` = `v`.`village_id` Left Join `ward_villages` `wv` on `wv`.`id` = `v`.`ward_id` Where `v`.`assembly_part_id` = $part_id and `v`.`data_list_id` = $data_list_id;"));
      return view('admin.master.wardbandi.voter_list',compact('rs_voterLists', 'refreshdata'));
    } catch (Exception $e) {}
  }

  public function wardBandiFilterward(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(41);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $refresh = $request->refresh;
      $ward_id = intval(Crypt::decrypt($request->id));
      $total_mapped = DB::select(DB::raw("SELECT count(*) as `total_mapped` from `voters` where `ward_id` = $ward_id;"));   
      return view('admin.master.wardbandi.sr_no_form',compact('total_mapped', 'refresh'));
    } catch (\Exception $e) {
      $e_method = "WardBandiFilterward";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardBandiStore(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(41);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'data_list' => 'required', 
        'assembly_part' => 'required', 
        'ward' => 'required', 
        'from_sr_no' => 'required', 
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'data_list.required'=> 'Please Select Data List',
        'assembly_part.required'=> 'Please Select Assembly-Part',
        'ward.required'=> 'Please Select Ward',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $user_id = MyFuncs::getUserId();
      $data_list = intval(Crypt::decrypt($request->data_list));
      $ac_part_id = intval(Crypt::decrypt($request->assembly_part));
      $ward_id = intval(Crypt::decrypt($request->ward));

      $forcefully = 0;
      if ($request->forcefully == 1) {
        $forcefully = 1; 
      }

      if ($request->from_sr_no == null) {
        $from_sr_no = 0;
      }else {
        $from_sr_no = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 4));
      }
      if ($request->to_sr_no == null) {
        $to_sr_no = 0;
      }else  {
        $to_sr_no = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 4));
      }

      $message = DB::select(DB::raw("SELECT `uf_ward_bandi_voters` ($user_id, $ac_part_id, $ward_id, 0, $from_sr_no, $to_sr_no, $forcefully, $data_list) as `save_remarks`;"));
      
      $response=['status'=>1,'msg'=>$message[0]->save_remarks];  
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "WardBandiStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function removeVoter_wardbandi($id)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route("41, 42");
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $user_id = MyFuncs::getUserId();
      $vid = intval(Crypt::decrypt($id));
      $rs_update = DB::select(DB::raw("call `up_reset_voters_wardbandi` ($user_id, $vid);"));
      $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->Save_Result];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "removeVoter_wardbandi";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function WardBandiReport(Request $request)
  { 
    try{
      $permission_flag = MyFuncs::isPermission_route("41, 42");
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      $village = $request->village;
      $assembly_part = $request->assembly_part;
      $ward = $request->ward;
      return view('admin.master.wardbandi.report',compact('village','assembly_part','ward')); 
    } catch (\Exception $e) {
      $e_method = "WardBandiReport";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function WardBandiReportGenerate(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route("41, 42");
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $ac_part_id = intval(Crypt::decrypt($request->assembly_part));
      $village_id = intval(Crypt::decrypt($request->village));
      $ward_id = intval(Crypt::decrypt($request->ward));
      if ($request->report == 1) {
        $assemblyPart = DB::select(DB::raw("SELECT * from `assembly_parts` Where `id` = $ac_part_id limit 1;"));
        $ac_id = $assemblyPart[0]->assembly_id;
        $assembly = DB::select(DB::raw("SELECT * from `assemblys` Where `id` = $ac_id limit 1;"));
        $voterReports = DB::select(DB::raw("SELECT `v`.`sr_no`, `v`.`name_l`, `v`.`father_name_l`, `vil`.`name_l` as `vil_name`, `wv`.`ward_no` from `voters` `v` Left Join `villages` `vil` on `vil`.`id` = `v`.`village_id` Left Join `ward_villages` `wv` on `wv`.`id` = `v`.`ward_id`Where `v`.`assembly_part_id` = $ac_part_id order By `v`.`sr_no`;")); 
      }elseif ($request->report==2) {
        $assemblyPart = DB::select(DB::raw("SELECT * from `assembly_parts` Where `id` = $ac_part_id limit 1;"));
        $ac_id = $assemblyPart[0]->assembly_id;
        $assembly = DB::select(DB::raw("SELECT * from `assemblys` Where `id` = $ac_id limit 1;"));
        $voterReports = DB::select(DB::raw("select `v`.`sr_no`, `v`.`name_l`, `v`.`father_name_l` from `voters` `v` Where `v`.`assembly_part_id` = $ac_part_id and `v`.`village_id` = 0 order By `v`.`sr_no` ;")); 
      }elseif ($request->report==3) {
        $village = DB::select(DB::raw("SELECT * from `villages` Where `id` = $village_id limit 1;"));
        $wardVillage = DB::select(DB::raw("SELECT * from `ward_villages` Where `id` = $request->ward_id limit 1;"));
        
        $voterReports = DB::select(DB::raw("SELECT `a`.`code`, `ap`.`part_no`, `v`.`sr_no`, `v`.`name_l`, `v`.`father_name_l`from `voters` `v`Left Join `assemblys` `a` on `a`.`id` = `v`.`assembly_id`Left Join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id`Where `v`.`ward_id` = $request->ward_id order By `v`.`sr_no`;"));
      } 
      
      $path=Storage_path('fonts/');
      $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
      $fontDirs = $defaultConfig['fontDir']; 
      $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
      $fontData = $defaultFontConfig['fontdata']; 
      $mpdf = new \Mpdf\Mpdf([
             'fontDir' => array_merge($fontDirs, [
                 __DIR__ . $path,
             ]),
             'fontdata' => $fontData + [
                 'frutiger' => [
                     'R' => 'FreeSans.ttf',
                     'I' => 'FreeSansOblique.ttf',
                 ]
             ],
             'default_font' => 'freesans',
             'pagenumPrefix' => '',
            'pagenumSuffix' => '',
            'nbpgPrefix' => ' कुल ',
            'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
      ]); 
            
      if ($request->report==1) {
        $html = view('admin.master.wardbandi.report_list',compact('voterReports','assemblyPart','assembly'));
      }elseif ($request->report==2) {
        $html = view('admin.master.wardbandi.report_list2',compact('voterReports','assemblyPart','assembly'));
      }elseif ($request->report==3) {
        $html = view('admin.master.wardbandi.report_list3',compact('voterReports','village','wardVillage'));
      }
      $mpdf->WriteHTML($html); 
      $mpdf->Output();
    } catch (\Exception $e) {
      $e_method = "WardBandiReportGenerate";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardBandiWithBooth()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(42);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.wardbandiwithbooth.index',compact('rs_district', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "wardBandiWithBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function villageWiseAssemblyWard(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(42);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        return view('admin.common.error');
      }
      $assemblyParts = DB::select(DB::raw("SELECT `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $village_id order by `ac`.`code`, `ap`.`part_no`;"));
      $rs_dataList = DB::select(DB::raw("SELECT * from `import_type` order by `id`;"));
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ($village_id, 0)"));   
      return view('admin.master.wardbandiwithbooth.assembly_ward_select_box',compact('assemblyParts','WardVillages', 'rs_dataList'));
    } catch (\Exception $e) {
      $e_method = "villageWiseAssemblyWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function assemblywisevoterMapped(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(42);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $data_list_id = 0;
      $part_id = 0;
      if ($request->data_list_id!='null'){
        $data_list_id = intval(Crypt::decrypt($request->data_list_id));  
      }
      if ($request->part_id!='null'){
        $part_id = intval(Crypt::decrypt($request->part_id));  
      }
      $rs_voterLists = DB::select(DB::raw("SELECT `v`.`id`, `v`.`sr_no`, `v`.`name_l`, `v`.`father_name_l`, `wv`.`ward_no`, `po`.`booth_no`, `v`.`name_e`, `v`.`father_name_e`, `v`.`voter_card_no` from `voters` `v` Left join `ward_villages` `wv` on `wv`.`id` = `v`.`ward_id` Left Join `polling_booths` `po` on `po`.`id` = `v`.`Booth_Id`  Where `v`.`assembly_part_id` = $part_id and `v`.`data_list_id` = $data_list_id Order By `v`.`sr_no`;"));  
      return view('admin.master.wardbandiwithbooth.voter_list',compact('rs_voterLists', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "AssemblywisevoterMapped";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardWiseBooth(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(42);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->village_id));
      $ward_id = intval(Crypt::decrypt($request->ward_id));
      $selectbooths = DB::select(DB::raw("SELECT `id`, concat(`booth_no`,ifnull(`booth_no_c`,''),' - ',`name_e`) as `booth_name` from `polling_booths` Where `village_id` = $village_id and `id` in (select `boothid` from `booth_ward_voter_mapping` where `wardId` = $ward_id) Order by `booth_name`;"));
      return view('admin.master.wardbandiwithbooth.booth_select_box',compact('selectbooths'));
    } catch (\Exception $e) {
      $e_method = "wardWiseBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function boothWiseTotalMappedWard(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(42);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $booth_id = intval(Crypt::decrypt($request->id));
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $total_mapped=DB::select(DB::raw("SELECT count(*) as `total_mapped` from `voters` where `booth_id` = $booth_id;"));   
      return view('admin.master.wardbandiwithbooth.sr_no_form',compact('total_mapped', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "boothWiseTotalMappedWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function wardBandiWithBoothStore(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(42);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'data_list' => 'required',
        'assembly_part' => 'required', 
        'ward' => 'required', 
        'booth' => 'required', 
        'from_sr_no' => 'required',
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'data_list.required'=> 'Please Select Data List',
        'assembly_part.required'=> 'Please Select Assembly-Part',
        'ward.required'=> 'Please Select Ward',
        'booth.required'=> 'Please Select booth',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $user_id = MyFuncs::getUserId();
      $data_list = intval(Crypt::decrypt($request->data_list));
      $ac_part_id = intval(Crypt::decrypt($request->assembly_part));
      $ward_id = intval(Crypt::decrypt($request->ward));
      $booth_id = intval(Crypt::decrypt($request->booth));

      $forcefully = 0;
      if ($request->forcefully == 1) {
        $forcefully = 1; 
      }

      if ($request->from_sr_no == null) {
        $from_sr_no = 0;
      }else {
        $from_sr_no = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 4));
      }
      if ($request->to_sr_no == null) {
        $to_sr_no = 0;
      }else  {
        $to_sr_no = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 4));
      }

      $message = DB::select(DB::raw("SELECT `uf_ward_bandi_voters` ($user_id, $ac_part_id, $ward_id, $booth_id, $from_sr_no, $to_sr_no, $forcefully, $data_list) as `save_remarks`;"));

      $response=['status'=>1,'msg'=>$message[0]->save_remarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "wardBandiWithBoothStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

//MappingVillageAssemblyPart

  public function MappingVillageAssemblyPart()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(74);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.mappingvillageassemblypart.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "MappingVillageAssemblyPart";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingVillageAssemblyPartFilter(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(74);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $district_id = intval(Crypt::decrypt($request->district_id));
      $rs_assemblys = DB::select(DB::raw("SELECT * from `assemblys` where `district_id` = $district_id order by `code`;"));         
      return view('admin.master.mappingvillageassemblypart.ac_part_form',compact('rs_assemblys'));
    } catch (\Exception $e) {
      $e_method = "MappingVillageAssemblyPartFilter";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingVillageAssemblyPartTable(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(74);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->id));
      $rs_records = DB::select(DB::raw("SELECT `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $village_id order by `ac`.`code`, `ap`.`part_no`;"));
         
      return view('admin.master.mappingvillageassemblypart.table',compact('rs_records'));
    } catch (Exception $e) {
      $e_method = "MappingVillageAssemblyPartTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyWisePartNoUnmapped(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(74);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $ac_id = intval(Crypt::decrypt($request->id));
      $rs_parts = DB::select(DB::raw("SELECT * from `assembly_parts` where `assembly_id` = $ac_id and `village_id` = 0 order by `part_no`;"));
      return view('admin.master.assemblypart.part_no_select_box',compact('rs_parts'));  
    } catch (\Exception $e) {
      $e_method = "imageShowPath";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingVillageAssemblyPartStore(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(74);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'district' => 'required', 
        'block_mcs' => 'required', 
        'village' => 'required', 
        'assembly' => 'required', 
        'part_no' => 'required', 
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block_mcs.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'assembly.required'=> 'Please Select Assembly',
        'part_no.required'=> 'Please Select Part No.',        
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $village_id = intval(Crypt::decrypt($request->village));
      $part_id = intval(Crypt::decrypt($request->part_no));
      $rs_update = DB::select(DB::raw("UPDATE `assembly_parts` set `village_id` = $village_id where `id` = $part_id limit 1;"));
      $response=['status'=>1,'msg'=>'Record Saved Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "MappingVillageAssemblyPartStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingVillageAssemblyPartRemove($assemblyPart_id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(74);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $part_id = intval(Crypt::decrypt($request->assemblyPart_id));
      $rs_update = DB::select(DB::raw("UPDATE `assembly_parts` set `village_id` = 0 where `id` = $part_id limit 1;"));      
      $response=['status'=>1,'msg'=>'Remove Successfully'];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "MappingVillageAssemblyPartRemove";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingAcPartWithPanchayat()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(77);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.mappingAssemblyPartPanchayat.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "MappingAcPartWithPanchayat";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function mappingDistrictWiseAssembly(Request $request)
  { 
    try{
      $permission_flag = MyFuncs::isPermission_route(77);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $district_id = intval(Crypt::decrypt($request->id));
      $assemblys = DB::select(DB::raw("SELECT * from `assemblys` where `district_id` = $district_id order by `code`;"));
      return view('admin.master.assembly.assembly_value_select_box',compact('assemblys'));
    } catch (\Exception $e) {
      $e_method = "mappingDistrictWiseAssembly";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AssemblyWisePartNoAll(Request $request)
  { 
    try {
      // $permission_flag = MyFuncs::isPermission_route(77);
      // if(!$permission_flag){
      //   return view('admin.common.error');
      // }

      $ac_id = intval(Crypt::decrypt($request->id));

      $d_id = 0;
      $rs_fetch = DB::select(DB::raw("SELECT `district_id` from `assemblys` where `id` = $ac_id limit 1;"));
      if(count($rs_fetch) > 0){
        $d_id = $rs_fetch[0]->district_id;
      }
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        $ac_id = 0;
      }
      
      $rs_records = DB::select(DB::raw("SELECT `id` as `opt_id`, `part_no` as `opt_text` from `assembly_parts` where `assembly_id` = $ac_id order by `part_no`;"));
      $show_disabled = 1;
      $box_caption = "Part No.";
      return view('admin.common.select_box_v1',compact('rs_records'));  
    } catch (\Exception $e) {
      $e_method = "AssemblyWisePartNoAll";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingAcPartVillage(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(77);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $part_id = intval(Crypt::decrypt($request->part_no));
      $rs_villages = DB::select(DB::raw("SELECT `vil`.`name_e` from `assembly_parts` `ap` inner join `villages` `vil` on `vil`.`id` = `ap`.`village_id` where `ap`.`id` = $part_id limit 1;"));
      return view('admin.master.mappingAssemblyPartPanchayat.table',compact('rs_villages'));
    } catch (\Exception $e) {
      $e_method = "MappingAcPartVillage";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function AcPartVillageMappingStore(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(77);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'district' => 'required', 
        'block_mcs' => 'required', 
        'village' => 'required', 
        'assembly' => 'required', 
        'part_no' => 'required', 
      ]; 
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block_mcs.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'assembly.required'=> 'Please Select Assembly',
        'part_no.required'=> 'Please Select Part No.',        
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $village_id = intval(Crypt::decrypt($request->village));
      $part_id = intval(Crypt::decrypt($request->part_no));

      DB::select(DB::raw("UPDATE `assembly_parts` set `village_id` = $village_id where `id` = $part_id limit 1;"));
      $response=['status'=>1,'msg'=>'Records Saved Successfully'];
      return response()->json($response);    
    } catch (\Exception $e) {
      $e_method = "AcPartVillageMappingStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingBoothWard()
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(71);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.mappingBoothWard.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "MappingBoothWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingVillageWiseBooth(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(71);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->village_id));
      $booths = DB::select(DB::raw("SELECT * from `polling_booths` where `village_id` = $village_id order by `booth_no`, `booth_no_c`;"));
      return view('admin.master.booth.booth_select_box',compact('booths'));
    } catch (\Exception $e) {
      $e_method = "MappingVillageWiseBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingVillageOrBoothWiseWard(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(71);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->village_id));
      $booth_id = intval(Crypt::decrypt($request->id));
      $wards=DB::select(DB::raw("SELECT `id`, `ward_no`, 0 as `status` from `ward_villages` where `village_id` = $village_id and `id` not in (Select `wardId` from `booth_ward_voter_mapping`) Union select `id`, `ward_no`, 1 as `status` from `ward_villages` where `village_id` = $village_id and `id` in (Select `wardId` from `booth_ward_voter_mapping` where `boothid` = $booth_id) Order By `ward_no`;"));

      return view('admin.master.mappingBoothWard.ward_select_box',compact('wards'));
    } catch (\Exception $e) {
      $e_method = "MappingVillageOrBoothWiseWard";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingBoothWardStore(Request $request)
  {  
    try{
      $permission_flag = MyFuncs::isPermission_route(71);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'booth' => 'required',
      ]; 
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'booth.required'=> 'Please Select Booth',      
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $village_id = intval(Crypt::decrypt($request->village));
      $booth_id = intval(Crypt::decrypt($request->booth));
      if (!empty($request->ward)) {
        $ward = implode(',',$request->ward);  
      }else {
       $ward = 0;  
      }   
      $rs_save = DB::select(DB::raw("call `up_process_booth_ward_voters` ('$booth_id','$ward', $village_id)"));
      $rs_save = DB::select(DB::raw("call `up_map_booth_ward` ('$booth_id','$ward')"));
      $response=['status'=>1,'msg'=>'Submit Successfully'];
      return response()->json($response); 
    } catch (Exception $e) {
      $e_method = "MappingBoothWardStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingWardBooth()
  {
    try{
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.mappingWardBooth.index',compact('rs_district'));     
    } catch (\Exception $e) {
      $e_method = "MappingWardBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingWardBoothTable(Request $request)
  {
    try{
      $ward_id = intval(Crypt::decrypt($request->id));
      $rs_booths = DB::select(DB::raw("SELECT `bwm`.`id`, `pb`.`booth_no`,`pb`.`booth_no_c`,`pb`.`name_e`, `pb`.`name_l`, `bwm`.`fromsrno`, `bwm`.`tosrno` from `booth_ward_voter_mapping` `bwm` Inner Join `polling_booths` `pb` on `pb`.`id` = `bwm`.`boothid` Where `bwm`.`wardId` = $ward_id Order By `bwm`.`fromsrno`;"));
      return view('admin.master.mappingWardBooth.table',compact('rs_booths'));   
    } catch (Exception $e) {
      $e_method = "MappingWardBoothTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingWardBoothSelectBooth(Request $request)
  {
    try{
      $village_id = intval(Crypt::decrypt($request->village_id));
      $ward_id = intval(Crypt::decrypt($request->id));
      $booths=DB::select(DB::raw("SELECT `id`, `booth_no`,`booth_no_c`,`name_e` from `polling_booths` Where `village_id` = $village_id and `id` not in (Select `boothid` from `booth_ward_voter_mapping`  Where `wardId` = $ward_id) Order By `booth_no`;"));
      return view('admin.master.booth.booth_select_box',compact('booths'));
    } catch (Exception $e) {
      $e_method = "MappingWardBoothSelectBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingWardBoothStore(Request $request)
  {
    try{
      $rules=[
        'ward' => 'required', 
        'booth' => 'required',
        'from_sr_no' => 'required',
        'to_sr_no' => 'required',
      ];
      $customMessages = [
        'ward.required'=> 'Please Select Ward',
        'booth.required'=> 'Please Select booth',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
        'to_sr_no.required'=> 'Please Enter To Sr. No.',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }

      $ward_id = intval(Crypt::decrypt($request->ward));
      $booth_id = intval(Crypt::decrypt($request->booth));

      if ($request->from_sr_no == null) {
        $from_sr_no = 0;
      }else {
        $from_sr_no = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 5));
      }
      if ($request->to_sr_no == null) {
        $to_sr_no = 0;
      }else  {
        $to_sr_no = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 5));
      }

      if (empty($request->rec_id)) {
         $rec_id = 0;
      }else {
         $rec_id = intval(Crypt::decrypt($request->rec_id));
      }
      $message = DB::select(DB::raw("call `up_process_ward_booth_voters` ($booth_id, $ward_id, $from_sr_no, $to_sr_no);"));
      $message = DB::select(DB::raw("call `up_map_ward_booth_voters` ($rec_id, $ward_id, $from_sr_no, $to_sr_no, $booth_id);"));
      $response=['status'=>$message[0]->save_status,'msg'=>$message[0]->Save_Result];
        return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "MappingWardBoothStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }  
  }

  public function MappingWardBoothEdit($id)
  {
    try{
      $rec_id = intval(Crypt::decrypt($id));
      $BoothWardVoterMapping = DB::select(DB::raw("SELECT * from `booth_ward_voter_mapping` where `id` = $rec_id limit 1;"));
      return view('admin.master.mappingWardBooth.edit',compact('BoothWardVoterMapping'));      
    } catch (\Exception $e) {
      $e_method = "MappingWardBoothEdit";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  //--------MappingWardWithMultipleBooth---------
  public function MappingWardWithMultipleBooth()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(73);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      
      $rs_district = SelectBox::get_district_access_list_v1();  
      return view('admin.master.MappingWardWithMultipleBooth.index',compact('rs_district'));

    } catch (\Exception $e) {
      $e_method = "MappingWardWithMultipleBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingWardWithMultipleBoothWardWiseBooth(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(73);
      if(!$permission_flag){
        return view('admin.common.error');
      }

      $vil_id = intval(Crypt::decrypt($request->village_id));
      $ward_id = intval(Crypt::decrypt($request->ward_id));

      $permission_flag = MyFuncs::check_village_access($vil_id);
      if($permission_flag == 0){
        $vil_id = 0;  
      }


      $booths = DB::select(DB::raw("SELECT `id`, concat(`booth_no`, `booth_no_c`,' - ', `name_e`) as `booth_name`, 0 as `status` From `polling_booths` Where `village_id` = $vil_id and `id` not in (select `boothid` from `booth_ward_voter_mapping` where  `is_complete_booth` = 1) Union Select `id`, concat(`booth_no`, ' - ', `name_e`) as `booth_name`, 1 as `status` From `polling_booths` Where `village_id` = $vil_id and `id` in (select `boothid` from `booth_ward_voter_mapping` where `wardId` =$ward_id and `is_complete_booth` = 1) Order by `booth_name`;"));   
      return view('admin.master.MappingWardWithMultipleBooth.select_box',compact('booths'));
    } catch (\Exception $e) {
      $e_method = "MappingWardWithMultipleBoothWardWiseBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function MappingWardWithMultipleBoothStore(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(73);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong']; 
        return response()->json($response);
      }

      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'ward' => 'required', 
      ];

      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block/MC',
        'village.required'=> 'Please Select Panchayat/MC',
        'ward.required'=> 'Please Select Ward No.',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }

      $vil_id = intval(Crypt::decrypt($request->village));
      $permission_flag = MyFuncs::check_village_access($vil_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong']; 
        return response()->json($response);
      }

      $ward_id = intval(Crypt::decrypt($request->ward));

      $booth_id=0;

      if (!empty($request->booth)){
        foreach ($request->booth as $key => $value) {
          $val_booth_id = intval(Crypt::decrypt($value));
          $booth_id=$booth_id.",".$val_booth_id;
        }
      }

      $saveBooth = DB::select(DB::raw("call `up_map_ward_booths`('$ward_id','$booth_id');"));

      $response=['status'=>1,'msg'=>'Updated Successfully']; 
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "MappingWardWithMultipleBoothStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVoterWardWithBooth()
  {
    try {
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.changeVoterWardWithBooth.index',compact('rs_district', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "changeVoterWardWithBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVotervillageWiseWardBooth(Request $request)
  {
    try{
      $village_id = intval(Crypt::decrypt($request->id));
      $refreshdata = MyFuncs::Refresh_data_voterEntry();  
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ($village_id, '0')")); 
      return view('admin.master.changeVoterWardWithBooth.ward_booth_select',compact('WardVillages', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "changeVotervillageWiseWardBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVoterWardWithBoothTable(Request $request)
  {
    try{
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $ward_id = intval(Crypt::decrypt($request->from_ward_id));
      $booth_id = intval(Crypt::decrypt($request->from_booth));
      $results = DB::select(DB::raw("call `up_fetch_list_suppliment_deleted_voter_detail` (0, $ward_id, $booth_id);"));  
      return view('admin.master.changeVoterWithWard.table',compact('results', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "changeVoterWardWithBoothTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVoterWardWithBoothStore(Request $request)
  {
    try {
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'from_ward' => 'required', 
        'from_booth' => 'required', 
        'from_sr_no' => 'required',
        'to_ward' => 'required', 
        'to_booth' => 'required', 
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'from_ward.required'=> 'Please Select From Ward',
        'from_booth.required'=> 'Please Select From Booth',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
        'to_ward.required'=> 'Please Select To Ward',
        'to_booth.required'=> 'Please Select To Booth',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $from_sn = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 5));
      $to_sn = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 5));
      if($from_sn == 0 && $to_sn == 0){
        $response=['status'=>0,'msg'=>'From Sr. No. and To Sr. No. Cannot Be Blank'];
        return response()->json($response);  
      }
      if($from_sn == 0){
        $from_sn = $to_sn;
      }
      if($to_sn == 0){
        $to_sn = $from_sn;
      }
      $from_booth = intval(Crypt::decrypt($request->from_booth));
      $to_booth = intval(Crypt::decrypt($request->to_booth));

      if($from_booth == $to_booth){
        $response=['status'=>0,'msg'=>'From Polling booth and To Polling Booth Cannot Be Same'];
        return response()->json($response);  
      }
      $user_id = MyFuncs::getUserId();
      $block_id = intval(Crypt::decrypt($request->block));
      $village_id = intval(Crypt::decrypt($request->village));
      $from_ward = intval(Crypt::decrypt($request->from_ward));
      $to_ward = intval(Crypt::decrypt($request->to_ward));
      
      $rs_update = DB::select(DB::raw("call `up_change_voters_wards` ($user_id, $block_id, $village_id, $from_ward, $from_booth, $from_sn, $to_sn, $to_ward, $to_booth)"));
      $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "changeVoterWardWithBoothStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVoterWithWardReStore($id, $ward_id)
  {
    try{
      $user_id = MyFuncs::getUserId();
      $rec_id = intval(Crypt::decrypt($id));
      $ward_id = intval(Crypt::decrypt($ward_id));
      $rs_restore = DB::select(DB::raw("call `up_restore_ward_booth_change` ($user_id, '$id', $ward_id)"));
      $rs_restore = reset($rs_restore);

      $response=['status'=>$rs_restore->rstatus,'msg'=>$rs_restore->rremarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "changeVoterWithWardReStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVoterWardWithBoothReport(Request $request)
  {
    try{
      $village_id = intval(Crypt::decrypt($request->village_id));
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ($village_id, 0);"));   
      return view('admin.master.changeVoterWardWithBooth.report_popup',compact('WardVillages'));
    } catch (\Exception $e) {
      $e_method = "changeVoterWardWithBoothReport";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function changeVoterWardWithBoothReportPdf(Request $request)
  {
    try {
      if (empty($request->report_type)) {
        $response=['status'=>0,'msg'=>'Plz Select Report Type'];
        return response()->json($response);   
      }
      if (empty($request->ward)) {
        $response=['status'=>0,'msg'=>'Plz Select Ward'];
        return response()->json($response);   
      }
      if (empty($request->booth)) {
        $response=['status'=>0,'msg'=>'Plz Select Polling Booth'];
        return response()->json($response);   
      }
      $report_selected = intval(Crypt::decrypt($request->report_type));
      $ward_id = intval(Crypt::decrypt($request->ward));
      $booth_id = intval(Crypt::decrypt($request->booth));

      $wardno_rs = DB::select(DB::raw("SELECT `wv`.`ward_no` from `ward_villages` `wv` where `wv`.`id` = $ward_id;"));
      $wardno = $wardno_rs[0]->ward_no;

      $booth_rs = DB::select(DB::raw("SELECT concat(`booth_no`, `booth_no_c`) as `booth` from `polling_booths` where `id` = $booth_id;"));
      $polling_booth = $booth_rs[0]->booth;
      
      $report_heading = '';
      if ($report_selected == 1) {
        $results= DB::select(DB::raw("call `up_fetch_list_suppliment_deleted_voter_detail`(0, $ward_id, $booth_id);"));
        $report_heading = 'Deleted (From Ward And Polling Booth) :: '.$wardno.' And '.$polling_booth;
      }else{
        $results= DB::select(DB::raw("call `up_fetch_list_suppliment_new_voter_detail`($ward_id, $booth_id);"));
        $report_heading = 'Added (To Ward And Polling Booth) :: '.$wardno.' And '.$polling_booth;
      }
      $path=Storage_path('fonts/');
      $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
      $fontDirs = $defaultConfig['fontDir']; 
      $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
      $fontData = $defaultFontConfig['fontdata']; 
      $mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [
          __DIR__ . $path,
        ]),
        'fontdata' => $fontData + [
          'frutiger' => [
            'R' => 'FreeSans.ttf',
            'I' => 'FreeSansOblique.ttf',
          ]
        ],
        'default_font' => 'freesans',
        'pagenumPrefix' => '',
        'pagenumSuffix' => '',
        'nbpgPrefix' => ' कुल ',
        'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
      ]); 
      $showbooth_flag = 1;
      $html = view('admin.master.changeVoterWithWard.pdf',compact('results', 'report_heading', 'showbooth_flag')); 
      $mpdf->WriteHTML($html); 
      $mpdf->Output();
    } catch (\Exception $e) {
      $e_method = "changeVoterWardWithBoothReportPdf";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function deleteSupplimentVoterWardBooth()
  {
    try {
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.deleteVoterWithWardBooth.index',compact('rs_district', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "deleteSupplimentVoterWardBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function showformSupplimentVoterWardBooth(Request $request)
  {
    try{
      $village_id = intval(Crypt::decrypt($request->id));
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ($village_id, 0);")); 
      return view('admin.master.deleteVoterWithWardBooth.ward_booth_select',compact('WardVillages', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "showformSupplimentVoterWardBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function submitSupplimentVoterWardBooth(Request $request)
  {
    try {
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'from_ward' => 'required', 
        'from_booth' => 'required', 
        'from_sr_no' => 'required',
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'from_ward.required'=> 'Please Select Ward',
        'from_booth.required'=> 'Please Select Booth',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $from_sn = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 5));
      $to_sn = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 5));
      if($from_sn == 0 && $to_sn == 0){
        $response=['status'=>0,'msg'=>'From Sr. No. and To Sr. No. Cannot Be Blank'];
        return response()->json($response);  
      }
      if($from_sn == 0){
        $from_sn = $to_sn;
      }
      if($to_sn == 0){
        $to_sn = $from_sn;
      }      
      
      $user_id = MyFuncs::getUserId();
      $block_id = intval(Crypt::decrypt($request->block));
      $village_id = intval(Crypt::decrypt($request->village));
      $from_ward = intval(Crypt::decrypt($request->from_ward));
      $from_booth = intval(Crypt::decrypt($request->from_booth));

      $rs_update = DB::select(DB::raw("call `up_change_voters_wards` ($user_id, $block_id, $village_id, $from_ward, $from_booth, $from_sn, $to_sn, 0, 0)"));
      $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
      return response()->json($response);      
    } catch (\Exception $e) {
      $e_method = "submitSupplimentVoterWardBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }    
  }

  public function claimObjAcPartSrnoChangeBooth()
  {
    try {
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.claimObjAcPartSrno.changebooth.index',compact('rs_district', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoChangeBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function claimObjAcPartSrnoChangeBoothForm(Request $request)
  {
    try{
      $village_id = intval(Crypt::decrypt($request->id)); 
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ($village_id, 0);"));
      $assemblyParts = DB::select(DB::raw("SELECT `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $village_id order by `ac`.`code`, `ap`.`part_no`;"));
      $importTypes = DB::select(DB::raw("select * from `import_type`"));
      return view('admin.master.claimObjAcPartSrno.changebooth.select_box_page',compact('WardVillages','assemblyParts','importTypes', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoChangeBoothForm";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function claimObjAcPartSrnoChangeBoothTable(Request $request)
  {
    try{
      if($request->block_id == "null" || empty($request->block_id)){
        $block_id = 0;
      }else{
        $block_id = intval(Crypt::decrypt($request->block_id));
      }

      if($request->part_id == "null" || empty($request->part_id)){
        $part_id = 0;
      }else{
        $part_id = intval(Crypt::decrypt($request->part_id));
      }

      if($request->data_list_id == "null" || empty($request->data_list_id)){
        $data_list_id = 0;
      }else{
        $data_list_id = intval(Crypt::decrypt($request->data_list_id));
      }
      
      $results= DB::select(DB::raw("SELECT * from `voter_list_master` where `block_id` = $block_id and `status` = 1;"));  
      if(count($results) == 0){
        $voter_list_id  = 0;
      }else{
        $voter_list_id  = $results[0]->id;
      }    
    
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $results= DB::select(DB::raw("SELECT `vt`.`id`, `vt`.`voter_card_no`, `vt`.`name_e`, `vt`.`father_name_e`, `vt`.`sr_no`, `vt`.`print_sr_no`, case `vt`.`status` when 2 then '' else concat(`vil`.`name_e`, ' - ', `wv`.`ward_no`, ' Booth :: ', `pb`.`booth_no`, ifnull(`pb`.`booth_no_c`, '')) end as `vil_ward`, case ifnull(`svd`.`ward_id`,0) when 0 then `vt`.`ward_id` else `svd`.`ward_id` end as `ward_id`, ifnull(concat(`fv`.`name_e`, ' - ', `fw`.`ward_no`, ' Booth :: ', `fpb`.`booth_no`, ifnull(`fpb`.`booth_no_c`, '')),'') as `from_vil_ward`, `vt`.`status` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` left join `suppliment_voters_deleted` `svd` on `svd`.`voters_id` = `vt`.`id` and `svd`.`suppliment_no` = $voter_list_id left join `villages` `fv` on `fv`.`id` = `svd`.`village_id` left join `ward_villages` `fw` on `fw`.`id` = `svd`.`ward_id` left join `polling_booths` `fpb` on `fpb`.`id` = `svd`.`booth_id` where `vt`.`assembly_part_id` = $part_id and `vt`.`data_list_id` = $data_list_id and `vt`.`suppliment_no` = $voter_list_id order by `vt`.`sr_no`;"));  
      $showbooth_flag = 0;
      return view('admin.master.claimObjAcPartSrno.changeWard.table',compact('results', 'showbooth_flag', 'refreshdata'));
    } catch (Exception $e) {
      $e_method = "claimObjAcPartSrnoChangeBoothTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function claimObjAcPartSrnoChangeBoothFormStore(Request $request)
  {
    try {
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required',
        'data_list' => 'required', 
        'assembly_part' => 'required',  
        'from_sr_no' => 'required',
        'to_ward' => 'required', 
        'to_booth' => 'required', 
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'data_list.required'=> 'Please Select Data List',
        'assembly_part.required'=> 'Please Select Assembly Part',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
        'to_ward.required'=> 'Please Select Ward',
        'to_booth.required'=> 'Please Select Booth',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $from_sn = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 5));
      $to_sn = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 5));
      if($from_sn == 0 && $to_sn == 0){
        $response=['status'=>0,'msg'=>'From Sr. No. and To Sr. No. Cannot Be Blank'];
        return response()->json($response);  
      }
      if($from_sn == 0){
        $from_sn = $to_sn;
      }
      if($to_sn == 0){
        $to_sn = $from_sn;
      }
      
      $user_id = MyFuncs::getUserId();
      $block_id = intval(Crypt::decrypt($request->block));
      $village_id = intval(Crypt::decrypt($request->village));
      $data_list = intval(Crypt::decrypt($request->data_list));
      $assembly_part = intval(Crypt::decrypt($request->assembly_part));
      $to_ward = intval(Crypt::decrypt($request->to_ward));
      $to_booth = intval(Crypt::decrypt($request->to_booth));

      $rs_update = DB::select(DB::raw("call `up_change_voters_wards_by_ac_srno` ($user_id, $block_id, $village_id, $assembly_part, $data_list, $from_sn, $to_sn, $to_ward, $to_booth)"));
      $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoChangeBoothFormStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }    
  }

  public function claimObjAcPartSrnoDeleteVoter()
  {
    try {
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.claimObjAcPartSrno.deleteVoter.index',compact('rs_district', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoDeleteVoter";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function claimObjAcPartSrnoDeleteVoterForm(Request $request)
  {
    try {
      $village_id = intval(Crypt::decrypt($request->id));
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $assemblyParts = DB::select(DB::raw("SELECT `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $village_id order by `ac`.`code`, `ap`.`part_no`;"));
      $importTypes = DB::select(DB::raw("select * from `import_type`"));
      return view('admin.master.claimObjAcPartSrno.deleteVoter.form',compact('assemblyParts', 'importTypes', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoDeleteVoterForm";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function claimObjAcPartSrnoDeleteVoterFormTable(Request $request)
  {
    try{
      if($request->block_id == "null" || empty($request->block_id)){
        $block_id = 0;
      }else{
        $block_id = intval(Crypt::decrypt($request->block_id));
      }

      if($request->part_id == "null" || empty($request->part_id)){
        $part_id = 0;
      }else{
        $part_id = intval(Crypt::decrypt($request->part_id));
      }

      if($request->data_list_id == "null" || empty($request->data_list_id)){
        $data_list_id = 0;
      }else{
        $data_list_id = intval(Crypt::decrypt($request->data_list_id));
      }
      
      $results = DB::select(DB::raw("SELECT * from `voter_list_master` where `block_id` = $block_id and `status` = 1;"));  
      if(count($results) == 0){
        $voter_list_id  = 0;
      }else{
        $voter_list_id  = $results[0]->id;
      }    
    
      $refreshdata = MyFuncs::Refresh_data_voterEntry();
      $results= DB::select(DB::raw("SELECT `vt`.`id`, `vt`.`voter_card_no`, `vt`.`name_e`, `vt`.`father_name_e`, `vt`.`sr_no`, `vt`.`print_sr_no`, ifnull(concat(`vil`.`name_e`, ' - ', `wv`.`ward_no`), '') as `vil_ward`, case ifnull(`svd`.`ward_id`,0) when 0 then `vt`.`ward_id` else `svd`.`ward_id` end as `ward_id`, ifnull(concat(`fv`.`name_e`, ' - ', `fw`.`ward_no`),'') as `from_vil_ward`, `vt`.`status` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `suppliment_voters_deleted` `svd` on `svd`.`voters_id` = `vt`.`id` and `svd`.`suppliment_no` = $voter_list_id left join `villages` `fv` on `fv`.`id` = `svd`.`village_id` left join `ward_villages` `fw` on `fw`.`id` = `svd`.`ward_id` where `vt`.`assembly_part_id` = $part_id and `vt`.`data_list_id` = $data_list_id and `vt`.`suppliment_no` = $voter_list_id order by `vt`.`sr_no`;"));  
      return view('admin.master.claimObjAcPartSrno.deleteVoter.table',compact('results', 'refreshdata'));
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoDeleteVoterFormTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }    
  }

  public function claimObjAcPartSrnoDeleteVoterStore(Request $request)
  {
    try {
      $rules=[
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required',
        'data_list' => 'required', 
        'assembly_part' => 'required',  
        'from_sr_no' => 'required',
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select Block / MC\'s',
        'village.required'=> 'Please Select Panchayat / MC\'s',
        'data_list.required'=> 'Please Select Data List',
        'assembly_part.required'=> 'Please Select Assembly Part',
        'from_sr_no.required'=> 'Please Enter From Sr. No.',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $from_sn = intval(substr(MyFuncs::removeSpacialChr($request->from_sr_no), 0, 5));
      $to_sn = intval(substr(MyFuncs::removeSpacialChr($request->to_sr_no), 0, 5));
      if($from_sn == 0 && $to_sn == 0){
        $response=['status'=>0,'msg'=>'From Sr. No. and To Sr. No. Cannot Be Blank'];
        return response()->json($response);  
      }
      if($from_sn == 0){
        $from_sn = $to_sn;
      }
      if($to_sn == 0){
        $to_sn = $from_sn;
      }
      
      $user_id = MyFuncs::getUserId();
      $block_id = intval(Crypt::decrypt($request->block));
      $village_id = intval(Crypt::decrypt($request->village));
      $data_list = intval(Crypt::decrypt($request->data_list));
      $assembly_part = intval(Crypt::decrypt($request->assembly_part));

      $rs_update = DB::select(DB::raw("call `up_change_voters_wards_by_ac_srno` ($user_id, $block_id, $village_id, $assembly_part, $data_list, $from_sn, $to_sn, 0, 0)"));
      $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "claimObjAcPartSrnoDeleteVoterStore";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function exception_handler()
  {
    try {

    } catch (\Exception $e) {
      $e_method = "imageShowPath";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

//   public function index(){ 
//     try {
//       $States=DB::select(DB::raw("select * from `states` order by `name_e`"));
//       return view('admin.master.states.index',compact('States'));
//     } catch (Exception $e) {}
//   }

//   public function store(Request $request,$id=null){  
//     $rules=[
//       'code' => 'required|unique:states,code,'.$id, 
//       'name_english' => 'required', 
//       'name_local_language' => 'required', 
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//       $errors = $validator->errors()->all();
//       $response=array();
//       $response["status"]=0;
//       $response["msg"]=$errors[0];
//       return response()->json($response);// response as json
//     }

//     $code = trim($request->code);
//     $name_e = trim($request->name_english);
//     $name_l = trim($request->name_local_language);
//     if($id==null){
//       $rs_update=DB::select(DB::raw("insert into `states` (`code`, `name_e`, `name_l`) values ('$code', '$name_e', '$name_l');"));
//       $response=['status'=>1,'msg'=>'Record Inserted Successfully'];
//     }else{
//       $rs_update=DB::select(DB::raw("update `states` set `code` = '$code', `name_e` = '$name_e', `name_l` = '$name_l' where `id` = $id;"));
//       $response=['status'=>1,'msg'=>'Record Updated Successfully'];
//     }
//     return response()->json($response);
//   }

//   public function edit($id){ 
//     try {
//       $States=DB::select(DB::raw("Select * from `states` where `id` = $id;"));  
//       return view('admin.master.states.edit',compact('States'));
//     } catch (Exception $e) {}
//   }

//   public function delete($id){
//     try {
//       $state_id = Crypt::decrypt($id);
//       $delete_rs = DB::select(DB::raw("delete from `states` where `id` = $state_id;"));  
//       return redirect()->back()->with(['message'=>'Record Deleted Successfully','class'=>'success']);  
//     } catch (Exception $e) {}

      
//   }

// // //-------districts--------------districts--------------districts---------------districts----//
  
// //     //------------block-mcs----------------------------//

// //     //------------village----------------------------// 

  

  // public function stateWiseDistrict(Request $request){
  //   try{
  //     $user_id = MyFuncs::getUserId(); 
  //     $state_id = intval(Crypt::decrypt($request->id));
  //     $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($user_id, $state_id)"));   
  //     return view('admin.master.districts.value_select_box',compact('Districts'));
  //   } catch (Exception $e) {}
  // }

  public function DistrictWiseBlock(Request $request, $block_mc_condition=0)
  {
    try {
      $d_id = intval(Crypt::decrypt($request->id));
      $user_id = MyFuncs::getUserId();
      
      $box_caption = "Block/MC";
      $show_disabled = 1;
      $rs_records = SelectBox::get_block_access_list_v1($d_id, $block_mc_condition);

      return view('admin.common.select_box_v1',compact('rs_records', 'box_caption', 'show_disabled'));
    } catch (\Exception $e) {
      $e_method = "DistrictWiseBlock";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function BlockWiseVillage(Request $request)
  {
    try {
      $b_id = intval(Crypt::decrypt($request->id));
      $is_permission = MyFuncs::check_block_access($b_id);
      if($is_permission == 0){
        $b_id = 0;
      }

      $box_caption = "Panchayat/MC";
      $show_disabled = 1;
      $rs_records = SelectBox::get_village_access_list_v1($b_id);

      return view('admin.common.select_box_v1',compact('rs_records', 'box_caption', 'show_disabled'));

    } catch (\Exception $e) {
      $e_method = "BlockWiseVillage";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VillageWiseWardAll(Request $request)
  {
    try{
      $vil_id = 0;
      if($request->id!='null'){
        $vil_id = intval(Crypt::decrypt($request->id));
      }
      
      $is_permission = MyFuncs::check_village_access($vil_id);
      if($is_permission == 0){
        $vil_id = 0;
      }

      $box_caption = "Ward No.";
      $show_disabled = 1;
      $rs_records = SelectBox::get_all_ward_village_v1($vil_id);

      return view('admin.common.select_box_v1',compact('rs_records', 'box_caption', 'show_disabled'));
    } catch (\Exception $e) {
      $e_method = "VillageWiseWardAll";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  

  // public function BlockWiseVillage(Request $request)
  // {
  //   try{
  //     $user_id = MyFuncs::getUserId();
  //     $d_id = intval(Crypt::decrypt($request->district_id));
  //     $b_id = intval(Crypt::decrypt($request->id));
  //     $Villages = DB::select(DB::raw("call `up_fetch_village_access` ($user_id, $d_id, $b_id, 0);"));  
  //     return view('admin.master.village.value_select_box',compact('Villages'));
  //   } catch (Exception $e) {}
  // }

  public function BlockWiseVoterListType(Request $request)
  {
    try{  
      $b_id = 0;
      if(!empty($request->id)){
        $b_id = intval(Crypt::decrypt($request->id));
      }
      $VoterListType = DB::select(DB::raw("SELECT * from `voter_list_master` where `block_id` = $b_id;"));  
      return view('admin.voterlistmaster.value_select_box',compact('VoterListType'));
    } catch (Exception $e) {}
  }


    

// //     //------------block-mcs-type---------------------------//
//   public function BlockMCSType()
//   {
//     try{
//       $BlockMCTypes = DB::select(DB::raw("select * from `block_mc_type` order by `block_mc_type_e`;"));
//       return view('admin.master.blockmctype.index',compact('BlockMCTypes'));
//     } catch (Exception $e) {}
//   }

//   public function BlockMCSTypeEdit($id)
//   {
//     try{
//       $BlockMCType = DB::select(DB::raw("select * from `block_mc_type` where `id` = $id;"));
//       return view('admin.master.blockmctype.edit',compact('BlockMCType'));
//     } catch (Exception $e) {}
//   }

//   public function BlockMCSTypeUpdate(Request $request,$id)
//   { 
//     $rules=[
//       'block_mc_type_e' => 'required', 
//       'block_mc_type_l' => 'required',       
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//       $errors = $validator->errors()->all();
//       $response=array();
//       $response["status"]=0;
//       $response["msg"]=$errors[0];
//       return response()->json($response);// response as json
//     }

//     try{
//       $name_e = trim(str_replace('\'', '', $request->block_mc_type_e));
//       $name_l = trim(str_replace('\'', '', $request->block_mc_type_l));
//       $rs_update = DB::select(DB::raw("update `block_mc_type` set `block_mc_type_e` = '$name_e', `block_mc_type_l` = '$name_l' where `id` = $id limit 1;"));
//       $response=['status'=>1,'msg'=>'Record Updated Successfully'];
//       return response()->json($response);
//     } catch (Exception $e) {}
//   }

// //----------------Genders------------------------
//   public function gender()
//   {  
//     try{
//       $genders = DB::select(DB::raw("select * from `genders` order by `genders`;"));   
//       return view('admin.master.gender.index',compact('genders'));
//     } catch (Exception $e) {}
//   }

//   public function genderEdit($id)
//   {
//     try{
//       $gender = DB::select(DB::raw("select * from `genders` where `id` = $id limit 1;"));
//       return view('admin.master.gender.edit',compact('gender'));
//     } catch (Exception $e) {}     
//   }

//   public function genderUpdate(Request $request,$id)
//   { 
//     $rules=[
//       'gender_english' => 'required', 
//       'gender_local_language' => 'required', 
//       'code_english' => 'required', 
//       'code_local_language' => 'required',  
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//       $errors = $validator->errors()->all();
//       $response=array();
//       $response["status"]=0;
//       $response["msg"]=$errors[0];
//       return response()->json($response);// response as json
//     }

//     try{
//       $name_e = trim(str_replace('\'', '', $request->gender_english));
//       $name_l = trim(str_replace('\'', '', $request->gender_local_language));
//       $code_e = trim(str_replace('\'', '', $request->code_english));
//       $code_l = trim(str_replace('\'', '', $request->code_local_language));
//       $rs_update = DB::select(DB::raw("update `genders` set `genders` = '$name_e', `genders_l` = '$name_l', `code` = '$code_e', `code_l` = '$code_l' where `id` = $id limit 1;"));
//       $response=['status'=>1,'msg'=>'Record Updated Successfully'];
//       return response()->json($response);
//     } catch (Exception $e) {}
//   }

// //     //------------Assembly----------------------------//


     

// //     //------------AssemblyPart----------------------------//


  

  

// //     //------------z-p-ward---------------------------//

//   public function ZilaParishad($value='')
//   {
//     try {             
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.zpward.index',compact('States'));
//     } catch (Exception $e) {}
//   }

//   public function ZilaParishadTable(Request $request)
//   {
//     try {
//       $ZilaParishads = DB::select(DB::raw("select * from `ward_zp` where `districts_id` = $request->district_id order by `ward_no`;"));             
//       return view('admin.master.zpward.table',compact('ZilaParishads'));
//     } catch (Exception $e) {}
//   }

//   public function ZilaParishadEdit($id)
//   {
//     try {
//       $ZilaParishad = DB::select(DB::raw("select * from `ward_zp` where `id` = $id limit 1;"));
//       return view('admin.master.zpward.edit',compact('ZilaParishad'));
//     } catch (Exception $e) {}
//   }

//   public function ZilaParishadUpdate(Request $request,$id)
//   {    
//     $rules=[ 
//       'zp_ward_no' => 'required',  
//       'zp_ward_name_english' => 'required',  
//       'zp_ward_name_local_language' => 'required',  
//     ]; 
//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//       $errors = $validator->errors()->all();
//       $response=array();
//       $response["status"]=0;
//       $response["msg"]=$errors[0];
//       return response()->json($response);// response as json
//     }
    
//     try {
//       $ward_no = trim(str_replace('\'', '', $request->zp_ward_no));
//       $name_e = trim(str_replace('\'', '', $request->zp_ward_name_english));
//       $name_l = trim(str_replace('\'', '', $request->zp_ward_name_local_language));
//       $rs_update = DB::select(DB::raw("update `ward_zp` set `ward_no` = '$ward_no', `name_e` = '$name_e', `name_l` = '$name_l' where `id` = $id limit 1;"));
//       $response=['status'=>1,'msg'=>'Record Saved Successfully'];
//       return response()->json($response);
//     } catch (Exception $e) {}
//   }

//   public function ZilaParishadDelete($id)
//   {
//     try {
//       $rs_delete = DB::select(DB::raw("delete from `ward_zp` where `id` = $id limit 1;"));
//       $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
//       return response()->json($response);
//     } catch (Exception $e) {}
//   }

// //     //------------Panchayat Samiti Wards----------------------------//

//   public function PanchayatSamiti($value='')
//   {
//     try {
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.psward.index',compact('States'));
//     } catch (Exception $e) {}
//   }

//   public function PanchayatSamitiTable(Request $request)
//   { 
//     try {
//       $PanchayatSamitis = DB::select(DB::raw("select * from `ward_ps` where `blocks_id` = $request->id order by `name_e`;"));
//       return view('admin.master.psward.table',compact('PanchayatSamitis'));
//     } catch (Exception $e) {}
//   }

//   public function PanchayatSamitiEdit($id)
//   {
//     try {
//       $PanchayatSamiti = DB::select(DB::raw("select * from `ward_ps` where `id` = $id limit 1;"));
//       return view('admin.master.psward.edit',compact('PanchayatSamiti'));
//     } catch (Exception $e) {}
//   }

//   public function PanchayatSamitiUpdate(Request $request,$id)
//   {    
//     $rules=[ 
//       'ps_ward_no' => 'required',  
//       'ps_ward_name_english' => 'required',  
//       'ps_ward_name_local_language' => 'required',  
//     ]; 
//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//       $errors = $validator->errors()->all();
//       $response=array();
//       $response["status"]=0;
//       $response["msg"]=$errors[0];
//       return response()->json($response);// response as json
//     }

//     try {
//       $ward_no = trim(str_replace('\'', '', $request->ps_ward_no));
//       $name_e = trim(str_replace('\'', '', $request->ps_ward_name_english));
//       $name_l = trim(str_replace('\'', '', $request->ps_ward_name_local_language));
//       $rs_update = DB::select(DB::raw("update `ward_ps` set `ward_no` = '$ward_no', `name_e` = '$name_e', `name_l` = '$name_l' where `id` = $id limit 1;"));
//       $response=['status'=>1,'msg'=>'Record Saved Successfully'];

//       $response=['status'=>1,'msg'=>'Update Successfully'];
//       return response()->json($response);
//     } catch (Exception $e) {}
//   }

//   public function PanchayatSamitiDelete($id)
//   {
//     try {
//       $rs_delete = DB::select(DB::raw("delete from `ward_ps` where `id` = $id limit 1;"));
//       $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
//       return response()->json($response);
//     } catch (Exception $e) {}
//   }

// //      //------------ward-village----------------------------//

  


// //     //-----Mapping Village Assembly Part-----------------
  

  
  

  

  

  

  

  
  

  

  

// //   //------------------------Mapping-Village Ward -To-PS Ward----------

//   public function MappingVillageWardToPSWard($value='')
//   {
//     try {
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.mappingvillageTopsward.index',compact('States'));
//     } catch (Exception $e) {}
//   }

//   public function blockwisePsWard(Request $request)
//   {
//     try {
//       $pswards = DB::select(DB::raw("select * from `ward_ps` where `blocks_id` = $request->id order by `ward_no`;"));
//       return view('admin.master.psward.value_select_box',compact('pswards'));
//     } catch (Exception $e) {}
//   }

//   public function BlockOrPSwardWiseVillage(Request $request)
//   {   
//     try {
//       $villages=DB::select(DB::raw("(Select `wv`.`id`, concat(`v`.`name_e`, '-', `wv`.`ward_no`) as `ps_ward_name`, 0 as `status` From `ward_villages` `wv` Inner Join `villages` `v` on `v`.`id` = `wv`.`village_id` Where `wv`.`is_locked` = 0 and `wv`.`blocks_id` =$request->block_id and (`wv`.`ps_ward_id` =0 Or `wv`.`ps_ward_id` is null)) Union (Select `wv`.`id`,  concat(`v`.`name_e`, '-', `wv`.`ward_no`) as `ps_ward_name` , 1 as `status` From `ward_villages` `wv` Inner Join `villages` `v` on `v`.`id` = `wv`.`village_id` Where `wv`.`is_locked` = 0 and `wv`.`blocks_id` =$request->block_id and `wv`.`ps_ward_id` =$request->id) Order By `ps_ward_name`;"));

//       return view('admin.master.mappingvillageTopsward.ward_move_select_box',compact('villages'));    
//     } catch (Exception $e) {}
//   }

//   public function MappingVillageToPSWardStore(Request $request)
//   { 
//     try {
//       if (!empty($request->village)) {
//         $village_id=implode(',',$request->village);  
//       }else {
//         $village_id=0;  
//       } 
   
//       DB::select(DB::raw("call `up_map_ward_villages_psward` ('$request->ps_ward','$village_id')"));
//       $response=['status'=>1,'msg'=>'Records Saved Successfully'];
//       return response()->json($response); 
//     } catch (Exception $e) {}
//   } 

// //-----Mapping Villages To ZP Ward-----------------------------------------------
  
//   public function MappingVillageToZPWard()
//   {
//     try {
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.mappingvillageTozpward.index',compact('States'));
//     } catch (Exception $e) {}      
//   }

//   public function districtwiseZPWard(Request $request)
//   {
//     try {
//       $zpwards = DB::select(DB::raw("select * from `ward_zp` where `districts_id` = $request->district_id order by `ward_no`;"));
//       return view('admin.master.zpward.value_select_box',compact('zpwards'));
//     } catch (Exception $e) {}
//   }

//   public function districtOrZpwardWiseVillage(Request $request)
//   {
//     try {
//       // $villages=DB::select(DB::raw("select `id`, `name_e`, 0 as `status` from `villages`where `is_locked` = 0 and `districts_id` = $request->district_id and `zp_ward_id` not in (Select `id` from `ward_zp` where `districts_id` = $request->district_id) Union select `id`, `name_e`, 1 as `status` from `villages`where `is_locked` = 0 and `districts_id` = $request->district_id and `zp_ward_id` = $request->id Order By `name_e`;"));
      
//       $villages=DB::select(DB::raw("select `vil`.`id`, `bl`.`name_e` as `bl_name`, `vil`.`name_e`, 0 as `status` from `villages` `vil` inner join `blocks_mcs` `bl` on `bl`.`id` = `vil`.`blocks_id` where `vil`.`is_locked` = 0 and `vil`.`districts_id` = $request->district_id and `vil`.`zp_ward_id` not in (Select `id` from `ward_zp` where `districts_id` = $request->district_id) Union select `vil1`.`id`, `bl1`.`name_e`  as `bl_name`, `vil1`.`name_e`, 1 as `status` from `villages` `vil1` inner join `blocks_mcs` `bl1` on `bl1`.`id` = `vil1`.`blocks_id` where `vil1`.`is_locked` = 0 and `vil1`.`districts_id` = $request->district_id and `vil1`.`zp_ward_id` = $request->id Order By `bl_name`, `name_e`;"));

//       return view('admin.master.mappingvillageTozpward.village_move_select_box',compact('villages'));
//     } catch (Exception $e) {}
//   }

//   public function MappingVillageToZPWardStore(Request $request)
//   { 
//     try {
//       if (!empty($request->village)) {
//         $village_id=implode(',',$request->village);  
//       }else {
//         $village_id=0;  
//       } 
       
//       DB::select(DB::raw("call `up_map_villages_zpward` ('$request->zp_ward','$village_id')"));
//       $response=['status'=>1,'msg'=>'Record Saved Successfully'];
//       return response()->json($response); 
//     } catch (Exception $e) {}
//   } 

// //------------Poll Day Time Set ----------------

// public function pollingDayTime()
// {
//   try {
//     $admin = Auth::guard('admin')->user();
//     $userid = $admin->id;  
//     $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));
      
//     return view('admin.master.pollingDayTime.index',compact('Districts'));
//   } catch (Exception $e) {}      
// }

// public function pollingDayTimeList(Request $request)
// {
//   try {
//     $PollingDayTimes = DB::select(DB::raw("select * from `polling_day_time` where `block_id` = $request->id limit 1;"));
      
//     return view('admin.master.pollingDayTime.list',compact('PollingDayTimes'));
//   } catch (Exception $e) {}
// }

// public function pollingDayTimesignature(Request $request,$path)
//     {  
//       $path=Crypt::decrypt($path);
//       $storagePath = storage_path('app/'.$path);              
//       $mimeType = mime_content_type($storagePath); 
//       if( ! \File::exists($storagePath)){

//         return view('error.home');
//       }
//       $headers = array(
//         'Content-Type' => $mimeType,
//         'Content-Disposition' => 'inline; '
//       );            
//       return Response::make(file_get_contents($storagePath), 200, $headers);     
//     }

// public function pollingDayTimeStore(Request $request,$id=null)
// { 
//   $rules=[ 
//     'block' => 'required',  
//     'polling_day_time_english' => 'required', 
//     'polling_day_time_local' => 'required', 
//     'signature' => 'required', 
//   ];

//   $validator = Validator::make($request->all(),$rules);
//   if ($validator->fails()) {
//     $errors = $validator->errors()->all();
//     $response=array();
//     $response["status"]=0;
//     $response["msg"]=$errors[0];
//     return response()->json($response);// response as json
//   }
  
//   try {  
//     $dirpath = Storage_path() . '/app/blocksign';
//     $vpath = '/blocksign';
//     @mkdir($dirpath, 0755, true);
//     chmod($dirpath, 0755);
//     $name =$request->block;
    
//     $b_id = $request->block;
//     $time_e = trim(str_replace('\'', '', $request->polling_day_time_english));
//     $time_l = trim(str_replace('\'', '', $request->polling_day_time_local));
//     $sign_path = $vpath.'/'.$name.'.jpg';

//     //--start-image-save
//     $file =$request->signature;
//     $image = file_get_contents($file); 
//     $image= \Storage::put($sign_path, $image);
//     // chmod($sign_path, 0755);
//     //--end-image-save
    
//     DB::select(DB::raw("call `up_save_pollingDayTime` ($b_id, '$time_e', '$time_l', '$sign_path')"));
//     $response=['status'=>1,'msg'=>'Record Saved Successfully'];
//     return response()->json($response);
//   } catch (Exception $e) {}
// }

// //-----------Voter slip notes -------------------
// public function voterSlipNotes()
// {
//   try {
//     $admin = Auth::guard('admin')->user();
//     $userid = $admin->id;  
//     $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));
      
//     return view('admin.master.voterSlipNotes.index',compact('Districts'));
//   } catch (Exception $e) {}      
// }

// public function voterSlipNotesShow(Request $request)
// {
//   try {
//     $voterSlipNotes = DB::select(DB::raw("select * from `voter_slip_notes` where `district_id` = $request->id order by `note_srno`;"));
      
//     return view('admin.master.voterSlipNotes.list',compact('voterSlipNotes'));
//   } catch (Exception $e) {}
// }

// public function voterSlipNotesStore(Request $request,$id=null)
// { 
//   $rules=[ 
//     'district' => 'required', 
//     'notes' => 'required', 
//     'srno' => 'required', 
//   ]; 
//   $validator = Validator::make($request->all(),$rules);
//   if ($validator->fails()) {
//     $errors = $validator->errors()->all();
//     $response=array();
//     $response["status"]=0;
//     $response["msg"]=$errors[0];
//     return response()->json($response);// response as json
//   } 
//   try {  
//     $record_id = 0;
//     $notes_text = trim(str_replace('\'', '', $request->notes));
//     if (!empty($id)){
//       $record_id = $id;
//     }
//     if ($record_id == 0){
//       DB::select(DB::raw("insert into `voter_slip_notes` (`district_id`,`note_srno`,`note_text`) value('$request->district','$request->srno','$notes_text');"));
//     }else{
//       DB::select(DB::raw("update `voter_slip_notes` set `note_srno` = '$request->srno', `note_text` = '$notes_text' where `id` = $record_id;"));
//     }
//     $response=['status'=>1,'msg'=>'Record Saved Successfully'];
//     return response()->json($response);
//   } catch (Exception $e) {}
// }

// public function voterSlipNotesDelete($id)
// {
//   try {
//     $voterSlipNotes = DB::select(DB::raw("delete from `voter_slip_notes` where `id` = $id limit 1;"));
      
//     $response=['status'=>1,'msg'=>'Record Deleted Successfully'];
//     return response()->json($response);
//   } catch (Exception $e) {}
// }

// public function voterSlipNotesEditForm($id)
//   {
//     try {
//       $rs_edit = DB::select(DB::raw("select * from `voter_slip_notes` where `id` = $id limit 1;"));
//       return view('admin.master.voterSlipNotes.edit',compact('rs_edit'));
//     } catch (Exception $e) {}
//   }


// //-----------Voter List Master -------------------

// public function voterImportType()
// {
//   try {
//     $admin = Auth::guard('admin')->user();
//     $userid = $admin->id;  
//     $importTypes = DB::select(DB::raw("select * from `import_type`"));
    
      
//     return view('admin.voterlistmaster.voter_import_type',compact('importTypes'));
//   } catch (Exception $e) {}
// }
// public function voterImportTypeStore(Request $request,$id=null)
// {  
//   $rules=[
//     'description' => 'required',
//     'date' => 'required',
    
//   ]; 
//   $validator = Validator::make($request->all(),$rules);
//   if ($validator->fails()) {
//     $errors = $validator->errors()->all();
//     $response=array();
//     $response["status"]=0;
//     $response["msg"]=$errors[0];
//     return response()->json($response);// response as json
//   } 
//   try {
//     $description = trim(str_replace('\'', '', $request->description));
//       if (empty($id)) {
//           $rs_update = DB::select(DB::raw("insert into `import_type` (`description` , `date` , `status`) values ('$description' , '$request->date' , 0);"));
//       }
//       if (!empty($id)) {
//           $rs_update = DB::select(DB::raw("update `import_type` set `description` = '$description' , `date` = '$request->date' where `id` = $id"));
//       } 
//     $response=['status'=>1,'msg'=>'Record Saved Successfully'];
//     return response()->json($response);
//   } catch (Exception $e) {}
// }
// public function setVoterImortTypeEdit($id)
// {
//   try {
//     $VoterimportType = DB::select(DB::raw("select * from `import_type` where `id` = $id limit 1;"));
//     return view('admin.voterlistmaster.voter_import_type_edit',compact('VoterimportType'));
//   } catch (Exception $e) {}
// }
// public function setVoterImortTypeDefault($id)
// {
//   try{
//     $rs_update = DB::select(DB::raw("call `up_set_voter_import_Default` ($id);"));
//      return redirect()->back()->with(['message'=>'Default Set Successfully','class'=>'success']);
//   } catch (Exception $e) {}
// }
  


//    //---------MappingBoothWard----------MappingBoothWard-----------------MappingBoothWard
  

  

  

  

// //    //------MappingWardBooth-----------------MappingWardBooth--------MappingWardBooth
   
  

  

  

  

  


// //----------ward-bandi----------WardBandi----------------------------------------------------//




  


// //---------Ward Bandi With Booth Enter Voter Detail----------------------------








//   //--------mappingAcpartBoothWardwise---------
//   public function mappingAcpartBoothWardwise()
//   {
//     try{ 
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));  
//       return view('admin.master.mappingAcpartBoothWardwise.index',compact('States'));     
//     } catch (Exception $e) {}
//   }
//   public function mappingAcpartBoothWardwiseTable(Request $request)
//   {
//     try{ 
//       $mappingAcpartBoothWardwise = DB::select(DB::raw("select `ac`.`code`, `ap`.`part_no`, ifnull(concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')), '') as `booth`, `ap`.`id` as `acpartid`, ifnull(`pb`.`id`,0) as `booth_id`, count(*) as `total_vote` from `voters` `vt` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` left join `mapping_acpart_booth_wardwise` `map` on `map`.`ward_id` = `vt`.`ward_id` and `map`.`acpart_id` = `vt`.`assembly_part_id` left join `polling_booths` `pb` on `pb`.`id` = `map`.`booth_id` where `vt`.`status` <> 2 and `vt`.`ward_id` = $request->ward_id group by `ac`.`code`, `ap`.`part_no`, `pb`.`booth_no`, `ap`.`id`, `pb`.`id`, `pb`.`booth_no_c` order by `ac`.`code`, `ap`.`part_no`, `pb`.`booth_no`;"));
//       $selectbooths= DB::select(DB::raw("Select `id`, concat(`booth_no`,ifnull(`booth_no_c`,''),' - ',`name_e`) as `booth_name` From `polling_booths` Where `id` in (select `boothid` from `booth_ward_voter_mapping` where `wardId` =$request->ward_id ) Order by `booth_name`;"));  
//       return view('admin.master.mappingAcpartBoothWardwise.table_form',compact('mappingAcpartBoothWardwise', 'selectbooths'));     
//     } catch (Exception $e) {}
//   }
//   public function mappingAcpartBoothWardwiseStore(Request $request)
//   {
//     $rules=[
//       'states' => 'required', 
//       'district' => 'required', 
//       'block' => 'required', 
//       'village' => 'required', 
//       'ward' => 'required', 
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//         $errors = $validator->errors()->all();
//         $response=array();
//         $response["status"]=0;
//         $response["msg"]=$errors[0];
//         return response()->json($response);// response as json
//     }  
//     try{ 
//       $ward_id = $request->ward;
//       $rs_update = DB::select(DB::raw("delete from `mapping_acpart_booth_wardwise` where `ward_id` = $ward_id;"));
//       foreach ($request->booth_id as $key => $value) {
//         $rs_update = DB::select(DB::raw("insert into `mapping_acpart_booth_wardwise` (`ward_id`, `acpart_id`, `booth_id`) values ($ward_id, $key, $value);"));
//         // echo $key. "  ".$value."\n"; 
//       }
//       // return null;
//       // return $request;

//       // $saveBooth = DB::select(DB::raw("call `up_map_ward_booths`('$request->ward','$booth_id')"));

//       $response=['status'=>1,'msg'=>'Submit Successfully']; 
//       return response()->json($response); 
//     } catch (Exception $e) {}
//   }
//   //--------changeVoterWithWard---------
//   public function changeVoterWithWard()
//   {
//     try {
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.changeVoterWithWard.index',compact('States', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function changeVotervillageWiseWard(Request $request)
//   {
//     try{ 
//       $refreshdata = MyFuncs::Refresh_data_voterEntry(); 
//       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
//       return view('admin.master.changeVoterWithWard.ward_select',compact('WardVillages', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function changeVoterWithWardTable(Request $request)
//   {
//     try{

//       $ward_id = $request->ward_id;
//       $block_id = $request->block_id;
//       if($block_id == "null"){
//         $block_id = 0;
//       }
//       $results= DB::select(DB::raw("select * from `voter_list_master` where `block_id` = $block_id and `status` = 1;"));  
//       if(count($results) == 0){
//         $voter_list_id  = 0;
//       }else{
//         $voter_list_id  = $results[0]->id;
//       }

//       $refreshdata = MyFuncs::Refresh_data_voterEntry(); 
//       $results= DB::select(DB::raw("call `up_fetch_list_suppliment_deleted_voter_detail` ($block_id, $ward_id, 0);"));  
//       $showbooth_flag = 0;
//       return view('admin.master.changeVoterWithWard.table',compact('results', 'showbooth_flag', 'refreshdata'));
//     } catch (Exception $e) {
          
//     }
//   }

  


//   public function changeVoterWithWardReport(Request $request)
//   {
//     try{  
//       $WardVillages = DB::select(DB::raw("call up_fetch_ward_village_access ('$request->village_id','0')"));   
//       return view('admin.master.changeVoterWithWard.report_popup',compact('WardVillages'));
//     } catch (Exception $e) {}
//   }


//   public function changeVoterWithWardReportPdf(Request $request)
//   { 
//     $report_selected = $request->report_type;
//     $ward_id = $request->ward;

//     if ($report_selected == 0) {
//       $response=['status'=>0,'msg'=>'Plz Select Report Type'];
//       return response()->json($response);   
//     }
//     if ($ward_id == 0) {
//       $response=['status'=>0,'msg'=>'Plz Select Ward'];
//       return response()->json($response);   
//     }

//     $wardno_rs = DB::select(DB::raw("select `wv`.`ward_no`, `blocks_id` from `ward_villages` `wv` where `wv`.`id` = $request->ward;"));
//     $wardno = $wardno_rs[0]->ward_no;
//     $block_id = $wardno_rs[0]->blocks_id;
    

    
//     $report_heading = '';
//     if ($report_selected == 1) {
//       $results= DB::select(DB::raw("call `up_fetch_list_suppliment_deleted_voter_detail`($block_id, $ward_id, 0);"));
//       $report_heading = 'Deleted (From Ward) :: '.$wardno;
//     }else{
//       $results= DB::select(DB::raw("call `up_fetch_list_suppliment_new_voter_detail`($ward_id, 0);"));
//       $report_heading = 'Added (To Ward) :: '.$wardno;
//     }
    

//     $path=Storage_path('fonts/');
//     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
//     $fontDirs = $defaultConfig['fontDir']; 
//     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
//     $fontData = $defaultFontConfig['fontdata']; 
//     $mpdf = new \Mpdf\Mpdf([
//     'fontDir' => array_merge($fontDirs, [
//     __DIR__ . $path,
//     ]),
//     'fontdata' => $fontData + [
//     'frutiger' => [
//     'R' => 'FreeSans.ttf',
//     'I' => 'FreeSansOblique.ttf',
//     ]
//     ],
//     'default_font' => 'freesans',
//     'pagenumPrefix' => '',
//     'pagenumSuffix' => '',
//     'nbpgPrefix' => ' कुल ',
//     'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
//     ]); 
//     $showbooth_flag = 0;
//     $html = view('admin.master.changeVoterWithWard.pdf',compact('results', 'report_heading', 'showbooth_flag')); 
//     $mpdf->WriteHTML($html); 
//     $mpdf->Output();

//   } 

//   public function changeVoterWithWardStore(Request $request)
//   { 
    
//     $rules=[ 

//       'states' => 'required',  
//       'district' => 'required',  
//       'block' => 'required',  
//       'village' => 'required',  
//       'from_ward' => 'required',  
//       'from_sr_no' => 'required',  
//       'to_ward' => 'required',  
           
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//         $errors = $validator->errors()->all();
//         $response=array();
//         $response["status"]=0;
//         $response["msg"]=$errors[0];
//         return response()->json($response);// response as json
//     }
//     $from_sn = trim($request->from_sr_no);
//     $to_sn = trim($request->to_sr_no);
//     if($from_sn == '' && $to_sn == ''){
//       $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
//       return response()->json($response);  
//     }
//     if($from_sn == ''){
//       $from_sn = $to_sn;
//     }
//     if($to_sn == ''){
//       $to_sn = $from_sn;
//     }
//     if($request->from_ward == $request->to_ward){
//       $response=['status'=>0,'msg'=>'From Ward And To Ward Cannot be Same'];
//       return response()->json($response);  
//     }
    
//     $admin = Auth::guard('admin')->user();
//     $userid = $admin->id;
//     $block_id = $request->block;
//     $village_id = $request->village;
//     $rs_update = DB::select(DB::raw("call `up_change_voters_wards` ($userid, $block_id, $village_id, $request->from_ward, 0, $from_sn, $to_sn, $request->to_ward, 0)"));
//     $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
//     return response()->json($response);
//   }

  

  

  

  

  

  


//   public function deleteSupplimentVoterWard()
//   {
//     try {
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.deleteVoterWithWard.index',compact('States', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function showformDeleteSupplimentVoterWard(Request $request)
//   {
//     try{  
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
//       return view('admin.master.deleteVoterWithWard.ward_select',compact('WardVillages', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function submitDeleteSupplimentVoterWard(Request $request)
//   { 
    
//     $rules=[ 

//       'states' => 'required',  
//       'district' => 'required',  
//       'block' => 'required',  
//       'village' => 'required',  
//       'from_ward' => 'required',  
//       'from_sr_no' => 'required',     
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//         $errors = $validator->errors()->all();
//         $response=array();
//         $response["status"]=0;
//         $response["msg"]=$errors[0];
//         return response()->json($response);// response as json
//     }
//     $from_sn = trim($request->from_sr_no);
//     $to_sn = trim($request->to_sr_no);
//     if($from_sn == '' && $to_sn == ''){
//       $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
//       return response()->json($response);  
//     }
//     if($from_sn == ''){
//       $from_sn = $to_sn;
//     }
//     if($to_sn == ''){
//       $to_sn = $from_sn;
//     }
    
//     $admin = Auth::guard('admin')->user();
//     $userid = $admin->id;
//     $block_id = $request->block;
//     $village_id = $request->village;
    
//     $rs_update = DB::select(DB::raw("call `up_change_voters_wards` ($userid, $block_id, $village_id, $request->from_ward, 0, $from_sn, $to_sn, 0, 0)"));
//     $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
//     return response()->json($response);
//   }


// // public function addSupplimentVoterWard()
// // {
// //   try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.addVoterWithWard.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// // }
// // public function showFormAddSupplimentVoterWard(Request $request)
// // {
// //   try{  
// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       $importTypes = DB::select(DB::raw("select * from `import_type` ;"));
// //       return view('admin.master.addVoterWithWard.select_ac_ward',compact('WardVillages' , 'assemblyParts' , 'importTypes'));
// //     } catch (Exception $e) {
      
// //     }
// // }
// // public function addSupplimentVoterWardSubmit(Request $request)
// // { 
  
// //   $rules=[ 

// //     'states' => 'required',  
// //     'district' => 'required',  
// //     'block' => 'required',  
// //     'village' => 'required',  
// //     'from_ward' => 'required',  
// //     'assembly_part' => 'required',     
// //     'from_sr_no' => 'required',     
// //   ];

// //   $validator = Validator::make($request->all(),$rules);
// //   if ($validator->fails()) {
// //       $errors = $validator->errors()->all();
// //       $response=array();
// //       $response["status"]=0;
// //       $response["msg"]=$errors[0];
// //       return response()->json($response);// response as json
// //   }
// //   $from_sn = trim($request->from_sr_no);
// //   $to_sn = trim($request->to_sr_no);
// //   if($from_sn == '' && $to_sn == ''){
// //     $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
// //     return response()->json($response);  
// //   }
// //   if($from_sn == ''){
// //     $from_sn = $to_sn;
// //   }
// //   if($to_sn == ''){
// //     $to_sn = $from_sn;
// //   }
  
// //   $rs_update = DB::select(DB::raw("call `up_add_voters_wards_suppliment` ($request->assembly_part,$from_sn, $to_sn,$request->from_ward, 0)"));
// //   $response=['status'=>$rs_update[0]->status,'msg'=>$rs_update[0]->result];
// //   return response()->json($response);
// // }

// // public function addSupplimentVoterWardtable(Request $request)
// // {
// //   try{
// //     $results= DB::select(DB::raw("call `up_fetch_list_suppliment_assembly_new_voter_detail` ($request->ward_id, $request->booth_id);"));  
// //     return view('admin.master.addVoterWithWard.table',compact('results'));
// //   } catch (Exception $e) {}
// // }

// // public function addedSupplimentVoterWardDelete($id, $ward_id)
// // {
// //   try{
// //     $rs_restore = DB::select(DB::raw("call `up_delete_ward_booth_added_suppliment` ('$id', $ward_id)"));
// //     $rs_restore = reset($rs_restore);

// //     $response=['status'=>$rs_restore->rstatus,'msg'=>$rs_restore->rremarks];
// //     return response()->json($response);
// //   } catch (Exception $e) {}
// // }

// // public function addSupplimentVoterWithWardReport(Request $request)
// // {
// //   try{  
// //     $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->village_id','0')"));   
// //     return view('admin.master.addVoterWithWard.report_popup',compact('WardVillages'));
// //   } catch (Exception $e) {}
// // }

// // public function addSupplimentVoterWithWardBoothReport(Request $request)
// // {
// //   try{  
// //     $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->village_id','0')"));   
// //     return view('admin.master.addVoterWithWardBooth.report_popup',compact('WardVillages'));
// //   } catch (Exception $e) {}
// // }

// // public function addVoterWithWardReportPdf(Request $request)
// // {

// //   $ward_id = $request->ward;
  
// //   if ($ward_id == 0) {
// //     $response=['status'=>0,'msg'=>'Plz Select Ward'];
// //     return response()->json($response);   
// //   }
  
// //   $wardno_rs = DB::select(DB::raw("select `wv`.`ward_no` from `ward_villages` `wv` where `wv`.`id` = $request->ward;"));
// //   $wardno = $wardno_rs[0]->ward_no;

// //   $results= DB::select(DB::raw("call `up_fetch_list_suppliment_assembly_new_voter_detail`($ward_id, 0);"));
// //   $report_heading = 'Added Voter List (Ward No.) :: '.$wardno;
  

// //   $path=Storage_path('fonts/');
// //   $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //   $fontDirs = $defaultConfig['fontDir']; 
// //   $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //   $fontData = $defaultFontConfig['fontdata']; 
// //   $mpdf = new \Mpdf\Mpdf([
// //   'fontDir' => array_merge($fontDirs, [
// //   __DIR__ . $path,
// //   ]),
// //   'fontdata' => $fontData + [
// //   'frutiger' => [
// //   'R' => 'FreeSans.ttf',
// //   'I' => 'FreeSansOblique.ttf',
// //   ]
// //   ],
// //   'default_font' => 'freesans',
// //   'pagenumPrefix' => '',
// //   'pagenumSuffix' => '',
// //   'nbpgPrefix' => ' कुल ',
// //   'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
// //   ]); 
// //   $showbooth_flag = 0;
// //   $html = view('admin.master.addVoterWithWard.pdfreport',compact('results', 'report_heading', 'showbooth_flag')); 
// //   $mpdf->WriteHTML($html); 
// //   $mpdf->Output();

// // }

// // public function addVoterWithWardBoothReportPdf(Request $request)
// // {

// //   $ward_id = $request->ward;
// //   $booth_id = $request->booth;
  
// //   if ($ward_id == 0) {
// //     $response=['status'=>0,'msg'=>'Plz Select Ward'];
// //     return response()->json($response);   
// //   }
  
// //   $wardno_rs = DB::select(DB::raw("select `wv`.`ward_no` from `ward_villages` `wv` where `wv`.`id` = $request->ward limit 1;"));
// //   $wardno = $wardno_rs[0]->ward_no;

// //   $boothno_rs = DB::select(DB::raw("select `booth_no`, `booth_no_c` from `polling_booths` where `id` = $booth_id limit 1;"));
// //   $boothNo = $boothno_rs[0]->booth_no.' '.$boothno_rs[0]->booth_no_c;

// //   $results= DB::select(DB::raw("call `up_fetch_list_suppliment_assembly_new_voter_detail`($ward_id, $booth_id);"));
// //   $report_heading = 'Added Voter List (Ward No. - Booth No.) :: '.$wardno.' - '.$boothNo;
  

// //   $path=Storage_path('fonts/');
// //   $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //   $fontDirs = $defaultConfig['fontDir']; 
// //   $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //   $fontData = $defaultFontConfig['fontdata']; 
// //   $mpdf = new \Mpdf\Mpdf([
// //   'fontDir' => array_merge($fontDirs, [
// //   __DIR__ . $path,
// //   ]),
// //   'fontdata' => $fontData + [
// //   'frutiger' => [
// //   'R' => 'FreeSans.ttf',
// //   'I' => 'FreeSansOblique.ttf',
// //   ]
// //   ],
// //   'default_font' => 'freesans',
// //   'pagenumPrefix' => '',
// //   'pagenumSuffix' => '',
// //   'nbpgPrefix' => ' कुल ',
// //   'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
// //   ]); 
// //   $showbooth_flag = 0;
// //   $html = view('admin.master.addVoterWithWard.pdfreport',compact('results', 'report_heading', 'showbooth_flag')); 
// //   $mpdf->WriteHTML($html); 
// //   $mpdf->Output();

// // }


//   //-----------Colony Detail Ward--------------
//   public function colonyDetail()
//   {
//     try{
//       $admin = Auth::guard('admin')->user(); 
//       $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
//       return view('admin.master.colonyDetailWard.index',compact('Districts'));     
//     } catch (Exception $e) {}
//   }

//   public function colonyDetailWardList(Request $request)
//   { 
//     try{
//       $admin = Auth::guard('admin')->user(); 
//       $colony_details_wards = DB::select(DB::raw("select `wv`.`id`, `wv`.`ward_no`, `wcd`.`colony_detail` 
//       from `ward_villages` `wv`
//       left join `ward_colony_detail` `wcd` on `wcd`.`ward_id` = `wv`.`id`
//       where `wv`.`village_id` = $request->village
//       order by `wv`.`ward_no`;"));   
  
//       return view('admin.master.colonyDetailWard.list',compact('colony_details_wards'));     
//     } catch (Exception $e) {}
//   }

//   public function colonyDetailWardUpdate(Request $request)
//   {
   
//     try{
//       if(trim($request->colony_detail) == '') {
//         $response=['status'=>0,'msg'=>'Please Enter Colony Detail'];
//         return response()->json($response);  
//       } 
//       $admin = Auth::guard('admin')->user(); 
//       $colony_detail = $request->colony_detail;
//       $colony_detail = str_replace("\'", "", $colony_detail);
//       $colony_detail = str_replace("\\", "", $colony_detail);
//       $colony_detail_wards = DB::select(DB::raw("call `up_save_colony_detail_ward`($request->ward_id , '$colony_detail');"));   
//       $response=['status'=>1,'msg'=>'Update Successfully'];
//       return response()->json($response); 
//     } catch (Exception $e) {}
//   }


// // public function addSupplimentVoterWardBooth()
// // {
// //   try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.addVoterWithWardBooth.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// // }
// // public function showFormAddSupplimentVoterWardBooth(Request $request)
// // {
// //   try{  
// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.addVoterWithWardBooth.select_ac_ward_booth',compact('WardVillages','assemblyParts'));
// //     } catch (Exception $e) {
      
// //     }
// // }
// // public function SubmitAddSupplimentVoterWardBooth(Request $request)
// // { 
  
// //   $rules=[ 

// //     'states' => 'required',  
// //     'district' => 'required',  
// //     'block' => 'required',  
// //     'village' => 'required',  
// //     'from_ward' => 'required',  
// //     'from_booth' => 'required',  
// //     'assembly_part' => 'required',     
// //     'from_sr_no' => 'required',     
// //   ];

// //   $validator = Validator::make($request->all(),$rules);
// //   if ($validator->fails()) {
// //       $errors = $validator->errors()->all();
// //       $response=array();
// //       $response["status"]=0;
// //       $response["msg"]=$errors[0];
// //       return response()->json($response);// response as json
// //   }
// //   $from_sn = trim($request->from_sr_no);
// //   $to_sn = trim($request->to_sr_no);
// //   if($from_sn == '' && $to_sn == ''){
// //     $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
// //     return response()->json($response);  
// //   }
// //   if($from_sn == ''){
// //     $from_sn = $to_sn;
// //   }
// //   if($to_sn == ''){
// //     $to_sn = $from_sn;
// //   }
  
// //   $rs_update = DB::select(DB::raw("call `up_add_voters_wards_suppliment` ($request->assembly_part,$from_sn, $to_sn, $request->from_ward, $request->from_booth)"));
// //   $response=['status'=>$rs_update[0]->status,'msg'=>$rs_update[0]->result];
// //   return response()->json($response);
// // }




// //  //-----------last-Voter-SrNo-ward--------------
// //   public function lastVoterSrNoWard()
// //   {
// //     try{
// //       $admin = Auth::guard('admin')->user(); 
// //       $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
// //       return view('admin.master.lastVoterSrNoWard.index',compact('Districts'));     
// //     } catch (Exception $e) {}
// //   }

// //   public function lastVoterSrNoWardList(Request $request)
// //   { 
// //     try{
// //       $admin = Auth::guard('admin')->user(); 
// //       $last_srno_wards = DB::select(DB::raw("select `wv`.`id`, `wv`.`ward_no`, `lsw`.`last_srno` 
// //       from `ward_villages` `wv`
// //       left join `last_srno_ward` `lsw` on `lsw`.`ward_id` = `wv`.`id`
// //       where `wv`.`village_id` =$request->village
// //       order by `wv`.`ward_no`;"));   
// //       return view('admin.master.lastVoterSrNoWard.list',compact('last_srno_wards'));     
// //     } catch (Exception $e) {}
// //   }

// //   public function lastVoterSrNoWardUpdate(Request $request)
// //   {
   
// //     try{
// //       if(trim($request->sr_no) == '') {
// //         $response=['status'=>0,'msg'=>'Please Enter Sr. No.'];
// //         return response()->json($response);  
// //       } 
// //       $admin = Auth::guard('admin')->user(); 
// //       $last_srno_wards = DB::select(DB::raw("call `up_save_last_srno_ward`($request->ward_id , $request->sr_no)"));   
// //       $response=['status'=>1,'msg'=>'Update Successfully'];
// //       return response()->json($response); 
// //     } catch (Exception $e) {}
// //   }

// //   //-----------last-Voter-SrNo-Booth--------------

// //   public function lastVoterSrNoBooth()
// //   {
// //     try{
// //       $admin = Auth::guard('admin')->user(); 
// //       $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
// //       return view('admin.master.lastVoterSrNoBooth.index',compact('Districts'));     
// //     } catch (Exception $e) {}
// //   }

// //   public function lastVoterSrNoBoothList(Request $request)
// //   { 
// //     try{
// //       $admin = Auth::guard('admin')->user(); 
// //       $last_srno_ward_booth = DB::select(DB::raw("select `wv`.`id`, `wv`.`booth_no`, `lsw`.`last_srno` 
// //       from `polling_booths` `wv`
// //       left join `last_srno_ward_booth` `lsw` on `lsw`.`booth_id` = `wv`.`id`
// //       where `wv`.`village_id` =$request->village
// //       order by `wv`.`booth_no`;"));   
// //       return view('admin.master.lastVoterSrNoBooth.list',compact('last_srno_ward_booth'));     
// //     } catch (Exception $e) {}
// //   }

// //   public function lastVoterSrNoBoothUpdate(Request $request)
// //   {
   
// //     try{
// //       if(trim($request->sr_no) == '') {
// //         $response=['status'=>0,'msg'=>'Please Enter Sr. No.'];
// //         return response()->json($response);  
// //       }
// //       $admin = Auth::guard('admin')->user(); 
// //       $last_srno_wards = DB::select(DB::raw("call `up_save_last_srno_booth`($request->booth_id , $request->sr_no)"));   
// //       $response=['status'=>1,'msg'=>'Update Successfully'];
// //       return response()->json($response); 
// //     } catch (Exception $e) {}
// //   }

// //   //---------new-voter-ward-wise--------------

// //   public function newVoterWardWise($value='')
// //   {
// //     try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.newVoterWardWise.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function newVoterWardWiseForm(Request $request)
// //   {
// //     try {
// //       $import_type = DB::select(DB::raw("select * from `import_type`"));
// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.newVoterWardWise.select_ward_ac',compact('import_type','WardVillages','assemblyParts'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function newVoterWardWiseTable(Request $request)
// //   {
// //     try {
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l` 
// //                                     from `voters` `vt` 
// //                                     left join `villages` `vil` on `vil`.`id` = `vt`.`village_id`
// //                                     left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id`
// //                                     where `vt`.`assembly_part_id` = $request->part_id 
// //                                     and `vt`.`status` in (0,1) 
// //                                     and `vt`.`data_list_id` = $request->id 
// //                                     order by `vt`.`sr_no` ;"));
// //       return view('admin.master.newVoterWardWise.table',compact('results'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function newVoterWardWisesave(Request $request)
// //   { 
  
// //     $rules=[ 

// //       'states' => 'required',  
// //       'district' => 'required',  
// //       'block' => 'required',  
// //       'village' => 'required',  
// //       'ward' => 'required',  
// //       'assembly_part' => 'required',     
     
// //       'data_list' => 'required',     
// //       'from_sr_no' => 'required',     
// //     ];

// //     $validator = Validator::make($request->all(),$rules);
// //     if ($validator->fails()) {
// //         $errors = $validator->errors()->all();
// //         $response=array();
// //         $response["status"]=0;
// //         $response["msg"]=$errors[0];
// //         return response()->json($response);// response as json
// //     }
// //     $from_sn = trim($request->from_sr_no);
// //     $to_sn = trim($request->to_sr_no);
// //     if($from_sn == '' && $to_sn == ''){
// //       $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
// //       return response()->json($response);  
// //     }
// //     if($from_sn == ''){
// //       $from_sn = $to_sn;
// //     }
// //     if($to_sn == ''){
// //       $to_sn = $from_sn;
// //     }
    
// //     $rs_update = DB::select(DB::raw("call `up_add_voters_wards_suppliment_datalistwise` ($request->assembly_part , $from_sn , $to_sn , $request->ward , 0 , $request->data_list)"));
// //     $response=['status'=>$rs_update[0]->status,'msg'=>$rs_update[0]->result];
// //     return response()->json($response);
// //   }
// //   public function newVoterWardWisedelete($id, $ward_id)
// //   {
// //     try{
// //       $rs_restore = DB::select(DB::raw("call `up_delete_ward_booth_added_suppliment` ('$id', $ward_id)"));
// //       $rs_restore = reset($rs_restore);

// //       $response=['status'=>$rs_restore->rstatus,'msg'=>$rs_restore->rremarks];
// //       return response()->json($response);
// //     } catch (Exception $e) {}
// //   }

// //   public function newVoterWardWisereport(Request $request)
// //   {
// //     $village_id=$request->village_id;
// //     $ward_id=$request->ward_id;
// //     $assembly_part=$request->assembly_part;
// //     $data_list=$request->data_list;
    
// //     return view('admin.master.newVoterWardWise.report_popup',compact('village_id' , 'ward_id' ,'assembly_part' , 'data_list')); 
// //   }
  
// //   public function newVoterWardWisereportgenerate(Request $request)
// //   {
    
    
// //     $import_type=DB::select(DB::raw("select * from `import_type` where `id` =$request->data_list limit 1"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Data List Not Selected, Plz Select Data List First','class'=>'error']); 
// //     }
// //     $datatype = $import_type[0]->description;

// //     if($request->report == 1 || $request->report == 4){
// //       $import_type=DB::select(DB::raw("select `asb`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`id` = $request->assembly_part limit 1;"));
// //       if(count($import_type)==0){
// //        return redirect()->back()->with(['message'=>'Assembly Part Not Selected, Plz Select Assembly Part First','class'=>'error']); 
// //       }
// //       $assembly_code = $import_type[0]->code.' - '.$import_type[0]->part_no;  
// //     }
    
// //     if($request->report==2 || $request->report==3 || $request->report==5){
// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l;  
// //     }
    
// //     if($request->report==2){
// //       $ward_villages=DB::select(DB::raw("select * from `ward_villages` where `id` =$request->ward_id limit 1"));
// //       if(count($ward_villages)==0){
// //        return redirect()->back()->with(['message'=>'Ward No. Not Selected, Plz Select Ward No. First','class'=>'error']); 
// //       }
// //       $ward_no = $ward_villages[0]->ward_no;
// //     }
    
// //     if ($request->report==1) {
// //       $report_heading = 'Voter Not Mapped (New Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, '' as  `village_name`, '' as `ward_no`, '' as `status`, '' as `ward_id`, `vt`.`house_no_l` from `voters` `vt` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 0 and `vt`.`data_list_id` =$request->data_list order by `vt`.`sr_no` ;"));
       
// //     }elseif ($request->report==2) {
// //       $report_heading = 'Ward Check List (New Voters) <br> Data List ::'.$datatype.'<br>Ward No. :: '.$village_name. ' - '.$ward_no;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vt`.`status`, `vt`.`house_no_l`, `ac`.`code`, `ap`.`part_no` from `voters` `vt` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` where `vt`.`data_list_id` = $request->data_list and `vt`.`ward_id` = $request->ward_id  and  `vt`.`status` in (0,1) order by `ac`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;"));
       
// //     }elseif ($request->report==3) {
// //       $report_heading = 'MC Check List (New Voters) <br> Data List ::'.$datatype.'<br>MC Name :: '.$village_name;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`house_no_l` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`data_list_id` = $request->data_list and `vt`.`village_id` =$request->village_id  and  `vt`.`status` in (0,1) order by `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==4) {
// //       $report_heading = 'Assembly Part Check List (New Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`house_no_l` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` in (0,1) and `vt`.`data_list_id` = $request->data_list order by `vt`.`sr_no` ;")); 
// //     }elseif($request->report==5) {
      
// //       $report_heading = 'MC Check List (New Voters -- Not Mapped)'.$village_name.' <br> Data List ::'.$datatype;
// //       $results = DB::select(DB::raw("select `vt`.`voter_card_no`, `vt`.`name_l`, `vt`.`house_no_l`, `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` from `voters` `vt` inner join `assembly_parts` `ap` on `vt`.`assembly_part_id` = `ap`.`id` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->village_id and `status` = 0 and `data_list_id` = $request->data_list order by `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;")); 
// //     }
    
// //     $path=Storage_path('fonts/');
// //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //     $fontDirs = $defaultConfig['fontDir']; 
// //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //     $fontData = $defaultFontConfig['fontdata']; 
// //     $mpdf = new \Mpdf\Mpdf([
// //            'fontDir' => array_merge($fontDirs, [
// //                __DIR__ . $path,
// //            ]),
// //            'fontdata' => $fontData + [
// //                'frutiger' => [
// //                    'R' => 'FreeSans.ttf',
// //                    'I' => 'FreeSansOblique.ttf',
// //                ]
// //            ],
// //            'default_font' => 'freesans',
// //            'pagenumPrefix' => '',
// //           'pagenumSuffix' => '',
// //           'nbpgPrefix' => ' कुल ',
// //           'nbpgSuffix' => ' पृष्ठों का पृष्ठ',
// //     ]);
// //     if($request->report==2){
// //       $html = view('admin.master.newVoterWardWise.pdf_report_complete_ward',compact('report_heading','results'));
// //     }elseif($request->report<=4){
// //       $html = view('admin.master.newVoterWardWise.report',compact('report_heading','results'));   
// //     }else{
// //       $html = view('admin.master.markDeleteVoter.pdf_report_notmapped_village',compact('report_heading','results'));   
// //     }
    
// //     $mpdf->WriteHTML($html); 
// //     $mpdf->Output();

// //   }
// // //------------------------mark-delete-voter--------------------------
// //  public function markDeleteVoter($value='')
// //   {
// //     try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.markDeleteVoter.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markDeleteVoterForm(Request $request)
// //   {
// //     try {
// //       $import_type = DB::select(DB::raw("select * from `import_type`")); 
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.markDeleteVoter.select_ac_data_list',compact('import_type','assemblyParts'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markDeleteVotertable(Request $request)
// //   {
// //     try {
// //       $village_id=$request->village_id;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`assembly_part_id` = $request->part_id and `vt`.`status` = 2 and `vt`.`data_list_id` = $request->id order by `vt`.`sr_no` ;"));
// //       return view('admin.master.markDeleteVoter.table',compact('results','village_id'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markDeleteVoterupdate($voter_id, $village_id)
// //   {
// //     try {

// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$village_id','0')"));
// //       return view('admin.master.markDeleteVoter.popup',compact('voter_id','WardVillages'));
// //     }catch (Exception $e) {
        
// //     }
// //   }
// //   public function markDeleteVoterStore(Request $request)
// //   { 
  
// //     $rules=[ 

// //       'ward_id' => 'required',  
// //       'sr_no' => 'required',  
         
// //     ];

// //     $validator = Validator::make($request->all(),$rules);
// //     if ($validator->fails()) {
// //         $errors = $validator->errors()->all();
// //         $response=array();
// //         $response["status"]=0;
// //         $response["msg"]=$errors[0];
// //         return response()->json($response);// response as json
// //     } 

// //     $rs_update = DB::select(DB::raw("call `up_mark_delete_ward_suppliment_datalist` ('$request->voter_id','$request->ward_id','$request->sr_no', 0)"));

// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }
// //   public function markDeleteVoterRestore($voter_id , $ward_id)
// //   {  
// //     $rs_update = DB::select(DB::raw("call `up_restore_ward_booth_deleted_suppliment_datalist` ('$voter_id','$ward_id')")); 
// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }
// //   public function markDeleteVoterReport(Request $request)
// //   {
// //     $village_id=$request->village_id; 
// //     $assembly_part=$request->assembly_part;
// //     $data_list=$request->data_list;
    
// //     return view('admin.master.markDeleteVoter.report_popup',compact('village_id' ,'assembly_part' , 'data_list')); 
// //   }

// //   public function markDeleteVoterReportGenerate(Request $request)
// //   {
// //     $import_type=DB::select(DB::raw("select * from `import_type` where `id` =$request->data_list limit 1"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Data List Not Selected, Plz Select Data List First','class'=>'error']); 
// //     }
// //     $datatype = $import_type[0]->description;

// //     if($request->report==1 || $request->report==3){
// //       $import_type=DB::select(DB::raw("select `asb`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`id` = $request->assembly_part limit 1;"));
// //       if(count($import_type)==0){
// //        return redirect()->back()->with(['message'=>'Assembly Part Not Selected, Plz Select Assembly Part First','class'=>'error']); 
// //       }
// //       $assembly_code = $import_type[0]->code.' - '.$import_type[0]->part_no;
// //     }
    

// //     if($request->report==2 || $request->report==4){
// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l; 
// //     }

// //     if ($request->report==1) {
// //       $report_heading = 'Voter Not Marked (Deleted Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, '' as  `village_name`, '' as `ward_no`, '' as `status`, '' as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l` from `voters` `vt` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 2 and `village_id` = 0 and `vt`.`data_list_id` =$request->data_list order by `vt`.`sr_no` ;"));
     
// //     }elseif ($request->report==2) {
// //       $report_heading = 'MC Check List (Deleted Voters) <br> Data List ::'.$datatype.'<br>MC Name :: '.$village_name;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `wv`.`ward_no`, `vt`.`status`, `vt`.`house_no_l`, `ac`.`code`, `ap`.`part_no` from `voters` `vt` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`data_list_id` = $request->data_list and `vt`.`village_id` = $request->village_id  and  `vt`.`status` = 2 order by `wv`.`ward_no`, `ac`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==3) {
// //       $report_heading = 'Assembly Part Check List (Deleted Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 2 and `vt`.`data_list_id` = $request->data_list order by `vt`.`sr_no` ;")); 
// //     }elseif($request->report==4) {
      
// //       $report_heading = 'MC Check List (Deleted Voters -- Not Mapped)'.$village_name.' <br> Data List ::'.$datatype;
// //       $results = DB::select(DB::raw("select `vt`.`voter_card_no`, `vt`.`name_l`, `vt`.`house_no_l`, `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` from `voters` `vt` inner join `assembly_parts` `ap` on `vt`.`assembly_part_id` = `ap`.`id` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->village_id and `status` = 2 and `data_list_id` = $request->data_list and `vt`.`village_id` = 0 order by `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;")); 
// //     } 
  
// //     $path=Storage_path('fonts/');
// //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //     $fontDirs = $defaultConfig['fontDir']; 
// //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //     $fontData = $defaultFontConfig['fontdata']; 
// //     $mpdf = new \Mpdf\Mpdf([
// //            'fontDir' => array_merge($fontDirs, [
// //                __DIR__ . $path,
// //            ]),
// //            'fontdata' => $fontData + [
// //                'frutiger' => [
// //                    'R' => 'FreeSans.ttf',
// //                    'I' => 'FreeSansOblique.ttf',
// //                ]
// //            ],
// //            'default_font' => 'freesans',
// //            'pagenumPrefix' => '',
// //           'pagenumSuffix' => '',
// //           'nbpgPrefix' => ' कुल ',
// //           'nbpgSuffix' => ' पृष्ठों का पृष्ठ',
// //     ]);

// //     if($request->report==2) {
// //       $html = view('admin.master.markDeleteVoter.pdf_report_complete_village',compact('report_heading','results'));  
// //     }elseif($request->report<4) {
// //       $html = view('admin.master.markDeleteVoter.report_pdf',compact('report_heading','results')); 
// //     }else{
// //       $html = view('admin.master.markDeleteVoter.pdf_report_notmapped_village',compact('report_heading','results'));  
// //     }
    
// //     $mpdf->WriteHTML($html); 
// //     $mpdf->Output();

// //   }

// //   //------------------------mark-Modification-voter--------------------------
// //  public function markModificationVoter($value='')
// //   {
// //     try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.markModificationVoter.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationVoterform(Request $request)
// //   {
// //     try {
// //       $import_type = DB::select(DB::raw("select * from `import_type`")); 
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.markModificationVoter.select_ac_data_list',compact('import_type','assemblyParts'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationVoterTable(Request $request)
// //   {
// //     try {
// //       $village_id=$request->village_id;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`assembly_part_id` = $request->part_id and `vt`.`status` = 3 and `vt`.`data_list_id` = $request->id order by `vt`.`sr_no` ;"));
// //       return view('admin.master.markModificationVoter.table',compact('results','village_id'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationupdate($voter_id, $village_id)
// //   {
// //     try {

// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$village_id','0')"));
// //       return view('admin.master.markModificationVoter.popup',compact('voter_id','WardVillages'));
// //     }catch (Exception $e) {
        
// //     }
// //   }
// //    public function markModificationVoterStore(Request $request)
// //   { 
  
// //     $rules=[ 

// //       'ward_id' => 'required',  
// //       'sr_no' => 'required',  
         
// //     ];

// //     $validator = Validator::make($request->all(),$rules);
// //     if ($validator->fails()) {
// //         $errors = $validator->errors()->all();
// //         $response=array();
// //         $response["status"]=0;
// //         $response["msg"]=$errors[0];
// //         return response()->json($response);// response as json
// //     } 

// //     $rs_update = DB::select(DB::raw("call `up_mark_modified_ward_suppliment_datalist` ('$request->voter_id','$request->ward_id','$request->sr_no', 0)"));

// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }

// //   public function markModificationVoterRestore($voter_id , $ward_id)
// //   {  
// //     $rs_update = DB::select(DB::raw("call `up_restore_ward_booth_modified_suppliment_datalist` ('$voter_id','$ward_id')")); 
// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }

// //   public function markModificationVoterReport(Request $request)
// //   {
// //     $village_id=$request->village_id; 
// //     $assembly_part=$request->assembly_part;
// //     $data_list=$request->data_list;
    
// //     return view('admin.master.markModificationVoter.report_popup',compact('village_id' ,'assembly_part' , 'data_list')); 
// //   } 

// //   public function markModificationVoterReportGenerate(Request $request)
// //   {
// //     $import_type=DB::select(DB::raw("select * from `import_type` where `id` =$request->data_list limit 1"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Data List Not Selected, Plz Select Data List First','class'=>'error']); 
// //     }
// //     $datatype = $import_type[0]->description;

// //     if ($request->report==1 || $request->report==3) {
// //       $import_type=DB::select(DB::raw("select `asb`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`id` = $request->assembly_part limit 1;"));
// //       if(count($import_type)==0){
// //        return redirect()->back()->with(['message'=>'Assembly Part Not Selected, Plz Select Assembly Part First','class'=>'error']); 
// //       }
// //       $assembly_code = $import_type[0]->code.' - '.$import_type[0]->part_no;
// //     }

// //     if($request->report==2 || $request->report==4){
// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l;
// //     }

    

// //     if ($request->report==1) {
// //       $report_heading = 'Voter Not Marked (Modified Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, '' as  `village_name`, '' as `ward_no`, '' as `status`, '' as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l` from `voters` `vt` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 3 and `village_id` = 0 and `vt`.`data_list_id` =$request->data_list order by `vt`.`sr_no` ;"));
     
// //     }elseif ($request->report==2) {
// //       $report_heading = 'MC Check List (Modified Voters) <br> Data List ::'.$datatype.'<br>MC Name :: '.$village_name;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `wv`.`ward_no`, `vt`.`status`, `vt`.`house_no_l`, `ac`.`code`, `ap`.`part_no` from `voters` `vt` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`data_list_id` = $request->data_list and `vt`.`village_id` = $request->village_id  and  `vt`.`status` = 3 order by `wv`.`ward_no`, `ac`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;"));  
// //     }elseif ($request->report==3) {
// //       $report_heading = 'Assembly Part Check List (Modified Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 3 and `vt`.`data_list_id` = $request->data_list order by `vt`.`sr_no` ;")); 
// //     }elseif($request->report==4) {
      
// //       $report_heading = 'MC Check List (Modified Voters -- Not Mapped)'.$village_name.' <br> Data List ::'.$datatype;
// //       $results = DB::select(DB::raw("select `vt`.`voter_card_no`, `vt`.`name_l`, `vt`.`house_no_l`, `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` from `voters` `vt` inner join `assembly_parts` `ap` on `vt`.`assembly_part_id` = `ap`.`id` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->village_id and `status` = 3 and `data_list_id` = $request->data_list and `vt`.`village_id` = 0 order by `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;")); 
// //     } 
  
// //     $path=Storage_path('fonts/');
// //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //     $fontDirs = $defaultConfig['fontDir']; 
// //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //     $fontData = $defaultFontConfig['fontdata']; 
// //     $mpdf = new \Mpdf\Mpdf([
// //            'fontDir' => array_merge($fontDirs, [
// //                __DIR__ . $path,
// //            ]),
// //            'fontdata' => $fontData + [
// //                'frutiger' => [
// //                    'R' => 'FreeSans.ttf',
// //                    'I' => 'FreeSansOblique.ttf',
// //                ]
// //            ],
// //            'default_font' => 'freesans',
// //            'pagenumPrefix' => '',
// //           'pagenumSuffix' => '',
// //           'nbpgPrefix' => ' कुल ',
// //           'nbpgSuffix' => ' पृष्ठों का पृष्ठ',
// //     ]);


// //     if($request->report==2) {
// //       $html = view('admin.master.markDeleteVoter.pdf_report_complete_village',compact('report_heading','results')); 
// //     }elseif($request->report<4) {
// //       $html = view('admin.master.markDeleteVoter.report_pdf',compact('report_heading','results')); 
// //     }else{
// //       $html = view('admin.master.markDeleteVoter.pdf_report_notmapped_village',compact('report_heading','results'));  
// //     }
// //     $mpdf->WriteHTML($html); 
// //     $mpdf->Output();

// //   }

// //   //new-voter-booth-wise-----------------------------

// //   public function newVoterBoothWise()
// //   {
// //     try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.newVoterBoothWise.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function newVoterBoothWiseForm(Request $request)
// //   {
// //     try {
// //       $import_type = DB::select(DB::raw("select * from `import_type`"));
// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
// //       $booths = DB::select(DB::raw("select * from `polling_booths` where `village_id` = $request->id order by `booth_no`;"));
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.newVoterBoothWise.select_ward_booth_acpart',compact('import_type','WardVillages','booths','assemblyParts'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function newVoterBoothWiseTable(Request $request)
// //   {
// //     try {
// //       $village_id=$request->village_id;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->part_id and `vt`.`status` in (0,1) and `vt`.`data_list_id` = $request->id order by `vt`.`sr_no` ;"));
// //       return view('admin.master.newVoterBoothWise.table',compact('results','village_id'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //    public function newVoterBoothWiseStore(Request $request)
// //   { 
  
// //     $rules=[ 

// //       'states' => 'required',  
// //       'district' => 'required',  
// //       'block' => 'required',  
// //       'village' => 'required',  
// //       'ward' => 'required',  
// //       'booth' => 'required',  
// //       'assembly_part' => 'required',     
     
// //       'data_list' => 'required',     
// //       'from_sr_no' => 'required',     
// //     ];

// //     $validator = Validator::make($request->all(),$rules);
// //     if ($validator->fails()) {
// //         $errors = $validator->errors()->all();
// //         $response=array();
// //         $response["status"]=0;
// //         $response["msg"]=$errors[0];
// //         return response()->json($response);// response as json
// //     }
// //     $from_sn = trim($request->from_sr_no);
// //     $to_sn = trim($request->to_sr_no);
// //     if($from_sn == '' && $to_sn == ''){
// //       $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
// //       return response()->json($response);  
// //     }
// //     if($from_sn == ''){
// //       $from_sn = $to_sn;
// //     }
// //     if($to_sn == ''){
// //       $to_sn = $from_sn;
// //     }
    
// //     $rs_update = DB::select(DB::raw("call `up_add_voters_wards_suppliment_datalistwise` ($request->assembly_part , $from_sn , $to_sn , $request->ward , $request->booth , $request->data_list)"));
// //     $response=['status'=>$rs_update[0]->status,'msg'=>$rs_update[0]->result];
// //     return response()->json($response);
// //   }


// //   public function newVoterBoothWiseDelete($voter_id , $ward_id)
// //   {  
// //     $rs_update = DB::select(DB::raw("call `up_delete_ward_booth_added_suppliment` ('$voter_id','$ward_id')")); 
// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }


// //   public function newVoterBoothWiseReport(Request $request)
// //   {
// //     $village_id=$request->village_id;
// //     $ward_id=$request->ward_id;
// //     $assembly_part=$request->assembly_part;
// //     $data_list=$request->data_list;
// //     $booth = $request->booth;
    
    
// //     return view('admin.master.newVoterBoothWise.report_popup',compact('village_id' , 'ward_id' ,'assembly_part' , 'data_list', 'booth')); 
// //   }


// //   public function newVoterBoothWiseReportGenerate(Request $request)
// //   {
    
    
// //     $import_type=DB::select(DB::raw("select * from `import_type` where `id` =$request->data_list limit 1"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Data List Not Selected, Plz Select Data List First','class'=>'error']); 
// //     }
// //     $datatype = $import_type[0]->description;

// //     $import_type=DB::select(DB::raw("select `asb`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`id` = $request->assembly_part limit 1;"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Assembly Part Not Selected, Plz Select Assembly Part First','class'=>'error']); 
// //     }
// //     $assembly_code = $import_type[0]->code.' - '.$import_type[0]->part_no;

    

    

// //     if ($request->report==1) {
// //       $report_heading = 'Voter Not Mapped (New Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, '' as  `village_name`, '' as `ward_no`, '' as `status`, '' as `ward_id`, `vt`.`house_no_l`, '' as `c_booth_no` from `voters` `vt` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 0 and `vt`.`data_list_id` =$request->data_list order by `vt`.`sr_no` ;"));
       
// //     }elseif ($request->report==2) {
// //       $ward_villages=DB::select(DB::raw("select * from `ward_villages` where `id` =$request->ward_id limit 1"));
// //       if(count($ward_villages)==0){
// //        return redirect()->back()->with(['message'=>'Ward No. Not Selected, Plz Select Ward No. First','class'=>'error']); 
// //       }
// //       $ward_no = $ward_villages[0]->ward_no;

// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l;

// //       $report_heading = 'Ward Check List (New Voters) <br> Data List ::'.$datatype.'<br>Ward No. :: '.$village_name. ' - '.$ward_no.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`data_list_id` = $request->data_list and `vt`.`ward_id` = $request->ward_id  and  `vt`.`status` in (0,1) order by `vt`.`sr_no` ;"));
       
// //     }elseif ($request->report==3) {
// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l;

// //       $report_heading = 'MC Check List (New Voters) <br> Data List ::'.$datatype.'<br>MC Name :: '.$village_name.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`data_list_id` = $request->data_list and `vt`.`village_id` =$request->village_id  and  `vt`.`status` in (0,1) order by `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==4) {
// //       $report_heading = 'Assembly Part Check List (New Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` in (0,1) and `vt`.`data_list_id` = $request->data_list order by `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==5) {
// //       $rs_result=DB::select(DB::raw("select concat(`booth_no`, ifnull(`booth_no_c`,'')) as `c_booth_no` from `polling_booths` where `id` =$request->booth limit 1"));
// //       if(count($rs_result)==0){
// //        return redirect()->back()->with(['message'=>'Booth No. Not Selected, Plz Select Booth No. First','class'=>'error']); 
// //       }
// //       $booth_no = $rs_result[0]->c_booth_no;

// //       $report_heading = 'Polling Booth Check List (New Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code.'<br>Polling Booth :: '.$booth_no;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` in (0,1) and `vt`.`data_list_id` = $request->data_list and `vt`.`booth_id` = $request->booth order by `vt`.`sr_no` ;")); 
// //     } 
    
// //     $path=Storage_path('fonts/');
// //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //     $fontDirs = $defaultConfig['fontDir']; 
// //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //     $fontData = $defaultFontConfig['fontdata']; 
// //     $mpdf = new \Mpdf\Mpdf([
// //            'fontDir' => array_merge($fontDirs, [
// //                __DIR__ . $path,
// //            ]),
// //            'fontdata' => $fontData + [
// //                'frutiger' => [
// //                    'R' => 'FreeSans.ttf',
// //                    'I' => 'FreeSansOblique.ttf',
// //                ]
// //            ],
// //            'default_font' => 'freesans',
// //            'pagenumPrefix' => '',
// //           'pagenumSuffix' => '',
// //           'nbpgPrefix' => ' कुल ',
// //           'nbpgSuffix' => ' पृष्ठों का पृष्ठ',
// //     ]);
    
// //     $html = view('admin.master.newVoterBoothWise.report',compact('report_heading','results')); 
// //     $mpdf->WriteHTML($html); 
// //     $mpdf->Output();

// //   }



// //   //--------------------------------

// //   public function markDeleteVoterBoothWise()
// //   {
// //     try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.markDeleteVoterBoothWise.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// //   }


// //   public function markDeleteVoterBoothWiseForm(Request $request)
// //   {
// //     try {
// //       $import_type = DB::select(DB::raw("select * from `import_type`")); 
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.markDeleteVoterBoothWise.select_booth_acpart',compact('import_type','assemblyParts'));
// //     } catch (Exception $e) {
        
// //     }
// //   }

// //   public function markDeleteVotertableboothwise(Request $request)
// //   {
// //     try {
// //       $village_id=$request->village_id;
// //       if(trim($request->part_id)==''){
// //         $part_id = 0;
// //         $data_list_id = 0;
// //       }else{
// //         $part_id = $request->part_id;
// //         $data_list_id = $request->id;
// //       }
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $part_id and `vt`.`status` = 2 and `vt`.`data_list_id` = $data_list_id order by `vt`.`sr_no` ;"));
// //       return view('admin.master.markDeleteVoterBoothWise.table',compact('results','village_id'));
// //     } catch (Exception $e) {
        
// //     }
// //   }

// //   public function markDeleteboothVoterupdate($voter_id, $village_id)
// //   {
// //     try {
// //       $booths = DB::select(DB::raw("select `id`, concat(`booth_no`, ifnull(`booth_no_c`,'')) as `c_booth_no`, `name_e` from `polling_booths` where `village_id` = $village_id order by `booth_no`;"));
// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$village_id','0')"));
// //       return view('admin.master.markDeleteVoterBoothWise.popup',compact('voter_id','WardVillages', 'booths'));
// //     }catch (Exception $e) {
        
// //     }
// //   }

// //   public function markDeleteboothVoterStore(Request $request)
// //   { 
  
// //     $rules=[ 

// //       'ward_id' => 'required',  
// //       'booth_id' => 'required',  
// //       'sr_no' => 'required',  
         
// //     ];

// //     $validator = Validator::make($request->all(),$rules);
// //     if ($validator->fails()) {
// //         $errors = $validator->errors()->all();
// //         $response=array();
// //         $response["status"]=0;
// //         $response["msg"]=$errors[0];
// //         return response()->json($response);// response as json
// //     } 

// //     $rs_update = DB::select(DB::raw("call `up_mark_delete_ward_suppliment_datalist` ('$request->voter_id','$request->ward_id','$request->sr_no', $request->booth_id)"));

// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }

// //   public function markDeleteboothVoterRestore($voter_id , $ward_id)
// //   {  
// //     $rs_update = DB::select(DB::raw("call `up_restore_ward_booth_deleted_suppliment_datalist` ('$voter_id','$ward_id')")); 
// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }

// //   public function markDeleteVotertableboothwiseReport(Request $request)
// //   {
// //     $village_id=$request->village_id; 
// //     $assembly_part=$request->assembly_part;
// //     $data_list=$request->data_list;
    
// //     return view('admin.master.markDeleteVoterBoothWise.report_popup',compact('village_id' ,'assembly_part' , 'data_list')); 
// //   }


// //   public function markDeleteVoterBoothReportGenerate(Request $request)
// //   {
// //     $import_type=DB::select(DB::raw("select * from `import_type` where `id` =$request->data_list limit 1"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Data List Not Selected, Plz Select Data List First','class'=>'error']); 
// //     }
// //     $datatype = $import_type[0]->description;

// //     $import_type=DB::select(DB::raw("select `asb`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`id` = $request->assembly_part limit 1;"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Assembly Part Not Selected, Plz Select Assembly Part First','class'=>'error']); 
// //     }
// //     $assembly_code = $import_type[0]->code.' - '.$import_type[0]->part_no;

// //     if ($request->report==1) {
// //       $report_heading = 'Voter Not Marked (Deleted Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, '' as  `village_name`, '' as `ward_no`, '' as `status`, '' as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 2 and `vt`.`village_id` = 0 and `vt`.`data_list_id` =$request->data_list order by `vt`.`sr_no` ;"));
     
// //     }elseif ($request->report==2) {
// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l; 

// //       $report_heading = 'MC Check List (Deleted Voters) <br> Data List ::'.$datatype.'<br>MC Name :: '.$village_name;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`data_list_id` = $request->data_list and `vt`.`village_id` =$request->village_id  and  `vt`.`status` = 2 order by `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==3) {
// //       $report_heading = 'Assembly Part Check List (Deleted Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 2 and `vt`.`data_list_id` = $request->data_list order by `vt`.`sr_no` ;")); 
// //     } 
  
// //     $path=Storage_path('fonts/');
// //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //     $fontDirs = $defaultConfig['fontDir']; 
// //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //     $fontData = $defaultFontConfig['fontdata']; 
// //     $mpdf = new \Mpdf\Mpdf([
// //            'fontDir' => array_merge($fontDirs, [
// //                __DIR__ . $path,
// //            ]),
// //            'fontdata' => $fontData + [
// //                'frutiger' => [
// //                    'R' => 'FreeSans.ttf',
// //                    'I' => 'FreeSansOblique.ttf',
// //                ]
// //            ],
// //            'default_font' => 'freesans',
// //            'pagenumPrefix' => '',
// //           'pagenumSuffix' => '',
// //           'nbpgPrefix' => ' कुल ',
// //           'nbpgSuffix' => ' पृष्ठों का पृष्ठ',
// //     ]);
// //     $html = view('admin.master.markDeleteVoterBoothWise.report_pdf',compact('report_heading','results')); 
// //     $mpdf->WriteHTML($html); 
// //     $mpdf->Output();

// //   }




// //   //--------------------------------

// //   public function markModificationVoterBoothWise()
// //   {
// //     try {
// //       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
// //       return view('admin.master.markModificationVoterBoothWise.index',compact('States'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationVoterBoothForm(Request $request)
// //   {
// //     try {
// //       $import_type = DB::select(DB::raw("select * from `import_type`")); 
// //       $assemblyParts = DB::select(DB::raw("select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
// //       return view('admin.master.markModificationVoterBoothWise.select_ac_data_list',compact('import_type','assemblyParts'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationVoterBoothTable(Request $request)
// //   {
// //     try {
// //       $village_id=$request->village_id;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->part_id and `vt`.`status` = 3 and `vt`.`data_list_id` = $request->id order by `vt`.`sr_no` ;"));
// //       return view('admin.master.markModificationVoterBoothWise.table',compact('results','village_id'));
// //     } catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationVoterBoothUpdate($voter_id, $village_id)
// //   {
// //     try {
// //       $booths = DB::select(DB::raw("select `id`, concat(`booth_no`, ifnull(`booth_no_c`,'')) as `c_booth_no`, `name_e` from `polling_booths` where `village_id` = $village_id order by `booth_no`;"));
      
// //       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$village_id','0')"));

// //       return view('admin.master.markModificationVoterBoothWise.popup',compact('voter_id','WardVillages', 'booths'));
// //     }catch (Exception $e) {
        
// //     }
// //   }
// //   public function markModificationVoterBoothUpStore(Request $request)
// //   { 
  
// //     $rules=[ 

// //       'ward_id' => 'required',  
// //       'sr_no' => 'required',  
// //       'booth_id' => 'required',  
         
// //     ];

// //     $validator = Validator::make($request->all(),$rules);
// //     if ($validator->fails()) {
// //         $errors = $validator->errors()->all();
// //         $response=array();
// //         $response["status"]=0;
// //         $response["msg"]=$errors[0];
// //         return response()->json($response);// response as json
// //     } 

// //     $rs_update = DB::select(DB::raw("call `up_mark_modified_ward_suppliment_datalist` ('$request->voter_id','$request->ward_id','$request->sr_no', $request->booth_id)"));

// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }

// //   public function markModificationVoterBoothUpRestore($voter_id , $ward_id)
// //   {  
// //     $rs_update = DB::select(DB::raw("call `up_restore_ward_booth_modified_suppliment_datalist` ('$voter_id','$ward_id')")); 
// //     $response=['status'=>$rs_update[0]->rstatus,'msg'=>$rs_update[0]->rremarks];
// //     return response()->json($response);
// //   }

// //   public function markModificationVoterBoothUpReport(Request $request)
// //   {
// //     $village_id=$request->village_id; 
// //     $assembly_part=$request->assembly_part;
// //     $data_list=$request->data_list;
    
// //     return view('admin.master.markModificationVoterBoothWise.report_popup',compact('village_id' ,'assembly_part' , 'data_list')); 
// //   }

// //   public function markModificationVoterBoothUpReportGenerate(Request $request)
// //   {
// //     $import_type=DB::select(DB::raw("select * from `import_type` where `id` =$request->data_list limit 1"));
// //     if(count($import_type)==0){
// //      return redirect()->back()->with(['message'=>'Data List Not Selected, Plz Select Data List First','class'=>'error']); 
// //     }
// //     $datatype = $import_type[0]->description;

// //     if ($request->report<=3) {
// //       $import_type=DB::select(DB::raw("select `asb`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`id` = $request->assembly_part limit 1;"));
// //       if(count($import_type)==0){
// //        return redirect()->back()->with(['message'=>'Assembly Part Not Selected, Plz Select Assembly Part First','class'=>'error']); 
// //       }
// //       $assembly_code = $import_type[0]->code.' - '.$import_type[0]->part_no;
// //     }

    

// //     if ($request->report==1) {
// //       $report_heading = 'Voter Not Marked (Modified Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, '' as  `village_name`, '' as `ward_no`, '' as `status`, '' as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 3 and `vt`.`village_id` = 0 and `vt`.`data_list_id` =$request->data_list order by `vt`.`sr_no` ;"));
     
// //     }elseif ($request->report==2) {

// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l;

    
// //       $report_heading = 'MC Check List (Modified Voters) <br> Data List ::'.$datatype.'<br>MC Name :: '.$village_name;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`data_list_id` = $request->data_list and `vt`.`village_id` =$request->village_id  and  `vt`.`status` = 3 order by `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==3) {
// //       $report_heading = 'Assembly Part Check List (Modified Voters) <br> Data List ::'.$datatype.'<br>Assembly Part No. :: '.$assembly_code;
// //       $results = DB::select(DB::raw("select `vt`.`id`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`name_l`, `vil`.`name_l` as `village_name`, `wv`.`ward_no`, `vt`.`status`, `wv`.`id` as `ward_id`, `vt`.`print_sr_no`, `vt`.`house_no_l`, `vt`.`booth_id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `c_booth_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` where `vt`.`assembly_part_id` = $request->assembly_part and `vt`.`status` = 3 and `vt`.`data_list_id` = $request->data_list order by `vt`.`sr_no` ;")); 
// //     }elseif ($request->report==4) {
// //       $villages=DB::select(DB::raw("select * from `villages` where `id` =$request->village_id limit 1"));
// //       if(count($villages)==0){
// //        return redirect()->back()->with(['message'=>'MC Not Selected, Plz Select MC First','class'=>'error']); 
// //       }
// //       $village_name = $villages[0]->name_l;

// //       $report_heading = 'MC Check List (Modified Voters -- Not Mapped)'.$village_name.' <br> Data List ::'.$datatype;
// //       $results = DB::select(DB::raw("select `vt`.`voter_card_no`, `vt`.`name_l`, `vt`.`house_no_l`, `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` from `voters` `vt` inner join `assembly_parts` `ap` on `vt`.`assembly_part_id` = `ap`.`id` inner join `assemblys` `asb` on `asb`.`id` = `ap`.`assembly_id` where `ap`.`village_id` = $request->village_id and `status` = 3 and `data_list_id` = $request->data_list order by `asb`.`code`, `ap`.`part_no`, `vt`.`sr_no` ;")); 
// //     }
  
// //     $path=Storage_path('fonts/');
// //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //     $fontDirs = $defaultConfig['fontDir']; 
// //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //     $fontData = $defaultFontConfig['fontdata']; 
// //     $mpdf = new \Mpdf\Mpdf([
// //            'fontDir' => array_merge($fontDirs, [
// //                __DIR__ . $path,
// //            ]),
// //            'fontdata' => $fontData + [
// //                'frutiger' => [
// //                    'R' => 'FreeSans.ttf',
// //                    'I' => 'FreeSansOblique.ttf',
// //                ]
// //            ],
// //            'default_font' => 'freesans',
// //            'pagenumPrefix' => '',
// //           'pagenumSuffix' => '',
// //           'nbpgPrefix' => ' कुल ',
// //           'nbpgSuffix' => ' पृष्ठों का पृष्ठ',
// //     ]);
// //     if ($request->report<=3) {
// //       $html = view('admin.master.markDeleteVoterBoothWise.report_pdf',compact('report_heading','results')); 
// //     }else{
// //       $html = view('admin.master.markDeleteVoter.pdf_report_notmapped_village',compact('report_heading','results')); 
// //     }
// //     $mpdf->WriteHTML($html); 
// //     $mpdf->Output();

// //   }



// // //---------------------------claimObjAcPartSrnoChangeWard-------------------------//

//   public function claimObjAcPartSrnoChangeWard()
//   {
//     try {
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.claimObjAcPartSrno.changeWard.index',compact('States', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function claimObjAcPartSrnoChangeWardForm(Request $request)
//   {
//     try{  
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
//       $assemblyParts = DB::select(DB::raw("  select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
//       $importTypes = DB::select(DB::raw("select * from `import_type`"));
//       return view('admin.master.claimObjAcPartSrno.changeWard.select_box_page',compact('WardVillages','assemblyParts','importTypes', 'refreshdata'));
//     } catch (Exception $e) {}
//   }


//   public function claimObjAcPartSrnoChangeWardTable(Request $request)
//   {
//     $block_id = $request->block_id;
//     $part_id = $request->part_id;
//     $data_list_id = $request->data_list_id;
//     if($part_id == "null"){
//       $part_id = 0;
//     }
//     if($data_list_id == "null"){
//       $data_list_id = 0;
//     }
//     if($block_id == "null"){
//       $block_id = 0;
//     }
//     $results= DB::select(DB::raw("select * from `voter_list_master` where `block_id` = $block_id and `status` = 1;"));  
//     if(count($results) == 0){
//       $voter_list_id  = 0;
//     }else{
//       $voter_list_id  = $results[0]->id;
//     }
    
//     try{
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $results= DB::select(DB::raw("select `vt`.`id`, `vt`.`voter_card_no`, `vt`.`name_e`, `vt`.`father_name_e`, `vt`.`sr_no`, `vt`.`print_sr_no`, ifnull(concat(`vil`.`name_e`, ' - ', `wv`.`ward_no`), '') as `vil_ward`, case ifnull(`svd`.`ward_id`,0) when 0 then `vt`.`ward_id` else `svd`.`ward_id` end as `ward_id`, ifnull(concat(`fv`.`name_e`, ' - ', `fw`.`ward_no`),'') as `from_vil_ward`, `vt`.`status` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `suppliment_voters_deleted` `svd` on `svd`.`voters_id` = `vt`.`id` and `svd`.`suppliment_no` = $voter_list_id left join `villages` `fv` on `fv`.`id` = `svd`.`village_id` left join `ward_villages` `fw` on `fw`.`id` = `svd`.`ward_id` where `vt`.`assembly_part_id` = $part_id and `vt`.`data_list_id` = $data_list_id and `vt`.`suppliment_no` = $voter_list_id order by `vt`.`sr_no`;"));  
//       return view('admin.master.claimObjAcPartSrno.changeWard.table',compact('results', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function claimObjAcPartSrnoChangeWardFormStore(Request $request)
//   { 
  
//     $rules=[ 

//       'states' => 'required',  
//       'district' => 'required',  
//       'block' => 'required',  
//       'village' => 'required',  
//       'assembly_part' => 'required',  
//       'data_list' => 'required',  
//       'from_sr_no' => 'required',  
//       'to_ward' => 'required',  
           
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//         $errors = $validator->errors()->all();
//         $response=array();
//         $response["status"]=0;
//         $response["msg"]=$errors[0];
//         return response()->json($response);// response as json
//     }
//     $from_sn = trim($request->from_sr_no);
//     $to_sn = trim($request->to_sr_no);
//     if($from_sn == '' && $to_sn == ''){
//       $response=['status'=>0,'msg'=>'From Sr. No. and To. Sr. No. Cannot Be Blank'];
//       return response()->json($response);  
//     }
//     if($from_sn == ''){
//       $from_sn = $to_sn;
//     }
//     if($to_sn == ''){
//       $to_sn = $from_sn;
//     }

//     $admin = Auth::guard('admin')->user();
//     $userid = $admin->id;
//     $block_id = $request->block;
//     $village_id = $request->village;
//     $rs_update = DB::select(DB::raw("call `up_change_voters_wards_by_ac_srno` ($userid, $block_id, $village_id, $request->assembly_part, $request->data_list, $from_sn, $to_sn, $request->to_ward, 0)"));
//     $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
//     return response()->json($response);
//   }

// //   //-------------delete-voter-------------------
  

  

  

  

// //   //-------------change-booth-------------------
  

  

  

  


//   public function reportClaimObjWardACPart(Request $request)
//   {
//     try{  
//       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->village_id','0')"));   
//       return view('admin.master.claimObjAcPartSrno.changeWard.report_popup',compact('WardVillages'));
//     } catch (Exception $e) {}
//   }


//   public function changeVoterWithWardACPartReportPdf(Request $request)
//   { 
//     $report_selected = $request->report_type;
//     $ward_id = $request->ward;

//     if ($report_selected == 0) {
//       $response=['status'=>0,'msg'=>'Plz Select Report Type'];
//       return response()->json($response);   
//     }
//     if ($ward_id == 0) {
//       $response=['status'=>0,'msg'=>'Plz Select Ward'];
//       return response()->json($response);   
//     }

//     $wardno_rs = DB::select(DB::raw("select `wv`.`ward_no`, `blocks_id` from `ward_villages` `wv` where `wv`.`id` = $request->ward;"));
//     $wardno = $wardno_rs[0]->ward_no;
//     $block_id = $wardno_rs[0]->blocks_id;
    

    
//     $report_heading = '';
//     if ($report_selected == 1) {
//       $results= DB::select(DB::raw("call `up_fetch_list_suppliment_deleted_voter_acpartsrno_detail`($block_id, $ward_id, 0);"));
//       $report_heading = 'Deleted (From Ward) :: '.$wardno;
//     }else{
//       $results= DB::select(DB::raw("call `up_fetch_list_suppliment_new_voter_acpartsrno_detail`($block_id, $ward_id, 0);"));
//       $report_heading = 'Added (To Ward) :: '.$wardno;
//     }
    

//     $path=Storage_path('fonts/');
//     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
//     $fontDirs = $defaultConfig['fontDir']; 
//     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
//     $fontData = $defaultFontConfig['fontdata']; 
//     $mpdf = new \Mpdf\Mpdf([
//     'fontDir' => array_merge($fontDirs, [
//     __DIR__ . $path,
//     ]),
//     'fontdata' => $fontData + [
//     'frutiger' => [
//     'R' => 'FreeSans.ttf',
//     'I' => 'FreeSansOblique.ttf',
//     ]
//     ],
//     'default_font' => 'freesans',
//     'pagenumPrefix' => '',
//     'pagenumSuffix' => '',
//     'nbpgPrefix' => ' कुल ',
//     'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
//     ]); 
//     $showbooth_flag = 0;
//     $html = view('admin.master.claimObjAcPartSrno.changeWard.report_pdf',compact('results', 'report_heading', 'showbooth_flag')); 
//     $mpdf->WriteHTML($html); 
//     $mpdf->Output();

//   } 


//   public function claimObjAcPartEpicNoAddNewVoter()
//   {
//     try {
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $States = DB::select(DB::raw("select * from `states` order by `name_e`;"));
//       return view('admin.master.claimObjAcPartSrno.addVoter.index',compact('States', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function claimObjAcPartEpicAddWardForm(Request $request)
//   {
//     try{  
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access` ('$request->id','0')"));
//       $assemblyParts = DB::select(DB::raw("  select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $request->id order by `ac`.`code`, `ap`.`part_no`;"));
//       $importTypes = DB::select(DB::raw("select * from `import_type`"));
//       return view('admin.master.claimObjAcPartSrno.addVoter.select_box_page',compact('WardVillages','assemblyParts','importTypes', 'refreshdata'));
//     } catch (Exception $e) {}
//   }

//   public function claimObjAcPartEpicAddVoterWardTable(Request $request)
//   {
//     $block_id = $request->block_id;
//     $part_id = $request->part_id;
//     $data_list_id = 1;
//     if($part_id == "null"){
//       $part_id = 0;
//     }
//     if($data_list_id == "null"){
//       $data_list_id = 0;
//     }
//     if($block_id == "null"){
//       $block_id = 0;
//     }
//     $results= DB::select(DB::raw("select * from `voter_list_master` where `block_id` = $block_id and `status` = 1;"));  
//     if(count($results) == 0){
//       $voter_list_id  = 0;
//     }else{
//       $voter_list_id  = $results[0]->id;
//     }
    
//     try{
//       $refreshdata = MyFuncs::Refresh_data_voterEntry();
//       $results= DB::select(DB::raw("select `vt`.`id`, `vt`.`name_e`, `vt`.`father_name_e`, `vt`.`sr_no`, `vt`.`print_sr_no`, ifnull(concat(`vil`.`name_e`, ' - ', `wv`.`ward_no`), '') as `vil_ward`, case ifnull(`svd`.`ward_id`,0) when 0 then `vt`.`ward_id` else `svd`.`ward_id` end as `ward_id`, ifnull(concat(`fv`.`name_e`, ' - ', `fw`.`ward_no`),'') as `from_vil_ward`, `vt`.`status`, `vt`.`voter_card_no` from `voters` `vt` left join `villages` `vil` on `vil`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `suppliment_voters_deleted` `svd` on `svd`.`voters_id` = `vt`.`id` and `svd`.`suppliment_no` = $voter_list_id left join `villages` `fv` on `fv`.`id` = `svd`.`village_id` left join `ward_villages` `fw` on `fw`.`id` = `svd`.`ward_id` where `vt`.`assembly_part_id` = $part_id and `vt`.`suppliment_no` = $voter_list_id order by `vt`.`sr_no`;"));  
//       return view('admin.master.claimObjAcPartSrno.changeWard.table',compact('results', 'refreshdata'));
//     } catch (Exception $e) {}
//   }


//   public function addNewVoterDataFromServer(Request $request)
//   { 
//     $rules=[ 
//       'states' => 'required',  
//       'district' => 'required',  
//       'block' => 'required',  
//       'village' => 'required',
//       'assembly_part' => 'required',  
//       'epic_no' => 'required', 
//       'to_ward' => 'required',      
//     ];

//     $validator = Validator::make($request->all(),$rules);
//     if ($validator->fails()) {
//         $errors = $validator->errors()->all();
//         $response=array();
//         $response["status"]=0;
//         $response["msg"]=$errors[0];
//         return response()->json($response);// response as json
//     }

//     $district_id = $request->district;

//     $epic_no = trim($request->epic_no);
//     $epic_no = str_replace("\\", "", $epic_no);
//     $epic_no = str_replace("\'", "", $epic_no);
    
//     // if(empty($request->to_booth)){
//       $to_booth = 0;
//     //   $response=['status'=>0,'msg'=>'Plz Select Booth No.'];
//     //   return response()->json($response);
//     // }else{
//     //   $to_booth = $request->to_booth;
//     // }

//     $assembly_part = $request->assembly_part;

//     $assemblyPart=DB::select(DB::raw("select * from `assembly_parts` where `id` = $assembly_part limit 1;"));
//     $ac_part_id = $assembly_part;
//     $part_no = $assemblyPart[0]->part_no;
//     $ac_id = $assemblyPart[0]->assembly_id;
//     $assembly=DB::select(DB::raw("select * from `assemblys` where `id` = $ac_id and `district_id` = $district_id limit 1;"));
//     $ac_code = $assembly[0]->code;

    
//     $data_import_id = 1;

//     $dirpath = Storage_path() . '/app/vimage/'.$data_import_id.'/'.$ac_id.'/'.$ac_part_id;
//     $vpath = '/vimage/'.$data_import_id.'/'.$ac_id.'/'.$ac_part_id;
//     @mkdir($dirpath, 0755, true);

//     $datas = DB::connection('sqlsrv')->select("select top 1 SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, EMAIL_ID, MOBILE_NO, PHOTO from eroll where ac_no =$ac_code and part_no =$part_no and EPIC_No = '$epic_no' ");
        
    
//     foreach ($datas as $key => $value) { 
//       $o_village_id = 0;
//       $o_ward_id = 0;
//       $o_print_srno = 0;
//       $o_suppliment = 0;
//       $o_booth_id = 0;
//       $o_district_id = $district_id;
      
//       $o_status = 0;  
              
//       $name_l=str_replace('਍', '', $value->name_l);
//       $name_l=str_replace('\'', '', $name_l);

//       $name_e=substr(str_replace('਍', '', $value->name_en),0,49);
//       $name_e=substr(str_replace('\'', '', $name_e),0,49);
     
//       $f_name_e=substr(str_replace('਍', '', $value->fname_en),0,49);
//       $f_name_e=substr(str_replace('\'', '', $f_name_e),0,49);

//       $f_name_l=str_replace('਍', '', $value->FName_L);
//       $f_name_l=str_replace('\'', '', $f_name_l);

//       if ($value->RLN_Type=='F') {
//         $relation=1;  
//       }
//       elseif ($value->RLN_Type=='G') {
//         $relation=2;  
//       } 
//       elseif ($value->RLN_Type=='H') {
//         $relation=3;  
//       } 
//       elseif ($value->RLN_Type=='M') {
//         $relation=4;  
//       } 
//       elseif ($value->RLN_Type=='O') {
//         $relation=5;  
//       } 
//       elseif ($value->RLN_Type=='W') {
//         $relation=6;  
//       }
//       if ($value->GENDER=='M') {
//         $gender_id=1;  
//       }
//       elseif ($value->GENDER=='F') {
//         $gender_id=2;  
//       }else{
//         $gender_id=3;  
//       }  
//       $house_e = substr(str_replace('\\',' ', $value->C_House_no),0,49);
//       $house_e = substr(str_replace('\'',' ', $house_e),0,49);

//       $house_l = str_replace("\\",' ', $value->C_House_No_V1);
//       $house_l = str_replace('\'',' ', $house_l);
      
//       $newId = DB::select(DB::raw("call up_save_voter_detail($o_district_id, $ac_id, $ac_part_id, $value->SlNoInPart, '$value->EPIC_No', '$house_e', '$house_l','','$name_e','$name_l','$f_name_e','$f_name_l', $relation, $gender_id, $value->AGE, '$value->MOBILE_NO', 'v', $o_suppliment, $o_status, $o_village_id, $o_ward_id, '$o_print_srno', $o_booth_id, $data_import_id, '*');"));
      
//       $image=$value->PHOTO;
//       $name = $value->SlNoInPart;
//       $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);
    

//       $admin = Auth::guard('admin')->user();
//       $userid = $admin->id;
//       $block_id = $request->block;
//       $village_id = $request->village;
//       $rs_update = DB::select(DB::raw("call `up_change_voters_wards_by_ac_srno` ($userid, $block_id, $village_id, $request->assembly_part, $data_import_id, $value->SlNoInPart, $value->SlNoInPart, $request->to_ward, $to_booth)"));
//       $response=['status'=>$rs_update[0]->s_status,'msg'=>$rs_update[0]->result];
//       return response()->json($response);  
      
//     }

//     if(count($datas) == 0){
//       $response=['status'=>0,'msg'=>'No Data Found'];
//       return response()->json($response);
//     }  
      
//   }








//   //Previous Commented
//   // public function changeVoterWithWardReport(Request $request)
// // {
// //   try{  
// //       $WardVillages = DB::select(DB::raw("call up_fetch_ward_village_access ('$request->village_id','0')"));   
// //       return view('admin.master.changeVoterWithWard.report_popup',compact('WardVillages'));
// //     } catch (Exception $e) {
        
// //     }
// // }


// // public function changeVoterWithWardReportPdf(Request $request)
// // { 
// //   $report_selected = $request->report_type;
// //   $ward_id = $request->ward;

// //   if ($report_selected == 0) {
// //     $response=['status'=>0,'msg'=>'Plz Select Report Type'];
// //     return response()->json($response);   
// //   }
// //   if ($ward_id == 0) {
// //     $response=['status'=>0,'msg'=>'Plz Select Ward'];
// //     return response()->json($response);   
// //   }

// //   $wardno_rs = DB::select(DB::raw("select `wv`.`ward_no` from `ward_villages` `wv` where `wv`.`id` = $request->ward;"));
// //   $wardno = $wardno_rs[0]->ward_no;
  

  
// //   $report_heading = '';
// //   if ($report_selected == 1) {
// //     $results= DB::select(DB::raw("call `up_fetch_list_suppliment_deleted_voter_detail`($ward_id, 0);"));
// //     $report_heading = 'Deleted (From Ward) :: '.$wardno;
// //   }else{
// //     $results= DB::select(DB::raw("call `up_fetch_list_suppliment_new_voter_detail`($ward_id, 0);"));
// //     $report_heading = 'Added (To Ward) :: '.$wardno;
// //   }
  

// //   $path=Storage_path('fonts/');
// //   $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
// //   $fontDirs = $defaultConfig['fontDir']; 
// //   $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
// //   $fontData = $defaultFontConfig['fontdata']; 
// //   $mpdf = new \Mpdf\Mpdf([
// //   'fontDir' => array_merge($fontDirs, [
// //   __DIR__ . $path,
// //   ]),
// //   'fontdata' => $fontData + [
// //   'frutiger' => [
// //   'R' => 'FreeSans.ttf',
// //   'I' => 'FreeSansOblique.ttf',
// //   ]
// //   ],
// //   'default_font' => 'freesans',
// //   'pagenumPrefix' => '',
// //   'pagenumSuffix' => '',
// //   'nbpgPrefix' => ' कुल ',
// //   'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
// //   ]); 
// //   $showbooth_flag = 0;
// //   $html = view('admin.master.changeVoterWithWard.pdf',compact('results', 'report_heading', 'showbooth_flag')); 
// //   $mpdf->WriteHTML($html); 
// //   $mpdf->Output();

// // } 




//   //--------------End----------











    

     
// //     public function ZilaParishadStore(Request $request)
// //    {  
// //        $rules=[ 
// //             'district' => 'required',  
// //             'zp_ward_no' => 'required',  
// //       ]; 
// //       $validator = Validator::make($request->all(),$rules);
// //       if ($validator->fails()) {
// //           $errors = $validator->errors()->all();
// //           $response=array();
// //           $response["status"]=0;
// //           $response["msg"]=$errors[0];
// //           return response()->json($response);// response as json
// //       }
// //       else {
// //         DB::select(DB::raw("call up_create_zp_ward ('$request->district','$request->zp_ward_no','0')")); 
// //        $response=['status'=>1,'msg'=>'Submit Successfully'];
// //        return response()->json($response);
// //       }
// //     }

// //     public function PanchayatSamitiStore(Request $request)
// //    {   
// //        $rules=[ 
// //             'block' => 'required',  
// //             'ps_ward' => 'required',  
// //       ]; 
// //       $validator = Validator::make($request->all(),$rules);
// //       if ($validator->fails()) {
// //           $errors = $validator->errors()->all();
// //           $response=array();
// //           $response["status"]=0;
// //           $response["msg"]=$errors[0];
// //           return response()->json($response);// response as json
// //       }
// //       else {
// //         DB::select(DB::raw("call up_create_ps_ward ('$request->block','$request->ps_ward','0')")); 
// //        $response=['status'=>1,'msg'=>'Submit Successfully'];
// //        return response()->json($response);
// //       }
// //     }


// //    public function BlockbtnClickByForm($value='')
// //    {
// //      return view('admin.master.block.block_form_div');
// //    }

// //     //
// //    public function BtnClickByvillageForm()
// //    {
// //      return view('admin.master.village.form_div'); 
// //    }

// //     public function villageUpdate(Request $request,$id=null)
// //    {  
// //        $rules=[
             
// //             'code' => 'required|unique:villages,code,'.$id, 
// //             'name_english' => 'required', 
// //             'name_local_language' => 'required', 
// //             // 'syllabus' => 'required', 
// //       ];

// //       $validator = Validator::make($request->all(),$rules);
// //       if ($validator->fails()) {
// //           $errors = $validator->errors()->all();
// //           $response=array();
// //           $response["status"]=0;
// //           $response["msg"]=$errors[0];
// //           return response()->json($response);// response as json
// //       }
// //       else {
// //         $village=Village::find($id); 
// //         $village->code=$request->code; 
// //         $village->name_e=$request->name_english; 
// //         $village->name_l=$request->name_local_language; 
// //         $village->save(); 
// //        $response=['status'=>1,'msg'=>'Update Successfully'];
// //        return response()->json($response);
// //       }
// //     }

     





    






// //   



// //   

    
    
    














// //   public function changeVoterWithWardExcel()
// //   {
// //     try {  
// //           return view('admin.master.changeVoterWithWard.excel');
// //         } catch (Exception $e) {
            
// //         }
// //   }
// //   public function changeVoterWithWardExcelStore(Request $request)
// //   {
// //     $admin=Auth::guard('admin')->user(); 
// //     if($request->hasFile('excel_file')){
// //       DB::select(DB::raw("delete from `tmp_shift_voter_ward_booth` where `userid` = $admin->id;"));  
// //       $path = $request->file('excel_file')->getRealPath();
// //       $results = Excel::load($path, function($reader) {})->get(); 
// //       foreach ($results as $values) { 
// //         foreach ($values as $key => $value) { 
// //         $SaveDatas= DB::select(DB::raw("call up_change_voters_wards_excel ('$admin->id','$value->district_code','$value->block_code','$value->village_code','$value->from_ward','$value->from_booth','$value->to_ward','$value->to_booth','$value->from_sr_no','$value->to_sr_no')")); 
// //         }
// //       }
      
// //       $result_dates=DB::select(DB::raw("select * from `tmp_shift_voter_ward_booth` where `userid`= $admin->id;"));
// //       $response = array();
// //       $response['status'] = 1;
// //       $response['msg'] = 'Uploaded';
// //       $response['data'] =view('admin.master.changeVoterWithWard.excel_result_data',compact('result_dates'))->render();
// //       return response()->json($response);  
// //     }
// //       $response=['status'=>0,'msg'=>'File Not Select'];
// //       return response()->json($response);
// //   }


  

}







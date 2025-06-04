<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;
use App\Rules\ValidateFile;

class VoterDetailsController extends Controller
{
  protected $e_controller = "VoterDetailsController";

  public function districtWiseAssembly(Request $request)
  {
    try{
      $d_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        $d_id = 0;
      }
      $assemblys = DB::select(DB::raw("SELECT * from `assemblys` where `district_id` = $d_id order by `code`;"));
      return view('admin.master.assembly.assembly_value_select_box',compact('assemblys'));
    } catch (\Exception $e) {
      $e_method = "districtWiseAssembly";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VillageWiseWardMultiple(Request $request)
  { 
    try{
      $village_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $village_id = 0;
      }
      $WardVillages = DB::select(DB::raw("call up_fetch_ward_village_access ('$village_id','0')"));   
      return view('admin.master.PrepareVoterList.select_ward_value',compact('WardVillages'));     
    } catch (\Exception $e) {
      $e_method = "VillageWiseWardMultiple";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VillageWiseWard(Request $request)
  {
    try{
      $village_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $village_id = 0;
      }
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access`($village_id, 1);"));
      return view('admin.voterDetails.select_ward_no',compact('WardVillages')); 
    } catch (Exception $e) {}
  }

  // Panchayat Voter List
  public function PrepareVoterListPanchayat()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(81);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $admin = Auth::guard('admin')->user(); 
      $rs_district = SelectBox::get_district_access_list_v1();  
      $rslistPrepareOption = DB::select(DB::raw("SELECT * from `list_prepare_option`"));
      $rslistSortingOption = DB::select(DB::raw("SELECT * from `list_sorting_option`")); 
      return view('admin.master.PrepareVoterList.index',compact('rs_district', 'rslistPrepareOption', 'rslistSortingOption'));
    } catch (\Exception $e) {
      $e_method = "PrepareVoterListPanchayat";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function PrepareVoterListBoothWise()
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(83);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      $rslistPrepareOption = DB::select(DB::raw("SELECT * from `list_prepare_option`;"));
      $rslistSortingOption = DB::select(DB::raw("SELECT * from `list_sorting_option`;"));
      
      return view('admin.master.PrepareVoterList.booth.index',compact('rs_district', 'rslistPrepareOption', 'rslistSortingOption'));  
    } catch (\Exception $e) {
      $e_method = "PrepareVoterListBoothWise";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function PrepareVoterListMultipleBooth()
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(84);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      $rslistPrepareOption = DB::select(DB::raw("SELECT * from `list_prepare_option`;"));
      $rslistSortingOption = DB::select(DB::raw("SELECT * from `list_sorting_option`;"));
      
      return view('admin.master.PrepareVoterList.booth.multiple_index',compact('rs_district', 'rslistPrepareOption', 'rslistSortingOption'));  
    } catch (\Exception $e) {
      $e_method = "PrepareVoterListMultipleBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VoterListDownload()
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(104);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();    
      return view('admin.master.voterlistdownload.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "VoterListDownload";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function BlockWiseDownloadTable(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(104);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $block_id = intval(Crypt::decrypt($request->block_id));
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        $block_id = 0;
      }
      $voter_list_master_id = intval(Crypt::decrypt($request->voter_list_master_id));
      $voterlistprocesseds = DB::select(DB::raw("SELECT `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`report_type`, `vlp`.`id`, `vlp`.`status`,  `vlp`.`folder_path`, `vlp`.`file_path_p`, `vlp`.`file_path_w`, `vlp`.`file_path_h`, `submit_time`, `start_time`, `finish_time`, `expected_time_start` from `voter_list_processeds` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `block_id` = $block_id and `voter_list_master_id` = $voter_list_master_id order by `vil`.`name_e`, `wv`.`ward_no`;"));
      return view('admin.master.voterlistdownload.download_table',compact('voterlistprocesseds')); 
    } catch (Exception $e) {
      $e_method = "BlockWiseDownloadTable";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function downloadCaptcha($id, $condition)
  {  
    try{
      $permission_flag = MyFuncs::isPermission_route(104);
      if(!$permission_flag){
        return view('admin.common.error_popup');
      }
      
      $rec_id = $id;
      $condition = $condition;
      return view('admin.master.voterlistdownload.download_captcha',compact('rec_id', 'condition'));
    } catch (\Exception $e) {
      $e_method = "captchaPopup";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VoterListDownloadPDF(Request $request, $id, $condition)
  {  
    try{
      
      $permission_flag = MyFuncs::isPermission_route(104);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $this->validate($request, [        
        'captcha' => 'required|captcha' 
      ]); 
      
      $role_id = MyFuncs::getUserRoleId();
      $user_id = MyFuncs::getUserId();
      $from_ip = MyFuncs::getIp();

      $rec_id = intval(Crypt::decrypt($id));
      
      $download_type = 0;
      if($condition == 'p'){
        $download_type = 1;
      }elseif($condition == 'w'){
        $download_type = 2;
      }
      if($download_type > 0){
        $rs_update = DB::select(DB::raw("call `up_log_voter_list_download`($role_id, $user_id, '$from_ip', $rec_id, $download_type);"));  
      }
      

      $voterlistprocesseds = DB::select(DB::raw("SELECT `folder_path`, `file_path_p`, `file_path_w`, `file_path_h` from `voter_list_processeds` where `id` = $rec_id limit 1;"));
      if(count($voterlistprocesseds)==0){
        return null;
      }
      $voterlistprocesseds = reset($voterlistprocesseds);

      $documentUrl = Storage_path().$voterlistprocesseds->folder_path;
      if($condition == 'p'){$documentUrl = $documentUrl.$voterlistprocesseds->file_path_p;} 
      elseif($condition == 'w'){$documentUrl = $documentUrl.$voterlistprocesseds->file_path_w;} 
      elseif($condition == 'h'){$documentUrl = $documentUrl.$voterlistprocesseds->file_path_h;} 
      
      if(file_exists($documentUrl)){                
        return response()->file($documentUrl);
      }else{
        return 'File Not Found';
      }          
    } catch (Exception $e) {
      $e_method = "VoterListDownloadPDF";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VoterListDownloadPDFH($id, $condition)
  {  
    try{
      
      $permission_flag = MyFuncs::isPermission_route(104);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $voterlistprocesseds = DB::select(DB::raw("SELECT `folder_path`, `file_path_p`, `file_path_w`, `file_path_h` from `voter_list_processeds` where `id` = $rec_id limit 1;"));
      if(count($voterlistprocesseds)==0){
        return null;
      }
      $voterlistprocesseds = reset($voterlistprocesseds);

      $documentUrl = Storage_path().$voterlistprocesseds->folder_path;
      if($condition == 'h'){
        $documentUrl = $documentUrl.$voterlistprocesseds->file_path_h;
      } 
      
      if(file_exists($documentUrl)){                
        return response()->file($documentUrl);
      }else{
        return 'File Not Found';
      }          
    } catch (Exception $e) {
      $e_method = "VoterListDownloadPDFH";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function processingStatus(Request $request)
  { 
    try{
      $permission_flag = MyFuncs::isPermission_route(106);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $voterlistprocesseds = DB::select(DB::raw("SELECT `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`report_type`, `vlp`.`id`, `vlp`.`status`, `vlp`.`folder_path`, `vlp`.`file_path_p`, `vlp`.`file_path_w`, `vlp`.`file_path_h`, `submit_time`, `start_time`, `finish_time`, `expected_time_start`, `dis`.`name_e` as `d_name`, `bl`.`code` as `b_code`, `bl`.`name_e` as `b_name` from `voter_list_processeds` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` inner join `districts` `dis` on `dis`.`id` = `vil`.`districts_id` inner join `blocks_mcs` `bl` on `bl`.`id` = `vil`.`blocks_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `status` <> 1 order by `vlp`.`id`;"));

      return view('admin.master.voterlistdownload.processing_status',compact('voterlistprocesseds')); 
    } catch (\Exception $e) {
      $e_method = "processingStatus";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function index()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(121);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1(); 
      return view('admin.voterDetails.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "index";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }   
  }

  public function form(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(121);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $ac_part_id = intval(Crypt::decrypt($request->part_no));
      $sr_no = intval(substr(MyFuncs::removeSpacialChr($request->srno_part), 0, 4));

      $rs_fetch = DB::select(DB::raw("SELECT `id`, `tag` from `import_type` where `status` = 1 limit 1;"));
      $data_list_id = $rs_fetch[0]->id;
      $rs_record = DB::select(DB::raw("SELECT `id`, `data_list_id`, `assembly_id`, `assembly_part_id`, `sr_no`, `voter_card_no`, `name_e`, `name_l`, `relation`, `father_name_e`, `father_name_l`, `house_no_e`, `house_no_l`, `gender_id`, `dob`, `age`, `mobile_no` from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $sr_no and `data_list_id` = $data_list_id limit 1;"));

      $genders= DB::select(DB::raw("SELECT * from `genders` order by `id`;"));  
      $Relations= DB::select(DB::raw("SELECT * from `relation` order by `relation_e`;"));  
      return view('admin.voterDetails.form',compact('genders', 'Relations', 'rs_record'));
    } catch (Exception $e) {
      $e_method = "index";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }   
  }

  public function VillageWiseVoterList(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(121);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->village_id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $village_id = 0;
      }
      $rs_voterLists = DB::select(DB::raw("SELECT `v`.`id`, `v`.`sr_no`, `v`.`voter_card_no`, `v`.`name_e`, `v`.`name_l`, `v`.`father_name_l`, `vil`.`name_l` as `vil_name`, `wv`.`ward_no`, `v`.`village_id` from `voters` `v` inner join `villages` `vil` on `vil`.`id` = `v`.`village_id` inner join `ward_villages` `wv` on `wv`.`id` = `v`.`ward_id` where `v`.`status` = 1 and `v`.`source` = 'n' and `v`.`village_id` = $village_id;"));
      return view('admin.voterDetails.table',compact('rs_voterLists'));
    } catch (\Exception $e) {
      $e_method = "VillageWiseVoterList";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function VillageWiseAcParts(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(121);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $village_id = intval(Crypt::decrypt($request->id));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $village_id = 0;
      }
      $assemblyParts = DB::select(DB::raw("SELECT `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $village_id order by `ac`.`code`, `ap`.`part_no`;"));
      return view('admin.voterDetails.select_box_ac_parts',compact('assemblyParts'));
    } catch (\Exception $e) {
      $e_method = "VillageWiseAcParts";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function checkdictionaryFName(Request $request, $condition_type)
  { 
    try {
      $name_english = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
      if ($condition_type == 3) {
        $rs_result = DB::select(DB::raw("SELECT `uf_house_convert_e_2_h` ('$name_english') as 'name_l'"));   
      }
      else{  
        $rs_result = DB::select(DB::raw("SELECT `uf_name_convert_e_2_h` ('$name_english') as 'name_l'")); 
      }
      return view('admin.voterDetails.dictionary_popup',compact('name_english', 'rs_result', 'condition_type')); 
    } catch (\Exception $e) {
      $e_method = "checkdictionaryFName";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function getTranslateData(Request $request)
  {
    try {
      $name_e = strtoupper(MyFuncs::removeSpacialChr($request['name_e']));

      if($name_e!='') {
        $rs_fetch = DB::select(DB::raw("SELECT `uf_name_convert_e_2_h` ('$name_e') as 'name_l' limit 1;"));
        if (count($rs_fetch) > 0){
          $name_h = $rs_fetch[0]->name_l; 
        }else{
          $rs_fetch = DB::select(DB::raw("SELECT `uf_name_convert_e_2_h` ('$name_e') as 'name_l' limit 1;"));
          if (count($rs_fetch) > 0){
            $name_h = $rs_fetch[0]->name_l; 
          }
        } 
      }else {
        $name_h = ''; 
      }
      $str = $name_h;
      echo json_encode(array('st'=>1,'msg'=>$str));
    } catch (\Exception $e) {
      $e_method = "getTranslateData";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function getTraDataHouse(Request $request)
  {
    try {
      $name_e = strtoupper(MyFuncs::removeSpacialChr($request['name_e']));

      if($name_e!='') {
        $rs_fetch = DB::select(DB::raw("SELECT `uf_house_convert_e_2_h` ('$name_e') as 'name_l' limit 1;"));
        if (count($rs_fetch) > 0){
          $name_h = $rs_fetch[0]->name_l; 
        }else{
          $rs_fetch = DB::select(DB::raw("SELECT `uf_house_convert_e_2_h` ('$name_e') as 'name_l' limit 1;"));
          if (count($rs_fetch) > 0){
            $name_h = $rs_fetch[0]->name_l; 
          }
        } 
      }else {
        $name_h = ''; 
      }
      $str = $name_h;
      echo json_encode(array('st'=>1,'msg'=>$str));
    } catch (\Exception $e) {
      $e_method = "getTraDataHouse";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function checkDuplicateRecord(Request $request)
  { 
    try {
      $check_type = intval($request->check_type);
      $rec_id = intval(Crypt::decrypt($request->rec_id));
      $condition = "";
      if ($check_type == 1) {
        $voter_id_no = substr(MyFuncs::removeSpacialChr($request->voter_id_no), 0, 20);
        if($voter_id_no == ""){
          $condition = " where `vt`.`id` = 0 ";  
        }else{
          $condition = " where `vt`.`voter_card_no` = '$voter_id_no' and `vt`.`id` <> $rec_id ";
        }
        
        
      }else{
        $d_id = intval(Crypt::decrypt($request->district_id));
        $name_english = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
        $f_h_name_english = substr(MyFuncs::removeSpacialChr($request->f_h_name_english), 0, 50);
        $date_of_birth = substr(MyFuncs::removeSpacialChr($request->date_of_birth), 0, 10);
        $date_of_birth = str_replace("-", "/", $date_of_birth);
        $date_of_birth = str_replace(".", "/", $date_of_birth);
        
        $condition = " where `vt`.`district_id` = $d_id and `vt`.`name_e` = '$name_english' and `vt`.`father_name_e` = '$f_h_name_english' and `vt`.`dob` = '$date_of_birth' and `vt`.`id` <> $rec_id ";
      }
      
      $rs_result = DB::select(DB::raw("SELECT `dst`.`name_e` as `d_name`, concat(`ac`.`code`, ' - ', `ac`.`name_e`) as `ac_name`, `ap`.`part_no`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`data_list_id`, `vt`.`assembly_id`, `vt`.`assembly_part_id`, `vt`.`house_no_e`, `vl`.`name_e` as `v_name`, `wv`.`ward_no`, `pb`.`booth_no` from `voters` `vt` inner join `districts` `dst` on `dst`.`id` = `vt`.`district_id` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` left join `villages` `vl` on `vl`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` $condition;"));

      if (count($rs_result) == 0) {
        return '';
      }
      return view('admin.voterDetails.checkDuplicateRecord',compact('rs_result')); 
    } catch (Exception $e) {
      $e_method = "checkDuplicateRecord";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  // public function NameConvert(Request $request, $condition_type)
  // {
  //   try {
  //     $name_english = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
  //     if ($condition_type==3) {
  //       $name_english = DB::select(DB::raw("SELECT uf_house_convert_e_2_h ('$name_english') as 'name_l'"));   
  //     }
  //     else{  
  //       $name_english = DB::select(DB::raw("SELECT uf_name_convert_e_2_h ('$name_english') as 'name_l'")); 
  //     }

  //     $name_l = preg_replace('/[\x00]/', '', $name_english[0]->name_l);
  //     return view('admin.voterDetails.name_hindi_value',compact('name_l','condition_type')); 
  //   } catch (\Exception $e) {
  //     $e_method = "NameConvert";
  //     return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
  //   }   
  // }

  public function calculateAge(Request $request)
  {
    try {
      $date1 = date_create($request->id);
      $date2 = date_create(date('Y-m-d'));
      $diff = date_diff($date1, $date2);
      return view('admin.voterDetails.age_value',compact('diff'));
    } catch (\Exception $e) {
      $e_method = "calculateAge";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    } 
  }

  public function store(Request $request)
  {
    try {

      

      $permission_flag = MyFuncs::isPermission_route(121);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[            
        'rec_id' => 'required', 
        'district' => 'required', 
        'assembly' => 'required', 
        'part_no' => 'required', 
        'srno_part' => 'required', 
        'name_english' => 'required', 
        'name_local_language' => 'required', 
        'relation' => 'required', 
        'f_h_name_english' => 'required', 
        'f_h_name_local_language' => 'required', 
        'house_no_english' => 'required', 
        'house_no_local_language' => 'required', 
        'gender' => 'required', 
        'age' => 'required', 
        'voter_id_no' => 'required',
        // 'image' => [ 'nullable','image','mimes:jpeg,jpg,png','max:20', new ValidateFile(array('jpeg','jpg','png'), $request->image->getClientOriginalName(), $request->image->extension())],
      ];
      if ($request->hasFile('image')){
        $rules=[
          'image' => [ 'nullable','image','mimes:jpeg,jpg,png','max:20', new ValidateFile(array('jpeg','jpg', 'png'), $request->image->getClientOriginalName(), $request->image->extension())],    
        ];      
      }
      $customMessages = [
        'rec_id.required'=> 'Something Went Wrong',
        'district.required'=> 'Please Select District',
        'assembly.required'=> 'Please Select Assembly',
        'part_no.required'=> 'Please Select Assembly Part',
        'srno_part.required'=> 'Please Enter Sr No. in Part.',
        'name_english.required'=> 'Please Enter Name English',
        'name_local_language.required'=> 'Please Enter Name Hindi',
        'relation.required'=> 'Please Select Relation',
        'f_h_name_english.required'=> 'Please Enter F/H English',
        'f_h_name_local_language.required'=> 'Please Enter F/H Hindi',
        'house_no_english.required'=> 'Please Enter House No. English',
        'house_no_local_language.required'=> 'Please Enter House No. Hindi',
        'gender.required'=> 'Please Select Gender',
        'age.required'=> 'Please Enter Age',
        'voter_id_no.required'=> 'Please Enter Voter/Epic No.',

        'image.image'=> 'Image Should Be Image',
        'image.mimes'=> 'Image Should Be In JPG/JPEG/PNG Format',
        'image.max'=> 'Image Size Should Be Maximun of 20 KB',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }



      $d_id = intval(Crypt::decrypt($request->district));
      $permission_flag = MyFuncs::check_district_access($d_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      
      $rec_id = intval(Crypt::decrypt($request->rec_id));
      $ac_part_id = intval(Crypt::decrypt($request->part_no));
      $sr_no = intval(substr(MyFuncs::removeSpacialChr($request->srno_part), 0, 5));

      $name_e = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
      $name_h = MyFuncs::removeSpacialChr($request->name_local_language);
      $fname_e = substr(MyFuncs::removeSpacialChr($request->f_h_name_english), 0, 50);
      $fname_h = MyFuncs::removeSpacialChr($request->f_h_name_local_language);
      $hno_e = substr(MyFuncs::removeSpacialChr($request->house_no_english), 0, 20);
      $h_no_h = MyFuncs::removeSpacialChr($request->house_no_local_language);
      $age = intval(substr(MyFuncs::removeSpacialChr($request->age), 0, 3));
      $epic_no = substr(MyFuncs::removeSpacialChr($request->voter_id_no), 0, 20);
      
      $aadhar_no = "";
      
      $mobile = "";
      
      $relation_id = intval(Crypt::decrypt($request->relation));
      $gender_id = intval(Crypt::decrypt($request->gender));
      $birth_date = substr(MyFuncs::removeSpacialChr($request->date_of_birth), 0, 10);
      $birth_date = str_replace("-", "/", $birth_date);
      $birth_date = str_replace(".", "/", $birth_date);

      if($age < 18){
        $response=['status'=>0,'msg'=>'Age Cannot Be Less Then 18'];
        return response()->json($response);
      }

      if($sr_no > 9999){
        $response=['status'=>0,'msg'=>'Sr. No. Cannot Be of 5 Digit'];
        return response()->json($response);
      }
      
      $rs_fetch = DB::select(DB::raw("SELECT `id`, `tag` from `import_type` where `status` = 1 limit 1;"));
      $data_list_id = $rs_fetch[0]->id;
      $data_tag = $rs_fetch[0]->tag;

      if($sr_no == 0){
        $response=['status'=>0,'msg'=>'Sr. No. cannot be zero'];
        return response()->json($response);  
      }
      
      $rs_fetch = DB::select(DB::raw("SELECT `id` from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $sr_no and `data_list_id` = $data_list_id and `id` <> $rec_id limit 1;"));
      if(count($rs_fetch)>0){
        $response=['status'=>0,'msg'=>'Sr. No. already Exists'];
        return response()->json($response);  
      }

      
      $rs_fetch = DB::select(DB::raw("SELECT `assembly_id` from `assembly_parts` where `id` = $ac_part_id limit 1;"));
      $ac_id = $rs_fetch[0]->assembly_id;
      
      $new_id = $sr_no;
      if ($request->hasFile('image')){
        if($_FILES['image']['size'] > 20*1024) {
          $response=['status'=>0,'msg'=>'Image Size cannot be more then 20 KB'];
          return response()->json($response); 
        }
        $image = $request->image;
        $vpath = '/vimage/'.$data_list_id.'/'.$ac_id.'/'.$ac_part_id;
        $filename = $new_id.'.jpg';
        $dirpath = Storage_path() . '/app/vimage/'.$data_list_id.'/'.$ac_id.'/'.$ac_part_id;
        @mkdir($dirpath, 0755, true);
        $image->storeAs($vpath, $filename);
      }else{
        if($rec_id == 0){
          $response=['status'=>0,'msg'=>'Please Choose Image'];
          return response()->json($response);  
        }
      }

      $user_id = MyFuncs::getUserId();
      $from_ip = MyFuncs::getIp();
      $rs_save = DB::select(DB::raw("call `up_save_voter_detail`($rec_id, $d_id, $ac_id, $ac_part_id, $sr_no, '$epic_no', '$hno_e', '$h_no_h', '','$name_e', '$name_h', '$fname_e', '$fname_h', $relation_id, $gender_id, $age, '$mobile', 'n', 0, 0, 0, 0, 0, 0, $data_list_id, '$data_tag', '$birth_date', $user_id, '$from_ip');"));

      
      $response=['status'=>1,'msg'=>'Submit Successfully'];
      return response()->json($response);
    } catch (Exception $e) {
      $e_method = "store";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  // public function store(Request $request)
  // {
  //   try {
  //     $permission_flag = MyFuncs::isPermission_route(121);
  //     if(!$permission_flag){
  //       $response=['status'=>0,'msg'=>'Something Went Wrong'];
  //       return response()->json($response);
  //     }
  //     $rules=[            
  //       'district' => 'required', 
  //       'block' => 'required', 
  //       'village' => 'required', 
  //       'ward_no' => 'required', 
  //       'ac_part_id' => 'required', 
  //       'srno_part' => 'required', 
  //       'booth_no' => 'required', 
  //       'name_english' => 'required', 
  //       'name_local_language' => 'required', 
  //       'relation' => 'required', 
  //       'f_h_name_english' => 'required', 
  //       'f_h_name_local_language' => 'required', 
  //       'house_no_english' => 'required', 
  //       'house_no_local_language' => 'required', 
  //       'gender' => 'required', 
  //       'age' => 'required', 
  //       'voter_id_no' => 'required',
  //       'image' => 'required|image|mimes:jpeg,jpg,png|max:20',
  //     ];
  //     $customMessages = [
  //       'district.required'=> 'Please Select District',
  //       'block.required'=> 'Please Select Block / MC\'s',
  //       'village.required'=> 'Please Select Panchayat / MC\'s',
  //       'ward_no.required'=> 'Please Select Ward No.',
  //       'ac_part_id.required'=> 'Please Select Assembly Part',
  //       'srno_part.required'=> 'Please Enter Sr No. in Part.',
  //       'booth_no.required'=> 'Please Select Booth No.',
  //       'name_english.required'=> 'Please Enter Name English',
  //       'name_local_language.required'=> 'Please Enter Name Hindi',
  //       'relation.required'=> 'Please Select Relation',
  //       'f_h_name_english.required'=> 'Please Enter F/H English',
  //       'f_h_name_local_language.required'=> 'Please Enter F/H Hindi',
  //       'house_no_english.required'=> 'Please Enter House No. English',
  //       'house_no_local_language.required'=> 'Please Enter House No. Hindi',
  //       'gender.required'=> 'Please Select Gender',
  //       'age.required'=> 'Please Enter Age',
  //       'voter_id_no.required'=> 'Please Enter Voter/Epic No.',

  //       'image.required'=> 'Please Choose Image',
  //       'image.image'=> 'Image Should Be Image',
  //       'image.mimes'=> 'Image Should Be In JPG/JPEG/PNG Format',
  //       'image.max'=> 'Image Size Should Be Maximun of 20 KB',
  //     ];
  //     $validator = Validator::make($request->all(),$rules, $customMessages);
  //     if ($validator->fails()) {
  //       $errors = $validator->errors()->all();
  //       $response=array();
  //       $response["status"]=0;
  //       $response["msg"]=$errors[0];
  //       return response()->json($response);// response as json
  //     }

  //     $d_id = intval(Crypt::decrypt($request->district));
  //     $permission_flag = MyFuncs::check_district_access($d_id);
  //     if($permission_flag == 0){
  //       $response=['status'=>0,'msg'=>'Something Went Wrong'];
  //       return response()->json($response);
  //     }
      
  //     $bl_id = intval(Crypt::decrypt($request->block));
  //     $permission_flag = MyFuncs::check_block_access($bl_id);
  //     if($permission_flag == 0){
  //       $response=['status'=>0,'msg'=>'Something Went Wrong'];
  //       return response()->json($response);
  //     }
  //     $vil_id = intval(Crypt::decrypt($request->village));
  //     $permission_flag = MyFuncs::check_village_access($vil_id);
  //     if($permission_flag == 0){
  //       $response=['status'=>0,'msg'=>'Something Went Wrong'];
  //       return response()->json($response);
  //     }

  //     $ward_id = intval(Crypt::decrypt($request->ward_no));
  //     $ac_part_id = intval(Crypt::decrypt($request->ac_part_id));
  //     $booth_id = intval(Crypt::decrypt($request->booth_no));
  //     $sr_no = intval(substr(MyFuncs::removeSpacialChr($request->srno_part), 0, 5));

  //     $name_e = substr(MyFuncs::removeSpacialChr($request->name_english), 0, 50);
  //     $name_h = MyFuncs::removeSpacialChr($request->name_local_language);
  //     $fname_e = substr(MyFuncs::removeSpacialChr($request->f_h_name_english), 0, 50);
  //     $fname_h = MyFuncs::removeSpacialChr($request->f_h_name_local_language);
  //     $hno_e = substr(MyFuncs::removeSpacialChr($request->house_no_english), 0, 20);
  //     $h_no_h = MyFuncs::removeSpacialChr($request->house_no_local_language);
  //     $age = intval(substr(MyFuncs::removeSpacialChr($request->age), 0, 3));
  //     $epic_no = substr(MyFuncs::removeSpacialChr($request->voter_id_no), 0, 20);
      
  //     $aadhar_no = "";
  //     if (!empty($request->Aadhaar_no)){
  //       $aadhar_no = substr(MyFuncs::removeSpacialChr($request->Aadhaar_no), 0, 12);  
  //     }
      
  //     $mobile = "";
  //     if (!empty($request->mobile_no)){
  //       $mobile = substr(MyFuncs::removeSpacialChr($request->mobile_no), 0, 10);  
  //     }
      
  //     $relation_id = intval(Crypt::decrypt($request->relation));
  //     $gender_id = intval(Crypt::decrypt($request->gender));
  //     $birth_date = substr(MyFuncs::removeSpacialChr($request->date_of_birth), 0, 10);
  //     $birth_date = str_replace("-", "/", $birth_date);
  //     $birth_date = str_replace(".", "/", $birth_date);

  //     if($age < 18){
  //       $response=['status'=>0,'msg'=>'Age Cannot Be Less Then 18'];
  //       return response()->json($response);
  //     }
      
  //     $rs_fetch = DB::select(DB::raw("SELECT `id`, `tag` from `import_type` where `status` = 1 limit 1;"));
  //     $data_list_id = $rs_fetch[0]->id;
  //     $data_tag = $rs_fetch[0]->tag;

  //     if($sr_no == 0){
  //       $response=['status'=>0,'msg'=>'Sr. No. cannot be zero'];
  //       return response()->json($response);  
  //     }
  //     $rs_fetch = DB::select(DB::raw("SELECT `id` from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $sr_no and `data_list_id` = $data_list_id limit 1;"));
  //     if(count($rs_fetch)>0){
  //       $response=['status'=>0,'msg'=>'Sr. No. already Exists'];
  //       return response()->json($response);  
  //     }

      
  //     $rs_fetch = DB::select(DB::raw("SELECT `assembly_id` from `assembly_parts` where `id` = $ac_part_id limit 1;"));
  //     $ac_id = $rs_fetch[0]->assembly_id;
      
  //     $new_id = $sr_no;
  //     if ($request->hasFile('image')){
  //       if($_FILES['image']['size'] > 20*1024) {
  //         $response=['status'=>0,'msg'=>'Image Size cannot be more then 20 KB'];
  //         return response()->json($response); 
  //       }
  //       $image = $request->image;
  //       $vpath = '/vimage/'.$data_list_id.'/'.$ac_id.'/'.$ac_part_id;
  //       $filename = $new_id.'.jpg';
  //       $dirpath = Storage_path() . '/app/vimage/'.$data_list_id.'/'.$ac_id.'/'.$ac_part_id;
  //       @mkdir($dirpath, 0755, true);
  //       $image->storeAs($vpath, $filename);
  //     }else{
  //       $response=['status'=>0,'msg'=>'Please Choose Image'];
  //       return response()->json($response);
  //     }

  //     $rs_save = DB::select(DB::raw("call `up_save_voter_detail`($d_id, $ac_id, $ac_part_id, $sr_no, '$epic_no', '$hno_e', '$h_no_h','','$name_e','$name_h','$fname_e','$fname_h', $relation_id, $gender_id, $age, '$mobile', 'n', 0, 0, 0, 0, 0, 0, $data_list_id, '$data_tag', '$birth_date');"));

  //     $rs_fetch = DB::select(DB::raw("SELECT `id` from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $sr_no and `data_list_id` = $data_list_id limit 1;"));
  //     $new_id = $sr_no;


  //     $rs_fetch = DB::select(DB::raw("SELECT `id` from `voter_list_master` where `block_id` = $bl_id and `status` = 1 limit 1;"));
  //     $voter_list_id = $rs_fetch[0]->id;

      

  //     $user_id = MyFuncs::getUserId();
  //     $rs_update = DB::select(DB::raw("call `up_change_voters_wards_by_ac_srno` ($user_id, $bl_id, $vil_id, $ac_part_id, $data_list_id, $sr_no, $sr_no, $ward_id, $booth_id);"));

  //     $response=['status'=>1,'msg'=>'Submit Successfully'];
  //     return response()->json($response);
  //   } catch (Exception $e) {
  //     $e_method = "store";
  //     return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
  //   }
  // }

  public function exception_handler()
  {
    try {

    } catch (\Exception $e) {
      $e_method = "imageShowPath";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }
    
	

	



//   //--------Prepare-----Voter--------List-------PrepareVoterList----------




//   public function PrepareVoterListMunicipal()
//   {
//   	try{
//       $admin = Auth::guard('admin')->user(); 
//     	$Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
//       $rslistPrepareOption = DB::select(DB::raw("select * from `list_prepare_option`"));
//       $rslistSortingOption = DB::select(DB::raw("select * from `list_sorting_option`"));
      
//     	return view('admin.master.PrepareVoterList.municipal.index',compact('Districts', 'rslistPrepareOption', 'rslistSortingOption'));  
//     } catch (Exception $e) {}   
//   }


 	
 

// //-------------------Prepare-Voter-List-Booth-Wise---------------------
  


// //-------------------VoterListDownload---------------------
  

//   public function NewVoterListDownload($value='')
//   {
//     try{
//       $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
//       return view('voter_list_download',compact('States'));
//     } catch (Exception $e) {}
//   }

 



//   //--Vidhan Sabha List Download
//   public function VidhanSabhaListDownload($value='')
//   {
//     try{
//       $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
//       return view('admin.master.vidhansabhalistdownload.index',compact('States'));
//     } catch (Exception $e) {}
//   }

//   public function DistrictWiseVidhanDownloadTable(Request $request)
//   { 
//     try{

//       $voterlistprocesseds = DB::select(DB::raw("select `vl`.`id`, `ac`.`code`, `ac`.`name_e`, `vl`.`file_path`, `vl`.`folder_path`, `vl`.`status` from `vidhansabha_list` `vl` inner join `assemblys` `ac` on `ac`.`id` = `vl`.`assembly_id` where `vl`.`district_id` = $request->district_id order by `ac`.`code`;"));

//       return view('admin.master.vidhansabhalistdownload.download_table',compact('voterlistprocesseds')); 
//     } catch (Exception $e) {}
//   }

//   public function VidhanListDownloadPDF($id)
//   {  
//     try{
//       $voterlistprocesseds = DB::select(DB::raw("select `folder_path`, `file_path` from `vidhansabha_list` where `id` = $id limit 1;"));
//       if(count($voterlistprocesseds)==0){
//         return null;
//       }
//       $voterlistprocesseds = reset($voterlistprocesseds);

//       $documentUrl = Storage_path().$voterlistprocesseds->folder_path;
//       $documentUrl = $documentUrl.$voterlistprocesseds->file_path; 
      
//       return response()->file($documentUrl);          
//     } catch (Exception $e) {}
//   }
 
// //New Voter Add Detailed Entry
  




//   //Pending----------


  

  

     
// //-----------------------End----------------

 

 
//  //    public function districtWiseVillage(Request $request)
//  //    {
//  //       $villages=Village::where('districts_id',$request->id)->orderBy('code','ASC')->get();
//  //       return view('admin.voterDetails.village_value',compact('villages'));
//  //    }
//     public function AssemblyWisePartNo(Request $request)
//     {
       
//        $Parts = DB::select(DB::raw("select * from `assembly_parts` where `assembly_id` = $request->id order by `part_no`;"));  
//        return view('admin.voterDetails.select_part_no',compact('Parts')); 
//     }
 
//     // public function voterListEdit($voter_id)
//     // {
//     //   $genders= Gender::orderBy('id','ASC')->get();  
//     //   $Relations= Relation::orderBy('id','ASC')->get();
//     //   $voterlist=Voter::find($voter_id);
//     //    return view('admin.voterDetails.voter_list_edit',compact('voterlist','genders','Relations')); 
//     // }
 
 
//  //    }
//  //    public function voterUpdate(Request $request,$id)
//  //    {    
//  //        $rules=[            
             
//  //            'name_english' => 'required', 
//  //            'name_local_language' => 'required', 
//  //            'relation' => 'required', 
//  //            'f_h_name_english' => 'required', 
//  //            'f_h_name_local_language' => 'required', 
//  //            'house_no_english' => 'required', 
//  //            'house_no_local_language' => 'required', 
//  //            'gender' => 'required', 
//  //            'age' => 'required', 
//  //            'voter_id_no' => 'required',  
             
//  //      ];

//  //      $validator = Validator::make($request->all(),$rules);
//  //      if ($validator->fails()) {
//  //          $errors = $validator->errors()->all();
//  //          $response=array();
//  //          $response["status"]=0;
//  //          $response["msg"]=$errors[0];
//  //          return response()->json($response);// response as json
//  //      }
//  //      else {
//  //            $house_no=DB::select(DB::raw("Select `uf_converthno`('$request->house_no_english') as 'hno_int';")); 
//  //            $voter=Voter::find($id);  
//  //            $voter->name_e = $request->name_english;
//  //            $voter->name_l = $request->name_local_language;
//  //            $voter->father_name_e = $request->f_h_name_english;
//  //            $voter->father_name_l = $request->f_h_name_local_language;
//  //            $voter->voter_card_no = $request->voter_id_no;
//  //            $voter->house_no = $house_no[0]->hno_int;
//  //            $voter->house_no_e = $request->house_no_english;
//  //            $voter->house_no_l = $request->house_no_local_language; 
//  //            $voter->relation = $request->relation;
//  //            $voter->gender_id = $request->gender;
//  //            $voter->age = $request->age;
//  //            $voter->mobile_no = $request->mobile_no;
//  //            $voter->status =1;
//  //            $voter->save();             
//  //            $response=['status'=>1,'msg'=>'Update Successfully'];
//  //            return response()->json($response);
//  //      }
     

//  //    }
//  //    public function voterDelete($voter_id)
//  //    {
//  //       $voter=Voter::find($voter_id);   
//  //       $voter->delete();
//  //       $response=['status'=>1,'msg'=>'Delete Successfully'];
//  //            return response()->json($response);   
//  //    }

    
     
//  //    public function DeteleAndRestore()
//  //    {
//  //      $Districts= District::orderBy('name_e','ASC')->get();  
//  //      return view('admin.DeteleAndRestore.index',compact('Districts','genders','voters')); 
//  //    }
//  //    public function DeteleAndRestoreShow(Request $request)
//  //    {
//  //        $rules=[ 
//  //              'village' => 'required', 
//  //        ];

//  //        $validator = Validator::make($request->all(),$rules);
//  //        if ($validator->fails()) {
//  //            $errors = $validator->errors()->all();
//  //            $response=array();
//  //            $response["status"]=0;
//  //            $response["msg"]=$errors[0];
//  //            return response()->json($response);// response as json
//  //        }
//  //        $voters =Voter:: 
//  //                 where('village_id',$request->village)
//  //               ->where(function($query) use($request){ 
//  //                if (!empty($request->print_sr_no)) {
//  //                $query->where('print_sr_no', 'like','%'.$request->print_sr_no.'%'); 
//  //                }
//  //                if (!empty($request->name)) {
//  //                $query->where('name_e', 'like','%'.$request->name.'%'); 
//  //                }
//  //                if (!empty($request->father_name)) {
//  //                $query->where('father_name_e', 'like','%'.$request->father_name.'%'); 
//  //                } 
//  //               }) 
//  //               ->get(); 
//  //        $response= array();                       
//  //        $response['status']= 1;                       
//  //        $response['data']=view('admin.DeteleAndRestore.search_table',compact('voters'))->render();
//  //        return $response;
         
       
//  //    } 
//  //    public function DeteleAndRestoreDetele($id)
//  //    {
//  //      $voter=Voter::find($id);
//  //      $DeleteVoterDetail= new DeleteVoterDetail();
//  //      $DeleteVoterDetail->voter_id=$id;
//  //      $DeleteVoterDetail->voter_list_master_id=$voter->suppliment_no;
//  //      $DeleteVoterDetail->voter_list_master_id=$voter->suppliment_no;
//  //      $DeleteVoterDetail->previous_status=$voter->status;
//  //      $DeleteVoterDetail->status=2;
//  //      $DeleteVoterDetail->save();
//  //      $voter->status=2;
//  //      $voter->save();
//  //      $response=['status'=>1,'msg'=>'Delete Successfully'];
//  //      return response()->json($response);
//  //    }
//  //    public function DeteleAndRestoreRestore($id)
//  //    {
//  //      $DeleteVoterDetail=DeleteVoterDetail::where('voter_id',$id)->first();  
//  //      $voter=Voter::find($id);
//  //      $voter->status=$DeleteVoterDetail->previous_status;
//  //      $voter->save();
//  //      $DeleteVoterDetail->delete();
//  //      $response=['status'=>1,'msg'=>'Restore Successfully'];
//  //      return response()->json($response);
//  //    }
    

//  //  //--modify------modify-------------  
//  //    public function VoterDetailsModify($value='')
//  //    {
//  //       $Districts= District::orderBy('name_e','ASC')->get();  
//  //      return view('admin.modify.index',compact('Districts')); 
//  //    }
//  //    public function VoterDetailsModifyShow(Request $request)
//  //    {
//  //        $rules=[ 
//  //              'village' => 'required', 
//  //        ];

//  //        $validator = Validator::make($request->all(),$rules);
//  //        if ($validator->fails()) {
//  //            $errors = $validator->errors()->all();
//  //            $response=array();
//  //            $response["status"]=0;
//  //            $response["msg"]=$errors[0];
//  //            return response()->json($response);// response as json
//  //        }
//  //        $voters =Voter:: 
//  //                 where('village_id',$request->village)
//  //               ->where(function($query) use($request){ 
//  //                if (!empty($request->print_sr_no)) {
//  //                $query->where('print_sr_no', 'like','%'.$request->print_sr_no.'%'); 
//  //                }
//  //                if (!empty($request->name)) {
//  //                $query->where('name_e', 'like','%'.$request->name.'%'); 
//  //                }
//  //                if (!empty($request->father_name)) {
//  //                $query->where('father_name_e', 'like','%'.$request->father_name.'%'); 
//  //                } 
//  //               }) 
//  //               ->get(); 
//  //        $response= array();                       
//  //        $response['status']= 1;                       
//  //        $response['data']=view('admin.modify.table',compact('voters'))->render();
//  //        return $response;
         
       
//  //    }
//  //    public function VoterDetailsModifyEdit($voter_id)
//  //    {
//  //      $genders= Gender::orderBy('id','ASC')->get();  
//  //      $Relations= Relation::orderBy('id','ASC')->get(); 
//  //      $voter=Voter::find($voter_id); 
//  //     return view('admin.modify.edit',compact('voter','genders','Relations'));
//  //    }
//  //    public function VoterDetailsModifyStore(Request $request,$id)
//  //    { 
//  //      $voter=Voter::find($id);
//  //      $VoterListModify= new VoterListModify();
//  //      $VoterListModify->voter_id=$id;
//  //      $VoterListModify->name_e=$voter->name_e;
//  //      $VoterListModify->name_l=$voter->name_l;
//  //      $VoterListModify->father_name_e=$voter->father_name_e;
//  //      $VoterListModify->father_name_l=$voter->father_name_l;
//  //      $VoterListModify->house_no_e=$voter->house_no_e;
//  //      $VoterListModify->house_no_l=$voter->house_no_l;
//  //      $VoterListModify->age=$voter->age;
//  //      $VoterListModify->mobile_no=$voter->mobile_no;
//  //      $VoterListModify->relation=$voter->relation;
//  //      $VoterListModify->gender_id=$voter->gender_id; 
//  //      $VoterListModify->previous_status=$voter->status;
//  //      $VoterListModify->status=3; 
//  //      $VoterListModify->save(); 
       
//  //      $voter->name_e=$request->name_english;
//  //      $voter->name_l=$request->name_local_language;
//  //      $voter->father_name_e=$request->f_h_name_english;
//  //      $voter->father_name_l=$request->f_h_name_local_language;
//  //      $voter->house_no_e=$request->house_no_english;
//  //      $voter->house_no_l=$request->house_no_local_language;
//  //      $voter->age=$request->age;
//  //      $voter->mobile_no=$request->mobile_no;
//  //      $voter->relation=$request->relation;
//  //      $voter->gender_id=$request->gender;  
//  //      $voter->status=3; 
//  //      $voter->save();
//  //      //--start-image-save
//  //      if ($request->hasFile('image')) {
//  //          $dirpath = Storage_path() . '/app/vimage/'.$voter->assembly_id.'/'.$voter->assembly_part_id;
//  //          $vpath = '/vimage/'.$voter->assembly_id.'/'.$voter->assembly_part_id;
//  //          @mkdir($dirpath, 0755, true);
//  //          $file =$request->image;
//  //          $imagedata = file_get_contents($file);
//  //          $encode = base64_encode($imagedata);
//  //          $image=base64_decode($encode); 
//  //          $name =$voter->id;
//  //          $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg',$image);
//  //      }
//  //      //--end-image-save 
//  //      $response=['status'=>1,'msg'=>'Modify Successfully'];
//  //      return response()->json($response);
//  //    }
//  //    public function VoterDetailsModifyReset($id)
//  //    {
//  //      $VoterListModify=VoterListModify::where('voter_id',$id)->first();
//  //      $voter=Voter::find($id); 
//  //      $voter->name_e=$VoterListModify->name_e;
//  //      $voter->name_l=$VoterListModify->name_l;
//  //      $voter->father_name_e=$VoterListModify->father_name_e;
//  //      $voter->father_name_l=$VoterListModify->father_name_l;
//  //      $voter->house_no_e=$VoterListModify->house_no_e;
//  //      $voter->house_no_l=$VoterListModify->house_no_l;
//  //      $voter->age=$VoterListModify->age;
//  //      $voter->mobile_no=$VoterListModify->mobile_no;
//  //      $voter->relation=$VoterListModify->relation;
//  //      $voter->gender_id=$VoterListModify->gender_id; 
//  //      $voter->status=$VoterListModify->previous_status; 
//  //      $voter->save();
//  //      $VoterListModify->delete(); 
//  //      $response=['status'=>1,'msg'=>'Modify Successfully'];
//  //      return response()->json($response);
//  //    }

//  //    public function PrepareVoterListGenerate(Request $request)
//  //    {  
//  //      $rules=[            
//  //            'district' => 'required', 
//  //            'block' => 'required', 
//  //            'village' => 'required',            
//  //      ];
//  //      $validator = Validator::make($request->all(),$rules);
//  //      if ($validator->fails()) {
//  //          $errors = $validator->errors()->all();
//  //          $response=array();
//  //          $response["status"]=0;
//  //          $response["msg"]=$errors[0];
//  //          return response()->json($response);// response as json
//  //      }  
//  //    if ($request->proses_by==1) {
//  //        $voterListMaster=VoterListMaster::where('status',1)->first();
//  //        $voterlistprocessed=new VoterListProcessed(); 
//  //        $voterlistprocessed->district_id=$request->district; 
//  //        $voterlistprocessed->block_id=$request->block; 
//  //        $voterlistprocessed->village_id=$request->village; 
//  //        $voterlistprocessed->voter_list_master_id=$voterListMaster->id; 
//  //        $voterlistprocessed->report_type='panchayat'; 
//  //        $voterlistprocessed->submit_date=date('Y-m-d'); 
//  //        $voterlistprocessed->save();   
//  //        \Artisan::queue('voterlistpanchayat:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village]);
//  //      }
//  //      else if($request->proses_by==2) {
//  //      $unlock_village_voterlist = DB::select(DB::raw("call up_unlock_village_voterlist ('$request->village')"));
//  //       $response=['status'=>1,'msg'=>'Unlock Successfully'];
//  //            return response()->json($response);
//  //      }
//  //    }
     
    
 
//  //    public function PrepareVoterListMunicipalGenerate(Request $request)
//  //    {  
//  //      $rules=[            
//  //            'district' => 'required', 
//  //            'block' => 'required', 
//  //            'village' => 'required', 
//  //            'ward' => 'required', 
//  //      ];
//  //      $validator = Validator::make($request->all(),$rules);
//  //      if ($validator->fails()) {
//  //          $errors = $validator->errors()->all();
//  //          $response=array();
//  //          $response["status"]=0;
//  //          $response["msg"]=$errors[0];
//  //          return response()->json($response);// response as json
//  //      }  
//  //    if ($request->proses_by==1) {
//  //        $voterListMaster=VoterListMaster::where('status',1)->first();
//  //        $voterlistprocessed=new VoterListProcessed(); 
//  //        $voterlistprocessed->district_id=$request->district; 
//  //        $voterlistprocessed->block_id=$request->block; 
//  //        $voterlistprocessed->village_id=$request->village; 
//  //        $voterlistprocessed->ward_id=$request->ward; 
//  //        $voterlistprocessed->voter_list_master_id=$voterListMaster->id; 
//  //        $voterlistprocessed->report_type='mc'; 
//  //        $voterlistprocessed->submit_date=date('Y-m-d'); 
//  //        $voterlistprocessed->save(); 
          
//  //        \Artisan::queue('voterlistmc:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward]);  
//  //      }
//  //      else if($request->proses_by==2) {
//  //      $voterReports = DB::select(DB::raw("call up_unlock_voterlist ('$request->ward')"));
//  //       $response=['status'=>1,'msg'=>'Unlock Successfully'];
//  //            return response()->json($response);
//  //      }      
//  //    } 
 
 
}

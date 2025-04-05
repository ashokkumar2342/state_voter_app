<?php

namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Helper\MyFuncs;

class FrontController extends Controller
{
  public function searchVoter()
  {
    try{
      $rs_district= DB::select(DB::raw("SELECT `id` as `opt_id`, `name_e` as `opt_text` from `districts` order by `name_e`;"));    
      return view('search_voter',compact('rs_district'));
    } catch (Exception $e) {}
  }

  public function DistrictWiseMC(Request $request){
    try{ 
      $d_id = intval(Crypt::decrypt($request->id));
      $show_disabled = 0;
      $box_caption = "MC/Panchayat";
      $show_all = 1;
      $rs_records = DB::select(DB::raw("SELECT `vl`.`id` as `opt_id`, concat(`vl`.`code`, ' - ', `vl`.`name_e`) as `opt_text` from `villages` `vl` where `vl`.`districts_id` = $d_id order by `vl`.`name_e`;"));   
      return view('admin.common.select_box_v1',compact('rs_records', 'show_disabled', 'box_caption', 'show_all'));
    } catch (Exception $e) {}
  }

  public function searchVoterFilter(Request $request ,$cond)
  {
    if ($cond == 1) { 
      $rules=[ 
        'voter_card_no' => 'required', 
      ];
      $customMessages = [
        'voter_card_no.required'=> 'Please Enter EPIC Number',
      ];
    }else{
      $rules=[ 
        'district' => 'required', 
        'village' => 'required', 
        'v_name' => 'required|string|min:2|max:50',
        'father_name' => 'required|string|min:2|max:50',
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'village.required'=> 'Please Select MC',
        'v_name.required'=> 'Please Enter Name',
        'father_name.required'=> 'Please Enter Father\'s/Husband\'s',
      ];
      
    }

    $validator = Validator::make($request->all(),$rules, $customMessages);
    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
    }
    $condition = "";
    $search_by = $cond;



    if($search_by == 1){
      $voter_id_no = substr(MyFuncs::removeSpacialChr($request->voter_card_no), 0, 20);
      $condition = " where `vt`.`voter_card_no` = '$voter_id_no'";
    }else{

      $d_id = intval(Crypt::decrypt($request->district));
      $mc_id = intval(Crypt::decrypt($request->village));
      $name_english = substr(MyFuncs::removeSpacialChr($request->v_name), 0, 50);
      $f_h_name_english = substr(MyFuncs::removeSpacialChr($request->father_name), 0, 50);
      
      $condition = " where `vt`.`district_id` = $d_id and `vt`.`name_e` like '$name_english%' and `vt`.`father_name_e` like '$f_h_name_english%' ";
      if($mc_id > 0){
        $condition = $condition." and `vt`.`village_id` = ".$mc_id;
      }

      $age = MyFuncs::removeSpacialChr($request->age);
      if($age!='0'){
        $condition = $condition." and `vt`.`age` between $age ";
      }  

    }
    
    $myquery = "SELECT concat(`ac`.`code`, ' - ', `ac`.`name_e`) as `ac_name`, `ap`.`part_no`, `vt`.`sr_no`, `vt`.`voter_card_no`, `vt`.`house_no_e`, `vl`.`name_e` as `v_name`, `wv`.`ward_no`, `pb`.`booth_no`, `vt`.`name_e` as `voter_name`, `vt`.`father_name_e`, `vt`.`age`, `g`.`genders` from `voters` `vt` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` inner join `genders` `g` on `g`.`id` = `vt`.`gender_id` left join `villages` `vl` on `vl`.`id` = `vt`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` $condition order by `vt`.`name_e`";

    // return $myquery;
    $voters= DB::select(DB::raw($myquery));

    $response= array();                       
    $response['status']= 1;                       
    $response['data']=view('searchResult',compact('voters'))->render();
    return $response;
     
  }

  public function downloadVoterList()
  {
    try{
      $States = DB::select(DB::raw("SELECT * from `states` order by `name_e`;"));    
      return view('voter_list_download',compact('States'));
    } catch (Exception $e) {}
  }

  public function stateWiseDistrict(Request $request)
  {
    try{ 
      $state_id = intval(Crypt::decrypt($request->id));
      $show_disabled = 1;
      $box_caption = "District";
      $rs_records = DB::select(DB::raw("SELECT `id` as `opt_id`, `name_e` as `opt_text` from `districts` where `state_id` = $state_id order by `name_e`;"));     
      return view('admin.common.select_box_v1',compact('rs_records', 'show_disabled', 'box_caption'));
    } catch (Exception $e) {}
  }

  public function DistrictWiseBlock(Request $request)
  {
    try{
      $d_id = intval(Crypt::decrypt($request->id));
      $show_disabled = 1;
      $box_caption = "Block / MC's";
      $rs_records = DB::select(DB::raw("SELECT `id` as `opt_id`, `name_e` as `opt_text` from `blocks_mcs` where `districts_id` = $d_id order by `name_e`;"));     
      return view('admin.common.select_box_v1',compact('rs_records', 'show_disabled', 'box_caption'));
    } catch (Exception $e) {}
  }
  public function BlockWiseVoterListType(Request $request)
  {
    try{
      $b_id = 0;
      if(!empty($request->id)){
        $b_id = intval(Crypt::decrypt($request->id));
      }
      $show_disabled = 0;
      $show_all = 0;
      $box_caption = "Voter List";
      $rs_records = DB::select(DB::raw("SELECT `id` as `opt_id`, `voter_list_name` as `opt_text` from `voter_list_master` where `block_id` = $b_id and `status` = 1 order by `voter_list_name`;"));     
      return view('admin.common.select_box_v1',compact('rs_records', 'show_disabled', 'show_all', 'box_caption'));
    } catch (Exception $e) {}
  }


  public function tableShow(Request $request)
  { 
    try{
      $rules=[  
        'states' => 'required',  
        'district' => 'required',  
        'block' => 'required',  
        'voter_list_master_id' => 'required',
        'captcha' => 'required|captcha'  
      ]; 
      $validator = Validator::make($request->all(),$rules);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      }
      $block_id = intval(Crypt::decrypt($request->block));
      $voter_list_master_id = intval(Crypt::decrypt($request->voter_list_master_id));

      $voterlistprocesseds = DB::select(DB::raw("SELECT `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`report_type`, `vlp`.`id`, `vlp`.`status`,  `vlp`.`folder_path`, `vlp`.`file_path_p`, `vlp`.`file_path_w`, `vlp`.`file_path_h`, `submit_time`, `start_time`, `finish_time`, `expected_time_start` from `voter_list_processeds` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `block_id` = $block_id and `voter_list_master_id` = $voter_list_master_id and `status` = 1 order by `vil`.`name_e`, `wv`.`ward_no`;"));

      $response = array();
      $response['status'] = 1; 
      $response['data'] =view('download_table',compact('voterlistprocesseds'))->render();
      return response()->json($response);
    } catch (Exception $e) {}
  }

  public function download($id)
  {  
    try{
      $id = intval(Crypt::decrypt($id));
      $voterlistprocesseds = DB::select(DB::raw("SELECT `folder_path`, `file_path_w` from `voter_list_processeds` where `id` = $id limit 1;"));
      if(count($voterlistprocesseds)==0){
        return null;
      }
      $voterlistprocesseds = reset($voterlistprocesseds);

      $documentUrl = Storage_path().$voterlistprocesseds->folder_path;
      $documentUrl = $documentUrl.$voterlistprocesseds->file_path_w; 
      if(file_exists($documentUrl)){                
        return response()->file($documentUrl);
      }else{
        return 'File Not Found';
      }
    } catch (Exception $e) {}
  }
  //---search-voter--------------// 
}

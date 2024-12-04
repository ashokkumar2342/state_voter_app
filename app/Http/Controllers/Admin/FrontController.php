<?php

namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FrontController extends Controller
{
  public function downloadVoterList()
  {
    try{
      $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
      return view('voter_list_download',compact('States'));
    } catch (Exception $e) {}
  }
  public function stateWiseDistrict(Request $request){
    try{ 
      // return $request;
      $Districts=DB::select(DB::raw("select * from `districts` where `state_id` =$request->id"));   
      return view('admin.master.districts.value_select_box',compact('Districts'));
    } catch (Exception $e) {}
  }
  public function DistrictWiseBlock(Request $request){
    try{ 
      // return $request;
      $BlocksMcs=DB::select(DB::raw("select * from `blocks_mcs` where `districts_id` =$request->id"));   
      return view('admin.master.block.value_select_box',compact('BlocksMcs'));
    } catch (Exception $e) {}
  }
  public function BlockWiseVoterListType(Request $request)
  {
    try{  
      $b_id = 0;
      if(!empty($request->id)){$b_id = $request->id;}

      $admin = Auth::guard('admin')->user(); 

      $VoterListType = DB::select(DB::raw("select * from `voter_list_master` where `block_id` = $b_id;"));  
      return view('admin.voterlistmaster.value_select_box',compact('VoterListType'));
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
      $voterlistprocesseds = DB::select(DB::raw("select `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`report_type`, `vlp`.`id`, `vlp`.`status`,  `vlp`.`folder_path`, `vlp`.`file_path_p`, `vlp`.`file_path_w`, `vlp`.`file_path_h`, `submit_time`, `start_time`, `finish_time`, `expected_time_start` from `voter_list_processeds` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `block_id` = $request->block and `voter_list_master_id` = $request->voter_list_master_id order by `vil`.`name_e`, `wv`.`ward_no`;"));

      $response = array();
      $response['status'] = 1; 
      $response['data'] =view('download_table',compact('voterlistprocesseds'))->render();
      return response()->json($response);
    } catch (Exception $e) {}
  }
  public function download($id,$condition)
  {  
    try{
      $voterlistprocesseds = DB::select(DB::raw("select `folder_path`, `file_path_p`, `file_path_w`, `file_path_h` from `voter_list_processeds` where `id` = $id limit 1;"));
      if(count($voterlistprocesseds)==0){
        return null;
      }
      $voterlistprocesseds = reset($voterlistprocesseds);

      $documentUrl = Storage_path().$voterlistprocesseds->folder_path;
      if($condition == 'p'){$documentUrl = $documentUrl.$voterlistprocesseds->file_path_p;} 
      elseif($condition == 'w'){$documentUrl = $documentUrl.$voterlistprocesseds->file_path_w;} 
      elseif($condition == 'h'){$documentUrl = $documentUrl.$voterlistprocesseds->file_path_h;} 
      return response()->file($documentUrl);          
    } catch (Exception $e) {}
  }
  //---search-voter--------------//

  public function searchVoter()
  {
    try{
      $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
      return view('search_voter',compact('States'));
    } catch (Exception $e) {}
  } 
}

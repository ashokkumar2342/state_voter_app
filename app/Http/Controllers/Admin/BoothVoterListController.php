<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\BoothVoterList;
use App\Model\District;
use App\Model\PollingBooth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; 

class BoothVoterListController extends Controller
{ 
    public function index($value='')
    {   
      $Districts= District::orderBy('name_e','ASC')->get();
      return view('admin.master.PrepareBoothVoterList.index',compact('Districts'));
    }
    public function blockWiseBoothList(Request $request)
    {
      $booths=DB::select(DB::raw("select `pb`.`id`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`, '')) as `boothno`, `pb`.`name_e`, case ifnull(`bvl`.`status`,3) when 0 then 'Pending' when 1 then 'Download' when 2 then 'Processing' else '' end as `d_status`, 
      `bvl`.`folder_path`, `bvl`.`file_name` From `polling_booths` `pb`
      left join `booth_voter_list` `bvl` on `bvl`.`booth_id` = `pb`.`id`
      where `pb`.`blocks_id` =$request->block_id order By `pb`.`booth_no`"));
      return view('admin.master.PrepareBoothVoterList.table',compact('booths'));   
    }
    
    public function BoothVoterListProcess(Request $request)
    {   
        $rules=[            
            'district' => 'required', 
            'block' => 'required',  
            'booth' => 'required', 
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
        return response()->json($response);// response as json
       }
        foreach ($request->booth as $key => $value) { 
          $booths=DB::select(DB::raw("update `booth_voter_list` set `status` = 0 where `booth_id` = $value;"));

          $processMainPage = DB::select(DB::raw(" call `up_print_voterlist_booth`($value);"));

          \Artisan::queue('boothvotelist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'booth_id'=>$value]);
        }

        $response=['status'=>1,'msg'=>'Submit Successfully'];
        return response()->json($response);
    }
    
    public function boothVoterListDownload($id)
    {  
      $BoothVoterList=BoothVoterList::where('booth_id',$id)->first(); 
      $documentUrl = Storage_path().$BoothVoterList->folder_path.$BoothVoterList->file_name;  
      return response()->file($documentUrl); 
    }

    public function boothVoterListDownloadWOP($id)
    {  
      $BoothVoterList=BoothVoterList::where('booth_id',$id)->first(); 
      $documentUrl = Storage_path().$BoothVoterList->folder_path.$BoothVoterList->file_name;  
      return response()->file($documentUrl); 
    }   
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Model\Assembly;
// use App\Model\AssemblyPart;
// use App\Model\BlocksMc;
// use App\Model\DeleteVoterDetail;
// use App\Model\District;
// use App\Model\Gender;
// use App\Model\Relation;
// use App\Model\State;
// use App\Model\UserActivity;
// use App\Model\Village;
// use App\Model\Voter;
// use App\Model\VoterImage;
// use App\Model\VoterListMaster;
// use App\Model\VoterListProcessed;
// use App\Model\VoterListModify;
// use App\Model\WardVillage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Imagick;
use PDF;
use TCPDF;
use App\Helper\MyFuncs;

class VoterDetailsController extends Controller
{
    
  	public function districtWiseAssembly(Request $request)
  	{
  		try{
        $d_id = intval(Crypt::decrypt($request->id));
    		$assemblys = DB::select(DB::raw("SELECT * from `assemblys` where `district_id` = $d_id order by `code`;"));
    		return view('admin.master.assembly.assembly_value_select_box',compact('assemblys'));
    	} catch (Exception $e) {}
  	}

	public function VillageWiseWardAll(Request $request)
	{
		try{
			$r_id = 0;
			if($request->id!='null'){
				$r_id = intval(Crypt::decrypt($request->id));
			}
			$WardVillages = DB::select(DB::raw("SELECT * from `ward_villages` where `village_id` = $r_id order by `ward_no`;"));
			return view('admin.voterDetails.select_ward_no',compact('WardVillages')); 
		} catch (Exception $e) {}
	}

  public function VillageWiseWard(Request $request)
  {
    try{
      $r_id = 0;
      if(!empty($request->id)){
        $r_id = $request->id;
      }
      $WardVillages = DB::select(DB::raw("call `up_fetch_ward_village_access`($r_id, 1);"));
      // $WardVillages = DB::select(DB::raw("select * from `ward_villages` where `village_id` = $r_id order by `ward_no`;"));
      return view('admin.voterDetails.select_ward_no',compact('WardVillages')); 
    } catch (Exception $e) {}
  }

    //--------Prepare-----Voter--------List-------PrepareVoterList----------

  public function PrepareVoterListPanchayat()
  {
  	try{
    	$admin = Auth::guard('admin')->user(); 
    	$Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));  
      $rslistPrepareOption = DB::select(DB::raw("select * from `list_prepare_option`"));
      $rslistSortingOption = DB::select(DB::raw("select * from `list_sorting_option`")); 
    	return view('admin.master.PrepareVoterList.index',compact('Districts', 'rslistPrepareOption', 'rslistSortingOption'));     
    } catch (Exception $e) {}
  }


  public function PrepareVoterListMunicipal()
  {
  	try{
      $admin = Auth::guard('admin')->user(); 
    	$Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
      $rslistPrepareOption = DB::select(DB::raw("select * from `list_prepare_option`"));
      $rslistSortingOption = DB::select(DB::raw("select * from `list_sorting_option`"));
      
    	return view('admin.master.PrepareVoterList.municipal.index',compact('Districts', 'rslistPrepareOption', 'rslistSortingOption'));  
    } catch (Exception $e) {}   
  }


 	public function VillageWiseWardMultiple(Request $request)
  { 
  	try{
    	$WardVillages = DB::select(DB::raw("call up_fetch_ward_village_access ('$request->id','0')")); 
   
    	return view('admin.master.PrepareVoterList.select_ward_value',compact('WardVillages'));     
    } catch (Exception $e) {}
  }
 

//-------------------Prepare-Voter-List-Booth-Wise---------------------
  public function PrepareVoterListBoothWise()
  {
  	try{
      $admin = Auth::guard('admin')->user(); 
    	$Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
      $rslistPrepareOption = DB::select(DB::raw("select * from `list_prepare_option`"));
      $rslistSortingOption = DB::select(DB::raw("select * from `list_sorting_option`"));
      
    	return view('admin.master.PrepareVoterList.booth.index',compact('Districts', 'rslistPrepareOption', 'rslistSortingOption'));  
    } catch (Exception $e) {}
  }


//-------------------VoterListDownload---------------------
  public function VoterListDownload($value='')
  {
    try{
      $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
      return view('admin.master.voterlistdownload.index',compact('States'));
    } catch (Exception $e) {}
  }

  public function NewVoterListDownload($value='')
  {
    try{
      $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
      return view('voter_list_download',compact('States'));
    } catch (Exception $e) {}
  }

  public function BlockWiseDownloadTable(Request $request)
  { 
    try{

      $voterlistprocesseds = DB::select(DB::raw("select `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`report_type`, `vlp`.`id`, `vlp`.`status`,  `vlp`.`folder_path`, `vlp`.`file_path_p`, `vlp`.`file_path_w`, `vlp`.`file_path_h`, `submit_time`, `start_time`, `finish_time`, `expected_time_start` from `voter_list_processeds` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `block_id` = $request->block_id and `voter_list_master_id` = $request->voter_list_master_id order by `vil`.`name_e`, `wv`.`ward_no`;"));

      return view('admin.master.voterlistdownload.download_table',compact('voterlistprocesseds')); 
    } catch (Exception $e) {}
  }

  public function VoterListDownloadPDF($id,$condition)
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

  public function processingStatus(Request $request)
  { 
    try{

      $voterlistprocesseds = DB::select(DB::raw("select `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`report_type`, `vlp`.`id`, `vlp`.`status`, `vlp`.`folder_path`, `vlp`.`file_path_p`, `vlp`.`file_path_w`, `vlp`.`file_path_h`, `submit_time`, `start_time`, `finish_time`, `expected_time_start`, `dis`.`name_e` as `d_name`, `bl`.`code` as `b_code`, `bl`.`name_e` as `b_name` from `voter_list_processeds` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` inner join `districts` `dis` on `dis`.`id` = `vil`.`districts_id` inner join `blocks_mcs` `bl` on `bl`.`id` = `vil`.`blocks_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `status` <> 1 order by `vlp`.`id`;"));

      return view('admin.master.voterlistdownload.processing_status',compact('voterlistprocesseds')); 
    } catch (Exception $e) {}
  }

  //--Vidhan Sabha List Download
  public function VidhanSabhaListDownload($value='')
  {
    try{
      $States= DB::select(DB::raw("select * from `states` order by `name_e`;"));    
      return view('admin.master.vidhansabhalistdownload.index',compact('States'));
    } catch (Exception $e) {}
  }

  public function DistrictWiseVidhanDownloadTable(Request $request)
  { 
    try{

      $voterlistprocesseds = DB::select(DB::raw("select `vl`.`id`, `ac`.`code`, `ac`.`name_e`, `vl`.`file_path`, `vl`.`folder_path`, `vl`.`status` from `vidhansabha_list` `vl` inner join `assemblys` `ac` on `ac`.`id` = `vl`.`assembly_id` where `vl`.`district_id` = $request->district_id order by `ac`.`code`;"));

      return view('admin.master.vidhansabhalistdownload.download_table',compact('voterlistprocesseds')); 
    } catch (Exception $e) {}
  }

  public function VidhanListDownloadPDF($id)
  {  
    try{
      $voterlistprocesseds = DB::select(DB::raw("select `folder_path`, `file_path` from `vidhansabha_list` where `id` = $id limit 1;"));
      if(count($voterlistprocesseds)==0){
        return null;
      }
      $voterlistprocesseds = reset($voterlistprocesseds);

      $documentUrl = Storage_path().$voterlistprocesseds->folder_path;
      $documentUrl = $documentUrl.$voterlistprocesseds->file_path; 
      
      return response()->file($documentUrl);          
    } catch (Exception $e) {}
  }
 
//New Voter Add Detailed Entry
  public function store(Request $request)
  {    
    $rules=[            
      'district' => 'required', 
      'block' => 'required', 
      'village' => 'required', 
      'ward_no' => 'required', 
      'ac_part_id' => 'required', 
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
      'image' => 'required|max:500', 
    ];

    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }

    $d_id = $request->district;
    $bl_id = $request->block;
    $vil_id = $request->village;
    $ward_id = $request->ward_no;
    $ac_part_id = $request->ac_part_id;
    $sr_no = $request->srno_part;
    
    $booth_id = 0;
    if(!empty($request->booth_no)){
      $booth_id = $request->booth_no;
    }
    
    $name_e = MyFuncs::removeSpacialChr($request->name_english);
    $name_h = MyFuncs::removeSpacialChr($request->name_local_language);
    $fname_e = MyFuncs::removeSpacialChr($request->f_h_name_english);
    $fname_h = MyFuncs::removeSpacialChr($request->f_h_name_local_language);
    $hno_e = MyFuncs::removeSpacialChr($request->house_no_english);
    $h_no_h = MyFuncs::removeSpacialChr($request->house_no_local_language);
    $age = MyFuncs::removeSpacialChr($request->age);
    $epic_no = MyFuncs::removeSpacialChr($request->voter_id_no);
    
    $aadhar_no = "";
    if (!empty($request->Aadhaar_no)){
      $aadhar_no = MyFuncs::removeSpacialChr($request->Aadhaar_no);  
    }
    
    $mobile = "";
    if (!empty($request->mobile_no)){
      $mobile = MyFuncs::removeSpacialChr($request->mobile_no);  
    }
    
    $relation_id = $request->relation;
    $gender_id = $request->gender;
    $birth_date = $request->date_of_birth;
    
    $rs_fetch = DB::select(DB::raw("select `id`, `tag` from `import_type` where `status` = 1 limit 1;"));
    $data_list_id = $rs_fetch[0]->id;
    $data_tag = $rs_fetch[0]->tag;

    if($sr_no == 0){
      $response=['status'=>0,'msg'=>'Sr. No. cannot be zero'];
      return response()->json($response);  
    }
    $rs_fetch = DB::select(DB::raw("select `id` from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $sr_no and `data_list_id` = $data_list_id limit 1;"));
    if(count($rs_fetch)>0){
      $response=['status'=>0,'msg'=>'Sr. No. already Exists'];
      return response()->json($response);  
    }
    
    $rs_fetch = DB::select(DB::raw("select `assembly_id` from `assembly_parts` where `id` = $ac_part_id limit 1;"));
    $ac_id = $rs_fetch[0]->assembly_id;
    

    $rs_save = DB::select(DB::raw("call `up_save_voter_detail`($d_id, $ac_id, $ac_part_id, $sr_no, '$epic_no', '$hno_e', '$h_no_h','','$name_e','$name_h','$fname_e','$fname_h', $relation_id, $gender_id, $age, '$mobile', 'n', 0, 0, 0, 0, 0, 0, $data_list_id, '$data_tag');"));

    $rs_fetch = DB::select(DB::raw("select `id` from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $sr_no and `data_list_id` = $data_list_id limit 1;"));
    $new_id = $sr_no;


    $rs_fetch = DB::select(DB::raw("select `id` from `voter_list_master` where `block_id` = $bl_id and `status` = 1 limit 1;"));
    $voter_list_id = $rs_fetch[0]->id;
     
    
  //--start-image-save
    $dirpath = Storage_path() . '/app/vimage/'.$data_list_id.'/'.$ac_id.'/'.$ac_part_id;
    $vpath = '/vimage/'.$data_list_id.'/'.'/'.$ac_id.'/'.$ac_part_id;
    @mkdir($dirpath, 0755, true);
    $file =$request->image;
    $imagedata = file_get_contents($file);
    $encode = base64_encode($imagedata);
    $image=base64_decode($encode); 
    $name =$new_id;
    $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg',$image);
  //--end-image-save 


    $admin = Auth::guard('admin')->user();
    $userid = $admin->id;
    $rs_update = DB::select(DB::raw("call `up_change_voters_wards_by_ac_srno` ($userid, $bl_id, $vil_id, $ac_part_id, $data_list_id, $sr_no, $sr_no, $ward_id, $booth_id)"));

    $response=['status'=>1,'msg'=>'Submit Successfully'];
    return response()->json($response);
  }


  public function index()
  { 
    $admin = Auth::guard('admin')->user(); 
    $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
    $genders= DB::select(DB::raw("select * from `genders` order by `id`;"));  
    $Relations= DB::select(DB::raw("select * from `relation` order by `relation_e`;"));  
    
    return view('admin.voterDetails.index',compact('Districts','genders','Relations'));   
  }

  //Pending----------
  public function VillageWiseVoterList(Request $request)
  {
    $village_id = $request->village_id;

    $voterlists= DB::select(DB::raw("select `id`, `name_e`, `name_l`, `father_name_e` from `voters` where `status` = 1 and `source` = 'n' and `village_id` = $village_id;"));

    return view('admin.voterDetails.voter_list_table',compact('voterlists'));
  }

  public function VillageWiseAcParts(Request $request)
  {
    try{  
      $id = $request->id;
      $assemblyParts = DB::select(DB::raw("  select `ap`.`id`, `ac`.`code`, `ap`.`part_no` from `assembly_parts` `ap` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id`   where `ap`.`village_id` = $id order by `ac`.`code`, `ap`.`part_no`;"));
      return view('admin.voterDetails.select_box_ac_parts',compact('assemblyParts'));
    } catch (Exception $e) {}
  }

  public function calculateAge(Request $request)
  { 

    $date1=date_create($request->id);
    $date2=date_create(date('Y-m-d'));
    $diff=date_diff($date1,$date2);
    return view('admin.voterDetails.age_value',compact('diff')); 
  }

  public function NameConvert(Request $request,$condition_type)
  { 
    if ($condition_type==3) {
      $name_english= DB::select(DB::raw("select uf_house_convert_e_2_h ('$request->name_english') as 'name_l'"));   
    }
    else{  
      $name_english= DB::select(DB::raw("select uf_name_convert_e_2_h ('$request->name_english') as 'name_l'")); 
    }

    $name_l = preg_replace('/[\x00]/', '', $name_english[0]->name_l); 
    return view('admin.voterDetails.name_hindi_value',compact('name_l','condition_type'));   
  }   
//-----------------------End----------------

 

 
 //    public function districtWiseVillage(Request $request)
 //    {
 //       $villages=Village::where('districts_id',$request->id)->orderBy('code','ASC')->get();
 //       return view('admin.voterDetails.village_value',compact('villages'));
 //    }
    public function AssemblyWisePartNo(Request $request)
    {
       
       $Parts = DB::select(DB::raw("select * from `assembly_parts` where `assembly_id` = $request->id order by `part_no`;"));  
       return view('admin.voterDetails.select_part_no',compact('Parts')); 
    }
 
    // public function voterListEdit($voter_id)
    // {
    //   $genders= Gender::orderBy('id','ASC')->get();  
    //   $Relations= Relation::orderBy('id','ASC')->get();
    //   $voterlist=Voter::find($voter_id);
    //    return view('admin.voterDetails.voter_list_edit',compact('voterlist','genders','Relations')); 
    // }
 
 
 //    }
 //    public function voterUpdate(Request $request,$id)
 //    {    
 //        $rules=[            
             
 //            'name_english' => 'required', 
 //            'name_local_language' => 'required', 
 //            'relation' => 'required', 
 //            'f_h_name_english' => 'required', 
 //            'f_h_name_local_language' => 'required', 
 //            'house_no_english' => 'required', 
 //            'house_no_local_language' => 'required', 
 //            'gender' => 'required', 
 //            'age' => 'required', 
 //            'voter_id_no' => 'required',  
             
 //      ];

 //      $validator = Validator::make($request->all(),$rules);
 //      if ($validator->fails()) {
 //          $errors = $validator->errors()->all();
 //          $response=array();
 //          $response["status"]=0;
 //          $response["msg"]=$errors[0];
 //          return response()->json($response);// response as json
 //      }
 //      else {
 //            $house_no=DB::select(DB::raw("Select `uf_converthno`('$request->house_no_english') as 'hno_int';")); 
 //            $voter=Voter::find($id);  
 //            $voter->name_e = $request->name_english;
 //            $voter->name_l = $request->name_local_language;
 //            $voter->father_name_e = $request->f_h_name_english;
 //            $voter->father_name_l = $request->f_h_name_local_language;
 //            $voter->voter_card_no = $request->voter_id_no;
 //            $voter->house_no = $house_no[0]->hno_int;
 //            $voter->house_no_e = $request->house_no_english;
 //            $voter->house_no_l = $request->house_no_local_language; 
 //            $voter->relation = $request->relation;
 //            $voter->gender_id = $request->gender;
 //            $voter->age = $request->age;
 //            $voter->mobile_no = $request->mobile_no;
 //            $voter->status =1;
 //            $voter->save();             
 //            $response=['status'=>1,'msg'=>'Update Successfully'];
 //            return response()->json($response);
 //      }
     

 //    }
 //    public function voterDelete($voter_id)
 //    {
 //       $voter=Voter::find($voter_id);   
 //       $voter->delete();
 //       $response=['status'=>1,'msg'=>'Delete Successfully'];
 //            return response()->json($response);   
 //    }

    
     
 //    public function DeteleAndRestore()
 //    {
 //      $Districts= District::orderBy('name_e','ASC')->get();  
 //      return view('admin.DeteleAndRestore.index',compact('Districts','genders','voters')); 
 //    }
 //    public function DeteleAndRestoreShow(Request $request)
 //    {
 //        $rules=[ 
 //              'village' => 'required', 
 //        ];

 //        $validator = Validator::make($request->all(),$rules);
 //        if ($validator->fails()) {
 //            $errors = $validator->errors()->all();
 //            $response=array();
 //            $response["status"]=0;
 //            $response["msg"]=$errors[0];
 //            return response()->json($response);// response as json
 //        }
 //        $voters =Voter:: 
 //                 where('village_id',$request->village)
 //               ->where(function($query) use($request){ 
 //                if (!empty($request->print_sr_no)) {
 //                $query->where('print_sr_no', 'like','%'.$request->print_sr_no.'%'); 
 //                }
 //                if (!empty($request->name)) {
 //                $query->where('name_e', 'like','%'.$request->name.'%'); 
 //                }
 //                if (!empty($request->father_name)) {
 //                $query->where('father_name_e', 'like','%'.$request->father_name.'%'); 
 //                } 
 //               }) 
 //               ->get(); 
 //        $response= array();                       
 //        $response['status']= 1;                       
 //        $response['data']=view('admin.DeteleAndRestore.search_table',compact('voters'))->render();
 //        return $response;
         
       
 //    } 
 //    public function DeteleAndRestoreDetele($id)
 //    {
 //      $voter=Voter::find($id);
 //      $DeleteVoterDetail= new DeleteVoterDetail();
 //      $DeleteVoterDetail->voter_id=$id;
 //      $DeleteVoterDetail->voter_list_master_id=$voter->suppliment_no;
 //      $DeleteVoterDetail->voter_list_master_id=$voter->suppliment_no;
 //      $DeleteVoterDetail->previous_status=$voter->status;
 //      $DeleteVoterDetail->status=2;
 //      $DeleteVoterDetail->save();
 //      $voter->status=2;
 //      $voter->save();
 //      $response=['status'=>1,'msg'=>'Delete Successfully'];
 //      return response()->json($response);
 //    }
 //    public function DeteleAndRestoreRestore($id)
 //    {
 //      $DeleteVoterDetail=DeleteVoterDetail::where('voter_id',$id)->first();  
 //      $voter=Voter::find($id);
 //      $voter->status=$DeleteVoterDetail->previous_status;
 //      $voter->save();
 //      $DeleteVoterDetail->delete();
 //      $response=['status'=>1,'msg'=>'Restore Successfully'];
 //      return response()->json($response);
 //    }
    

 //  //--modify------modify-------------  
 //    public function VoterDetailsModify($value='')
 //    {
 //       $Districts= District::orderBy('name_e','ASC')->get();  
 //      return view('admin.modify.index',compact('Districts')); 
 //    }
 //    public function VoterDetailsModifyShow(Request $request)
 //    {
 //        $rules=[ 
 //              'village' => 'required', 
 //        ];

 //        $validator = Validator::make($request->all(),$rules);
 //        if ($validator->fails()) {
 //            $errors = $validator->errors()->all();
 //            $response=array();
 //            $response["status"]=0;
 //            $response["msg"]=$errors[0];
 //            return response()->json($response);// response as json
 //        }
 //        $voters =Voter:: 
 //                 where('village_id',$request->village)
 //               ->where(function($query) use($request){ 
 //                if (!empty($request->print_sr_no)) {
 //                $query->where('print_sr_no', 'like','%'.$request->print_sr_no.'%'); 
 //                }
 //                if (!empty($request->name)) {
 //                $query->where('name_e', 'like','%'.$request->name.'%'); 
 //                }
 //                if (!empty($request->father_name)) {
 //                $query->where('father_name_e', 'like','%'.$request->father_name.'%'); 
 //                } 
 //               }) 
 //               ->get(); 
 //        $response= array();                       
 //        $response['status']= 1;                       
 //        $response['data']=view('admin.modify.table',compact('voters'))->render();
 //        return $response;
         
       
 //    }
 //    public function VoterDetailsModifyEdit($voter_id)
 //    {
 //      $genders= Gender::orderBy('id','ASC')->get();  
 //      $Relations= Relation::orderBy('id','ASC')->get(); 
 //      $voter=Voter::find($voter_id); 
 //     return view('admin.modify.edit',compact('voter','genders','Relations'));
 //    }
 //    public function VoterDetailsModifyStore(Request $request,$id)
 //    { 
 //      $voter=Voter::find($id);
 //      $VoterListModify= new VoterListModify();
 //      $VoterListModify->voter_id=$id;
 //      $VoterListModify->name_e=$voter->name_e;
 //      $VoterListModify->name_l=$voter->name_l;
 //      $VoterListModify->father_name_e=$voter->father_name_e;
 //      $VoterListModify->father_name_l=$voter->father_name_l;
 //      $VoterListModify->house_no_e=$voter->house_no_e;
 //      $VoterListModify->house_no_l=$voter->house_no_l;
 //      $VoterListModify->age=$voter->age;
 //      $VoterListModify->mobile_no=$voter->mobile_no;
 //      $VoterListModify->relation=$voter->relation;
 //      $VoterListModify->gender_id=$voter->gender_id; 
 //      $VoterListModify->previous_status=$voter->status;
 //      $VoterListModify->status=3; 
 //      $VoterListModify->save(); 
       
 //      $voter->name_e=$request->name_english;
 //      $voter->name_l=$request->name_local_language;
 //      $voter->father_name_e=$request->f_h_name_english;
 //      $voter->father_name_l=$request->f_h_name_local_language;
 //      $voter->house_no_e=$request->house_no_english;
 //      $voter->house_no_l=$request->house_no_local_language;
 //      $voter->age=$request->age;
 //      $voter->mobile_no=$request->mobile_no;
 //      $voter->relation=$request->relation;
 //      $voter->gender_id=$request->gender;  
 //      $voter->status=3; 
 //      $voter->save();
 //      //--start-image-save
 //      if ($request->hasFile('image')) {
 //          $dirpath = Storage_path() . '/app/vimage/'.$voter->assembly_id.'/'.$voter->assembly_part_id;
 //          $vpath = '/vimage/'.$voter->assembly_id.'/'.$voter->assembly_part_id;
 //          @mkdir($dirpath, 0755, true);
 //          $file =$request->image;
 //          $imagedata = file_get_contents($file);
 //          $encode = base64_encode($imagedata);
 //          $image=base64_decode($encode); 
 //          $name =$voter->id;
 //          $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg',$image);
 //      }
 //      //--end-image-save 
 //      $response=['status'=>1,'msg'=>'Modify Successfully'];
 //      return response()->json($response);
 //    }
 //    public function VoterDetailsModifyReset($id)
 //    {
 //      $VoterListModify=VoterListModify::where('voter_id',$id)->first();
 //      $voter=Voter::find($id); 
 //      $voter->name_e=$VoterListModify->name_e;
 //      $voter->name_l=$VoterListModify->name_l;
 //      $voter->father_name_e=$VoterListModify->father_name_e;
 //      $voter->father_name_l=$VoterListModify->father_name_l;
 //      $voter->house_no_e=$VoterListModify->house_no_e;
 //      $voter->house_no_l=$VoterListModify->house_no_l;
 //      $voter->age=$VoterListModify->age;
 //      $voter->mobile_no=$VoterListModify->mobile_no;
 //      $voter->relation=$VoterListModify->relation;
 //      $voter->gender_id=$VoterListModify->gender_id; 
 //      $voter->status=$VoterListModify->previous_status; 
 //      $voter->save();
 //      $VoterListModify->delete(); 
 //      $response=['status'=>1,'msg'=>'Modify Successfully'];
 //      return response()->json($response);
 //    }

 //    public function PrepareVoterListGenerate(Request $request)
 //    {  
 //      $rules=[            
 //            'district' => 'required', 
 //            'block' => 'required', 
 //            'village' => 'required',            
 //      ];
 //      $validator = Validator::make($request->all(),$rules);
 //      if ($validator->fails()) {
 //          $errors = $validator->errors()->all();
 //          $response=array();
 //          $response["status"]=0;
 //          $response["msg"]=$errors[0];
 //          return response()->json($response);// response as json
 //      }  
 //    if ($request->proses_by==1) {
 //        $voterListMaster=VoterListMaster::where('status',1)->first();
 //        $voterlistprocessed=new VoterListProcessed(); 
 //        $voterlistprocessed->district_id=$request->district; 
 //        $voterlistprocessed->block_id=$request->block; 
 //        $voterlistprocessed->village_id=$request->village; 
 //        $voterlistprocessed->voter_list_master_id=$voterListMaster->id; 
 //        $voterlistprocessed->report_type='panchayat'; 
 //        $voterlistprocessed->submit_date=date('Y-m-d'); 
 //        $voterlistprocessed->save();   
 //        \Artisan::queue('voterlistpanchayat:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village]);
 //      }
 //      else if($request->proses_by==2) {
 //      $unlock_village_voterlist = DB::select(DB::raw("call up_unlock_village_voterlist ('$request->village')"));
 //       $response=['status'=>1,'msg'=>'Unlock Successfully'];
 //            return response()->json($response);
 //      }
 //    }
     
    
 
 //    public function PrepareVoterListMunicipalGenerate(Request $request)
 //    {  
 //      $rules=[            
 //            'district' => 'required', 
 //            'block' => 'required', 
 //            'village' => 'required', 
 //            'ward' => 'required', 
 //      ];
 //      $validator = Validator::make($request->all(),$rules);
 //      if ($validator->fails()) {
 //          $errors = $validator->errors()->all();
 //          $response=array();
 //          $response["status"]=0;
 //          $response["msg"]=$errors[0];
 //          return response()->json($response);// response as json
 //      }  
 //    if ($request->proses_by==1) {
 //        $voterListMaster=VoterListMaster::where('status',1)->first();
 //        $voterlistprocessed=new VoterListProcessed(); 
 //        $voterlistprocessed->district_id=$request->district; 
 //        $voterlistprocessed->block_id=$request->block; 
 //        $voterlistprocessed->village_id=$request->village; 
 //        $voterlistprocessed->ward_id=$request->ward; 
 //        $voterlistprocessed->voter_list_master_id=$voterListMaster->id; 
 //        $voterlistprocessed->report_type='mc'; 
 //        $voterlistprocessed->submit_date=date('Y-m-d'); 
 //        $voterlistprocessed->save(); 
          
 //        \Artisan::queue('voterlistmc:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward]);  
 //      }
 //      else if($request->proses_by==2) {
 //      $voterReports = DB::select(DB::raw("call up_unlock_voterlist ('$request->ward')"));
 //       $response=['status'=>1,'msg'=>'Unlock Successfully'];
 //            return response()->json($response);
 //      }      
 //    } 
 
 
}

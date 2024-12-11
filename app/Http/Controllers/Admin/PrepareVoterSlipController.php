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

class PrepareVoterSlipController extends Controller
{
  protected $e_controller = "PrepareVoterSlipController";

  public function index()
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(86);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.PrepareVoterSlip.index',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "index";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }    
  }

  public function PrepareVoterSlipGenerate(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(86);
      if(!$permission_flag){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $rules=[            
        'district' => 'required', 
        'block' => 'required', 
        'village' => 'required', 
        'ward' => 'required', 
        'booth' => 'required', 
        'slip_per_page' => 'required', 
      ];
      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select MC\'s',
        'village.required'=> 'Please Select MC\'s',
        'ward.required'=> 'Please Select Ward',
        'booth.required'=> 'Please Select Booth',
        'slip_per_page.required'=> 'Please Select Slip Per Page',
      ];
      $validator = Validator::make($request->all(),$rules, $customMessages);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        $response=array();
        $response["status"]=0;
        $response["msg"]=$errors[0];
        return response()->json($response);// response as json
      } 
      $district_id = intval(Crypt::decrypt($request->district));
      $permission_flag = MyFuncs::check_district_access($district_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $block_id = intval(Crypt::decrypt($request->block));
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $village_id = intval(Crypt::decrypt($request->village));
      $permission_flag = MyFuncs::check_village_access($village_id);
      if($permission_flag == 0){
        $response=['status'=>0,'msg'=>'Something Went Wrong'];
        return response()->json($response);
      }
      $ward_id = intval(Crypt::decrypt($request->ward));
      $booth_id = intval(Crypt::decrypt($request->booth));

      $slip_per_page = intval(Crypt::decrypt($request->slip_per_page));

      $PrepareVoterSlipSave = DB::select(DB::raw("call `up_process_voter_slip` ($district_id, $block_id, $village_id, $ward_id, $booth_id);"));

      \Artisan::queue('preparevoterslip:generate',['district_id'=>$district_id, 'block_id'=>$block_id, 'village_id'=>$village_id, 'ward_id'=>$ward_id, 'booth_id'=>$booth_id, 'slip_per_page'=>$slip_per_page]);

      // \Artisan::call('queue:work --tries=1 --timeout=2000');

      $response=['status'=>1,'msg'=>'Submit Successfully'];
      return response()->json($response); 
    } catch (\Exception $e) {
      $e_method = "PrepareVoterSlipGenerate";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function PrepareVoterSlipDownload( )
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(110);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.PrepareVoterSlip.download',compact('rs_district'));
    } catch (\Exception $e) {
      $e_method = "PrepareVoterSlipDownload";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }   
  }

  public function PrepareVoterSlipDownloadResult(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(110);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $block_id = intval(Crypt::decrypt($request->block_id));
      $permission_flag = MyFuncs::check_block_access($block_id);
      if($permission_flag == 0){
        $block_id = 0;
      }
      $voterlistprocesseds = DB::select(DB::raw("SELECT `vil`.`name_e`, `wv`.`ward_no`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `vlp`.`id`, `vlp`.`status`, `vlp`.`folder_path`, `vlp`.`file_path`, `submit_time`, `start_time`, `finish_time`, `expected_time_start` from `voter_slip_processed` `vlp` inner join `villages` `vil` on `vil`.`id` = `vlp`.`village_id` left join `ward_villages` `wv` on `wv`.`id` = `vlp`.`ward_id` left join `polling_booths` `pb` on `pb`.`id` = `vlp`.`booth_id` where `block_id` = $block_id order by `vil`.`name_e`, `wv`.`ward_no`;"));

      return view('admin.master.PrepareVoterSlip.download_result',compact('voterlistprocesseds'));
    } catch (\Exception $e) {
      $e_method = "PrepareVoterSlipDownloadResult";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function PrepareVoterSlipResultDownload($id)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(110);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rec_id = intval(Crypt::decrypt($id));
      $VoterSlipProcessed = DB::select(DB::raw("SELECT * from `voter_slip_processed` where `id` = $rec_id limit 1;")); 
      $documentUrl = Storage_path().$VoterSlipProcessed[0]->folder_path.'/'.$VoterSlipProcessed[0]->file_path;  
      return response()->file($documentUrl);
    } catch (\Exception $e) {
      $e_method = "PrepareVoterSlipResultDownload";
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

  


  //   // public function villageWiseWard(Request $request)
  //   // {
  //   //   $wards=WardVillage::where('village_id',$request->village_id)->orderBy('ward_no','ASC')->get();
  //   //   return view('admin.master.PrepareVoterSlip.ward_select_box',compact('wards'));   
  //   // }
  //   // public function villageWiseBooth(Request $request)
  //   // {
  //   //     if ($request->ward_id!=0) { 
  //   //         $booths=PollingBooth::where('village_id',$request->village_id)->orderBy('booth_no','ASC')->get();
  //   //     }else{
  //   //        $booths=[]; 
  //   //     }
  //   //     return view('admin.master.PrepareVoterSlip.booth_select_box',compact('booths'));   
  //   // }

  

  // // public function PrepareVoterSlipGenerate(Request $request)
  // // {   
  // //   $rules=[            
  // //     'district' => 'required', 
  // //     'block' => 'required', 
  // //     'village' => 'required', 
  // //     'ward' => 'required', 
  // //     'booth' => 'required', 
  // //     'slip_per_page' => 'required', 
  // //   ];
  // //   $validator = Validator::make($request->all(),$rules);
  // //   if ($validator->fails()) {
  // //     $errors = $validator->errors()->all();
  // //     $response=array();
  // //     $response["status"]=0;
  // //     $response["msg"]=$errors[0];
  // //     return response()->json($response);// response as json
  // //   } 
  // //   $PrepareVoterSlipSave= DB::
  // //         select(DB::raw("call `up_process_voter_slip` ($request->district, $request->block, $request->village, $request->ward, $request->booth, , $request->slip_per_page)"));
  // //   $district_id = $request->district;
  // //   $block_id = $request->block; 
  // //   $village_id = $request->village; 
  // //   $ward_id = $request->ward;
  // //   $booth_id = $request->booth;

  // //   // $blockcode=BlocksMc::find($block_id);
  // //   // $wardno=WardVillage::find($ward_id); 
  // //   // $villagename=Village::find($village_id);
  // //   // $pollingboothdetail=PollingBooth::find($booth_id);
    
  // //   // $VoterSlipProcessed=VoterSlipProcessed::where('district_id',$district_id)->where('block_id',$block_id)->where('village_id',$village_id)->where('ward_id',$ward_id)->where('booth_id',$booth_id)->first();

  // //   $rs_result = DB::select(DB::raw("select * from `villages` where `id` = $village_id limit 1"));
  // //   $village_name_e = $rs_result[0]->name_e;

  // //   $VoterSlipProcessed = DB::select(DB::raw("select * from `voter_slip_processed` where `village_id` = $village_id and `ward_id` = $ward_id and `booth_id` = $booth_id limit 1;"));
  // //   $VoterSlipProcessed = reset($VoterSlipProcessed);

  // //   $newId=DB::select(DB::raw("Update `voter_slip_processed` set `status` = 2 where `id` = $VoterSlipProcessed->id;"));

  // //   $dirpath = Storage_path() . $VoterSlipProcessed->folder_path;
  // //   @mkdir($dirpath, 0755, true);
  // //   chmod($dirpath, 0755);

  // //   $path=Storage_path('fonts/');
  // //   $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
  // //   $fontDirs = $defaultConfig['fontDir']; 
  // //   $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
  // //   $fontData = $defaultFontConfig['fontdata']; 
  // //   $mpdf_slip = new \Mpdf\Mpdf([
  // //       'fontDir' => array_merge($fontDirs, [
  // //                __DIR__ . $path,
  // //            ]),
  // //            'fontdata' => $fontData + [
  // //                'frutiger' => [
  // //                    'R' => 'FreeSans.ttf',
  // //                    'I' => 'FreeSansOblique.ttf',
  // //                ]
  // //            ],
  // //            'default_font' => 'freesans',
  // //        ]);

    

  // //   if ($ward_id==0) {
  // //       $WardVillages = DB::select(DB::raw("select * from `ward_villages` where `village_id` = $village_id;"));
  // //       $pagetype=1;
  // //   }
  // //   else {
  // //       $WardVillages = DB::select(DB::raw("select * from `ward_villages` where `id` = $ward_id;"));
  // //       if ($booth_id==0){
  // //           $pagetype=2;    
  // //       }else{
  // //           $pagetype=3;
  // //       }
        
  // //   }
    
    
  // //   $voterListMaster = DB::select(DB::raw("select * from `voter_list_master` where `block_id` = $block_id and `status` = 1 limit 1;"));

  // //   $blockMcs = DB::select(DB::raw("select * from `blocks_mcs` where `id` = $block_id limit 1;"));
    

  // //   if ($blockMcs[0]->block_mc_type_id==1){
  // //       $slipheader = 'पंचायत ('.$blockMcs[0]->name_l.') '.$voterListMaster[0]->remarks2.' - '.$voterListMaster[0]->year_base;
  // //   }else{
  // //       $slipheader = $blockMcs[0]->name_l.' '.$voterListMaster[0]->remarks2.' - '.$voterListMaster[0]->year_base;
  // //   }


  // //   $rs_slip_note = DB::select(DB::raw("select * from `voter_slip_notes` where `district_id` = $district_id order by `note_srno`;"));

  // //   $html = view('admin.master.PrepareVoterList.voter_list_section.start_pdf');

  // //   $html = $html.'</style></head><body>';

    
  // //   $mpdf_slip->WriteHTML($html);
    
  // //   $wardcount = 1;
  // //   foreach ($WardVillages as $WardVillage) {
  // //       echo "Processing Voter Slip :: ".$village_name_e.' - '.$WardVillage->ward_no." \n";
  // //       if ($wardcount>1){
  // //           $mpdf_slip->WriteHTML('<pagebreak>');    
  // //       }
  // //       $wardcount++;
  // //       $ward_no = $WardVillage->ward_no;

  // //       if ($booth_id==0){$booth_condition = "";}else{$booth_condition = " And `v`.`booth_id` = $booth_id";}

  // //       // $booth_condition = "";
  // //       $query = "select `v`.`id`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, `ap`.`part_no`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `g`.`genders_l`, concat(`pb`.`booth_no`, `pb`.`booth_no_c`) as `boothno`, `pb`.`name_l` as `pb_name`, `v`.`data_list_id`, `v`.`sr_no` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` left join `polling_booths` `pb` on `pb`.`id` = `v`.`booth_id` where `v`.`ward_id` =$WardVillage->id And `v`.`status` in (0,1,3) $booth_condition Order By `v`.`print_sr_no` limit 19;";
  // //       // dd($query);
  // //       $voterReports = DB::select(DB::raw("$query"));
        
  // //       $polldatetime = DB::select(DB::raw("select * from `polling_day_time` where `block_id` = $block_id limit 1;"));

  // //       $main_page=$this->prepareVoterSlip($voterReports, $ward_no, $polldatetime, $slipheader, $blockMcs, $rs_slip_note);
  // //       $mpdf_slip->WriteHTML($main_page);
    
  // //   }
    
         
  // //   $mpdf_slip->WriteHTML('</body></html>');
    
    
  // //   $filepath = Storage_path() . $VoterSlipProcessed->folder_path .'/'. $VoterSlipProcessed->file_path;
  // //   $mpdf_slip->Output($filepath, 'F');
  // //   chmod($filepath, 0755);

    
  // //   $newId=DB::select(DB::raw("Update `voter_slip_processed` set `status` = 1 where `id` = $VoterSlipProcessed->id;"));
    

    
  // // }
  // // public function prepareVoterSlip($voterReports, $wardno, $polldatetime, $slipheader, $blockMcs, $slipNotes)
  // // {
        
  // //       return $main_page=view('admin.master.PrepareVoterSlip.slip_per_page_10',compact('voterReports', 'wardno', 'polldatetime', 'slipheader', 'blockMcs', 'slipNotes'));  

  // // }

     
}

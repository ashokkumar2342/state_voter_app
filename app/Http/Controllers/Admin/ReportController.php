<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    // public function PrintVoterList()
    // {
        
    //     return view('admin.report.index');
    // } 
    // public function PrintVoterListGenerate(Request $request)
    // {
    //   $path=Storage_path('fonts/');
    //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    //     $fontDirs = $defaultConfig['fontDir']; 
    //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    //     $fontData = $defaultFontConfig['fontdata']; 
    //      $mpdf = new \Mpdf\Mpdf([
    //          'fontDir' => array_merge($fontDirs, [
    //              __DIR__ . $path,
    //          ]),
    //          'fontdata' => $fontData + [
    //              'frutiger' => [
    //                  'R' => 'FreeSans.ttf',
    //                  'I' => 'FreeSansOblique.ttf',
    //              ]
    //          ],
    //          'default_font' => 'freesans',
    //          'pagenumPrefix' => '',
    //         'pagenumSuffix' => '',
    //         'nbpgPrefix' => ' कुल ',
    //         'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
    //      ]); 
          
    //   if ($request->report==1) {  
    //    $voterReports= DB::select(DB::raw("select `a`.`code`, `a`.`name_e`, `a`.`name_l`, `ap`.`part_no`,(Select Count(*) From `voters` where `assembly_part_id` = `ap`.`id` ) as `Total_Votes`,(Select Count(*) From `voters` where `assembly_part_id` = `ap`.`id` and `village_id` <> 0) as `Mapped_Votes`from `assemblys` `a`Inner Join `assembly_parts` `ap` on `ap`.`assembly_id` = `a`.`id`Order by `a`.`code`, `ap`.`part_no`;"));
       
    //   $html = view('admin.report.report1',compact('voterReports'));
    //   }
    //   elseif ($request->report==2) {
    //   $voterReports= DB::select(DB::raw("select `a`.`code`, `a`.`name_e`, `a`.`name_l`, `ap`.`part_no`, `v`.`name_e`, `v`.`name_l`from `assemblys` `a`Inner Join `assembly_parts` `ap` on `ap`.`assembly_id` = `a`.`id`Left Join `villages` `v` on `v`.`id` = `ap`.`village_id`Order by `a`.`code`, `ap`.`part_no`;"));
    //   $html = view('admin.report.report2',compact('voterReports'));
    //   }
    //   elseif ($request->report==3) {
    //   $voterReports= DB::select(DB::raw("select `a`.`code`, `a`.`name_e`, `a`.`name_l`, `ap`.`part_no`, `v`.`name_e`, `v`.`name_l`from `assemblys` `a`Inner Join `assembly_parts` `ap` on `ap`.`assembly_id` = `a`.`id`Inner Join `villages` `v` on `v`.`id` = `ap`.`village_id`Order by `v`.`name_e`, `a`.`code`, `ap`.`part_no`;"));
    //   $html = view('admin.report.report3',compact('voterReports'));
    //   }
    //   elseif ($request->report==4) {
    //   $voterReports= DB::select(DB::raw("select `v`.`name_e`, `v`.`name_l`, `wv`.`ward_no`, (Select Count(*) From `voters` where `ward_id` = `wv`.`id` ) as `Total_Votes`from `villages` `v`Inner Join `ward_villages` `wv` on `wv`.`village_id` = `v`.`id`Order By `v`.`name_e`, `wv`.`ward_no`;"));
    //   $html = view('admin.report.report4',compact('voterReports'));
    //   }
      
    //   $mpdf->WriteHTML($html); 
    //   $mpdf->Output();
    // }

  ///--------------------------------report--------report----------------------------

  public function ReportIndex($value='')
  {
    $admin = Auth::guard('admin')->user();
    $userid = $admin->id;  
    $role_id = $admin->role_id;
    $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));
    $reportTypes = DB::select(DB::raw("select * from `report_types` where `available_for` >= $role_id order by `id`;"));
    return view('admin.report.report.index',compact('reportTypes','Districts'));  
  }


  public function villageWiseBooth(Request $request)
  {
    $id = 0;
    if(!empty($request->id)){$id = $request->id;}
    $booths = DB::select(DB::raw("select * from `polling_booths` where `village_id` = $id order by `booth_no`;"));
    return view('admin.report.report.booth_select_box',compact('booths'));
  }

  public function districtwiseZPWard(Request $request)
  {
    $id = 0;
    if(!empty($request->id)){$id = $request->id;}
    $zpWards = DB::select(DB::raw("select * from `ward_zp` where `districts_id` = $id order by `ward_no`;"));
    return view('admin.report.report.zpward_select_box',compact('zpWards'));
  }

  public function blockwisePSWard(Request $request)
  {
    $id = 0;
    if(!empty($request->id)){$id = $request->id;}
    $psWardsno = DB::select(DB::raw("select * from `ward_ps` where `blocks_id` =$id order by `ward_no`;"));
    return view('admin.report.report.psward_select_box',compact('psWardsno'));
  }

  public function StatisticalReportGenerate(Request $request)
  {
    if($request->submit_type == 1){
      return $this->ReportGenerateExcel($request);
    }else{
      // return $this->ReportGeneratePDF($request);
    }
    
  }
    

  public function PrepareQuery5($role_id, $d_id, $b_id, $p_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where `v`.`districts_id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==3){
      $condition = " Where `v`.`blocks_id` in (select `block_id` from `user_block_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==4){
      $condition = " Where `v`.`id` in (select `village_id` from `user_village_assigns` where `user_id` = $user_id) ";
    }  
    if($p_id>0){
      $condition = " Where `v`.`id` = $p_id ";
    }elseif($b_id >0){
      $condition = " Where `v`.`blocks_id` = $b_id ";
    }elseif($d_id >0){
      $condition = " Where `v`.`districts_id` = $d_id ";
    }

    $query = "select `b`.`name_e` as `block_name`, `v`.`name_e`, `v`.`name_l`, (Select Count(*) From `ward_villages` `wv` Where `wv`.`village_id` = `v`.`id`) as `twards`, `wz`.`ward_no` as `zp_wardno` from `villages` `v` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` Left Join `ward_zp` `wz` on `wz`.`id` = `v`.`zp_ward_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`;";
    return $query;  
  }


  public function PrepareQuery9($role_id, $d_id, $b_id, $p_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where `v`.`districts_id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==3){
      $condition = " Where `v`.`blocks_id` in (select `block_id` from `user_block_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==4){
      $condition = " Where `v`.`id` in (select `village_id` from `user_village_assigns` where `user_id` = $user_id) ";
    }  
    if($p_id>0){
      $condition = " Where `v`.`id` = $p_id ";
    }elseif($b_id >0){
      $condition = " Where `v`.`blocks_id` = $b_id ";
    }elseif($d_id >0){
      $condition = " Where `v`.`districts_id` = $d_id ";
    }

    $query = "select `b`.`name_e` as `block_name`, `v`.`name_e`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `village_id` = `v`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `village_id` = `v`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `village_id` = `v`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `village_id` = `v`.`id`) as `tvote` from  `villages` `v` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`;";
    return $query;  
  }

  public function PrepareQuery10($role_id, $d_id, $b_id, $p_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where `v`.`districts_id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==3){
      $condition = " Where `v`.`blocks_id` in (select `block_id` from `user_block_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==4){
      $condition = " Where `v`.`id` in (select `village_id` from `user_village_assigns` where `user_id` = $user_id) ";
    }  
    if($p_id>0){
      $condition = " Where `v`.`id` = $p_id ";
    }elseif($b_id >0){
      $condition = " Where `v`.`blocks_id` = $b_id ";
    }elseif($d_id >0){
      $condition = " Where `v`.`districts_id` = $d_id ";
    }

    $query = "select `b`.`name_e` as `block_name`, `v`.`name_e`, `wv`.`ward_no`, `wps`.`ward_no` as `ps_wardno`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `ward_id` = `wv`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `ward_id` = `wv`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `ward_id` = `wv`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `ward_id` = `wv`.`id`) as `tvote` from `ward_villages` `wv` Inner Join `villages` `v` on `v`.`id` = `wv`.`village_id` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` Left Join `ward_ps` `wps` on `wps`.`id` = `wv`.`ps_ward_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`, `wv`.`ward_no`;";
    return $query;  
  }
  
  public function PrepareQuery15($role_id, $d_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where `dis`.`id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }else{
      $condition = " Where `dis`.`id` = 0) ";
    }  
    if($d_id >0){
      $condition = " Where `dis`.`id` = $d_id ";
    }

    $query = "select `dis`.`name_e`, `wzp`.`ward_no`, (select count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `village_id` in (select `id` from `villages` where `zp_ward_id` = `wzp`.`id`)) as `tmale`, (select count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `village_id` in (select `id` from `villages` where `zp_ward_id` = `wzp`.`id`)) as `tfmale`, (select count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `village_id` in (select `id` from `villages` where `zp_ward_id` = `wzp`.`id`)) as `tomale`, (select count(`id`) from `voters` where `status` <> 2 and `village_id` in (select `id` from `villages` where `zp_ward_id` = `wzp`.`id`)) as `tvoter` from `ward_zp` `wzp` inner join `districts` `dis` on `dis`.`id` = `wzp`.`districts_id` $condition order by `dis`.`name_e`, `wzp`.`ward_no`;";
    return $query;  
  }
  
  public function PrepareQuery25($role_id, $d_id, $b_id, $p_id, $w_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where `v`.`districts_id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==3){
      $condition = " Where `v`.`blocks_id` in (select `block_id` from `user_block_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==4){
      $condition = " Where `v`.`id` in (select `village_id` from `user_village_assigns` where `user_id` = $user_id) ";
    }

    if($w_id>0){
      $condition = " Where `wv`.`id` = $w_id ";
    }elseif($p_id>0){
      $condition = " Where `v`.`id` = $p_id ";
    }elseif($b_id >0){
      $condition = " Where `v`.`blocks_id` = $b_id ";
    }elseif($d_id >0){
      $condition = " Where `v`.`districts_id` = $d_id ";
    }

    // $query = "select `b`.`name_e` as `block_name`, `v`.`name_e`, `wv`.`ward_no`, `wps`.`ward_no` as `ps_wardno`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `ward_id` = `wv`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `ward_id` = `wv`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `ward_id` = `wv`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `ward_id` = `wv`.`id`) as `tvote` from `ward_villages` `wv` Inner Join `villages` `v` on `v`.`id` = `wv`.`village_id` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` Left Join `ward_ps` `wps` on `wps`.`id` = `wv`.`ps_ward_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`, `wv`.`ward_no`;";
    
    $query = "select `b`.`name_e` as `block_name`, `v`.`name_e`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `pb`.`name_e` as `booth_e`, `pb`.`name_l` as `booth_l`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `booth_id` = `pb`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `booth_id` = `pb`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `booth_id` = `pb`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `booth_id` = `pb`.`id`) as `tvote` from `polling_booths` `pb` Inner Join `villages` `v` on `v`.`id` = `pb`.`village_id` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`, `pb`.`booth_no`;";
    return $query;  
  }

  public function PrepareQuery30($role_id, $d_id, $b_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where  `wps`.`districts_id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==3){
      $condition = " Where `wps`.`blocks_id` in (select `block_id` from `user_block_assigns` where `user_id` = $user_id) ";
    }  
    if($b_id >0){
      $condition = " Where `wps`.`blocks_id` = $b_id ";
    }elseif($d_id >0){
      $condition = " Where  `wps`.`districts_id` = $d_id ";
    }

    $query = "select `b`.`name_e` as `block_name`, `wps`.`ward_no` as `ps_wardno`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `ward_id` in ( select `id` from `ward_villages` where `ps_ward_id` = `wps`.`id`)) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `ward_id` in ( select `id` from `ward_villages` where `ps_ward_id` = `wps`.`id`)) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `ward_id` in ( select `id` from `ward_villages` where `ps_ward_id` = `wps`.`id`)) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `ward_id` in ( select `id` from `ward_villages` where `ps_ward_id` = `wps`.`id`)) `tvote` from `ward_ps` `wps` Inner Join `blocks_mcs` `b` on `b`.`id` = `wps`.`blocks_id` $condition Order By  `wps`.`districts_id`, `b`.`name_e`, `wps`.`ward_no`;";
    return $query;  
  }

  public function PrepareQuery31($role_id, $d_id, $b_id)
  {
    $admin = Auth::guard('admin')->user();
    $user_id = $admin->id;
    $condition = '';
    if ($role_id==1){
      $condition = "";
    }elseif($role_id==2){
      $condition = " Where  `pb`.`districts_id` in (select `district_id` from `user_district_assigns` where `user_id` = $user_id) ";
    }elseif($role_id==3){
      $condition = " Where `pb`.`blocks_id` in (select `block_id` from `user_block_assigns` where `user_id` = $user_id) ";
    }  
    if($b_id >0){
      $condition = " Where `pb`.`blocks_id` = $b_id ";
    }elseif($d_id >0){
      $condition = " Where  `pb`.`districts_id` = $d_id ";
    }

    $query = "select `bl`.`name_e` as `bl_name`, `vil`.`name_e` as `vil_name`, concat(`pb`.`booth_no`,`pb`.`booth_no_c`) as `boothno`, `pb`.`name_e`, `pb`.`name_l` from `polling_booths` `pb` inner join `blocks_mcs` `bl` on `bl`.`id` = `pb`.`blocks_id` left join `villages` `vil` on `vil`.`id` = `pb`.`village_id`  $condition Order By `bl`.`name_e`, `pb`.`booth_no`,`pb`.`booth_no_c`;";
    return $query;  
  }
  

  public function ReportGenerateExcel(Request $request)
  {
    // dd($request);
    // return $request;
    $d_id = 0;
    $b_id  =0;
    $p_id = 0;
    $w_id = 0;
    $ac_id   = 0;
    $ac_part_id = 0;
    $booth_id = 0;
    $zp_id = 0;
    $ps_id = 0;
    $report_type = 0;

    if(!empty($request->report_type_id)){
      $report_type = $request->report_type_id;
    }
    if($report_type == 0){
      $response=array();
      $response["status"]=0;
      $response["msg"]='Plz Select Report Type';
      return response()->json($response);  
    }
    
    if(!empty($request->district)){$d_id = $request->district;}
    if(!empty($request->block)){$b_id = $request->block;}
    if(!empty($request->village)){$p_id = $request->village;}
    if(!empty($request->ward_no)){$w_id = $request->ward_no;}
    if(!empty($request->assembly)){$ac_id = $request->assembly;}
    if(!empty($request->part_no)){$ac_part_id = $request->part_no;}
    if(!empty($request->booth)){$booth_id = $request->booth;}
    if(!empty($request->zp_ward)){$zp_id = $request->zp_ward;}
    if(!empty($request->ps_ward)){$ps_id = $request->ps_ward;}
    


    
    $user=Auth::guard('admin')->user();  
    $user_role = $user->role_id;
    $user_id = $user->id;


    if ($report_type == 5){
      $tcols = 5;
      $qcols = array(
        array('Block Name',25),
        array('Village Name (E)',25),
        array('Village Name (H)',25),
        array('Total Wards', 10),
        array('Zila Parishad Ward No.',15)
        ); 
      $query = $this->PrepareQuery5($user_role, $d_id, $b_id, $p_id);
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 9){
      $tcols = 6;
      $qcols = array(
        array('Block Name',24),
        array('Village Name',24),
        array('Male',13),
        array('Female',13),
        array('Other',13),
        array('Total',13)
        ); 
      $query = $this->PrepareQuery9($user_role, $d_id, $b_id, $p_id);
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 10){
      $tcols = 8;
      $qcols = array(
        array('Block Name',20),
        array('Village Name',20),
        array('Ward No.',10),
        array('PS Ward No.',10),
        array('Male',10),
        array('Female',10),
        array('Other',10),
        array('Total',10)
        ); 
      $query = $this->PrepareQuery10($user_role, $d_id, $b_id, $p_id);
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 15){
      $tcols = 6;
      $qcols = array(
        array('District',35),
        array('Zila Parishad Ward',25),
        array('Male',10),
        array('Female',10),
        array('Other',10),
        array('Total',10)
        ); 
      $query = $this->PrepareQuery15($user_role, $d_id);
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 20){
      $tcols = 4;
      $qcols = array(
        array('Part No.',25),
        array('From Sr. No.',25),
        array('To Sr. No.',25),
        array('Ward No.',25)
        ); 

      $rs_result=DB::select(DB::raw("call `up_prepare_asmb_part_srn_list_wardwise_report`($p_id);"));
      $query = 'select `partno`, `fromsrno`, `tosrno`, `wardno` from `voters_srno_detail_village` where `village_id` = '.$p_id. ' order by `partno`, `fromsrno`';
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 21){
      $tcols = 6;
      $qcols = array(
        array('Ac No.',15),
        array('Part No.',15),
        array('From Sr. No.',15),
        array('To Sr. No.',15),
        array('Ward No.',20),
        array('Booth No.',20)
        ); 

      $rs_result=DB::select(DB::raw("call `up_prepare_asmb_part_srn_list_wardwise__for_mc_report`($p_id);"));
      $query = 'select `acno`, `partno`, `fromsrno`, `tosrno`, `wardno`, `booth_no` from `voters_srno_detail_mc` where `village_id` = '.$p_id. ' order by `acno`, `partno`, `fromsrno`';
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 25){
      $tcols = 9;
      $qcols = array(
        array('Block Name',10),
        array('Village Name',10),
        array('Booth No.',10),
        array('Booth (E)',15),
        array('Booth (H)',15),
        array('Male',10),
        array('Female',10),
        array('Other',10),
        array('Total',10)
        ); 
      $query = $this->PrepareQuery25($user_role, $d_id, $b_id, $p_id, $w_id);
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 30){
      $tcols = 6;
      $qcols = array(
        array('Block Name',40),
        array('Ward No.',20),
        array('Male',10),
        array('Female',10),
        array('Other',10),
        array('Total',10)
        ); 
      $query = $this->PrepareQuery30($user_role, $d_id, $b_id);
      $rs_result=DB::select(DB::raw("$query"));
    }elseif ($report_type == 31){     //Booth List
      $tcols = 5;
      $qcols = array(
        array('Block Name',10),
        array('Panchayat Name',10),
        array('Booth No.',10),
        array('Name (E)',35),
        array('Name (H)',35)
        ); 
      $query = $this->PrepareQuery31($user_role, $d_id, $b_id);
      $rs_result=DB::select(DB::raw("$query"));
    }

    $response = array();
    $response['status'] = 1; 
    $response['data'] =view('admin.report.report.result_data',compact('rs_result', 'report_type', 'tcols', 'qcols'))->render();
    return response()->json($response); 
  }
  


  // public function ReportGeneratePDF(Request $request)
  // {
  //   ini_set('memory_limit','999M');
  //   ini_set("pcre.backtrack_limit", "100000000");
  
  //   $report_type = $request->report_type_id;
  //   $user=Auth::guard('admin')->user();  
  //   $report_header = "";
  //   if ($report_type == 5){
  //     $report_header = "Village List";
  //   }elseif ($report_type == 10){
  //     $report_header = "Ward Wise Voter Detail";
  //   }elseif ($report_type == 3){
  //     $report_header = "Village Wise Voter Detail";
  //   }


  //   $path=Storage_path('fonts/');
  //   $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
  //   $fontDirs = $defaultConfig['fontDir']; 
  //   $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
  //   $fontData = $defaultFontConfig['fontdata']; 
  //   $mpdf = new \Mpdf\Mpdf([
  //        'fontDir' => array_merge($fontDirs, [
  //            __DIR__ . $path,
  //        ]),
  //        'fontdata' => $fontData + [
  //            'frutiger' => [
  //                'R' => 'FreeSans.ttf',
  //                'I' => 'FreeSansOblique.ttf',
  //            ]
  //        ],
  //        'default_font' => 'freesans',
  //        'pagenumPrefix' => '',
  //       'pagenumSuffix' => '',
  //       'nbpgPrefix' => ' कुल ',
  //       'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
  //    ]);

  //   $html = view('admin.report.report.result_pdf',compact('report_header')); 
  //   $html = $html.$this->ReportGenerateExcel($request);
  //   // return $html;
  //   $mpdf->WriteHTML($html); 
  //   $mpdf->WriteHTML('</body></html>');
  //   $mpdf->Output();
  // }

  //----start----duplicateVoter----------------//
  public function duplicateVoter()
  {
    $states = DB::select(DB::raw("select * from `states`")); 
    return view('admin.report.duplicatevoter.index',compact('states'));
  }
  public function duplicateVoterCardNo(Request $request)
  {
    $voterCardno = DB::select(DB::raw("select `voter_card_no` from `voters` where `village_id` =$request->village_id and `status` in (0,1,3) group by `voter_card_no` having count(*) > 1 ;")); 
    return view('admin.report.duplicatevoter.card_no',compact('voterCardno'));
  }
  public function duplicateVoterTable(Request $request)
  {
    $rs_result = DB::select(DB::raw("select `vt`.`id`, `ac`.`code`, `ap`.`part_no`, `vt`.`sr_no`, `vt`.`name_e`, `vt`.`father_name_e`, `wv`.`ward_no`, `vt`.`print_sr_no`, `dl`.`description`, case `vt`.`status` when 0 then 'Old Data' when 1 then 'New' when 3 then 'Modified' else '' end as `voter_status` from `voters` `vt` inner join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` inner join `import_type` `dl` on `dl`.`id` = `vt`.`data_list_id` where `vt`.`status` in (0,1,3) and `vt`.`voter_card_no` = '$request->voter_card_no' and `vt`.`village_id` = $request->village_id order by `vt`.`data_list_id`;")); 
    return view('admin.report.duplicatevoter.table',compact('rs_result'));
  }
  public function duplicateVoterdelete($id)
  {
    $rs_result = DB::select(DB::raw("insert into `voters_backup_deleted` select * from `voters` where `id` = $id;")); 
    $rs_result = DB::select(DB::raw("delete from `voters` where `id` = $id;")); 
    
    $response=['status'=>1,'msg'=>'Delete Successfully']; 
    return response()->json($response);
  }

  //----End----duplicateVoter----------------//

  //----start----checkVoterStatus----------------//
  public function checkVoterStatus()
  {
    $states = DB::select(DB::raw("select * from `states`")); 
    return view('admin.report.checkVoterStatus.index',compact('states'));
  }
  public function checkVoterStatusSearch(Request $request)
  { 
    $rules=[
      'states' => 'required', 
      'district' => 'required', 
      'block' => 'required', 
      'village_id' => 'required', 
      'voter_card_no' => 'required', 
    ];

    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }
    $rs_result = DB::select(DB::raw("select `vt`.`id`, `ac`.`code`, `ap`.`part_no`, `vt`.`sr_no`, `vt`.`name_e`, `vt`.`father_name_e`, `wv`.`ward_no`, `vt`.`print_sr_no`, `dl`.`description`, case `vt`.`status` when 0 then 'Old Data' when 1 then 'New' when 3 then 'Modified' else '' end as `voter_status` from `voters` `vt` inner join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` inner join `assemblys` `ac` on `ac`.`id` = `vt`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` = `vt`.`assembly_part_id` inner join `import_type` `dl` on `dl`.`id` = `vt`.`data_list_id` where `vt`.`status` in (0,1,3) and `vt`.`voter_card_no` = '$request->voter_card_no' and `vt`.`village_id` = $request->village_id order by `vt`.`data_list_id`;"));
    $response = array();
    $response['status'] = 1; 
    $response['data'] =view('admin.report.checkVoterStatus.table',compact('rs_result'))->render();
    return response()->json($response); 
    
  } 
  //----End----checkVoterStatus----------------//

}


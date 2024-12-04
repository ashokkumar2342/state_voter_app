<?php

namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
  public static function removeSpacialChr($strValue)
  {
    $newString = trim(str_replace('\'', '', $strValue));
    $newString = trim(str_replace('\\', '', $newString));
    return $newString;
  }

  public static function OnlyEnglishChr($strValue)
  {
    $splChrPos = intval(strpos($strValue,"/"));
    if($splChrPos > 0){
      $newString = substr($strValue,0, $splChrPos);  
    }else{
      $newString = $strValue;
    }
    
    $newString = trim(str_replace('\'', '', $newString));
    $newString = trim(str_replace('\\', '', $newString));
    return $newString;
  }

  public function index()
  {
    try{
      $admin = Auth::guard('admin')->user();
      $role_id = $admin->role_id;

      if($role_id == 1){
        $dataTypes= DB::select(DB::raw("select * from `import_data_type` order by `id`"));
      }else{
        $dataTypes= DB::select(DB::raw("select * from `import_data_type` where `id` <> 1 order by `id`"));
      }
      return view('admin.import.index',compact('dataTypes'));
    } catch (Exception $e) {}
  }


  public function sampleHelpFile(Request $request)
  {
    try{
      $sampleHelpFiles= DB::select(DB::raw("select * from `import_data_type` where `id` = $request->id limit 1; ")); 
      return view('admin.import.sample_help_file',compact('sampleHelpFiles'));
    } catch (Exception $e) {}
  }

  public function showPreviousUpload(Request $request)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $data_type = $request->id;
      if ($data_type == 1){
        $SaveResult = DB::select(DB::raw("select * from `tmp_import_districts` where `userid` = $userid;"));
        return view('admin.import.district_import_data',compact('SaveResult'));
      }elseif ($data_type == 2){
        $SaveResult = DB::select(DB::raw("select * from `tmp_import_assembly` where `userid` = $userid;"));
        return view('admin.import.assembly_import_data',compact('SaveResult'));
      }elseif ($data_type == 3){
        $SaveResult = DB::select(DB::raw("select * from `tmp_import_blocks` where `userid` = $userid;"));
        return view('admin.import.block_import_data',compact('SaveResult'));
      }elseif ($data_type == 4){
        $SaveResult = DB::select(DB::raw("select * from `tmp_import_villages` where `userid` = $userid;"));
        return view('admin.import.village_import_data',compact('SaveResult'));
      }elseif ($data_type == 9){
        $SaveResult = DB::select(DB::raw("select * from `tmp_import_wardBandi_data` where `userid` = $userid;"));
        return view('admin.import.wardbandi_import_data',compact('SaveResult'));
      }elseif ($data_type == 10){
        $SaveResult = DB::select(DB::raw("select * from `tmp_import_polling_booth` where `userid` = $userid;"));
        return view('admin.import.polling_booth_import_data',compact('SaveResult'));
      }elseif ($data_type == 11){
        $SaveResult = DB::select(DB::raw("select * from `tmp_shift_voter_ward_booth` where `userid` = $userid;"));
        return view('admin.import.import_suppliment_data',compact('SaveResult'));
      }elseif ($data_type == 20){
        $SaveResult = DB::select(DB::raw("select * from `tmp_check_voters_detail`;"));
        return view('admin.import.check_voter_status_from_excel',compact('SaveResult'));
      }elseif ($data_type == 25){
        $SaveResult = DB::select(DB::raw("select * from `tmp_supplement_deleted_ac_part_srno_voters_detail` where `user_id` = $userid;"));
        return view('admin.import.delete_voter_ac_part_srno_from_excel',compact('SaveResult'));
      }

            
    } catch (Exception $e) {}
  }

  public function store(Request $request)
  {
    $rules=[
      'data_type' => 'required',
    ];

    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }

    try{
      $data_type = $request->data_type;
      if($request->hasFile('excel_file')){
        $path = $request->file('excel_file')->getRealPath();
        $excel_data = Excel::load($path, function($reader) {})->get();

        $response = array();
        $response['status'] = 1;
        $response['msg'] = 'Import Successfully';
        
        if ($data_type == 1){
          $response['data'] = $this->save_district_data($excel_data);
        }elseif ($data_type == 2){
          $response['data'] = $this->save_assembly_data($excel_data);
        }elseif ($data_type == 3){
          $response['data'] = $this->save_block_data($excel_data);
        }elseif ($data_type == 4){
          $response['data'] = $this->save_panchayat_data($excel_data);
        }elseif ($data_type == 9){
          $response['data'] = $this->save_wardbandi_data($excel_data);
        }elseif ($data_type == 10){
          $response['data'] = $this->save_polling_booth_data($excel_data);
        }elseif ($data_type == 11){
          $response['data'] = $this->save_suppliment_data($excel_data);
        }elseif ($data_type == 20){
          $response['data'] = $this->check_voters_detail($excel_data);
        }elseif ($data_type == 25){
          $response['data'] = $this->delete_voters_ac_part_srnos($excel_data);
        }



        return response()->json($response);
      }
      $response=['status'=>0,'msg'=>'File Not Select'];
      return response()->json($response);      
    } catch (Exception $e) {}   
  }


  public function delete_voters_ac_part_srnos($exceldata)
  {
    try{
      
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_supplement_deleted_ac_part_srno_voters_detail` where `user_id` = $userid;"));

      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $dist_code = $this->removeSpacialChr($rowData->dist_code);
          $block_code = $this->removeSpacialChr($rowData->block_code);
          $village_code = $this->removeSpacialChr($rowData->village_code);
          $ac_no = $this->removeSpacialChr($rowData->ac_no);
          $part_no = $this->removeSpacialChr($rowData->part_no);
          $from_sr_no = intval($this->removeSpacialChr($rowData->from_sr_no));
          $to_sr_no = intval($this->removeSpacialChr($rowData->to_sr_no));
          $data_list = intval($this->removeSpacialChr($rowData->data_list));
          

          $SaveResult = DB::select(DB::raw("call `up_delete_voters_ac_part_srno_excel` ('$userid', '$dist_code','$block_code', '$village_code', '$ac_no','$part_no','$from_sr_no', '$to_sr_no', '$data_list');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_supplement_deleted_ac_part_srno_voters_detail` where `user_id` = $userid;"));
      return view('admin.import.delete_voter_ac_part_srno_from_excel',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }


  public function check_voters_detail($exceldata)
  {
    try{
      
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("truncate table `tmp_check_voters_detail`;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $srno = $this->removeSpacialChr($rowData->srno);
          $regisno = $this->removeSpacialChr($rowData->regisno);
          $age = $this->removeSpacialChr($rowData->age);
          $sex = $this->removeSpacialChr($rowData->sex);
          $dec_name = $this->OnlyEnglishChr($rowData->dec_name);
          $dec_fname = $this->OnlyEnglishChr($rowData->dec_fname);
          $dec_mname = $this->OnlyEnglishChr($rowData->dec_mname);
          $dec_spouse = $this->OnlyEnglishChr($rowData->dec_spouse);
          

          $SaveResult = DB::select(DB::raw("call `up_check_vater_status_from_excel` ('$srno', '$regisno','$age', '$sex', '$dec_name','$dec_fname','$dec_mname', '$dec_spouse');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_check_voters_detail`;"));
      return view('admin.import.check_voter_status_from_excel',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }

  public function save_suppliment_data($exceldata)
  {
    try{
      
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_shift_voter_ward_booth` where `userid` = $userid;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $s_code = trim(str_replace('\'', '', $rowData->state_code));
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $b_code = trim(str_replace('\'', '', $rowData->block_code));
          $v_code = trim(str_replace('\'', '', $rowData->panchayat_code));

          $from_ward = trim(str_replace('\'', '', $rowData->from_ward));
          $from_booth = trim(str_replace('\'', '', $rowData->from_booth));
          $from_srn = trim(str_replace('\'', '', $rowData->from_sr_no));
          $to_srn = trim(str_replace('\'', '', $rowData->to_sr_no));
          $to_ward = trim(str_replace('\'', '', $rowData->to_ward));
          $to_booth = trim(str_replace('\'', '', $rowData->to_booth));

          if($from_booth == 'null'){
            $from_booth = '';
          }
          if($to_booth == 'null'){
            $to_booth = '';
          }

          // dd($to_booth);
          // dd("call `up_change_voters_wards_excel` ('".$userid."', '".$s_code."','".$d_code."', '".$b_code."', '".$v_code."','".$from_ward."','".$from_booth."', '".$to_ward."', '".$to_booth."', '".$from_srn."', '".$to_srn."');");
          $SaveResult = DB::select(DB::raw("call `up_change_voters_wards_excel` ('$userid', '$s_code','$d_code', '$b_code', '$v_code','$from_ward','$from_booth', '$to_ward', '$to_booth', '$from_srn', '$to_srn');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_shift_voter_ward_booth` where `userid` = $userid;"));
      return view('admin.import.import_suppliment_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }


  public function save_polling_booth_data($exceldata)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_import_polling_booth` where `userid` = $userid;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $s_code = trim(str_replace('\'', '', $rowData->state_code));
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $b_code = trim(str_replace('\'', '', $rowData->block_code));
          $v_code = trim(str_replace('\'', '', $rowData->panchayat_code));
          $booth_no = trim(str_replace('\'', '', $rowData->booth_no));
          $name_e = trim(str_replace('\'', '', $rowData->name_e));
          $name_l = trim(str_replace('\'', '', $rowData->name_h));
          $aux_name = trim(str_replace('\'', '', $rowData->aux));

          $SaveResult = DB::select(DB::raw("call `up_import_polling_booth` ('$userid', '$s_code','$d_code', '$b_code', '$v_code','$booth_no','$name_e', '$name_l', '$aux_name');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_import_polling_booth` where `userid` = $userid;"));
      return view('admin.import.polling_booth_import_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }

  public function save_wardbandi_data($exceldata)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_import_wardBandi_data` where `userid` = $userid;"));
      // dd($exceldata);
      foreach ($exceldata as $key => $excelRows) {
        // dd($excelRows);
        foreach ($excelRows as $key => $rowData) {
          // dd($rowData);
          $ac_code = trim(str_replace('\'', '', $rowData->ac_no));
          $part_no = trim(str_replace('\'', '', $rowData->part_no));
          $from_srn = trim(str_replace('\'', '', $rowData->from_srn));
          $to_srn = trim(str_replace('\'', '', $rowData->to_srn));
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $b_code = trim(str_replace('\'', '', $rowData->block_code));
          $v_code = trim(str_replace('\'', '', $rowData->panchayat_code));
          $ward_no = trim(str_replace('\'', '', $rowData->ward_no));
          $booth_no = trim(str_replace('\'', '', $rowData->booth_no));
          $data_list_id = trim(str_replace('\'', '', $rowData->data_list));

          // dd("call `up_import_wardbandi_booth` ('".$userid."', '".$ac_code."','".$part_no."', '".$from_srn."', '".$to_srn."','".$d_code."','".$b_code."', '".$v_code."', '".$ward_no."', '".$booth_no."', 1, ".$data_list_id.");");
          $SaveResult = DB::select(DB::raw("call `up_import_wardbandi_booth` ('$userid', '$ac_code','$part_no', '$from_srn', '$to_srn','$d_code','$b_code', '$v_code', '$ward_no', '$booth_no', 1, $data_list_id);"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_import_wardBandi_data` where `userid` = $userid;"));
      return view('admin.import.wardbandi_import_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }


  public function save_panchayat_data($exceldata)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_import_villages` where `userid` = $userid;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $b_code = trim(str_replace('\'', '', $rowData->block_code));
          $p_code = trim(str_replace('\'', '', $rowData->panchayat_code));
          $name_e = trim(str_replace('\'', '', $rowData->name_e));
          $name_l = trim(str_replace('\'', '', $rowData->name_h));
          $p_wards = trim(str_replace('\'', '', $rowData->panchayat_wards));


          $SaveResult = DB::select(DB::raw("call `up_create_village_excel` ('$userid', '$d_code','$b_code', '$p_code', '$name_e','$name_l','$p_wards');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_import_villages` where `userid` = $userid;"));
      return view('admin.import.village_import_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }


  public function save_block_data($exceldata)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_import_blocks` where `userid` = $userid;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $b_code = trim(str_replace('\'', '', $rowData->block_code));
          $name_e = trim(str_replace('\'', '', $rowData->name_e));
          $name_l = trim(str_replace('\'', '', $rowData->name_h));
          $block_type = trim(str_replace('\'', '', $rowData->block_type));
          $ps_wards = trim(str_replace('\'', '', $rowData->ps_wards));


          $SaveResult = DB::select(DB::raw("call `up_create_block_excel` ('$userid', '$d_code','$b_code','$name_e','$name_l','$ps_wards', '$block_type');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_import_blocks` where `userid` = $userid;"));
      return view('admin.import.block_import_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }

  public function save_assembly_data($exceldata)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_import_assembly` where `userid` = $userid;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $a_code = trim(str_replace('\'', '', $rowData->ac_code));
          $name_e = trim(str_replace('\'', '', $rowData->name_e));
          $name_l = trim(str_replace('\'', '', $rowData->name_h));
          $booths = trim(str_replace('\'', '', $rowData->booths));


          $SaveResult = DB::select(DB::raw("call `up_create_assembly_excel` ('$userid', '$d_code','$a_code','$name_e','$name_l','$booths');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_import_assembly` where `userid` = $userid;"));
      return view('admin.import.assembly_import_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }


  public function save_district_data($exceldata)
  {
    try{
      $admin = Auth::guard('admin')->user();
      $userid = $admin->id;

      $SaveResult=DB::select(DB::raw("delete from `tmp_import_districts` where `userid` = $userid;"));
      
      foreach ($exceldata as $key => $excelRows) {
        foreach ($excelRows as $key => $rowData) {
          $s_code = trim(str_replace('\'', '', $rowData->state_code));
          $d_code = trim(str_replace('\'', '', $rowData->district_code));
          $name_e = trim(str_replace('\'', '', $rowData->name_e));
          $name_l = trim(str_replace('\'', '', $rowData->name_h));
          $zpward = trim(str_replace('\'', '', $rowData->zp_wards));


          $SaveResult = DB::select(DB::raw("call `up_create_district_excel` ('$userid', '$s_code','$d_code','$name_e','$name_l','$zpward');"));
        }
      }

      $SaveResult = DB::select(DB::raw("select * from `tmp_import_districts` where `userid` = $userid;"));
      return view('admin.import.district_import_data',compact('SaveResult'))->render();

    } catch (Exception $e) {}
  }

   // public function importVote(Request $request)
   // {
   //   if($request->hasFile('import_file')){  
   //      $path = $request->file('import_file')->getRealPath();
   //      $results = Excel::load($path, function($reader) {})->get(); 
   //      foreach ($results as $key => $value) {
   //        if (!empty($value->part_no)) { 
   //          $saveVote= DB::select(DB::raw("call up_import_wardbandi_booth ('$value->ac_no','$value->part_no','$value->from_sr_no','$value->to_sr_no','$value->district_code','$value->block_code','$value->village_code','$value->ward_no','$value->booth_no')"));
   //        }
   //      } 
   //      $response=['status'=>1,'msg'=>'Import Successfully'];
   //          return response()->json($response);
   //    }
   //      $response=['status'=>0,'msg'=>'File Not Select'];
   //          return response()->json($response); 
   // }





   // public function DistrictExportSample($value='')
   // {
   //  $user=Auth::guard('admin')->user(); 
   //  $Districts= DB::select(DB::raw("call up_fetch_import_district_sample ('$user->id')"));
   //  return view('admin.import.district_sample',compact('Districts'));  
   // }
   // public function DistrictImportForm($value='')
   // { 
   // 	 return view('admin.import.district_import_form'); 
   // }
   // public function DistrictImportStore(Request $request)
   // {
   // 	 if($request->hasFile('import_file')){  
   //      $path = $request->file('import_file')->getRealPath();
   //      $results = Excel::load($path, function($reader) {})->get();
   //      $user = Auth::guard('admin')->user();
   //      $tmp_import_districts=TmpImportDistrict::where('userid',$user->id)->pluck('userid')->toArray();
   //      $Old_tmp_import_districts=TmpImportDistrict::whereIn('userid',$tmp_import_districts)->delete();
   //     foreach ($results as $key => $value) {         
   //           if (!empty($value->state_id)) {
   //           $SaveResult=DB::select(DB::raw("call up_create_district_excel ('$user->id','$value->state_id','$value->district_code','$value->district_name_eng','$value->district_name_hindi','$value->total_zp_wards')"));      
   //          } 
   //      }
   //      $disImportedDatas=TmpImportDistrict::all();
   //      $response = array();
   //      $response['status'] = 1;
   //      // $response['msg'] = 'Import Successfully';
   //      $response['data'] =view('admin.import.district_import_data',compact('disImportedDatas'))->render();
   //      return response()->json($response);  
   //    }
      
   //   $response=['status'=>0,'msg'=>'File Not Select'];
   //          return response()->json($response);  
   // }
   // public function AssemblyExportSample()
   // {
   //  $user=Auth::guard('admin')->user();  
   //  $assemblys= DB::select(DB::raw("call up_fetch_import_assembly_sample ('$user->id')"));
   //  return view('admin.import.assembly_sample',compact('assemblys'));  
   // }
   // public function AssemblyImportForm($value='')
   // {
   // 	 return view('admin.import.assembly_import_form');
   // }
   // public function AssemblyImportStore(Request $request)
   // {
   // 	 if($request->hasFile('import_file')){  
   //      $path = $request->file('import_file')->getRealPath();
   //      $results = Excel::load($path, function($reader) {})->get();
   //      $user = Auth::guard('admin')->user();
   //      $TmpImportAssembly=TmpImportAssembly::where('userid',$user->id)->pluck('userid')->toArray();
   //      $Old_TmpImportAssembly=TmpImportAssembly::whereIn('userid',$TmpImportAssembly)->delete();
   //     foreach ($results as $key => $value) {    
   //           if (!empty($value->district_id)) {
   //           $SaveResult=DB::select(DB::raw("call up_create_assembly_excel ('$user->id','$value->district_id','$value->assembly_code','$value->assembly_name_eng','$value->assembly_name_hindi','$value->total_parts')"));      
   //          } 
   //      }
   //      $AssImportedDatas=TmpImportAssembly::all();
   //      $response = array();
   //      $response['status'] = 1;
   //      $response['data'] =view('admin.import.assembly_import_data',compact('AssImportedDatas'))->render();
   //      return response()->json($response);  
   //    }

   //   $response=['status'=>0,'msg'=>'File Not Select'];
   //          return response()->json($response);  
   // }
   // public function BlockExportSample()
   // {
   //  $user=Auth::guard('admin')->user();  
   //  $blocks= DB::select(DB::raw("call up_fetch_import_assembly_sample ('$user->id')"));
   //  return view('admin.import.block_sample',compact('blocks'));  
   // }
   // public function BlockImportForm($value='')
   // {
   //   return view('admin.import.block_import_form');
   // }
   // public function BlockImportStore(Request $request)
   // {
   //   if($request->hasFile('import_file')){  
   //      $path = $request->file('import_file')->getRealPath();
   //      $results = Excel::load($path, function($reader) {})->get();
   //      $user = Auth::guard('admin')->user();
   //      $TmpImportBlock=TmpImportBlock::where('userid',$user->id)->pluck('userid')->toArray();
   //      $Old_TmpImportBlock=TmpImportBlock::whereIn('userid',$TmpImportBlock)->delete();
   //     foreach ($results as $key => $value) {    
   //           if (!empty($value->district_id)) {
   //           $SaveResult=DB::select(DB::raw("call up_create_block_excel ('$user->id','0','$value->district_id','$value->block_code','$value->block_name_eng','$value->block_name_hindi','$value->total_wards','$value->block_mc_type_id')"));      
   //          } 
   //      }
   //      $BloImportedDatas=TmpImportBlock::all();
   //      $response = array();
   //      $response['status'] = 1;
   //      $response['data'] =view('admin.import.block_import_data',compact('BloImportedDatas'))->render();
   //      return response()->json($response);  
   //    }

   //   $response=['status'=>0,'msg'=>'File Not Select'];
   //          return response()->json($response);  
   // }
   // public function VillageExportSample()
   // {
   //  $user=Auth::guard('admin')->user();  
   //  $villages= DB::select(DB::raw("call up_fetch_import_village_sample ('$user->id')"));
   //  return view('admin.import.village_sample',compact('villages'));  
   // }
   // public function VillageImportForm($value='')
   // {
   // 	 return view('admin.import.village_import_form');
   // }
   // public function VillageImportStore(Request $request)
   // {
   // 	 if($request->hasFile('import_file')){  
   //      $path = $request->file('import_file')->getRealPath();
   //      $results = Excel::load($path, function($reader) {})->get();
   //      $user = Auth::guard('admin')->user();
   //      $TmpImportVillage=TmpImportVillage::where('userid',$user->id)->pluck('userid')->toArray();
   //      $Old_TmpImportVillage=TmpImportVillage::whereIn('userid',$TmpImportVillage)->delete();
   //     foreach ($results as $key => $value) {    
   //           if (!empty($value->district_id)) {
   //           $SaveResult=DB::select(DB::raw("call up_create_village_excel ('$user->id','$value->state_id','$value->district_id','$value->block_id','$value->village_code','$value->village_name_eng','$value->village_name_hindi','$value->total_wards')"));      
   //          } 
   //      }
   //      $VillImportedDatas=TmpImportVillage::all();
   //      $response = array();
   //      $response['status'] = 1;
   //      $response['data'] =view('admin.import.village_import_data',compact('VillImportedDatas'))->render();
   //      return response()->json($response);  
   //    }

   //   $response=['status'=>0,'msg'=>'File Not Select'];
   //          return response()->json($response);  
   // }

   // public function VillageWardExportSample()
   // {
   //  $user=Auth::guard('admin')->user();  
   //  $villagewards=DB::select(DB::raw("call up_fetch_import_map_wards_sample ('$user->id')"));
   //  return view('admin.import.village_ward_sample',compact('villagewards'));  
   // }
   // public function VillageWardImportForm($value='')
   // {
   //   return view('admin.import.village_ward_form');
   // }
   // public function VillageWardImportStore(Request $request)
   // {
   //   if($request->hasFile('import_file')){  
   //      $path = $request->file('import_file')->getRealPath();
   //      $results = Excel::load($path, function($reader) {})->get();
   //      $user = Auth::guard('admin')->user();
   //      $TmpImportMapVillageWard=TmpImportMapVillageWard::where('userid',$user->id)->pluck('userid')->toArray();
   //      $Old_TmpImportMapVillageWard=TmpImportMapVillageWard::whereIn('userid',$TmpImportMapVillageWard)->delete();
   //     foreach ($results as $key => $value) {
   //        if(empty($value->village_id)){       
   //         $SaveResult=DB::select(DB::raw("call up_imp_map_village_wards_excel ('$user->id','$value->state_id','$value->district_id','$value->block_id','$value->village_id','$value->total_wards','$value->zp_ward_no','$value->ps_ward_no')"));      
   //         } 
   //      }
   //      $villageSamples=TmpImportMapVillageWard::all();
   //      $response = array();
   //      $response['status'] = 1;
   //      $response['data'] =view('admin.import.village_ward_data',compact('villageSamples'))->render();
   //      return response()->json($response);  
   //    }

   //   $response=['status'=>0,'msg'=>'File Not Select'];
   //          return response()->json($response);  
   // }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Admin;
use App\Helper\MyFuncs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Auth;

class DatabaseConnectionController extends Controller
{

  public function DatabaseConnection()
  {
    try {
      $serverName=getenv('DB_HOST_2');
      $database=getenv('DB_DATABASE_2');
      $username=getenv('DB_USERNAME_2');
      $passward=getenv('DB_PASSWORD_2'); 
      // $serverName = "a";
      return view('admin.DatabaseConnection.form',compact('serverName','database','username','passward'));    
    } catch (Exception $e) {
      return $e; 
    }
  }

  public function ConnectionStore(Request $request)
  {
    try {   
        
      $this->changeEnvironmentVariable('DB_HOST_2',$request->ip);
      $this->changeEnvironmentVariable('DB_DATABASE_2',$request->database);
      $this->changeEnvironmentVariable('DB_USERNAME_2',$request->user_name);
      $this->changeEnvironmentVariable('DB_PASSWORD_2',$request->password);
          
 
      // \Artisan::call('config:cache');
      // \Artisan::call('config:clear');
      \Artisan::call('cache:clear'); 
        
      $response=['status'=>1,'msg'=>'Connection Setting Saved Successfully'];
      return response()->json($response);
    } catch (Exception $e) { } 
  }
  
  public static function changeEnvironmentVariable($key,$value)
  {
    $path = base_path('.env');

    if(is_bool(env($key))){$old = env($key)? 'true' : 'false';}
    elseif(env($key)===null){$old = 'null';}
    else{$old = env($key);}

    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            "$key=".$old, "$key=".$value, file_get_contents($path)
        ));
    }
  } 

public function getTable()
{
  try{
    $admin = Auth::guard('admin')->user();
    $userid = $admin->id;  
    $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));
    
    return view('admin.DatabaseConnection.table',compact('Districts'));
  } catch (Exception $e) {}
}

public function assemblyWisePartNo(Request $request)
{ 
  $ac_id = 0; 
  if(!empty($request->id)){
    $ac_id = $request->id;
  }
  try{
    // $partnos=DB::select(DB::raw("select `ap`.`id`, `ap`.`part_no`, `ap`.`assembly_id`, count(`v`.`id`) as `rtotal` from `assembly_parts` `ap` Left Join `voters` `v` on `v`.`assembly_part_id` = `ap`.`id` Where `ap`.`assembly_id` = $ac_id Group by `ap`.`id`, `ap`.`part_no`, `ap`.`assembly_id` Order By `ap`.`part_no`")); 
    $data_import_detail = DB::select(DB::raw("select * from `import_type` where `status` = 1 limit 1;"));
    $data_import_id = $data_import_detail[0]->id;
    $partnos=DB::select(DB::raw("select `ap`.`id`, `ap`.`part_no`, `ap`.`assembly_id`, (select count(*) from `voters` where `assembly_part_id` = `ap`.`id` and `data_list_id` = $data_import_id) as `rtotal` from `assembly_parts` `ap` Where `ap`.`assembly_id` = $ac_id Order By `ap`.`part_no`;")); 
    return view('admin.DatabaseConnection.part_no_value',compact('partnos')); 
  } catch (Exception $e) {}
} 

public function tableRecordStore(Request $request)
{  
  // $this->auto_unlock_lock();
  // $response=['status'=>1,'msg'=>'Request Submit Successfully'];
  // return response()->json($response);


  $assembly=DB::select(DB::raw("select * from `assemblys` Where `id` = $request->ac_code limit 1"));
  // dd($request->part_no);
  foreach ($request->part_no as $key => $part_no) {
  \Artisan::queue('data:transfer',['district_id'=>$request->district_id, 'ac_code'=>$assembly[0]->code,'part_no'=>$part_no]); 
  }
  
  // \Artisan::call('queue:work');
  $response=['status'=>1,'msg'=>'Request Submit Successfully'];
  return response()->json($response);
} 

public function processDelete($ac_id,$part_id)
{  
  $rs_update = DB::select(DB::raw("call up_delete_part_port_voter ('$ac_id','$part_id')"));
  return redirect()->back()->with(['message'=>'Record Deleted Successfully','class'=>'success']);
}  





//--------------End-------------------------
    
  
//Code to unloac and lock villages having from srmo and to srno not correct
  // public function auto_unlock_lock() 
  // {
  //   $rs_villages = DB::select(DB::raw("select distinct `wv`.`village_id` from `main_page_detail` `mpd` inner join `ward_villages` `wv` on `wv`.`id` = `mpd`.`ward_id` inner join `blocks_mcs` `bl` on `bl`.`id` = `wv`.`blocks_id` where `mpd`.`to_sr_no` <> `mpd`.`total` and `bl`.`block_mc_type_id` = 1 limit 50;"));
    
  //   foreach ($rs_villages as $key => $val_village){
  //     $village_id = $val_village->village_id;
  //     $rs_vil_detail = DB::select(DB::raw("select * from `villages` where `id` = $village_id limit 1;"));
  //     $d_id = $rs_vil_detail[0]->districts_id;
  //     $b_id = $rs_vil_detail[0]->blocks_id;

  //     $rs_update = DB::select(DB::raw("call `up_unlock_village_voterlist` ('$village_id');"));
      
  //     $rs_update = DB::select(DB::raw("call `up_process_village_voterlist` ('$village_id', 0, 1);"));

  //     if ($rs_update[0]->save_status==1){
  //       \Artisan::queue('voterlist:generate',['district_id'=>$d_id,'block_id'=>$b_id,'village_id'=>$village_id,'ward_id'=>0,'booth_id'=>0]);  
  //     }
  //   }
    
  // }  


  //Code to unlock and lock villages having Main page detail not created
  public function auto_unlock_lock() 
  {
    $rs_villages = DB::select(DB::raw("select * from `voter_list_processeds` where `ward_id` = 0 and `submit_date` = '2022-06-13';"));
    
    foreach ($rs_villages as $key => $val_village){
      $village_id = $val_village->village_id;

      $rs_result = DB::select(DB::raw("select count(*) as `tcount` from `main_page_detail` `mpd` inner join `ward_villages` `wl` on `wl`.`id` = `mpd`.`ward_id` where `wl`.`village_id` = $village_id;"));
      if($rs_result[0]->tcount == 0){
        $rs_vil_detail = DB::select(DB::raw("select * from `villages` where `id` = $village_id limit 1;"));
        $d_id = $rs_vil_detail[0]->districts_id;
        $b_id = $rs_vil_detail[0]->blocks_id;

        $rs_update = DB::select(DB::raw("call `up_unlock_village_voterlist` ('$village_id');"));
        
        $rs_update = DB::select(DB::raw("call `up_process_village_voterlist` ('$village_id', 0, 1);"));

        if ($rs_update[0]->save_status==1){
          \Artisan::queue('voterlist:generate',['district_id'=>$d_id,'block_id'=>$b_id,'village_id'=>$village_id,'ward_id'=>0,'booth_id'=>0]);  
        }
      }
    }
    
  }

  
  
  
      
     
    
  public function MysqlDataTransfer()
  {
    $admin = Auth::guard('admin')->user();
    $userid = $admin->id;  
    $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));

    return view('admin.DatabaseConnection.mysqldatatransfer.index',compact('Districts'));      
  }


  // public function MysqlDataTransferDistrictWiseBlock(Request $request)
  // {
  //   $blocks=BlocksMc::where('districts_id',$request->id)->orderBy('name_e','ASC')->get();
  //   return view('admin.DatabaseConnection.mysqldatatransfer.block_select_box',compact('blocks'));     
  // }

  //  public function MysqlDataTransferBlockWiseVillage(Request $request)
  //  {
  //    $villages=Village::where('blocks_id',$request->id)->orderBy('name_e','ASC')->get();
  //    return view('admin.DatabaseConnection.mysqldatatransfer.village_select_box',compact('villages'));     
  //  }
  //  public function MysqlDataTransferVillageWiseWard(Request $request)
  //  {
  //    $wards=WardVillage::where('village_id',$request->id)->orderBy('ward_no','ASC')->get();
  //    return view('admin.DatabaseConnection.mysqldatatransfer.ward_select_box',compact('wards'));     
  //  }

  public function MysqlDataTransferStore(Request $request)
  {   
    \Artisan::queue('mysqldata:transfer',['district_id'=>$request->district_id,'block_id'=>$request->block_id,'village_id'=>$request->village_id,'ward_id'=>$request->ward_id]);

    $response=['status'=>1,'msg'=>'Submit Successfully'];
    return response()->json($response);
  } 


       
}

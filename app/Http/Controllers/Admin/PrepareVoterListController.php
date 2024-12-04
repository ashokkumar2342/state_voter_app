<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Imagick;
use PDF;
use TCPDF;

class PrepareVoterListController extends Controller
{
  public function PrepareVoterListGenerate(Request $request)
  {   
    $rules=[            
      'district' => 'required', 
      'block' => 'required', 
      'village' => 'required',            
      'ward' => 'required',            
      'booth' => 'required',
      'list_prepare_option' => 'required',
      'list_sorting_option' => 'required',           
    ];

    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }

    $full_supplement = $request->list_prepare_option;
    $sorting_order = $request->list_sorting_option;

    // \Artisan::queue('voterlist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward,'booth_id'=>$request->booth]);
    // $response=['status'=>1,'msg'=>'Submitted'];
    // return response()->json($response);

    if ($request->ward==0) {  //Process for Panchayat
      $rs_update = DB::select(DB::raw("call `up_process_village_voterlist` ('$request->village', $full_supplement, $sorting_order);"));
      
    }elseif ($request->booth == 0){ //Process For MC Ward
      
      $rs_update= DB::select(DB::raw("call `up_process_voterlist` ('$request->ward', 1, 2, $full_supplement, $sorting_order)")); 
      
    }else{                        //Process For MC Ward Booth
      
      $rs_update= DB::select(DB::raw("call `up_process_voterlist_booth` ('$request->ward', '$request->booth', 0, $full_supplement, $sorting_order)")); 
    } 

    // $rs_queue_invoke = DB::select(DB::raw("select `uf_invoke_artisan`() as `queue_invoke`;")); 
    // if($rs_queue_invoke[0]->queue_invoke == 1){
    //   \Artisan::call('queue:work' , ['--tries' => 1, '--timeout' => 2000]);  
    // }
    

    if ($rs_update[0]->save_status==1){
      \Artisan::queue('voterlist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward,'booth_id'=>$request->booth]);  
    }
      
    $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
    return response()->json($response);
  }



  public function UnlockVoterList($value='')
  {
    try{
        $admin = Auth::guard('admin')->user(); 
        $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
        return view('admin.master.PrepareVoterList.UnlockVoterList.index',compact('Districts'));     
    } catch (Exception $e) {}
    
  }

  public function UnlockVoterListMc()
  {
    try{
      $admin = Auth::guard('admin')->user(); 
      $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
        
      return view('admin.master.PrepareVoterList.UnlockVoterList.unlock_mc',compact('Districts'));  
    } catch (Exception $e) {}   
  }

  public function UnlockVoterListBooth()
  {
    try{
        $admin = Auth::guard('admin')->user(); 
        $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
      return view('admin.master.PrepareVoterList.UnlockVoterList.unlock_booth',compact('Districts'));  
    } catch (Exception $e) {}   
  }


  public function unlockVoterListUnlock(Request $request)
  {
    $rules=[            
      'district' => 'required', 
      'block' => 'required', 
      'village' => 'required',            
      'ward' => 'required',            
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

    
    if ($request->ward==0) {  //Unlock for Panchayat
      $rs_update = DB::select(DB::raw("call `up_unlock_village_voterlist` ('$request->village');"));
      
    }elseif ($request->booth == 0){ //Process For MC Ward
      $rs_update= DB::select(DB::raw("call `up_unlock_voterlist` ('$request->ward')")); 
      
    }else{                        //Process For MC Ward Booth
      $rs_update= DB::select(DB::raw("call `up_unlock_voterlist_booth` ('$request->ward', '$request->booth')")); 
    } 

    $response=['status'=>1,'msg'=>'Unlock Sccessfully'];
    return response()->json($response);
  }

  public function checkPhotoQuality()
  {
    $admin = Auth::guard('admin')->user();
    $assemblys= DB::select(DB::raw("select * from `assemblys` Order By `id`"));  
    return view('admin.checkPhotoQuality.index',compact('assemblys'));
  }
  public function checkPhotoQualityAllAC()
  {
    $admin = Auth::guard('admin')->user();
    $userid = $admin->id;  
    $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));  
    return view('admin.checkPhotoQuality.all_ac',compact('Districts'));
  }

  public function checkPhotoQualityAsmbStore(Request $request)
  {
    $rules=[            
      'assembly' => 'required',
    ]; 
    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }                 
    
    foreach ($request->part_no as $key => $part_no) {
      \Artisan::queue('check:photoquality',['assembly'=>$request->assembly,'part_no'=>$part_no,'from_sr_no'=>0,'to_sr_no'=>0]); 
    } 
    $response=['status'=>1,'msg'=>'Submit Successfully'];
    return response()->json($response);
  } 

  public function checkPhotoQualityStore(Request $request)
  {
    $rules=[            
      'assembly' => 'required', 
      'part_no' => 'required', 
      'from_sr_no' => 'required', 
      'to_sr_no' => 'required', 
    ]; 
    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }                 //Process For MC Ward Booth
    // $rs_update= DB::select(DB::raw("call `up_process_voterlist_booth` ('$request->ward', '$request->booth', 1)")); 
    \Artisan::queue('check:photoquality',['assembly'=>$request->assembly,'part_no'=>$request->part_no,'from_sr_no'=>$request->from_sr_no,'to_sr_no'=>$request->to_sr_no]); 
    $response=['status'=>1,'msg'=>'Submit Successfully'];
    return response()->json($response);
  }

  public function prepareVoterListSupplimentDatalistwise()
  {
    try{
      $admin = Auth::guard('admin')->user(); 
      $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
      return view('admin.master.PrepareVoterList.supplimentDatalistwise.index',compact('Districts'));     
    } catch (Exception $e) {}
  }

  public function prepareVoterListSupplimentDatalistwiseStore(Request $request)
  {   
    $rules=[            
      'district' => 'required', 
      'block' => 'required', 
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

    // $rs_update= DB::select(DB::raw("call `up_process_spcl_suple_voterlist_ward` ('$request->ward')")); 
    

    // \Artisan::queue('datalist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward,'booth_id'=>0]);
      
    // $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
    // return response()->json($response);
  }

  //-------------booth-wise------------------------

  public function prepareVoterListSupplimentDatalistBoothwise()
  {
    try{
      $admin = Auth::guard('admin')->user(); 
      $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
      return view('admin.master.PrepareVoterList.supplimentDatalistwise.index_booth',compact('Districts'));     
    } catch (Exception $e) {}
  }
  public function prepareVoterListSupplimentDatalistwiseBoothStore(Request $request)
  {   
    $rules=[            
      'district' => 'required', 
      'block' => 'required', 
      'village' => 'required',            
      'ward' => 'required',            
      'booth' => 'required',            
    ];
    // return($request);
    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    } 

    // $rs_update= DB::select(DB::raw("call `up_process_spcl_suple_voterlist_booth` ('$request->ward', $request->booth)")); 
    

    // \Artisan::queue('datalist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward,'booth_id'=>$request->booth]);
      
    // $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
    // return response()->json($response);
  } 
}

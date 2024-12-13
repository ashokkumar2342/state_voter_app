<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use PDF;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;

class PrepareVoterListController extends Controller
{
  protected $e_controller = "PrepareVoterListController";

  public function PrepareVoterListGenerate(Request $request)
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(83);
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
        'list_prepare_option' => 'required',
        'list_sorting_option' => 'required',           
      ];

      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select MC\'s',
        'village.required'=> 'Please Select MC\'s',
        'ward.required'=> 'Please Select Ward',
        'booth.required'=> 'Please Select Booth',
        'list_prepare_option.required'=> 'Please Select List Prepare Option',
        'list_sorting_option.required'=> 'Please Select List Sorting Option',
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

      $full_supplement = intval(Crypt::decrypt($request->list_prepare_option));
      $sorting_order = intval(Crypt::decrypt($request->list_sorting_option));

      if ($ward_id == 0) { //Process for Panchayat
        $rs_update = DB::select(DB::raw("call `up_process_village_voterlist` ($village_id, $full_supplement, $sorting_order);"));
      
      }elseif ($booth_id == 0){ //Process For MC Ward
        $rs_update= DB::select(DB::raw("call `up_process_voterlist` ($ward_id, 1, 2, $full_supplement, $sorting_order)"));
      
      }else{//Process For MC Ward Booth
        $rs_update= DB::select(DB::raw("call `up_process_voterlist_booth` ($ward_id, $booth_id, 1, $full_supplement, $sorting_order)")); 
      }
      if ($rs_update[0]->save_status == 1){
        MyFuncs::startVoterListGenerateQueue();

        // \Artisan::queue('voterlist:generate',['district_id'=>$district_id, 'block_id'=>$block_id, 'village_id'=>$village_id, 'ward_id'=>$ward_id, 'booth_id'=>$booth_id]);
      }      
      $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
      return response()->json($response);
    } catch (\Exception $e) {
      $e_method = "PrepareVoterListGenerate";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function UnlockVoterListBooth()
  {
    try{
      $permission_flag = MyFuncs::isPermission_route(93);
      if(!$permission_flag){
        return view('admin.common.error');
      }
      $rs_district = SelectBox::get_district_access_list_v1();
      return view('admin.master.PrepareVoterList.UnlockVoterList.unlock_booth',compact('rs_district'));  
    } catch (\Exception $e) {
      $e_method = "UnlockVoterListBooth";
      return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    }
  }

  public function unlockVoterListUnlock(Request $request)
  {
    try {
      $permission_flag = MyFuncs::isPermission_route(93);
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
      ];

      $customMessages = [
        'district.required'=> 'Please Select District',
        'block.required'=> 'Please Select MC\'s',
        'village.required'=> 'Please Select MC\'s',
        'ward.required'=> 'Please Select Ward',
        'booth.required'=> 'Please Select Booth',
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
      
      if ($ward_id == 0) {  //Unlock for Panchayat
        $rs_update = DB::select(DB::raw("call `up_unlock_village_voterlist` ($village_id);"));
        
      }elseif ($booth_id == 0){ //Process For MC Ward
        $rs_update= DB::select(DB::raw("call `up_unlock_voterlist` ($ward_id)")); 
        
      }else{                        //Process For MC Ward Booth
        $rs_update= DB::select(DB::raw("call `up_unlock_voterlist_booth` ($ward_id, $booth_id)")); 
      }
      $response=['status'=>1,'msg'=>'Unlock Sccessfully'];
      return response()->json($response); 
    } catch (\Exception $e) {
      $e_method = "unlockVoterListUnlock";
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




  // public function UnlockVoterList($value='')
  // {
  //   try{
  //       $admin = Auth::guard('admin')->user(); 
  //       $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
  //       return view('admin.master.PrepareVoterList.UnlockVoterList.index',compact('Districts'));     
  //   } catch (Exception $e) {}
    
  // }

  // public function UnlockVoterListMc()
  // {
  //   try{
  //     $admin = Auth::guard('admin')->user(); 
  //     $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));
        
  //     return view('admin.master.PrepareVoterList.UnlockVoterList.unlock_mc',compact('Districts'));  
  //   } catch (Exception $e) {}   
  // }

  

  // public function checkPhotoQuality()
  // {
  //   $admin = Auth::guard('admin')->user();
  //   $assemblys= DB::select(DB::raw("select * from `assemblys` Order By `id`"));  
  //   return view('admin.checkPhotoQuality.index',compact('assemblys'));
  // }
  // public function checkPhotoQualityAllAC()
  // {
  //   $admin = Auth::guard('admin')->user();
  //   $userid = $admin->id;  
  //   $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($userid, 0);"));  
  //   return view('admin.checkPhotoQuality.all_ac',compact('Districts'));
  // }

  // public function checkPhotoQualityAsmbStore(Request $request)
  // {
  //   $rules=[            
  //     'assembly' => 'required',
  //   ]; 
  //   $validator = Validator::make($request->all(),$rules);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors()->all();
  //     $response=array();
  //     $response["status"]=0;
  //     $response["msg"]=$errors[0];
  //     return response()->json($response);// response as json
  //   }                 
    
  //   foreach ($request->part_no as $key => $part_no) {
  //     \Artisan::queue('check:photoquality',['assembly'=>$request->assembly,'part_no'=>$part_no,'from_sr_no'=>0,'to_sr_no'=>0]); 
  //   } 
  //   $response=['status'=>1,'msg'=>'Submit Successfully'];
  //   return response()->json($response);
  // } 

  // public function checkPhotoQualityStore(Request $request)
  // {
  //   $rules=[            
  //     'assembly' => 'required', 
  //     'part_no' => 'required', 
  //     'from_sr_no' => 'required', 
  //     'to_sr_no' => 'required', 
  //   ]; 
  //   $validator = Validator::make($request->all(),$rules);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors()->all();
  //     $response=array();
  //     $response["status"]=0;
  //     $response["msg"]=$errors[0];
  //     return response()->json($response);// response as json
  //   }                 //Process For MC Ward Booth
  //   // $rs_update= DB::select(DB::raw("call `up_process_voterlist_booth` ('$request->ward', '$request->booth', 1)")); 
  //   \Artisan::queue('check:photoquality',['assembly'=>$request->assembly,'part_no'=>$request->part_no,'from_sr_no'=>$request->from_sr_no,'to_sr_no'=>$request->to_sr_no]); 
  //   $response=['status'=>1,'msg'=>'Submit Successfully'];
  //   return response()->json($response);
  // }

  // public function prepareVoterListSupplimentDatalistwise()
  // {
  //   try{
  //     $admin = Auth::guard('admin')->user(); 
  //     $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
  //     return view('admin.master.PrepareVoterList.supplimentDatalistwise.index',compact('Districts'));     
  //   } catch (Exception $e) {}
  // }

  // public function prepareVoterListSupplimentDatalistwiseStore(Request $request)
  // {   
  //   $rules=[            
  //     'district' => 'required', 
  //     'block' => 'required', 
  //     'village' => 'required',            
  //     'ward' => 'required',            
  //   ];

  //   $validator = Validator::make($request->all(),$rules);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors()->all();
  //     $response=array();
  //     $response["status"]=0;
  //     $response["msg"]=$errors[0];
  //     return response()->json($response);// response as json
  //   } 

  //   // $rs_update= DB::select(DB::raw("call `up_process_spcl_suple_voterlist_ward` ('$request->ward')")); 
    

  //   // \Artisan::queue('datalist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward,'booth_id'=>0]);
      
  //   // $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
  //   // return response()->json($response);
  // }

  // //-------------booth-wise------------------------

  // public function prepareVoterListSupplimentDatalistBoothwise()
  // {
  //   try{
  //     $admin = Auth::guard('admin')->user(); 
  //     $Districts = DB::select(DB::raw("call `up_fetch_district_access` ($admin->id, 0)"));   
  //     return view('admin.master.PrepareVoterList.supplimentDatalistwise.index_booth',compact('Districts'));     
  //   } catch (Exception $e) {}
  // }
  // public function prepareVoterListSupplimentDatalistwiseBoothStore(Request $request)
  // {   
  //   $rules=[            
  //     'district' => 'required', 
  //     'block' => 'required', 
  //     'village' => 'required',            
  //     'ward' => 'required',            
  //     'booth' => 'required',            
  //   ];
  //   // return($request);
  //   $validator = Validator::make($request->all(),$rules);
  //   if ($validator->fails()) {
  //     $errors = $validator->errors()->all();
  //     $response=array();
  //     $response["status"]=0;
  //     $response["msg"]=$errors[0];
  //     return response()->json($response);// response as json
  //   } 

  //   // $rs_update= DB::select(DB::raw("call `up_process_spcl_suple_voterlist_booth` ('$request->ward', $request->booth)")); 
    

  //   // \Artisan::queue('datalist:generate',['district_id'=>$request->district,'block_id'=>$request->block,'village_id'=>$request->village,'ward_id'=>$request->ward,'booth_id'=>$request->booth]);
      
  //   // $response=['status'=>$rs_update[0]->save_status,'msg'=>$rs_update[0]->save_remarks];
  //   // return response()->json($response);
  // } 
}

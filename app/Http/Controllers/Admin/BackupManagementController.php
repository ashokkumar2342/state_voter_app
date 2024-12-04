<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BackupManagementController extends Controller
{
	public function index($value='')
	{	
		$Districts = DB::select(DB::raw("select * from `districts`"));
		return view('admin.master.PrepareVoterList.backupmanagement.index' , compact('Districts'));
	}

	public function submit(Request $request)
	{	
		$rules=[            
	      'district' => 'required', 
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
	    $backup_type = 2;     //For EDMS
	                 
      	$rs_update= DB::select(DB::raw("call `up_process_backup_management` ('$request->district', '$request->assembly')")); 
    
      	\Artisan::queue('backupmanagement:generate',['district_id'=>$request->district,'ac_id'=>$request->assembly,'backup_type'=>$backup_type]); 
      
	    $response=['status'=>1,'msg'=>'Request Submit Successfully'];
	    return response()->json($response);
	}
}
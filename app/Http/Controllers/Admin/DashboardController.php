<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\createToken;
use Storage;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
  
  public function index()
  {
    $user_rs=Auth::guard('admin')->user();  
    $user_id = $user_rs->id;
    $rs_fetch = DB::select(DB::raw("SELECT `id` from `admins` where `id` = $user_id and `password_expire_on` <= curdate();"));
    if(count($rs_fetch) > 0){
        return redirect()->route('admin.account.change.password');
    }
    $count_rs = DB::select(DB::raw("select `uf_district_count`($user_id) as `dcount`, `uf_block_count`($user_id) as `bcount`, `uf_village_count`($user_id) as `vcount`, `uf_ward_count`($user_id) as `wcount`;"));
    $District = $count_rs[0]->dcount; 
    $block = $count_rs[0]->bcount;  
    $village = $count_rs[0]->vcount; 
    $wardVillage = $count_rs[0]->wcount; 
    return view('admin.dashboard.dashboard',compact('District','block','village','wardVillage')); 
  }  
  //--------End---------------

      

    
    // public function proFile()
    // {
    //     $admins = Auth::guard('admin')->user();
    //      return view('admin/dashboard/profile/view',compact('admins'));
    // }
    // public function proFileShow()
    // {
    //     $admins = Auth::guard('admin')->user();
    //      return view('admin/dashboard/profile/profile_show',compact('admins'));
    // }
    // public function profileUpdate(Request $request)
    // {
           
    //     $admins = Auth::guard('admin')->user();
    //      $rules=[
          
    //         'first_name' => 'required',
    //         'mobile' => 'required|digits:10',
    //         'email' => 'required',
    //         'dob' => 'required',
          
            
    //     ];

    //     $validator = Validator::make($request->all(),$rules);
    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         $response=array();
    //         $response["status"]=0;
    //         $response["msg"]=$errors[0];
    //         return response()->json($response);// response as json
    //     }
    //     else { 
    //             $admins=Admin::find($admins->id);
    //             $admins->first_name=$request->first_name;
    //             $admins->email=$request->email;
    //             $admins->mobile=$request->mobile;
    //             $admins->dob=$request->dob; 
    //             $admins->save(); 
    //             $response=['status'=>1,'msg'=>'Upload Successfully'];
    //             return response()->json($response); 
    //         } 
          
    // }
    // public function profilePhoto()
    // {
         
    //      return view('admin/dashboard/profile/profile_upload',compact('admins'));
    // } 
    // public function profilePhotoUpload(Request $request)
    // {
    //     $admins = Auth::guard('admin')->user();
    //      $rules=[
          
    //          // 'image' => 'required|mimes:jpeg,jpg,png,gif|max:5000'          
            
    //     ];

    //     $validator = Validator::make($request->all(),$rules);
    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         $response=array();
    //         $response["status"]=0;
    //         $response["msg"]=$errors[0];
    //         return response()->json($response);// response as json
    //     }
    //     else {  
    //             $data = $request->image; 
    //             list($type, $data) = explode(';', $data);
    //             list(, $data)      = explode(',', $data);
    //             $data = base64_decode($data);
    //             $image_name= time().'.jpg';       
    //             $path = Storage_path() . "/app/student/profile/admin/" . $image_name; 
    //             @mkdir(Storage_path() . "/app/student/profile/admin/", 0755, true);     
    //             file_put_contents($path, $data); 
    //             $admins->profile_pic = $image_name;
    //             $admins->save();
    //             return response()->json(['success'=>'done']);
            
            
    //       }
    // }
    //  public function proFilePhotoShow(Request $request,$profile_pic)
    //  {
    //      $profile_pic = Storage::disk('student')->get('profile/admin/'.$profile_pic);           
    //      return  response($profile_pic)->header('Content-Type', 'image/jpeg');
    //  }
    //  public function profilePhotoRefrash()
    //   {
    //       return view('admin.dashboard.profile.photo_refrash');
    //   } 
    //  public function passwordChange(Request $request)
    // {
    //     $rules=[
    //       'old_password' => 'required', 
    //       'password' => 'required|min:6|max:50', 
    //       'confirm_password' => 'required|min:6|max:50', 
    //     ];

    //     $validator = Validator::make($request->all(),$rules);
    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         $response=array();
    //         $response["status"]=0;
    //         $response["msg"]=$errors[0];
    //         return response()->json($response);// response as json
    //     }
    //     if ($request->confirm_password!=$request->password) {
    //         $response =array();
    //         $response['status'] =0;
    //         $response['msg'] ='Password Not Match';
    //         return $response;
    //     }
    //    $admin=Auth::guard('admin')->user();
    //     if (Hash::check($request->old_password, $admin->password))
    //     {
    //        $newPasswrod = Hash::make($request->password);
    //         $st=Admin::find($admin->id);
    //         $st->password =$newPasswrod ;
    //         $st->save();
    //         $response =array();
    //         $response['status'] =1;
    //         $response['msg'] ='Password Updated Successfully';
    //         return $response;
    //     }else{
    //        return 'not fond';
    //     }

    // }
   
}

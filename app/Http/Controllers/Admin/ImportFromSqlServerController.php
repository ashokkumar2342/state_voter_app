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

class ImportFromSqlServerController extends Controller
{

  public function index()
  {
     $from_districts = DB::connection('sqlsrv2')->select("select DisttCode, DisttName from District order by DisttName");
     $to_districts = DB::select("select * from `districts` order by `name_e`");
     return view('admin.ImportFromSqlServer.index',compact('from_districts','to_districts'));
  }

  public function sqlServerDistrictWiseBlock(Request $request)
  {
     $from_blocks = DB::connection('sqlsrv2')->select("select disttcode, CODE, NAme_eng from Block where disttcode = '$request->id' order by NAme_eng"); 
     return view('admin.ImportFromSqlServer.from_block',compact('from_blocks'));
  }

  public function sqlServerBlockWiseWillage(Request $request)
  { 
    // dd("select [name], PCODE  from Panchayat where disttcode = '".$request->district_id."' and Block = '".$request->id."' order by [name]");
     $from_panchayat = DB::connection('sqlsrv2')->select("select [name], PCODE  from Panchayat where disttcode = '$request->district_id' and Block = '$request->id' order by [name]"); 
     return view('admin.ImportFromSqlServer.from_panchayat',compact('from_panchayat'));
  }

  public function sqlServerDataTransfer(Request $request)
  {
    // return $request;
    $rules=[ 
    'from_district' => 'required',
    'from_block' => 'required',
    'from_panchayat' => 'required',
    'to_district' => 'required', 
    'to_block' => 'required', 
    'to_panchyat' => 'required', 
    ];

    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }
    \Artisan::queue('sqlServerDataTransfer:transfer',['from_district'=>$request->from_district,'from_block'=>$request->from_block,'from_panchayat'=>$request->from_panchayat,'to_district'=>$request->to_district,'to_block'=>$request->to_block,'to_panchyat'=>$request->to_panchyat]);
      
    $response=['status'=>1,'msg'=>'Request Submitted Successfully'];
    return response()->json($response);
  }


//----mySQLServer-----------------------//
  public function mySQLServer()
  {
     $from_districts = DB::connection('mysql_remote')->select("select * from `districts` order by `name_e`");
     $to_districts = DB::select("select * from `districts` order by `name_e`");
     return view('admin.ImportFromMySqlServer.index',compact('from_districts','to_districts'));
  }

  public function mySQLServerDistrictWiseBlock(Request $request)
  {
     $from_blocks = DB::connection('mysql_remote')->select("select * from `blocks_mcs` where `districts_id` =$request->id; "); 
     return view('admin.ImportFromMySqlServer.from_block',compact('from_blocks'));
  }

  public function mySQLServerBlockWiseWillage(Request $request)
  { 

     $from_panchayat = DB::connection('mysql_remote')->select("select * from `villages` where `districts_id` = $request->district_id and `blocks_id` =$request->id ;"); 
     return view('admin.ImportFromMySqlServer.from_panchayat',compact('from_panchayat'));
  }

  public function mySQLServerDataTransfer(Request $request)
  {
    // return $request;
    $rules=[ 
    'from_district' => 'required',
    'from_block' => 'required',
    'from_panchayat' => 'required',
    'to_district' => 'required', 
    'to_block' => 'required', 
    'to_panchyat' => 'required', 
    ];

    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      $response=array();
      $response["status"]=0;
      $response["msg"]=$errors[0];
      return response()->json($response);// response as json
    }
    \Artisan::queue('mysqlServerDataTransfer:transfer',['from_district'=>$request->from_district,'from_block'=>$request->from_block,'from_panchayat'=>$request->from_panchayat,'to_district'=>$request->to_district,'to_block'=>$request->to_block,'to_panchyat'=>$request->to_panchyat]);
      
    $response=['status'=>1,'msg'=>'Request Submitted Successfully'];
    return response()->json($response);
  }
}

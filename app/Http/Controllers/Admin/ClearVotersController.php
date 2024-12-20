<?php

namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;

class ClearVotersController extends Controller
{   
    protected $e_controller = "ClearVotersController";

    public function index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $rs_district = SelectBox::get_district_access_list_v1();
            return view('admin.master.ClearVoters.index',compact('rs_district'));
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function table(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            
            $village_id = intval(Crypt::decrypt($request->id));
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $village_id = 0;
            }
            
            $rs_result = DB::select(DB::raw("SELECT `pb`.`booth_no`, `wv`.`ward_no`, `vtd`.`total_votes`, `pb`.`id` as `pb_booth_id`, `wv`.`id` as `wv_ward_id` from `ward_villages` `wv` inner join (select distinct `ward_id`, `booth_id`, count(*) as `total_votes` from `voters` where `village_id` = $village_id and `status` <> 2 group by `ward_id`, `booth_id`) `vtd` on `vtd`.`ward_id` = `wv`.`id` inner join `polling_booths` `pb` on `pb`.`id` = `vtd`.`booth_id` order by `pb`.`booth_no`, `wv`.`ward_no`;"));
            return view('admin.master.ClearVoters.table',compact('rs_result'));            
        } catch (\Exception $e) {
            $e_method = "table";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function clear($booth_ward_id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            
            $val_input[] = explode(":", Crypt::decrypt($booth_ward_id));
            $booth_id = intval($val_input[0]);
            $ward_id = intval($val_input[1]);
            $user_id = MyFuncs::getUserId();
            $from_ip = MyFuncs::getIp();

            $village_id = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `wv`.`village_id` from `ward_villages` `wv` where `wv`.`id` = $ward_id limit 1;"));
            if (count($rs_fatch) > 0) {
                $village_id = $rs_fetch[0]->village_id;
            }            
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rs_clear = DB::select(DB::raw("call `up_clear_booth_part_voters`($user_id, $booth_id, $ward_id, '$from_ip');"));
            
            $response=['status'=>$rs_clear[0]->save_status,'msg'=>$rs_clear[0]->Save_Result];
            return response()->json($response);
                        
        } catch (\Exception $e) {
            $e_method = "clear";
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
}

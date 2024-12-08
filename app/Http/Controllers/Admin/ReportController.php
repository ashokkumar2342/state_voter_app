<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;

class ReportController extends Controller
{
    protected $e_controller = "ReportController";
    public function exception_handler()
    {
        try {
                
        } catch (\Exception $e) {
            $e_method = "imageShowPath";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function master_data_index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route("29");
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $role_id = MyFuncs::getUserRoleId();
            $report_type_id = 1;
            
            $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `name`; "));
            return view('admin.report.master_data.master_index',compact('reportTypes'));       
        } catch (\Exception $e) {
            $e_method = "master_data_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    // public function student_info_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("263");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 7;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `name`; "));
    //         return view('admin.report.studentInfoIndex',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "student_info_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function attendance_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("127");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 15;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
    //         return view('admin.report.attendance.index',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "attendance_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function fee_report_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("120");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
            
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 12;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `name`; "));
    //         return view('admin.report.fee_report_index',compact('reportTypes'));
    //     } catch (\Exception $e) {
    //         $e_method = "fee_report_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function library_report_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("153");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 17;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
    //         return view('admin.report.library.index',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "library_report_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function ut_ct_report_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("803");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 20;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
    //         return view('admin.report.ut_ct.index',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "ut_ct_report_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    public function formControlShow(Request $request)
    {
        try {
            $role_id = MyFuncs::getUserRoleId();
            $report_id = intval(Crypt::decrypt($request->id));
            $have_permission = MyFuncs::isPermission_reports($role_id, $report_id);
            if (! $have_permission){
                return "Not Permission";
            }
            if($report_id == 1){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_1',compact('rs_district'));
            }elseif($report_id == 2001){
                return view('admin.report.no_control_form');
            }        
        } catch (\Exception $e) {
            $e_method = "formControlShow";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $show_total_row = 1;
            $result_type = 1;
            $role_id = MyFuncs::getUserRoleId();
            $report_type = intval(Crypt::decrypt($request->report_type));
            if($report_type == 0){
                $response=array();
                $response["status"]=0;
                $response["msg"]='Please Select Report Type';
                return response()->json($response);  
            }

            $have_permission = MyFuncs::isPermission_reports($role_id, $report_type);
            if (! $have_permission){
                $response=array();
                $response["status"]=0;
                $response["msg"]='Not Permission';
                return response()->json($response);
            }
            
            if ($report_type == 1){
                if($request->district == 'null' || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }
                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->assembly == 'null' || empty($request->assembly)){
                    $ac_id = 0;
                }else{
                    $ac_id = intval(Crypt::decrypt($request->assembly));    
                }
                

                $result_type = 2;
                $show_total_row = 0;
                $tcols = 4;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Part No.',25, 'part_no', 0, '', 'left'),
                    array('First Sr. No.',25, 'first', 0, '', 'left'),
                    array('Last Sr. No.',25, 'last', 0, '', 'left'),
                    array('Total Voters',25, 'total_voters', 0, '', 'left'),
                );

                $rs_result=DB::select(DB::raw("SELECT `ap`.`part_no`, `vtd`.`first`, `vtd`.`last`, `vtd`.`total_voters` from `assembly_parts` `ap` inner join (select `vt`.`assembly_part_id`, count(*) as `total_voters`, min(`vt`.`sr_no`) as `first`, max(`vt`.`sr_no`) as `last` from `voters` `vt` where `vt`.`district_id` = $d_id and `vt`.`assembly_id` = $ac_id group by `vt`.`assembly_part_id`) `vtd` on `vtd`.`assembly_part_id` = `ap`.`id` where `ap`.`assembly_id` = $ac_id order by `ap`.`part_no`;"));
            }elseif ($report_type == 2000){
                $tcols = 2;
                $qcols = array(
                    array('Class Section',40),
                    array('Class Teacher',60),
                ); 
                $rs_result=DB::select(DB::raw("SELECT concat(`ct`.`name`, ' - ', `st`.`name`) as `class_section_name`, `uf_get_class_teacher_details`(`sec`.`id`) as `class_teacher` from `sections` `sec` inner join `class_types` `ct` on `ct`.`id` = `sec`.`class_id` inner join `section_types` `st` on `st`.`id` = `sec`.`section_id` where `sec`.`status` = 1 and `uf_get_daily_homework_status_section`(`sec`.`id`, curdate()) = 0 order by `ct`.`shorting_id`, `st`.`sorting_order_id`;"));
            }elseif($report_type == 2001){
                $result_type = 2;
                $show_total_row = 0;
                $tcols = 2;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Class-Section',60, 'class_name', 0, '', 'left'),
                    array('Total Students',40, 'count', 0, '', 'center'),
                );

                $rs_result=DB::select(DB::raw("SELECT concat(`ct`.`name`, ' - ', `sect`.`name`) as `class_name`, count(*) as `count` from `students` `si` inner join `sections` `sec` on `sec`.`id` = `si`.`class_section_id` inner join `class_types` `ct` on `ct`.`id` = `sec`.`class_id` inner join `section_types` `sect` on `sect`.`id` = `sec`.`section_id` where `si`.`student_status_id` = 1 group by `sect`.`name`, `ct`.`name`, `ct`.`shorting_id`, `sect`.`sorting_order_id` order by `ct`.`shorting_id`, `sect`.`sorting_order_id`;"));
            }

            $response = array();
            $response['status'] = 1; 
            if($result_type == 1){
                $response['data'] =view('admin.report.result',compact('rs_result', 'tcols', 'qcols'))->render();
            }elseif($result_type == 2){
                $response['data'] =view('admin.report.result_02',compact('rs_result', 'tcols', 'qcols', 'show_total_row'))->render();
            }
            return response()->json($response);           
        } catch (\Exception $e) {
            $e_method = "show";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
        
    }

}


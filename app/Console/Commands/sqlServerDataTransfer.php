<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class sqlServerDataTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sqlServerDataTransfer:transfer';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sqlServerDataTransfer Transfer ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    
    public function handle()
    { 
        //For Testing
        ini_set('max_execution_time', '7200');
        ini_set('memory_limit','999M');
        ini_set("pcre.backtrack_limit", "100000000");
      
        echo "Porting Started \n";

        $this->import_voter_other_detail();
        // $rs_ac_no = DB::connection('sqlsrv2')->select("SELECT distinct ASSEMBLY_CONSTITUENCY_NUMBER from eroll_data order by ASSEMBLY_CONSTITUENCY_NUMBER"); 

        echo "Sucess\n";
        // echo count($rs_fetch)."\n";
        

        // $this->import_data($from_district, $from_block, $from_panchayat, $to_district, $to_block, $to_panchyat);  
    }

    // public function handle()
    // { 
    //     ini_set('max_execution_time', '7200');
    //     ini_set('memory_limit','999M');
    //     ini_set("pcre.backtrack_limit", "100000000");
      
    //     $from_district = $this->argument('from_district');
    //     $from_block = $this->argument('from_block'); 
    //     $from_panchayat = $this->argument('from_panchayat'); 
    //     $to_district = $this->argument('to_district'); 
    //     $to_block = $this->argument('to_block'); 
    //     $to_panchyat = $this->argument('to_panchyat'); 

    //     echo "Porting Started \n";

    //     $this->import_data($from_district, $from_block, $from_panchayat, $to_district, $to_block, $to_panchyat);
        


        
      
    // }


    public function import_voter_other_detail()
    {
        $rs_ac_no = DB::connection('sqlsrv2')->select("SELECT distinct ASSEMBLY_CONSTITUENCY_NUMBER from eroll_data order by ASSEMBLY_CONSTITUENCY_NUMBER");
        foreach ($rs_ac_no as $key => $val_ac_no){
            $ac_no = $val_ac_no->ASSEMBLY_CONSTITUENCY_NUMBER;
            echo " AC No. :: ".$ac_no." \n";
            $rs_ac_part_no = DB::connection('sqlsrv2')->select("SELECT distinct PART_NUMBER from eroll_data where ASSEMBLY_CONSTITUENCY_NUMBER = $ac_no order by PART_NUMBER");
            foreach ($rs_ac_part_no as $key => $val_ac_part_no){
                $part_no = $val_ac_part_no->PART_NUMBER;
                // echo " Part No. :: ".$part_no." \n";
                $rs_fetch = DB::select(DB::raw("SELECT ifnull(max(`sr_no`), 0) as `last_sr_no` from `voter_other_detail` where `ac_no` = $ac_no and `part_no` = $part_no;"));
                $last_sr_no = $rs_fetch[0]->last_sr_no;

                $rs_data = DB::connection('sqlsrv2')->select("SELECT EPIC_ID, isnull(cast(APPROVAL_DATE as varchar),'') as approve_date, EPIC_NUMBER, PART_SERIAL_NUMBER, isnull(DISTRICT_CD, '') as dist_code from eroll_data where ASSEMBLY_CONSTITUENCY_NUMBER = $ac_no and PART_NUMBER = $part_no and PART_SERIAL_NUMBER > $last_sr_no order by PART_SERIAL_NUMBER");
                foreach ($rs_data as $key => $val_data){
                    $epic_id = $val_data->EPIC_ID;
                    if($val_data->approve_date == ''){
                        $approve_date = 'null';
                    }else{
                        $approve_date = "'".$val_data->approve_date."'";
                    }
                    $EPIC_NUMBER = $val_data->EPIC_NUMBER;
                    $PART_SERIAL_NUMBER = $val_data->PART_SERIAL_NUMBER;
                    if($val_data->dist_code == ''){
                        $dist_code = 'null';
                    }else{
                        $dist_code = "'".$val_data->dist_code."'";
                    }
                    $query = "INSERT into `voter_other_detail`(`epic_id`, `approval_date`, `epic_no`, `ac_no`, `part_no`, `sr_no`, `district_cd`) values ($epic_id, $approve_date, '$EPIC_NUMBER', $ac_no, $part_no, $PART_SERIAL_NUMBER, $dist_code);";
                    // echo $query."\n";
                    $rs_insert = DB::select(DB::raw("$query"));
                }


            }

                
        }
        
        

        $remarks = 'Porting Completed';
        
    }

    // public function import_data($from_district, $from_block, $from_panchayat, $to_district, $to_block, $to_panchyat)
    // {
    //     $data_import_id = 1;
      
    //     DB::connection('sqlsrv2')->update("update B02 set booth = 0 where booth is null");
    //     DB::connection('sqlsrv2')->update("update B02 set srn = 0 where srn is null");

    //     $rs_ac_partno = DB::connection('sqlsrv2')->select("select distinct ac_no, PART_NO from B02 where DisttCode = '$from_district' and Block_Code = '$from_block' and GP_Code = '$from_panchayat' and [status] in ('O', 'N') order by AC_NO, PART_NO");

    //     if(count($rs_ac_partno) == 0){
    //         $remarks = 'AC No and Part No. Not Exists';
    //         $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //         return null;
    //     }

    //     foreach ($rs_ac_partno as $key => $val_ac_partno) {

    //         $rs_data = DB::select(DB::raw("select * from `assemblys` where `code` = $val_ac_partno->ac_no and `district_id` = $to_district limit 1;"));
    //         if(count($rs_data) == 0){
    //             $remarks = 'AC ID Not Exists for AC :: '.$val_ac_partno->ac_no;
    //             $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //             continue;    
    //         }
    //         $l_ac_id = $rs_data[0]->id;
            
    //         $rs_data = DB::select(DB::raw("select * from `assembly_parts` where `part_no` = $val_ac_partno->PART_NO and `assembly_id` = $l_ac_id limit 1;"));
    //         if(count($rs_data) == 0){
    //             $remarks = 'Part ID Not Exists for Part No. :: '.$val_ac_partno->ac_no.' - '.$val_ac_partno->PART_NO;
    //             $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //             continue;    
    //         }
    //         $l_part_id = $rs_data[0]->id;

    //         $rs_gp_ward = DB::connection('sqlsrv2')->select("select distinct GP_Ward, booth from B02 where DisttCode = '$from_district' and Block_Code = '$from_block' and GP_Code = '$from_panchayat' and AC_NO = '$val_ac_partno->ac_no' and PART_NO = '$val_ac_partno->PART_NO' and [status] in ('O', 'N') order by GP_Ward");          

    //         if(count($rs_gp_ward) == 0){
    //             $remarks = 'Ward Nos Not Exists for Panchayat Code :: '.$from_panchayat;
    //             $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //             continue;
    //         }

    //         foreach ($rs_gp_ward as $key => $val_gp_ward) {
    //             $rs_data = DB::select(DB::raw("select * from `ward_villages` where `districts_id` = $to_district and `blocks_id` = $to_block and `village_id` = $to_panchyat and `ward_no` = $val_gp_ward->GP_Ward limit 1;"));
    //             if(count($rs_data) == 0){
    //                 $remarks = 'Ward Id Not Exists for Ward No. :: '.$from_panchayat.' - '.$val_gp_ward->GP_Ward;
    //                 $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //                 continue;    
    //             }   
    //             $l_ward_id = $rs_data[0]->id;


    //             if($val_gp_ward->booth > 0){
    //                 $rs_data = DB::select(DB::raw("select * from `polling_booths` where `districts_id` = $to_district and `blocks_id` = $to_block and `village_id` = $to_panchyat and `booth_no` = $val_gp_ward->booth limit 1;"));
    //                 if(count($rs_data) == 0){
    //                     $remarks = 'Booth Id Not Exists for Booth No. :: '.$from_panchayat.' - '.$val_gp_ward->booth;
    //                     $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //                     continue;    
    //                 }   
    //                 $l_booth_id = $rs_data[0]->id;
    //             }else{
    //                 $l_booth_id = 0;
    //             }


    //             $rs_gp_voters = DB::connection('sqlsrv2')->select("select * from B02 where DisttCode = '$from_district' and Block_Code = '$from_block' and GP_Code = '$from_panchayat' and AC_NO = '$val_ac_partno->ac_no' and PART_NO = '$val_ac_partno->PART_NO' and GP_Ward = '$val_gp_ward->GP_Ward' and booth = '$val_gp_ward->booth' and [status] in ('O', 'N') order by SLNOINPART");  

    //             if(count($rs_gp_voters) == 0){
    //                 $remarks = 'No Data Found for Ward and Booth :: '.$val_gp_ward->GP_Ward.' - '.$val_gp_ward->booth;
    //                 $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //                 continue;
    //             }

    //             foreach ($rs_gp_voters as $key => $value) {
    //                 $o_suppliment = 0;
    //                 $o_status = 0;

    //                 $name_l=str_replace('਍', '', $value->FM_NAME);
    //                 $name_l=str_replace('\'', '', $name_l);
    //                 $name_l=str_replace('\\', '', $name_l);

    //                 $name_e=substr(str_replace('਍', '', $value->name_eng),0,49);
    //                 $name_e=substr(str_replace('\'', '', $name_e),0,49);
    //                 $name_e=substr(str_replace('\\', '', $name_e),0,49);
                   
    //                 $f_name_e=substr(str_replace('਍', '', $value->fname_eng),0,49);
    //                 $f_name_e=substr(str_replace('\'', '', $f_name_e),0,49);
    //                 $f_name_e=substr(str_replace('\\', '', $f_name_e),0,49);

    //                 $f_name_l=str_replace('਍', '', $value->RLN_FM_NM);
    //                 $f_name_l=str_replace('\'', '', $f_name_l);
    //                 $f_name_l=str_replace('\\', '', $f_name_l);

    //                 if ($value->Rln=='F') {
    //                   $relation=1;  
    //                 }
    //                 elseif ($value->Rln=='G') {
    //                   $relation=2;  
    //                 } 
    //                 elseif ($value->Rln=='H') {
    //                   $relation=3;  
    //                 } 
    //                 elseif ($value->Rln=='M') {
    //                   $relation=4;  
    //                 } 
    //                 elseif ($value->Rln=='O') {
    //                   $relation=5;  
    //                 } 
    //                 elseif ($value->Rln=='W') {
    //                   $relation=6;  
    //                 }
    //                 if ($value->SEX=='M') {
    //                   $gender_id=1;  
    //                 }
    //                 elseif ($value->SEX=='F') {
    //                   $gender_id=2;  
    //                 }else{
    //                   $gender_id=3;  
    //                 }  
    //                 $house_e = substr(str_replace('\\',' ', $value->HOUSE_NO),0,49);
    //                 $house_e = substr(str_replace('\'',' ', $house_e),0,49);

    //                 $house_l = str_replace("\\",' ', $value->HOUSE_NO);
    //                 $house_l = str_replace('\'',' ', $house_l);
                    
                           
                    
    //                 $newId = DB::select(DB::raw("call up_save_voter_detail($to_district, $l_ac_id, $l_part_id, $value->SLNOINPART, '$value->IDCARD_NO', '$house_e', '$house_l','','$name_e','$name_l','$f_name_e','$f_name_l', $relation, $gender_id, $value->AGE, '$value->mobileno', 'v', $o_suppliment, $o_status, $to_panchyat, $l_ward_id, $value->srn, $l_booth_id, $data_import_id, '*');"));
        
                    
    //                 $dirpath = Storage_path() . '/app/vimage/'.$data_import_id.'/'.$l_ac_id.'/'.$l_part_id;
    //                 $vpath = '/vimage/'.$data_import_id.'/'.$l_ac_id.'/'.$l_part_id;
    //                 $image = $value->photo;
    //                 $name = $value->SLNOINPART;
    //                 $image= \Storage::disk('voterimage')->put($vpath.'/'.$name.'.jpg', $image);

    //             }

    //         }

    //     }


    //     $remarks = 'Porting Completed';
    //     $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
    //     $rs_remarks = DB::select(DB::raw("select * from `import_draft_status`;"));
    //     foreach ($rs_remarks as $key => $val_remark) {
    //         echo($val_remark->remarks."\n");
    //     }
    // }

    public function saveDraftPhoto($from_district, $from_block, $from_panchayat, $to_district)
    { 
        $from_district = $this->argument('from_district');
        $from_block = $this->argument('from_block'); 
        $from_panchayat = $this->argument('from_panchayat'); 
        
        $data_import_detail = DB::select(DB::raw("select * from `import_type` where `status` = 1 limit 1;"));
        $data_import_id = $data_import_detail[0]->id;
      
        
        $rs_ac_partno = DB::connection('sqlsrv2')->select("select distinct ac_no, PART_NO from B02 where DisttCode = '$from_district' and Block_Code = '$from_block' and GP_Code = '$from_panchayat' order by AC_NO, PART_NO");

        foreach ($rs_ac_partno as $key => $val_ac_partno) {

            $rs_data = DB::select(DB::raw("select * from `assemblys` where `code` = $val_ac_partno->ac_no and `district_id` = $to_district limit 1;"));
            $l_ac_id = $rs_data[0]->id;
            
            $rs_data = DB::select(DB::raw("select * from `assembly_parts` where `part_no` = $val_ac_partno->PART_NO and `assembly_id` = $l_ac_id limit 1;"));
            $l_part_id = $rs_data[0]->id;

            $dirpath = Storage_path() . '/app/vimage/'.$data_import_id.'/'.$l_ac_id.'/'.$l_part_id;
            $vpath = '/vimage/'.$data_import_id.'/'.$l_ac_id.'/'.$l_part_id;
            @mkdir($dirpath, 0755, true);


            $rs_gp_voters = DB::connection('sqlsrv2')->select("select * from photo where acno = '$val_ac_partno->ac_no' and partno = '$val_ac_partno->PART_NO' ");  
            if (count($rs_gp_voters) > 0) {
                foreach ($rs_gp_voters as $key => $value) {
                    $image=$value->photo;
                    $name = $value->slno;
                    $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);        
                }    
            }else{
                $rs_gp_voters = DB::connection('sqlsrv2')->select("select * from B02 where AC_NO = '$val_ac_partno->ac_no' and PART_No = '$val_ac_partno->PART_NO' "); 
                foreach ($rs_gp_voters as $key => $value) {
                    $image=$value->photo;
                    $name = $value->SLNOINPART;
                    $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);        
                }
            }
            

        }
      
    }

    

    public function saveLeftDraftPhoto($from_district, $from_block, $from_panchayat, $to_district)
    { 
        $from_district = $this->argument('from_district');
        $from_block = $this->argument('from_block'); 
        $from_panchayat = $this->argument('from_panchayat'); 
        
        $data_import_detail = DB::select(DB::raw("select * from `import_type` where `status` = 1 limit 1;"));
        $data_import_id = $data_import_detail[0]->id;
      
        $rs_ac_partno = DB::select(DB::raw("select distinct `assembly_id`, `assembly_part_id` from `draft_voter_data` order by `assembly_id`, `assembly_part_id`;"));        
        // $rs_ac_partno = DB::connection('sqlsrv2')->select("select distinct ac_no, PART_NO from B02 where DisttCode = '$from_district' and Block_Code = '$from_block' and GP_Code = '$from_panchayat' order by AC_NO, PART_NO");

        foreach ($rs_ac_partno as $key => $val_ac_partno) {

            $rs_data = DB::select(DB::raw("select * from `assemblys` where `id` = $val_ac_partno->assembly_id and `district_id` = $to_district limit 1;"));
            $l_ac_id = $val_ac_partno->assembly_id;
            $l_ac_code = $rs_data[0]->code;
            
            $rs_data = DB::select(DB::raw("select * from `assembly_parts` where `id` = $val_ac_partno->assembly_part_id and `assembly_id` = $l_ac_id limit 1;"));
            $l_part_id = $val_ac_partno->assembly_part_id;
            $l_part_no = $rs_data[0]->part_no;

            $dirpath = Storage_path() . '/app/vimage/'.$data_import_id.'/'.$l_ac_id.'/'.$l_part_id;
            $vpath = '/vimage/'.$data_import_id.'/'.$l_ac_id.'/'.$l_part_id;
            @mkdir($dirpath, 0755, true);

            $rs_left_voter = DB::select(DB::raw("select * from `draft_voter_data` where `assembly_id` = $l_ac_id and `assembly_part_id` = $l_part_id order by `sr_no` ;"));
            foreach ($rs_left_voter as $key => $val_left_voter) {
                $rs_gp_voters = DB::connection('sqlsrv2')->select("select * from photo where acno = $l_ac_code and partno = l_part_no and slno = $val_left_voter->sr_no ");  
                if (count($rs_gp_voters) > 0) {
                    foreach ($rs_gp_voters as $key => $value) {
                        $image=$value->photo;
                        $name = $value->slno;
                        $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);        
                    }    
                }else{
                    $rs_gp_voters = DB::connection('sqlsrv2')->select("select * from B02 where AC_NO = l_ac_code and PART_No = l_part_no and SLNOINPART = $val_left_voter->sr_no "); 
                    foreach ($rs_gp_voters as $key => $value) {
                        $image=$value->photo;
                        $name = $value->SLNOINPART;
                        $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);        
                    }
                }
            }

        }
      
    }

       
}

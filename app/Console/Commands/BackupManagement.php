<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackupManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupmanagement:generate {district_id} {ac_id} {backup_type}';
    /*Backup Type:- 1-Voter List, 2-For EDMS*/


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backupmanagement generate';

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
    //\Log::info(date('Y-m-d H:i:s'));
    public function handle()
    { 
        ini_set('max_execution_time', '7200');
        ini_set('memory_limit','999M');
        ini_set("pcre.backtrack_limit", "100000000");
        $district_id = $this->argument('district_id');
        $ac_id = $this->argument('ac_id'); 
        $backup_type = $this->argument('backup_type'); 

        $this->prepareAppBackup(1,22);
        return null;
        
        // if ($backup_type == 2){
        //     $file_content = "";

        //     $table_name = "districts";
        //     $query_source = "select `id`, `state_id`, `code`, `name_e`, `name_l` from `districts`;"; 
        //     $truncate = 1;
        //     $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate);

        //     $table_name = "blocks_mcs";
        //     $query_source = "select `id`, `states_id`, `districts_id`, `code`, `name_e`, `name_l`, 0 as `phase_no`, 0 as `on_line_id` from `blocks_mcs`;"; 
        //     $truncate = 1;
        //     $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate);

        //     $table_name = "polling_booths";
        //     $query_source = "select `id`, `states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`, 4 as `po_count`, 0 as `senstive`, 0 as `hyper`, 0 as `web_cast`, 0 as `video`, 0 as `crpf`, 0 as `female`, 0 as `building`, 0 as `dm`, 0 as `ss` from `polling_booths`;"; 
        //     $truncate = 1;
        //     $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate);
            
        //     $table_name = "villages";
        //     $query_source = "select `id`, `states_id`, `districts_id`, `blocks_id`, `code`, `name_e`, `name_l` from `villages`;"; 
        //     $truncate = 1;
        //     $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate);
            
        //     $fileName = "backup_for_edms.sql";
        //     \File::put(public_path('/backup/'.$fileName),$file_content);

        //     echo "Complete \n";
        //     return null;
        // }

        
        
        
        $rs_backup = DB::select(DB::raw("select * from `backup_list` where `block_id` = $ac_id and `status` = 0 limit 1;"));

        if(count($rs_backup) >0){
            $file_content = "";


            $backup_id = $rs_backup[0]->id;
            $rs_update = DB::select(DB::raw("update `backup_list` set `status` = 2 where `id` = $backup_id limit 1;"));


            // $rs_ac_list = DB::select(DB::raw("select * from `assemblys`;"));
            // foreach ($rs_ac_list as $key => $val_ac){
            //     $ac_id = $val_ac->id;
            //     $file_content = "";
            //     $fileName = $val_ac->id.".sql";            
            //     $file_content .= $this->preparevotersBackup($ac_id); 
            //     \File::put(public_path('/backup/'.$fileName),$file_content);  
            // }

            
            // /*Table Group 1*/
            // $file_content = "";
            // $fileName = "backup_group_1.sql";
            // $file_content .= $this->prepareTableGroupOneBackup();
            // \File::put(public_path('/backup/'.$fileName),$file_content);

            
            // // /*Table Group 2*/
            // $file_content = "";
            // $fileName = "backup_group_2.sql";
            // $file_content .= $this->prepareTableGroup2Backup();
            // \File::put(public_path('/backup/'.$fileName),$file_content);

            // // /*Table Group 3*/
            // $file_content = "";
            // $fileName = "backup_group_3.sql";
            // $file_content .= $this->prepareTableGroup3Backup();
            // \File::put(public_path('/backup/'.$fileName),$file_content);

            // /*Table Group 4*/
            // $file_content = "";
            // $fileName = "backup_group_4.sql";
            // $file_content .= $this->prepareTableGroup4Backup();
            // \File::put(public_path('/backup/'.$fileName),$file_content);


            /*Table Group 5*/
            $file_content = "";
            $fileName = "backup_group_5.sql";
            $file_content .= $this->prepareTableGroup5Backup();
            \File::put(public_path('/backup/'.$fileName),$file_content);

            
            // \File::put(public_path('/'.$fileName),$file_content);
            $rs_update = DB::select(DB::raw("update `backup_list` set `status` = 1 where `id` = $backup_id limit 1;"));

        }
        
          
    }


    public function prepareAppBackup($from_d_id, $to_d_id)
    {
        //App Backup Starts
        $file_content = "";

        $new_district_id = 1;
        $new_block_id = 1;
        $new_village_id = 1;
        $new_booth_id = 1;

        $dist_counter = $from_d_id;
        while($dist_counter <= $to_d_id){
            $table_name = "districts";
            $query_source = "select $new_district_id as `d_id`, `name_e` from `districts` where `id` = $dist_counter;"; 
            $truncate = 0;
            $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate, 0);    

            $rs_blocks = DB::select(DB::raw("select `id` from `blocks_mcs` where `districts_id` = $dist_counter and `block_mc_type_id` = 1;"));
            foreach ($rs_blocks as $key => $val_blocks){
                $table_name = "blocks_mcs";
                $query_source = "select $new_block_id as `block_id`, $new_district_id as `d_id`, `name_e` from `blocks_mcs` where `id` = $val_blocks->id limit 1;"; 
                $truncate = 0;
                $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate, 0);

                $rs_villages = DB::select(DB::raw("select `id` from `villages` where `blocks_id` = $val_blocks->id;"));
                foreach ($rs_villages as $key => $val_villages){
                    $table_name = "villages";
                    $query_source = "select $new_village_id as `vil_id`, $new_district_id as `d_id`, $new_block_id as `b_id`, `name_e` from `villages` where `id` = $val_villages->id;"; 
                    $truncate = 0;
                    $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate, 0);

                    $table_name = "INSERT INTO `voters_detail` (`epic_card_no`, `house_no`, `voter_name`, `father_name`, `age`, `mobile_no`, `village_id`, `ward_no`, `voter_sr_no`, `booth_id`, `booth_no`) VALUES";
                    $query_source = "select `vt`.`voter_card_no`, `vt`.`house_no_e`, `vt`.`name_e`, `vt`.`father_name_e`, `vt`.`age`, `vt`.`mobile_no`, $new_village_id  as `vil_id`, `wv`.`ward_no`, `vt`.`print_sr_no`, 0 as `booth_id`, '' as `boothno` from `voters` `vt` inner join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`village_id` = $val_villages->id and `vt`.`status` <> 2 and `source` <> 'n';"; 
                    $truncate = 0;
                    $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate, 1);

                    $dirpath = public_path()."/backup_for_app/".$new_district_id."/".$new_block_id."/";
                    @mkdir($dirpath, 0755, true);
                    $fileName = $new_district_id."/".$new_block_id."/".$new_village_id.".sql";
                    \File::put(public_path('/backup_for_app/'.$fileName),$file_content);

                    $new_village_id++;
                    $file_content = "";
                }
                $new_block_id++;    
            }

            $new_district_id++;
            $dist_counter++;
        }
        

        

        

        // $table_name = "polling_booths";
        // $query_source = "select `pb`.`id` as `pboothid`, `wv`.`id` as `vil_id`,  concat(`pb`.`booth_no`,ifnull(`pb`.`booth_no_c`,'')) as `boothno`, `pb`.`name_e` from `polling_booths` `pb` inner join `booth_ward_voter_mapping` `bwvm` on `bwvm`.`boothid` = `pb`.`id` inner join `ward_villages` `wv` on `wv`.`id` = `bwvm`.`wardId` where `pb`.`village_id` = 251;"; 
        // $truncate = 1;
        // $file_content .=$this->prepareBackup_forapp($table_name, $query_source, $truncate);

        // $fileName = "backup_for_app.sql";
        // \File::put(public_path('/backup/'.$fileName),$file_content);

        echo "Complete \n";
        return null;
    }


    public function prepareTableGroup5Backup()
    {
        $file_content = "";

        $condition = "";
        $truncate = 1;

        $table_name = "supplement_data_merged";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "vidhansabha_list";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "voters_new_mod_del";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        return $file_content;   
    }

    public function prepareTableGroup4Backup()
    {
        $file_content = "";

        $condition = "";
        $truncate = 1;

        $table_name = "booth_voter_list";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "duplicate_card";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        // $table_name = "history_print_sr_no";
        // $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "last_srno_ward";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "last_srno_ward_booth";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "main_page_detail";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "suppliment_voters_deleted";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "suppliment_voters_modified";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "suppliment_voters_new";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "voter_list_processeds";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "voter_slip_notes";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "voters_srno_detail";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "voters_srno_detail_text";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "voters_srno_detail_village";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "work_log";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        return $file_content;   
    }

    public function prepareTableGroup3Backup()
    {
        $file_content = "";

        $condition = "";
        $truncate = 1;

        $table_name = "blocks_mcs";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "booth_ward_voter_mapping";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "mapping_acpart_booth_wardwise";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "polling_booths";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "polling_day_time";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "villages";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "voter_list_master";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "ward_colony_detail";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "ward_ps";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "ward_villages";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "ward_zp";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        return $file_content;   
    }

    public function prepareTableGroup2Backup()
    {
        $file_content = "";

        $condition = "";
        $truncate = 1;

        $table_name = "admins";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "default_role_menu";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "default_role_quick_menu";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "minu_types";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "roles";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "sub_menus";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "user_block_assigns";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "user_district_assigns";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        $table_name = "user_village_assigns";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);        
        
        return $file_content;   
    }

    public function prepareTableGroupOneBackup()
    {
    
        

        $file_content = "";

        $condition = "";
        $truncate = 1;

        $table_name = "assembly_parts";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "assemblys";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "districts";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

        $table_name = "import_type";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        
        $table_name = "states";
        $file_content .= $this->prepareBackup($table_name, $condition, $truncate);

            
        
        return $file_content;   
    }


    public function preparevotersBackup($ac_id)
    {
    
        $rs_ac_parts = DB::select(DB::raw("select * from `assembly_parts` where `assembly_id` = $ac_id ;"));

        $file_content = "";

        foreach ($rs_ac_parts as $key => $val_ac_part){
            echo ("Processing Part :: ".$ac_id.'-'.$val_ac_part->part_no."\n");
            $table_name = "voters";
            $condition = " where `assembly_part_id` = $val_ac_part->id ";
            $truncate = 0;
            $file_content .= $this->prepareBackup($table_name, $condition, $truncate);
        }

        return $file_content;   
    }


    public function prepareBackup($table_name, $condition, $truncate)
    {
        
        $backup_content = '';
        if($truncate == 1){
            $backup_content .= "\nTRUNCATE TABLE `$table_name`;\n\n";
        }
        
        $rs_result = DB::select(DB::raw("select * from `$table_name` $condition;"));
        // dd($rs_result);
        if(count($rs_result) > 0){
            $counter = 1;

            foreach ($rs_result as $rs_row){
                if($counter == 1){
                    $backup_content .= "INSERT INTO `$table_name` VALUES (";    
                }else{
                    $backup_content .= ",\n(";    
                }

                $first_col = 1; 
                foreach ($rs_row as $value){
                    if($first_col == 1){
                        $first_col = 0;    
                    }else{
                        $backup_content .= ",";   
                    }

                    if(is_null($value)){
                        $backup_content .= "null";    
                    }else{
                        $c_text = str_replace("'", "''", $value);
                        $backup_content .= "'$c_text'";    
                    }
                    // $c_text = $value."";
                    // echo("db value :: ".$c_text."\n");
                    // if($c_text == 'null'){
                    //     $backup_content .= "null";
                    //     echo("condition null\n");
                    // }elseif($c_text != ''){
                    //     $backup_content .= "'$c_text'";
                    //     echo("condition not blank\n"); 
                    // }elseif(empty($c_text)){
                    //     $backup_content .= "null";
                    //     echo("condition empty\n");
                    // }else{
                    //     $backup_content .= "'$c_text'"; 
                    //     echo("else\n");   
                    // }    
                }
                $backup_content .= ")";

                $counter++;
                if($counter == 500){
                    $counter = 1;
                    $backup_content .= ";\n";
                }

            }
            if($counter != 1){
                $backup_content .= ";\n";
            }

        }
        // echo($backup_content."\n");
        return $backup_content;   
    }



    // public function prepareBackup($table_name, $condition, $truncate)
    // {
        
    //     $backup_content = '';
    //     if($truncate == 1){
    //         $backup_content .= "\nTRUNCATE TABLE `$table_name`;\n\n";
    //     }
    //     // $backup_content .= "\nIDENTITY_INSERT is set to OFF\n\n";

    //     $flag = 1;
    //     $page_no = 0;
    //     $page_size = 500;
    //     while($flag == 1) {
    //         $start_no = $page_size*$page_no;
    //         $rs_result = DB::select(DB::raw("select * from `$table_name` $condition limit $start_no, $page_size"));
    //         if(count($rs_result)==0){
    //             $flag = 0;   
    //         }else{
    //             $first_record = 1;
    //             $backup_content .= "INSERT INTO `$table_name` VALUES (";
    //             foreach ($rs_result as $rs_row) {
    //                 if($first_record == 1){
    //                     $first_record = 0;    
    //                 }else{
    //                     $backup_content .= ",\n(";   
    //                 }

    //                 $first_col = 1;
    //                 foreach ($rs_row as $value){
    //                     if($first_col == 1){
    //                         $first_col = 0;    
    //                     }else{
    //                         $backup_content .= ",";   
    //                     }
    //                     if($value=='null'){
    //                         $backup_content .= "$value";
    //                     }else{
    //                         $backup_content .= "'$value'";    
    //                     }
                        
    //                 }
    //                 $backup_content .= ")";

    //             }
    //             $backup_content .= ";\n";  
    //         }


    //         $page_no++;
    //     }


    //     // $backup_content .= "\nIDENTITY_INSERT is set to ON\n\n";

    //     return $backup_content;   
    // }



    public function prepareBackup_forapp($table_name, $query_source, $truncate, $with_insert)
    {
        
        $backup_content = '';
        if($truncate == 1){
            $backup_content .= "\nTRUNCATE TABLE `$table_name`;\n\n";
        }
        
        $rs_result = DB::select(DB::raw("$query_source;"));
        // dd($rs_result);
        if(count($rs_result) > 0){
            $counter = 1;

            foreach ($rs_result as $rs_row){
                if($counter == 1){
                    if($with_insert == 1){
                        $backup_content .= "$table_name (";    
                    }else{
                        $backup_content .= "INSERT INTO `$table_name` VALUES (";        
                    }
                    
                }else{
                    $backup_content .= ",\n(";    
                }

                $first_col = 1; 
                foreach ($rs_row as $value){
                    if($first_col == 1){
                        $first_col = 0;    
                    }else{
                        $backup_content .= ",";   
                    }

                    if(is_null($value)){
                        $backup_content .= "null";    
                    }else{
                        $c_text = str_replace("'", "''", $value);
                        $backup_content .= "'$c_text'";    
                    }
                    // $c_text = $value."";
                    // echo("db value :: ".$c_text."\n");
                    // if($c_text == 'null'){
                    //     $backup_content .= "null";
                    //     echo("condition null\n");
                    // }elseif($c_text != ''){
                    //     $backup_content .= "'$c_text'";
                    //     echo("condition not blank\n"); 
                    // }elseif(empty($c_text)){
                    //     $backup_content .= "null";
                    //     echo("condition empty\n");
                    // }else{
                    //     $backup_content .= "'$c_text'"; 
                    //     echo("else\n");   
                    // }    
                }
                $backup_content .= ")";

                $counter++;
                if($counter == 500){
                    $counter = 1;
                    $backup_content .= ";\n";
                }

            }
            if($counter != 1){
                $backup_content .= ";\n";
            }

        }
        // echo($backup_content."\n");
        return $backup_content;   
    }

    
}

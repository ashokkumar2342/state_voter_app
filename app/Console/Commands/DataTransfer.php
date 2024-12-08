<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class DataTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:transfer {district_id} {ac_code} {part_no}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data Transfer ';

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

      // $this->import_suppliment_voters(1);
      // $this->import_suppliment_voters(2);
      // return "";

      echo "Making Connection \n";
      // $serverName="10.145.2.48";
      // $database="s07_eroll_with_photo";
      // $username="election";
      // $passward="election#321"; 
      // $options = array(  "UID" =>$username,  "PWD" =>$passward,  "Database" =>$database, "CharacterSet" => "UTF-8");
      
      // dd( DB::connection('sqlsrv')->getHostName());
      // dd( DB::connection('sqlsrv')->getDatabaseName());
      // $datas = DB::connection('sqlsrv')->select("select top 1 * from Query");      
      // $conn = sqlsrv_connect($serverName, $options); 
      // if( $conn === false )
      // {
      // echo "Could not connect.\n";
      // die( print_r( sqlsrv_errors(), true));
      // }

      // dd($datas);

      ini_set('max_execution_time', '7200');
      ini_set('memory_limit','999M');
      ini_set("pcre.backtrack_limit", "100000000");
      
      $district_id = $this->argument('district_id'); 
      $ac_code = $this->argument('ac_code');
      $part_no = $this->argument('part_no'); 


      // $datas = DB::connection('sqlsrv')->select("select symbolid, symbol from SymbolMaster");
            
      // foreach ($datas as $key => $value) { 
      //     $image=$value->symbol;
      //     $name =$value->symbolid;
      //     $image= \Storage::disk('symbol')->put($name.'.jpg', $image);
      // }

      // return;

      // $database=getenv('DB_DATABASE_2');
      // echo $database."\n";
      
      // echo "OK"."\n";
      // $this->auto_unlock_lock();
      // return null;
      echo " Ac No. :: ".$ac_code.", Part No. :: ".$part_no." \n";
      $this->import_complete_part_vote($ac_code, $part_no, $district_id);

      // $rs_assembly_part=DB::select(DB::raw("select * from `assembly_parts` where `assembly_id` = 94 and part_no >= '0200' and part_no <= '0201';"));
      // foreach ($rs_assembly_part as $key => $value) {
      //   $ac_code = "22";
      //   $part_no = $value->part_no; 
      //   $district_id = 7;

      //   $this->import_complete_part_vote($ac_code, $part_no, $district_id);
          
      // }
      
      // return null;  

      // $this->insert_supplement_data();
      // return null;

      
      
      

      // if($data_import_id>=2 && $data_import_id<4) {
      //   $this->import_deleted_votes($ac_code, $part_no, $district_id, $ac_id, $ac_part_id, $data_import_id);
      // }

      // if($data_import_id == 4) { 
      //   $this->merge_supplement_data($district_id, $ac_id, $ac_part_id, $data_import_id, $data_tag);
      // }

      
    }

    public function import_complete_part_vote($ac_code, $part_no, $district_id)
    {
      $assembly = DB::select(DB::raw("SELECT * from `assemblys` where `code` = '$ac_code' and `district_id` = $district_id limit 1;"));
      $ac_id = $assembly[0]->id;
      $assemblyPart = DB::select(DB::raw("SELECT * from `assembly_parts` where `assembly_id` = $ac_id and `part_no` = '$part_no' limit 1;"));
      $ac_part_id = $assemblyPart[0]->id;

      // $rs_result = DB::select(DB::raw("select * from `voters` where `assembly_part_id` = $ac_part_id and `village_id` > 0 limit 1;"));
      // $data_exists = 1;
      // if(count($rs_result)==0){
        $data_exists = 0;  
      // }

      $data_import_detail = DB::select(DB::raw("SELECT * from `import_type` where `status` = 1 limit 1;"));
      $data_import_id = $data_import_detail[0]->id;
      $data_tag = $data_import_detail[0]->tag;
      
      $totalImport=DB::select(DB::raw("SELECT ifnull(max(`sr_no`),0) as `maxid` from `voters` where `assembly_id` = $ac_id and `assembly_part_id` = $ac_part_id and `data_list_id` = $data_import_id;"));
      $maxid=$totalImport[0]->maxid;
      
      // $datas = DB::connection('sqlsrv')->select("SELECT SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, EMAIL_ID, MOBILE_NO, PHOTO from Query where ac_no = $ac_code and part_no = $part_no order by SlNoInPart");
      $datas = DB::connection('sqlsrv2')->select("SELECT EPIC_NUMBER, Applicant_First_Name, isnull(Applicant_last_name, '') as Applicant_last_name, Applicant_First_Name_l1, isnull(Applicant_last_name_l1, '') as Applicant_last_name_l1, part_serial_number, age, gender, relation_type, Relation_name, isnull(relation_l_name, '') as relation_l_name, relation_name_l1, isnull(rln_l_nm_v1, '') as rln_l_nm_v1, isnull(house_number, '') as house_number, isnull(house_number_l1, '') as house_number_l1, photo from eroll_data where Assembly_constituency_number = $ac_code and Part_number = $part_no and part_serial_number > $maxid order by part_serial_number");

      if(count($datas)>0){
        echo("Porting :: ".$ac_code."-".$part_no." Records (".count($datas).")\n");  
        $this->save_data_into_mysql($district_id, $ac_id, $ac_part_id, $data_import_id, $data_tag, $datas, $data_exists);
      }
      
      // /*Delete Modified Data*/
      // $datas = DB::connection('sqlsrv')->select("select SlNoInPart from addition_modification where ac_no =$ac_code and part_no =$part_no and status_type = 'M' ");
            
      // foreach ($datas as $key => $value) { 
      //   $assemblyPart=DB::select(DB::raw("delete from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $value->SlNoInPart and data_list_id = 4 and `village_id` = 0 limit 1;"));  
      // }

      // /*Delete Deleted Data*/
      // $datas = DB::connection('sqlsrv')->select("select SlNoInPart from deletions where ac_no =$ac_code and part_no =$part_no ");
            
      // foreach ($datas as $key => $value) { 
      //   $assemblyPart=DB::select(DB::raw("delete from `voters` where `assembly_part_id` = $ac_part_id and `sr_no` = $value->SlNoInPart and data_list_id = 4  and `village_id` = 0 limit 1;"));  
      // }


      // $datas = DB::connection('sqlsrv2')->select("select SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, EMAIL_ID, MOBILE_NO, PHOTO from addition_modification where ac_no =$ac_code and part_no =$part_no and SlNoInPart > $maxid order by SlNoInPart");
      // $datas = DB::connection('sqlsrv')->select("select SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, EMAIL_ID, MOBILE_NO, PHOTO from addition_modification where ac_no =$ac_code and part_no =$part_no and SlNoInPart > $maxid order by SlNoInPart");
      // if(count($datas)>0){
      //   echo("Porting New Data :: ".$ac_code."-".$part_no." Records (".count($datas).")\n");  
      //   $this->save_data_into_mysql($district_id, $ac_id, $ac_part_id, $data_import_id, $data_tag, $datas, $data_exists);
      // }
      
      
    }

    public function save_data_into_mysql($district_id, $ac_id, $ac_part_id, $data_import_id, $data_tag, $datas, $data_exists)
    {
      $dirpath = Storage_path('local') . '/app/vimage/'.$data_import_id.'/'.$ac_id.'/'.$ac_part_id;
      $vpath = '/vimage/'.$data_import_id.'/'.$ac_id.'/'.$ac_part_id;
      @mkdir($dirpath, 0755, true);

      
      foreach ($datas as $key => $value) { 
        $o_village_id = 0;
        $o_ward_id = 0;
        $o_print_srno = 0;
        $o_suppliment = 0;
        $o_booth_id = 0;
        $o_district_id = $district_id;
        
        $o_status = 0;  
        
        $name_l = trim($value->Applicant_First_Name_l1.' '.$value->Applicant_last_name_l1);
        $name_l=str_replace('਍', '', $name_l);
        $name_l=str_replace('\'', '', $name_l);
        $name_l=str_replace('\\', '', $name_l);

        $name_e=trim($value->Applicant_First_Name.' '.$value->Applicant_last_name);
        $name_e=substr(str_replace('਍', '', $name_e),0,49);
        $name_e=substr(str_replace('\'', '', $name_e),0,49);
        $name_e=substr(str_replace('\\', '', $name_e),0,49);
        
        $f_name_e=trim($value->Relation_name.' '.$value->relation_l_name);
        $f_name_e=substr(str_replace('਍', '', $f_name_e),0,49);
        $f_name_e=substr(str_replace('\'', '', $f_name_e),0,49);
        $f_name_e=substr(str_replace('\\', '', $f_name_e),0,49);

        $f_name_l=trim($value->relation_name_l1.' '.$value->rln_l_nm_v1);
        $f_name_l=str_replace('਍', '', $f_name_l);
        $f_name_l=str_replace('\'', '', $f_name_l);
        $f_name_l=str_replace('\\', '', $f_name_l);

        $rln_type = trim($value->relation_type);
        $relation=1;
        if ($rln_type=='F' || $rln_type=='FTHR') {
          $relation=1;  
        }elseif ($rln_type=='G') {
          $relation=2;  
        }elseif ($rln_type=='HSBN') {
          $relation=3;  
        }elseif ($rln_type=='MTHR') {
          $relation=4;  
        }elseif ($rln_type=='OTHR') {
          $relation=5;  
        }elseif ($rln_type=='WIFE') {
          $relation=6;  
        }

        $sql_gender = trim($value->gender);
        if ($sql_gender=='M') {
          $gender_id=1;  
        }
        elseif ($sql_gender=='F') {
          $gender_id=2;  
        }else{
          $gender_id=3;  
        }  
        $house_e = substr(str_replace('\\',' ', $value->house_number),0,49);
        $house_e = substr(str_replace('\'',' ', $house_e),0,49);

        $house_l = str_replace("\\",' ', $value->house_number_l1);
        $house_l = str_replace('\'',' ', $house_l);
        
        $part_sr_no = intval($value->part_serial_number);
        $age = intval($value->age);
        $epic_no = trim($value->EPIC_NUMBER);
        if($data_exists == 0){
          $newId = DB::select(DB::raw("call up_save_voter_detail($o_district_id, $ac_id, $ac_part_id, $part_sr_no, '$epic_no', '$house_e', '$house_l','','$name_e','$name_l','$f_name_e','$f_name_l', $relation, $gender_id, $age, '', 'v', $o_suppliment, $o_status, $o_village_id, $o_ward_id, '$o_print_srno', $o_booth_id, $data_import_id, '$data_tag');"));          
        }else{
          // $newId = DB::select(DB::raw("call up_save_voter_detail_if_not_exists($o_district_id, $ac_id, $ac_part_id, $value->SlNoInPart, '$value->EPIC_No', '$house_e', '$house_l','','$name_e','$name_l','$f_name_e','$f_name_l', $relation, $gender_id, $value->AGE, '$value->MOBILE_NO', 'v', $o_suppliment, $o_status, $o_village_id, $o_ward_id, '$o_print_srno', $o_booth_id, $data_import_id, '$data_tag');"));  
        }

        $image=$value->photo;
        $name = $part_sr_no;
        $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);
        
        
      }
    }

    public function import_deleted_votes($ac_code, $part_no, $district_id, $ac_id, $ac_part_id, $data_import_id)
    { 
     
      
      $totalImport=DB::select(DB::raw("SELECT ifnull(max(`sr_no`),0) as `maxid` from `voters` where `assembly_id` = $ac_id and `assembly_part_id` = $ac_part_id and `data_list_id` = $data_import_id and `status` = 2 ;"));
      $maxid=$totalImport[0]->maxid;

      $datas = DB::connection('sqlsrv2')->select("SELECT SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, EMAIL_ID, MOBILE_NO from Deleted where ac_no =$ac_code and part_no =$part_no and SlNoInPart > $maxid order by SlNoInPart");
    


      foreach ($datas as $key => $value) { 
        $name = $value->EPIC_No;
        $o_village_id = 0;
        $o_ward_id = 0;
        $o_print_srno = 0;
        $o_suppliment = 0;
        $o_booth_id = 0;
        $o_district_id = $district_id;
        
        $o_status = 2;  
        
        
        $name_l=str_replace('਍', '', $value->name_l);
        $name_l=str_replace('\'', '', $name_l);

        $name_e=substr(str_replace('਍', '', $value->name_en),0,49);
        $name_e=substr(str_replace('\'', '', $name_e),0,49);
       
        $f_name_e=substr(str_replace('਍', '', $value->fname_en),0,49);
        $f_name_e=substr(str_replace('\'', '', $f_name_e),0,49);

        $f_name_l=str_replace('਍', '', $value->FName_L);
        $f_name_l=str_replace('\'', '', $f_name_l);

        if ($value->RLN_Type=='F') {
          $relation=1;  
        }
        elseif ($value->RLN_Type=='G') {
          $relation=2;  
        } 
        elseif ($value->RLN_Type=='H') {
          $relation=3;  
        } 
        elseif ($value->RLN_Type=='M') {
          $relation=4;  
        } 
        elseif ($value->RLN_Type=='O') {
          $relation=5;  
        } 
        elseif ($value->RLN_Type=='W') {
          $relation=6;  
        }
        if ($value->GENDER=='M') {
          $gender_id=1;  
        }
        elseif ($value->GENDER=='F') {
          $gender_id=2;  
        }else{
          $gender_id=3;  
        }  
        $house_e = substr(str_replace('\\',' ', $value->C_House_no),0,49);
        $house_e = substr(str_replace('\'',' ', $house_e),0,49);

        $house_l = str_replace("\\",' ', $value->C_House_No_V1);
        $house_l = str_replace('\'',' ', $house_l);
        
        $newId = DB::select(DB::raw("call up_save_voter_detail($o_district_id, $ac_id, $ac_part_id, $value->SlNoInPart, '$value->EPIC_No', '$house_e', '$house_l','','$name_e','$name_l','$f_name_e','$f_name_l', $relation, $gender_id, $value->AGE, '$value->MOBILE_NO', 'v', $o_suppliment, $o_status, $o_village_id, $o_ward_id, '$o_print_srno', $o_booth_id, $data_import_id);"));
        
        
      }


      
    }   



    public function import_suppliment_voters($tabletype)  /** 1-New and Modified, 2-Deleted */
    { 
      ini_set('max_execution_time', '7200');
      ini_set('memory_limit','999M');
      ini_set("pcre.backtrack_limit", "100000000");

      $data_import_detail = DB::select(DB::raw("SELECT * from `import_type` where `status` = 1 limit 1;"));
      $data_import_id = $data_import_detail[0]->id;
      
      if ($tabletype == 1){
        $rs_ac_list = DB::connection('sqlsrv')->select("SELECT distinct ac_no from addition_modification where ac_no >= 78");  
      }else{
        $rs_ac_list = DB::connection('sqlsrv')->select("SELECT distinct ac_no from deletions order by ac_no");    
      }
      echo "ok\n";
      echo $rs_ac_list[0]->ac_no;
      return "";


      foreach ($rs_ac_list as $key => $val_ac_list) {
        $ac_code = $val_ac_list->ac_no;

        $rs_districts = DB::select(DB::raw("SELECT `district_id` from `assemblys` where code = $ac_code order by `district_id`;"));
        foreach ($rs_districts as $key => $val_districts) {
          $district_id = $val_districts->district_id;

          $assembly = DB::select(DB::raw("SELECT * from `assemblys` where `code` = $ac_code and `district_id` = $district_id limit 1;"));
          $ac_id = $assembly[0]->id;

          if ($tabletype == 1){
            $rs_ac_part_list = DB::connection('sqlsrv')->select("SELECT distinct part_no from addition_modification where ac_no = $ac_code order by part_no");  
          }else{
            $rs_ac_part_list = DB::connection('sqlsrv')->select("SELECT distinct part_no from deletions where ac_no = $ac_code order by part_no");
          }
          
          foreach ($rs_ac_part_list as $key => $val_ac_parts){
            $part_no = $val_ac_parts->part_no;
            
            $assemblyPart = DB::select(DB::raw("SELECT * from `assembly_parts` where `assembly_id` = $ac_id and `part_no` = $part_no limit 1;"));
            if(count($assemblyPart)>0){
              $ac_part_id = $assemblyPart[0]->id;

              $dirpath = Storage_path('voterimage') . '/app/vimage/'.$data_import_id.'/'.$ac_id.'/'.$ac_part_id;
              $vpath = '/vimage/'.$data_import_id.'/'.$ac_id.'/'.$ac_part_id;
              @mkdir($dirpath, 0755, true);

              if ($tabletype == 1){
                $totalImport=DB::select(DB::raw("SELECT ifnull(max(`sr_no`),0) as `maxid` from `voters_new_mod_del` where `assembly_id` = $ac_id and `assembly_part_id` = $ac_part_id and `status` <> 2;"));
                $maxid=$totalImport[0]->maxid;

                $datas = DB::connection('sqlsrv')->select("SELECT SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, '' as EMAIL_ID, MOBILE_NO, PHOTO from addition_modification where ac_no = $ac_code and part_no = $part_no and SlNoInPart > $maxid order by SlNoInPart");  
              }else{
                $totalImport=DB::select(DB::raw("SELECT ifnull(max(`sr_no`),0) as `maxid` from `voters_new_mod_del` where `assembly_id` = $ac_id and `assembly_part_id` = $ac_part_id and `status` = 2;"));
                $maxid=$totalImport[0]->maxid;

                $datas = DB::connection('sqlsrv')->select("SELECT SlNoInPart, C_House_no, C_House_No_V1, FM_Name_EN + ' ' + IsNULL(LastName_EN,'') as name_en, FM_Name_V1 + ' ' + isNULL(LastName_V1,'') as name_l, RLN_Type, RLN_FM_NM_EN + ' ' + IsNULL(RLN_L_NM_EN,'') as fname_en, RLN_FM_NM_V1 + ' ' + IsNULL(RLN_L_NM_V1,'') as FName_L, EPIC_No, STATUS_TYPE, GENDER, AGE, '' as EMAIL_ID, MOBILE_NO, PHOTO from deletions where ac_no = $ac_code and part_no = $part_no and SlNoInPart > $maxid order by SlNoInPart");
              }
              

              echo("Porting :: ".$ac_code."-".$part_no." Records (".count($datas).")\n");

              foreach ($datas as $key => $value) { 
                if ($tabletype == 1){
                  if($value->STATUS_TYPE == 'N'){
                    $o_status = 0;
                  }else{
                    $o_status = 3; 
                  } 
                }else{
                  $o_status = 2;  
                }
                  
                
                $name_l=str_replace('਍', '', $value->name_l);
                $name_l=str_replace('\'', '', $name_l);

                $name_e=substr(str_replace('਍', '', $value->name_en),0,49);
                $name_e=substr(str_replace('\'', '', $name_e),0,49);
               
                $f_name_e=substr(str_replace('਍', '', $value->fname_en),0,49);
                $f_name_e=substr(str_replace('\'', '', $f_name_e),0,49);

                $f_name_l=str_replace('਍', '', $value->FName_L);
                $f_name_l=str_replace('\'', '', $f_name_l);

                if ($value->RLN_Type=='F') {
                  $relation=1;  
                }
                elseif ($value->RLN_Type=='G') {
                  $relation=2;  
                } 
                elseif ($value->RLN_Type=='H') {
                  $relation=3;  
                } 
                elseif ($value->RLN_Type=='M') {
                  $relation=4;  
                } 
                elseif ($value->RLN_Type=='O') {
                  $relation=5;  
                } 
                elseif ($value->RLN_Type=='W') {
                  $relation=6;  
                }
                if ($value->GENDER=='M') {
                  $gender_id=1;  
                }
                elseif ($value->GENDER=='F') {
                  $gender_id=2;  
                }else{
                  $gender_id=3;  
                }  
                $house_e = substr(str_replace('\\',' ', $value->C_House_no),0,49);
                $house_e = substr(str_replace('\'',' ', $house_e),0,49);

                $house_l = str_replace("\\",' ', $value->C_House_No_V1);
                $house_l = str_replace('\'',' ', $house_l);

                // $rs_insert = DB::select(DB::raw("insert into `voters_new_mod_del` (`assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`, `mobile_no`, `status`) values ($ac_id, $ac_part_id, '$value->EPIC_No', $value->SlNoInPart, '$house_e', '$house_l', `uf_converthno`('$house_e'), '', '$name_e', '$name_l', '$f_name_e', '$f_name_l', $relation, $gender_id, $value->AGE, '$value->MOBILE_NO', $o_status);"));

                //Code With 0 House No Numeric
                $rs_insert = DB::select(DB::raw("INSERT into `voters_new_mod_del` (`assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`, `mobile_no`, `status`) values ($ac_id, $ac_part_id, '$value->EPIC_No', $value->SlNoInPart, '$house_e', '$house_l', 0, '', '$name_e', '$name_l', '$f_name_e', '$f_name_l', $relation, $gender_id, $value->AGE, '$value->MOBILE_NO', $o_status);"));

                if ($tabletype == 1){
                  $image=$value->PHOTO;
                  $name = $value->SlNoInPart;
                  $image= \Storage::disk('voterimage')->put($vpath.'/'.$name.'.jpg', $image);  
                } 
              }

            }
            
          }
        }   
      }

    }




    public function merge_supplement_data($district_id, $ac_id, $part_id, $datalist_id, $datatag)  
    { 

      $rs_supplement = DB::select(DB::raw("SELECT count(*) as `supplement` from `voters_new_mod_del` where `assembly_id` = $ac_id and `assembly_part_id` = $part_id;"));
      $total_supplement = $rs_supplement[0]->supplement;

      if($total_supplement > 0){
        $rs_data = DB::select(DB::raw("SELECT count(*) as `merged` from `supplement_data_merged` where `district_id` = $district_id and `assembly_id` = $ac_id and `assembly_part_id` = $part_id;"));
        $total_merged = $rs_data[0]->merged;

        if($total_merged == 0){
          $rs_data = DB::select(DB::raw("SELECT count(*) as `total_vote` from `voters` where `district_id` = $district_id and `assembly_id` = $ac_id and `assembly_part_id` = $part_id and `data_list_id` = $datalist_id;"));
          $total_vote = $rs_data[0]->total_vote;

          if($total_vote > 0){
            $rs_data = DB::select(DB::raw("SELECT count(*) as `marking` from `voters` where `district_id` = $district_id and `assembly_id` = $ac_id and `assembly_part_id` = $part_id and `data_list_id` = $datalist_id and `village_id` > 0;"));
            $total_marking = $rs_data[0]->marking;

            if($total_marking == 0){
              /*Modified/Deleted vote to be deleted*/
              $rs_modified = DB::select(DB::raw("SELECT * from `voters_new_mod_del` where `assembly_id` = $ac_id and `assembly_part_id` = $part_id and `status` in (2,3);"));
              echo("Merging / Deletion Data\n");
              foreach ($rs_modified as $key => $val_modified){
                $rs_delete = DB::select(DB::raw("DELETE from `voters` where  `district_id` = $district_id and `assembly_id` = $ac_id and `assembly_part_id` = $part_id and `sr_no` = $val_modified->sr_no and `data_list_id` = $datalist_id limit 1;")); 
              }

              /*New And modified voters added*/
              $rs_insert = DB::select(DB::raw("INSERT into `voters` (`district_id`, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`, `mobile_no`, `source`, `suppliment_no`, `status`, `village_id`, `ward_id`, `print_sr_no`, `booth_id`, `data_list_id`, `data_list_tag`, `old_srno`) select $district_id, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`,  `mobile_no`, 'V', 0, 0, 0, 0, 0, 0, $datalist_id, '$datatag', 0 from `voters_new_mod_del` where `assembly_id` = $ac_id and `assembly_part_id` = $part_id and `status` <> 2;"));

            }else{
              $rs_insert = DB::select(DB::raw("INSERT into `voters` (`district_id`, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`, `mobile_no`, `source`, `suppliment_no`, `status`, `village_id`, `ward_id`, `print_sr_no`, `booth_id`, `data_list_id`, `data_list_tag`, `old_srno`) select $district_id, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`,  `mobile_no`, 'V', 0, 0, 0, 0, 0, 0, $datalist_id, '$datatag', 0 from `voters_new_mod_del` where `assembly_id` = $ac_id and `assembly_part_id` = $part_id and `status` = 0;"));          
            }
          }

          $rs_insert = DB::select(DB::raw("INSERT into `supplement_data_merged` (`district_id`, `assembly_id`, `assembly_part_id`) values ($district_id, $ac_id, $part_id);"));

        }  
      }
      

          
    }




    public function insert_supplement_data()  /*Voters after 05 Jan 2022 to 16 may 2022*/
    { 

      $rs_ac_list = DB::select(DB::raw("SELECT * from `assemblys` where `id`>= 37 order by `id`;"));
      foreach ($rs_ac_list as $key => $val_ac_list){
        $ac_id = $val_ac_list->id;
        $district_id = $val_ac_list->district_id;

        $rs_part_nos = DB::select(DB::raw("SELECT distinct `assembly_part_id` from `voters_new_mod_del` where `assembly_id` = $ac_id;"));
        foreach ($rs_part_nos as $key => $val_part_no){
          echo "processing part id :: ".$val_part_no->assembly_part_id." \n";
          $part_id = $val_part_no->assembly_part_id;
          //Check if Total Voters in list 4 exists
          $rs_result = DB::select(DB::raw("SELECT count(*) as `tvoters` from `voters` where `assembly_part_id` = $part_id and `data_list_id` = 4;"));
          $total_voter_list_4 = $rs_result[0]->tvoters;
          if($total_voter_list_4>0){
            //Check if total voters mapped
            $rs_result = DB::select(DB::raw("SELECT count(*) as `tvoters` from `voters` where `assembly_part_id` = $part_id and `ward_id` >0 and `data_list_id` = 4 ;"));  
            $voter_mapped_list_4 = $rs_result[0]->tvoters;
            if($voter_mapped_list_4>0){
              //check if data already inserted or not
              $rs_result = DB::select(DB::raw("SELECT * from `voters_new_mod_del` where `assembly_part_id` = $part_id and `status` = 0 limit 1;"));  
              if(count($rs_result)>0){
                $voter_srno = $rs_result[0]->sr_no;
                $rs_result = DB::select(DB::raw("SELECT * from `voters`  where `assembly_part_id` = $part_id and sr_no = $voter_srno and `data_list_id` = 4 limit 1;"));
                if(count($rs_result)>0){
                  echo "Data Already Merged \n";
                }else{
                  $del_process = 0;
                  $new_process = 1;
                  $this->add_delete_supplement_data($district_id, $part_id, $del_process, $new_process);
                  echo "New Data Merged \n";
                }  
              }else{
                echo "New Data not exists in this part \n";
              }
            }else{
              //process if data is not mapped
              //check if data already inserted or not
              $rs_result = DB::select(DB::raw("SELECT * from `voters_new_mod_del` where `assembly_part_id` = $part_id and `status` = 0 limit 1;"));  
              if(count($rs_result)>0){
                $voter_srno = $rs_result[0]->sr_no;
                $rs_result = DB::select(DB::raw("SELECT * from `voters`  where `assembly_part_id` = $part_id and sr_no = $voter_srno and `data_list_id` = 4 limit 1;"));
                if(count($rs_result)>0){
                  echo "Data Already Merged \n";
                }else{
                  $del_process = 1;
                  $new_process = 1;
                  $this->add_delete_supplement_data($district_id, $part_id, $del_process, $new_process);
                  echo "New Data Merged \n";
                }  
              }else{
                // prcess only deleted/modified data
                $del_process = 1;
                $new_process = 1;
                $this->add_delete_supplement_data($district_id, $part_id, $del_process, $new_process);
              }
            }
          }else{
            //check if voters mapped or not  (in data lists)
            $rs_result = DB::select(DB::raw("SELECT count(*) as `tvoters` from `voters` where `assembly_part_id` = $part_id and `ward_id` >0 ;"));  
            $voter_mapped = $rs_result[0]->tvoters;
            if($voter_mapped>0){
              //check if data already inserted or not
              $rs_result = DB::select(DB::raw("SELECT * from `voters_new_mod_del` where `assembly_part_id` = $part_id and `status` = 0 limit 1;"));  
              if(count($rs_result)>0){
                $voter_srno = $rs_result[0]->sr_no;
                $rs_result = DB::select(DB::raw("SELECT * from `voters`  where `assembly_part_id` = $part_id and sr_no = $voter_srno and `data_list_id` = 4 limit 1;"));
                if(count($rs_result)>0){
                  echo "Data Already Merged \n";
                }else{
                  $del_process = 0;
                  $new_process = 1;
                  $this->add_delete_supplement_data($district_id, $part_id, $del_process, $new_process);
                  echo "New Data Merged \n";
                }  
              }else{
                echo "New Data not exists in this part \n";
              }
            }else{
              //Nothing to do plz import from data import module
            }

          }

        }
      }

    }


  public function add_delete_supplement_data($district_id, $partid, $del_process, $new_process)  /*Voters after 05 Jan 2022 to 16 may 2022*/
  {
    $status_condition = ' and `status` = 0 ';
    if($del_process>0){
      $rs_modified = DB::select(DB::raw("SELECT * from `voters_new_mod_del` where `assembly_part_id` = $partid and `status` in (2);"));
      echo("Deleting Data\n");
      foreach ($rs_modified as $key => $val_modified){
        $rs_delete = DB::select(DB::raw("DELETE from `voters` where  `district_id` = $district_id and `assembly_part_id` = $partid and `sr_no` = $val_modified->sr_no and `data_list_id` = 4 and `ward_id` = 0 limit 1;")); 
      }
    }

    if($new_process > 0){
      $rs_insert = DB::select(DB::raw("INSERT into `voters` (`district_id`, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`, `mobile_no`, `source`, `suppliment_no`, `status`, `village_id`, `ward_id`, `print_sr_no`, `booth_id`, `data_list_id`, `data_list_tag`, `old_srno`) select $district_id, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`,  `mobile_no`, 'V', 0, 0, 0, 0, 0, 0, 4, '**', 0 from `voters_new_mod_del` where `assembly_part_id` = $partid $status_condition;"));
    }
  }


  // //Code to unloac and lock villages having from srmo and to srno not correct
  // public function auto_unlock_lock() 
  // {
  //   $rs_villages = DB::select(DB::raw("select distinct `wv`.`village_id` from `main_page_detail` `mpd` inner join `ward_villages` `wv` on `wv`.`id` = `mpd`.`ward_id` inner join `blocks_mcs` `bl` on `bl`.`id` = `wv`.`blocks_id` where `mpd`.`to_sr_no` <> `mpd`.`total` and `bl`.`block_mc_type_id` = 1 limit 10;"));
    
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
      
}

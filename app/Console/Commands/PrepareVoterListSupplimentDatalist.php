<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrepareVoterListSupplimentDatalist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datalist:generate {district_id} {block_id} {village_id} {ward_id} {booth_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'datalist generate';

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
        $block_id = $this->argument('block_id'); 
        $village_id = $this->argument('village_id'); 
        $ward_id = $this->argument('ward_id');
        $booth_id = $this->argument('booth_id');

        $voterListMaster = DB::select(DB::raw("select * from `voter_list_master` where `status` = 1 and `block_id` = $block_id limit 1;"));
        $voterListMaster = reset($voterListMaster); 

        $blockcode = DB::select(DB::raw("select * from `blocks_mcs` where `id` = $block_id limit 1;"));
        $blockcode = reset($blockcode);

        $wardno = DB::select(DB::raw("select * from `ward_villages` where `id` = $ward_id limit 1;")); 
        $wardno = reset($wardno);

        $villagename = DB::select(DB::raw("select * from `villages` where `id` = $village_id limit 1;"));
        $villagename = reset($villagename);

        $pollingboothdetail = DB::select(DB::raw("select * from `polling_booths` where `id` = $booth_id limit 1;"));
        $pollingboothdetail = reset($pollingboothdetail);


        $VoterListProcessed = DB::select(DB::raw("select * from `voter_list_processeds` where `village_id` = $village_id and `ward_id` = $ward_id and `booth_id` = $booth_id and `voter_list_master_id` = $voterListMaster->id and `status` = 0 limit 1;"));
        $VoterListProcessed = reset($VoterListProcessed);

        
        $dirpath = Storage_path() . $VoterListProcessed->folder_path;
        @mkdir($dirpath, 0755, true);

        $path=Storage_path('fonts/');
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir']; 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata']; 
        
        $mpdf_mainpage = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                     __DIR__ . $path,
                 ]),
                 'fontdata' => $fontData + [
                     'frutiger' => [
                         'R' => 'FreeSans.ttf',
                         'I' => 'FreeSansOblique.ttf',
                     ]
                 ],
                 'default_font' => 'freesans',
             ]);

        $mpdf_photo = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                     __DIR__ . $path,
                 ]),
                 'fontdata' => $fontData + [
                     'frutiger' => [
                         'R' => 'FreeSans.ttf',
                         'I' => 'FreeSansOblique.ttf',
                     ]
                 ],
                 'default_font' => 'freesans',
             ]);

        $mpdf_wp = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                     __DIR__ . $path,
                 ]),
                 'fontdata' => $fontData + [
                     'frutiger' => [
                         'R' => 'FreeSans.ttf',
                         'I' => 'FreeSansOblique.ttf',
                     ]
                 ],
                 'default_font' => 'freesans',
             ]);
        

        if ($booth_id==0) {
            $WardVillages = DB::select(DB::raw("select * from `ward_villages` where `id` = $ward_id limit 1;"));
            $pagetype=1;}
        else {
            $WardVillages = DB::select(DB::raw("select * from `ward_villages` where `id` = $ward_id limit 1;"));
            $pagetype=2;
        }  
          
        $html = view('admin.master.PrepareVoterList.voter_list_section.start_pdf');

        $html = $html.'</style></head><body>';

        
        $mpdf_photo->WriteHTML($html);
        $mpdf_mainpage->WriteHTML($html);
        $mpdf_wp->WriteHTML($html);
        
        
        $wardcount = 1;
        $totalpage=0;
        foreach ($WardVillages as $WardVillage) {
            if ($booth_id==0){$booth_condition = "";}else{$booth_condition = " And `v`.`booth_id` = $booth_id";}


            $mainpagedetails_rs= DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$WardVillage->id and `booth_id` = $booth_id;"));
            $mainpagedetails=count($mainpagedetails_rs);

            
            if ($mainpagedetails>0){

                if ($wardcount>1){
                    $mpdf_photo->WriteHTML('<pagebreak>');
                    $mpdf_wp->WriteHTML('<pagebreak>');
                    if(fmod($totalpage, 2)==1){
                        $mpdf_photo->WriteHTML('<pagebreak>');
                        $mpdf_wp->WriteHTML('<pagebreak>'); 
                    }    
                }
                $wardcount++;

                // $voterReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat(`v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `suppliment_voters_new` `sv` inner Join `voters` `v` on `sv`.`voters_id` = `v`.`id` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `sv`.`ward_id` = $WardVillage->id and `sv`.`suppliment_no` = $voterListMaster->id  $booth_condition Order By `v`.`print_sr_no`;"));

                $voterReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat(`v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = $WardVillage->id and `v`.`status` = 1  $booth_condition Order By `v`.`print_sr_no`;"));

                // $votermodifiedReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat(`v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `suppliment_voters_modified` `sv` inner join`voters` `v` on `sv`.`voters_id` = `v`.`id` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = $WardVillage->id and `sv`.`suppliment_no` = $voterListMaster->id  $booth_condition Order By `v`.`print_sr_no`;"));

                $votermodifiedReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat(`v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = $WardVillage->id and `v`.`status` = 3  $booth_condition Order By `v`.`print_sr_no`;"));

                // $voterDeletedReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat(`v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `suppliment_voters_deleted` `sv` inner join `voters` `v` on `sv`.`voters_id` = `v`.`id` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `sv`.`ward_id` = $WardVillage->id and `sv`.`suppliment_no` = $voterListMaster->id  $booth_condition Order By `v`.`print_sr_no`;"));

                $voterDeletedReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat(`v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = $WardVillage->id and `v`.`status` = 2  $booth_condition Order By `v`.`print_sr_no`;"));

                $mainpagedetails= DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$WardVillage->id and `booth_id` = $booth_id;"));
                
                


                $votercount = count($voterReports);
                $votermodifiedcount = count($votermodifiedReports);
                $voterdeletedcount = count($voterDeletedReports);

                $totalnewrows = (int)($votercount/3);
                if ($totalnewrows*3<$votercount){$totalnewrows++;}

                $totalmodifiedrows = (int)($votermodifiedcount/3);
                if ($totalmodifiedrows*3<$votermodifiedcount){$totalmodifiedrows++;}

                $totaldeletedrows = (int)($voterdeletedcount/3);
                if ($totaldeletedrows*3<$voterdeletedcount){$totaldeletedrows++;}
                
                $totalRows = $totalnewrows + $totalmodifiedrows + $totaldeletedrows;
                $totalpage = (int)($totalRows/9);
                if ($totalpage*9<$totalRows){$totalpage++;}
                $totalpage++;

                $main_page=$this->prepareMainPage($mainpagedetails, $totalpage, $pagetype, 1);
                $mpdf_photo->WriteHTML($main_page);
                $mpdf_mainpage->WriteHTML($main_page);
                $mpdf_wp->WriteHTML($main_page);

            
                $printphoto = 1;
                if ($votercount>0){
                    $SuchiType = 'घटक - 1 : परिवर्धन सूचि';
                    $PrintedRows = 0;
                    $cpageno = 1;
                    $main_page=$this->prepareVoterDetailSuppliment($voterReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno);
                    $mpdf_photo->WriteHTML($main_page);
                }

                if ($voterdeletedcount>0){
                    $SuchiType = 'घटक - 2 : विलोपन सूचि';
                    $PrintedRows = $totalnewrows;
                    $cpageno = (int)($PrintedRows/9);
                    $cpageno++;
                    $main_page=$this->prepareDeletedVoterSuppliment($voterDeletedReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno);
                    // echo($main_page);
                    $mpdf_photo->WriteHTML($main_page);
                }

                // echo($WardVillage->id.' - ');
                if ($votermodifiedcount>0){
                    $SuchiType = 'घटक - 3 : संसोधन सूचि';
                    $PrintedRows = $totalnewrows + $totaldeletedrows;
                    $cpageno = (int)($PrintedRows/9);
                    $cpageno++;

                    $main_page=$this->prepareVoterDetailSuppliment($votermodifiedReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno);
                    $mpdf_photo->WriteHTML($main_page);
                }

                

                
                $printphoto = 0;
                if ($votercount>0){
                    $SuchiType = 'घटक - 1 : परिवर्धन सूचि';
                    $PrintedRows = 0;
                    $cpageno = (int)($PrintedRows/9);
                    $cpageno++;
                    $main_page=$this->prepareVoterDetailSuppliment($voterReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno);
                    $mpdf_wp->WriteHTML($main_page);
                }

                if ($voterdeletedcount>0){
                    $SuchiType = 'घटक - 2 : विलोपन सूचि';
                    $PrintedRows = $totalnewrows;
                    $cpageno = (int)($PrintedRows/9);
                    $cpageno++;
                    $main_page=$this->prepareDeletedVoterSuppliment($voterDeletedReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno);
                    $mpdf_wp->WriteHTML($main_page);
                }

                
                if ($votermodifiedcount>0){
                    $SuchiType = 'घटक - 3 : संसोधन सूचि';
                    $PrintedRows = $totalnewrows + $totaldeletedrows;
                    $cpageno = (int)($PrintedRows/9);
                    $cpageno++;
                    $main_page=$this->prepareVoterDetailSuppliment($votermodifiedReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno);
                    $mpdf_wp->WriteHTML($main_page);
                }

                if ($totalRows>0){
                    $main_page=$this->prepareWardEndSuppliment($mainpagedetails, $totalpage, $votercount, $votermodifiedcount, $voterdeletedcount, $totalnewrows, $totalmodifiedrows, $totaldeletedrows);
                    $mpdf_photo->WriteHTML($main_page);
                    $mpdf_wp->WriteHTML($main_page);
                }
                
            }
        }
        

        $mpdf_photo->WriteHTML('</body></html>');
        $mpdf_mainpage->WriteHTML('</body></html>');
        $mpdf_wp->WriteHTML('</body></html>');
        
        
        $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path_h;
        $mpdf_mainpage->Output($filepath, 'F');

        $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path_p;
        $mpdf_photo->Output($filepath, 'F');

        $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path_w;
        $mpdf_wp->Output($filepath, 'F');


        $newId=DB::select(DB::raw("Update `voter_list_processeds` set `status` = 1 where `id` = $VoterListProcessed->id;"));

      
    }


    public function prepareWardEndSuppliment($mainpagedetails, $totalpage, $votercount, $votermodifiedcount, $voterdeletedcount, $totalnewrows, $totalmodifiedrows, $totaldeletedrows)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.supplimentDatalistwise.ward_end_suppliment',compact('mainpagedetails', 'totalpage', 'votercount', 'votermodifiedcount', 'voterdeletedcount', 'totalnewrows', 'totalmodifiedrows', 'totaldeletedrows'));    
    }

    public function prepareDeletedVoterSuppliment($voterReports, $mainpagedetails, $totalpage,$printphoto, $SuchiType, $PrintedRows, $cpageno)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.supplimentDatalistwise.deleted_voter_supplement',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'SuchiType', 'PrintedRows', 'cpageno'));    
    }

    public function prepareVoterDetailSuppliment($voterReports, $mainpagedetails, $totalpage,$printphoto, $SuchiType, $PrintedRows, $cpageno)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.supplimentDatalistwise.voter_detail_suppliment',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'SuchiType', 'PrintedRows', 'cpageno'));    
    }

    
    public function prepareMainPage($mainpagedetails, $totalpage, $main_page_type, $is_suppliment)
    {
        return $main_page = view('admin.master.PrepareVoterList.supplimentDatalistwise.main_page',compact('mainpagedetails', 'totalpage', 'main_page_type', 'is_suppliment'));    
    }
    
       
}

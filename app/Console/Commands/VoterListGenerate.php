<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VoterListGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voterlist:generate';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'voterlist generate';

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
        ini_set('max_execution_time', '28800');
        ini_set('memory_limit','9999M');
        ini_set("pcre.backtrack_limit", "100000000");

        $queue_id = 0;
        while($queue_id == 0){
            $rs_fetch = DB::select(DB::raw("call `up_fetch_id_for_voterListGenerate`();"));
            $queue_id = $rs_fetch[0]->queue_id;
            if($queue_id == 0){
                sleep(30);
            }
        }

        while ($queue_id > 0) {
            $rs_VoterListProcessed = DB::select(DB::raw("SELECT * from `voter_list_processeds` where `id` = $queue_id limit 1;"));            

            $district_id = $rs_VoterListProcessed[0]->district_id;
            $block_id = $rs_VoterListProcessed[0]->block_id; 
            $village_id = $rs_VoterListProcessed[0]->village_id; 
            $ward_id = $rs_VoterListProcessed[0]->ward_id;
            $booth_id = $rs_VoterListProcessed[0]->booth_id;

            $voterListMaster = DB::select(DB::raw("SELECT * from `voter_list_master` where `status` = 1 and `block_id` = $block_id limit 1;"));
            $voterListMaster = reset($voterListMaster);

            $blockcode = DB::select(DB::raw("SELECT * from `blocks_mcs` where `id` = $block_id limit 1;"));
            $blockcode = reset($blockcode);

            $wardno = DB::select(DB::raw("SELECT * from `ward_villages` where `id` = $ward_id limit 1;")); 
            $wardno = reset($wardno);

            $villagename = DB::select(DB::raw("SELECT * from `villages` where `id` = $village_id limit 1;"));
            $villagename = reset($villagename);

            $pollingboothdetail = DB::select(DB::raw("SELECT * from `polling_booths` where `id` = $booth_id limit 1;"));
            $polling_booth_area = "";
            if(count($pollingboothdetail) >0 ){
                $polling_booth_area = $pollingboothdetail[0]->booth_area_l;
            }
            $pollingboothdetail = reset($pollingboothdetail);

            foreach ($rs_VoterListProcessed as $key => $VoterListProcessed) {
                $dirpath = Storage_path() . $VoterListProcessed->folder_path;
                @mkdir($dirpath, 0755, true);
                chmod($dirpath, 0755);

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
                
                
                if ($ward_id==0) {
                    $WardVillages = DB::select(DB::raw("SELECT * from `ward_villages` where `village_id` = $village_id order by `ward_no`;"));
                    $pagetype=1;}
                elseif ($booth_id==0) {
                    $WardVillages = DB::select(DB::raw("SELECT * from `ward_villages` where `id` = $ward_id limit 1;"));
                    $pagetype=2;}
                else {
                    $WardVillages = DB::select(DB::raw("SELECT * from `ward_villages` where `id` = $ward_id limit 1;"));
                    $pagetype=3;
                }  
                  
                $html = view('admin.master.PrepareVoterList.voter_list_section.start_pdf');

                $html = $html.'</style></head><body>';

                
                $mpdf_photo->WriteHTML($html);
                $mpdf_mainpage->WriteHTML($html);
                $mpdf_wp->WriteHTML($html);

                if ($VoterListProcessed->full_supplement==0) {
                    $wardcount = 1;
                    $totalpage=0;
                    $voter_per_page = 30;
                    echo "Start Processing :: ".$villagename->name_e." \n";
                    foreach ($WardVillages as $WardVillage) {
                        echo "Processing :: ".$WardVillage->ward_no." \n";
                        if ($wardcount>1){
                            $mpdf_photo->WriteHTML('<pagebreak>');
                            $mpdf_wp->WriteHTML('<pagebreak>');
                            if(fmod($totalpage, 2)==1){
                                $mpdf_photo->WriteHTML('<pagebreak>');
                                $mpdf_wp->WriteHTML('<pagebreak>'); 
                            }    
                        }
                        $wardcount++;

                        if ($booth_id==0){$booth_condition = "";}else{$booth_condition = " And `v`.`booth_id` = $booth_id";}

                        $sr_condition = "";
                        $pageno = $VoterListProcessed->page_no;
                        $list_no = $VoterListProcessed->list_no;

                        if ($pagetype==2){
                            $sr_condition = " and `v`.`print_sr_no` >= $VoterListProcessed->from_srno and `v`.`print_sr_no` <= $VoterListProcessed->to_srno ";
                                
                        }else{
                            $pageno = 1;
                            $list_no = 1;    
                        }                    
                        

                        $voterReports = DB::select(DB::raw("SELECT `v`.`id`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`sr_no`, `v`.`voter_card_no`, concat(`v`.`data_list_tag`, `v`.`sr_no`, '/', `ap`.`part_no`) as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id`, `v`.`status` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` =$WardVillage->id And `v`.`status` in (0,1,3) $booth_condition $sr_condition Order By `v`.`print_sr_no`;"));

                        $rsDataListRemarks = DB::select(DB::raw("SELECT * from `import_type` where `id` in (select distinct `data_list_id` from `voters` `v` where `v`.`ward_id` =$WardVillage->id And `v`.`status` <> 2 $booth_condition);"));
                        
                        

                        $rs_mainpagedetails = DB::select(DB::raw("SELECT * from `main_page_detail` where `voter_list_master_id` = $voterListMaster->id and `ward_id` = $WardVillage->id and `booth_id` = $booth_id limit 1;"));
                        $mainpagedetails = count($rs_mainpagedetails);

                        
                        if ($mainpagedetails>0){
                            $mainpagedetails= DB::select(DB::raw("SELECT * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$WardVillage->id and `booth_id` = $booth_id limit 1;"));
                            
                            $voterssrnodetails = DB::select(DB::raw("SELECT * From `voters_srno_detail` where `voter_list_master_id` =$voterListMaster->id and `wardid` = $WardVillage->id And booth_id = $booth_id order by `partno`, `fromsrno`;"));

                            $voterssrnotext = DB::select(DB::raw("SELECT * From `voters_srno_detail_text` where `voter_list_master_id` =$voterListMaster->id and `wardid` = $WardVillage->id And booth_id = $booth_id order by `partno`;"));

                            $rs_result = DB::select(DB::raw("SELECT `partno` , count(*) as `trecords` From `voters_srno_detail` where `voter_list_master_id` =$voterListMaster->id and `wardid` = $WardVillage->id And booth_id = $booth_id group by `partno`;"));

                            $showsrnotext = 0;
                            $srno_rows = 0;
                            foreach ($rs_result as $val_srno) {
                                $srno_rows = $srno_rows + 2 + intdiv($val_srno->trecords,3);
                                if(fmod($val_srno->trecords,3)>0){
                                    $srno_rows = $srno_rows + 1;    
                                }
                            }
                            if($srno_rows > 15 && $srno_rows <= 100){
                                $showsrnotext = 1;    
                            }elseif($srno_rows > 100){
                                $showsrnotext = 2;
                            }
                            echo "Show text :: ".$showsrnotext." \n";

                            // $votercount = count($voterReports);
                            $votercount = $mainpagedetails[0]->total;
                            $totalpage = (int)($votercount/$voter_per_page);
                            if ($totalpage*$voter_per_page<$votercount){$totalpage++;}
                            $totalpage++;

                            $totalnewrows = (int)($votercount/3);
                            if ($totalnewrows*3<$votercount){$totalnewrows++;}
                            $totalRows = $totalnewrows;

                            $main_page=$this->prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $pagetype, 0, $rsDataListRemarks, $showsrnotext, $voterssrnotext, $polling_booth_area);

                            if($list_no<=1){
                                $mpdf_photo->WriteHTML($main_page);
                            }
                            $mpdf_mainpage->WriteHTML($main_page);
                            $mpdf_wp->WriteHTML($main_page);

                            $SuchiType = "";
                            $PrintedRows = 0;
                            // $cpageno = 1;
                            $cpageno = $pageno;
                        
                            $printphoto = 1;
                            $main_page=$this->prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                            $mpdf_photo->WriteHTML($main_page);
                        
                            $printphoto = 0;
                            $main_page=$this->prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                            $mpdf_wp->WriteHTML($main_page);

                            $filelastpageno = $list_no*300;
                            if($filelastpageno > $totalpage){
                                $filelastpageno = $totalpage;
                            }
                            if ($totalRows>0){
                                $main_page=$this->prepareWardEndDetail($mainpagedetails, $totalpage, $votercount, 0, 0, $totalnewrows, 0, 0, 10, $rsDataListRemarks, $filelastpageno);
                                $mpdf_photo->WriteHTML($main_page);
                                $mpdf_wp->WriteHTML($main_page);
                            }
                        }
                        
                    }

                }else{
                    $voter_per_page = 27;
                    $wardcount = 1;
                    $totalpage=0;
                    foreach ($WardVillages as $WardVillage) {
                        echo "Processing :: ".$WardVillage->ward_no." \n";
                        if ($booth_id==0){$booth_condition = "";}else{$booth_condition = " And `sv`.`booth_id` = $booth_id";}

                        $mainpagedetails_rs= DB::select(DB::raw("SELECT * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$WardVillage->id and `booth_id` = $booth_id;"));
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

                            $voterReports = DB::select(DB::raw("SELECT `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, concat(`v`.`data_list_tag`, `v`.`sr_no`, '/', `ap`.`part_no`) as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `suppliment_voters_new` `sv` inner Join `voters` `v` on `sv`.`voters_id` = `v`.`id` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `sv`.`ward_id` = $WardVillage->id and `sv`.`suppliment_no` = $voterListMaster->id and `v`.`suppliment_no` = $voterListMaster->id  $booth_condition Order By `v`.`print_sr_no`;"));

                            $votermodifiedReports = DB::select(DB::raw("SELECT `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, concat(`v`.`data_list_tag`, `v`.`sr_no`, '/', `ap`.`part_no`) as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `suppliment_voters_modified` `sv` inner join`voters` `v` on `sv`.`voters_id` = `v`.`id` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = $WardVillage->id and `sv`.`suppliment_no` = $voterListMaster->id  $booth_condition Order By `v`.`print_sr_no`;"));

                            $voterDeletedReports = DB::select(DB::raw("SELECT `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `sv`.`print_sr_no`, `v`.`voter_card_no`, concat(`v`.`data_list_tag`, `v`.`sr_no`, '/', `ap`.`part_no`) as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id` From `suppliment_voters_deleted` `sv` inner join `voters` `v` on `sv`.`voters_id` = `v`.`id` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `sv`.`ward_id` = $WardVillage->id and `sv`.`suppliment_no` = $voterListMaster->id  $booth_condition Order By `sv`.`print_sr_no`;"));

                            $rsDataListRemarks = DB::select(DB::raw("SELECT * from `import_type` where `id` in (select distinct `data_list_id` from `voters` `sv` where `sv`.`ward_id` =$WardVillage->id And `sv`.`suppliment_no` = $voterListMaster->id $booth_condition);"));
                            

                            $mainpagedetails= DB::select(DB::raw("SELECT * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$WardVillage->id and `booth_id` = $booth_id;"));
                            
                            $voterssrnodetails = DB::select(DB::raw("SELECT * From `voters_srno_detail` where `voter_list_master_id` =$voterListMaster->id and `wardid` = $WardVillage->id And booth_id = $booth_id;"));

                            $voterssrnotext = DB::select(DB::raw("SELECT * From `voters_srno_detail_text` where `voter_list_master_id` =$voterListMaster->id and `wardid` = $WardVillage->id And booth_id = $booth_id order by `partno`;"));

                            $showsrnotext = 0;

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

                            $main_page=$this->prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $pagetype, 1, $rsDataListRemarks, $showsrnotext, $voterssrnotext, $polling_booth_area);
                            $mpdf_photo->WriteHTML($main_page);
                            $mpdf_mainpage->WriteHTML($main_page);
                            $mpdf_wp->WriteHTML($main_page);

                            $printphoto = 1;
                            if ($votercount>0){
                                $SuchiType = 'घटक - 1 : परिवर्धन सूचि';
                                $PrintedRows = 0;
                                $cpageno = 1;
                                $main_page=$this->prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                                $mpdf_photo->WriteHTML($main_page);
                            }

                            if($voterdeletedcount>0 || $votermodifiedcount>0){
                                $PrintedRows = $totalnewrows;
                                $cpageno = (int)($PrintedRows/9);
                                $cpageno++;
                                if(fmod($PrintedRows, 9) == 0 && $PrintedRows > 0){
                                    $main_page=view('admin.master.PrepareVoterList.voter_list_section.page_end',compact('mainpagedetails', 'rsDataListRemarks', 'totalpage', 'cpageno'));
                                    $mpdf_photo->WriteHTML($main_page); 
                                    $cpageno++;
                                }
                            }

                            if ($voterdeletedcount>0){
                                $SuchiType = 'घटक - 2 : विलोपन सूचि';
                                // $PrintedRows = $totalnewrows;
                                // $cpageno = (int)($PrintedRows/9);
                                // $cpageno++;
                                $main_page=$this->prepareDeletedVoterSuppliment($voterDeletedReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                                $mpdf_photo->WriteHTML($main_page);
                            }

                            if($votermodifiedcount>0){
                                $PrintedRows = $totalnewrows + $totaldeletedrows;
                                $cpageno = (int)($PrintedRows/9);
                                $cpageno++;
                                if(fmod($PrintedRows, 9) == 0 && $PrintedRows > 0){
                                    $main_page=view('admin.master.PrepareVoterList.voter_list_section.page_end',compact('mainpagedetails', 'rsDataListRemarks', 'totalpage', 'cpageno'));
                                    $mpdf_photo->WriteHTML($main_page);
                                    $cpageno++;       
                                }
                            }

                            if ($votermodifiedcount>0){
                                $SuchiType = 'घटक - 3 : संसोधन सूचि';
                                // $PrintedRows = $totalnewrows + $totaldeletedrows;
                                // $cpageno = (int)($PrintedRows/9);
                                // $cpageno++;
                                $main_page=$this->prepareVoterDetail($votermodifiedReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                                
                                $mpdf_photo->WriteHTML($main_page);
                            }


                            $printphoto = 0;
                            if ($votercount>0){
                                $SuchiType = 'घटक - 1 : परिवर्धन सूचि';
                                $PrintedRows = 0;
                                $cpageno = 1;
                                $main_page=$this->prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                                $mpdf_wp->WriteHTML($main_page);
                            }

                            if($voterdeletedcount>0 || $votermodifiedcount>0){
                                $PrintedRows = $totalnewrows;
                                $cpageno = (int)($PrintedRows/9);
                                $cpageno++;
                                if(fmod($PrintedRows, 9) == 0 && $PrintedRows > 0){
                                    $main_page=view('admin.master.PrepareVoterList.voter_list_section.page_end',compact('mainpagedetails', 'rsDataListRemarks', 'totalpage', 'cpageno'));
                                    $mpdf_photo->WriteHTML($main_page); 
                                    $cpageno++;
                                }
                            }

                            if ($voterdeletedcount>0){
                                $SuchiType = 'घटक - 2 : विलोपन सूचि';
                                // $PrintedRows = $totalnewrows;
                                // $cpageno = (int)($PrintedRows/9);
                                // $cpageno++;
                                $main_page=$this->prepareDeletedVoterSuppliment($voterDeletedReports, $mainpagedetails, $totalpage, $printphoto, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                                $mpdf_wp->WriteHTML($main_page);
                            }

                            if($votermodifiedcount>0){
                                $PrintedRows = $totalnewrows + $totaldeletedrows;
                                $cpageno = (int)($PrintedRows/9);
                                $cpageno++;
                                if(fmod($PrintedRows, 9) == 0 && $PrintedRows > 0){
                                    $main_page=view('admin.master.PrepareVoterList.voter_list_section.page_end',compact('mainpagedetails', 'rsDataListRemarks', 'totalpage', 'cpageno'));
                                    $mpdf_photo->WriteHTML($main_page);
                                    $cpageno++;       
                                }
                            }

                            if ($votermodifiedcount>0){
                                $SuchiType = 'घटक - 3 : संसोधन सूचि';
                                // $PrintedRows = $totalnewrows + $totaldeletedrows;
                                // $cpageno = (int)($PrintedRows/9);
                                // $cpageno++;
                                $main_page=$this->prepareVoterDetail($votermodifiedReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
                                
                                $mpdf_wp->WriteHTML($main_page);
                            }

                            if ($totalRows>0){
                                $main_page=$this->prepareWardEndDetail($mainpagedetails, $totalpage, $votercount, $votermodifiedcount, $voterdeletedcount, $totalnewrows, $totalmodifiedrows, $totaldeletedrows, 9, $rsDataListRemarks, $totalpage);
                                $mpdf_photo->WriteHTML($main_page);
                                $mpdf_wp->WriteHTML($main_page);
                            }
                        }
                    }
                }


                $mpdf_photo->WriteHTML('</body></html>');
                $mpdf_mainpage->WriteHTML('</body></html>');
                $mpdf_wp->WriteHTML('</body></html>');
                if ($pagetype==1){
                    echo "Saving - ".$villagename->name_e."\n";
                }else{
                    echo "Saving - ".$villagename->name_e." :: ".$wardno->ward_no."\n";
                }
                
                $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path_h;
                $mpdf_mainpage->Output($filepath, 'F');
                chmod($filepath, 0755);

                $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path_p;
                $mpdf_photo->Output($filepath, 'F');
                chmod($filepath, 0755);

                $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path_w;
                $mpdf_wp->Output($filepath, 'F');
                chmod($filepath, 0755);


                $newId=DB::select(DB::raw("UPDATE `voter_list_processeds` set `status` = 1, `finish_time` = now() where `id` = $VoterListProcessed->id limit 1;"));
            }

            $queue_id = 0;
            while($queue_id == 0){
                $rs_fetch = DB::select(DB::raw("call `up_fetch_id_for_voterListGenerate`();"));
                $queue_id = $rs_fetch[0]->queue_id;
                if($queue_id == 0){
                    sleep(30);
                }
            }
        }
        
    }

    public function prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.voter_list_section.voter_detail',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'voter_per_page', 'SuchiType', 'PrintedRows', 'cpageno', 'rsDataListRemarks'));    
    }



    public function prepareWardEndDetail($mainpagedetails, $totalpage, $votercount, $votermodifiedcount, $voterdeletedcount, $totalnewrows, $totalmodifiedrows, $totaldeletedrows, $rowsPerPage, $rsDataListRemarks, $filelastpageno)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.voter_list_section.ward_end_suppliment',compact('mainpagedetails', 'totalpage', 'votercount', 'votermodifiedcount', 'voterdeletedcount', 'totalnewrows', 'totalmodifiedrows', 'totaldeletedrows', 'rowsPerPage', 'rsDataListRemarks', 'filelastpageno'));    
    }

    

    public function prepareDeletedVoterSuppliment($voterReports, $mainpagedetails, $totalpage,$printphoto, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.supplimentDatalistwise.deleted_voter_supplement',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'SuchiType', 'PrintedRows', 'cpageno', 'rsDataListRemarks'));    
    }

    
    public function prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $main_page_type, $is_suppliment, $rsDataListRemarks, $showsrnotext, $voterssrnotext, $polling_booth_area)
    {
        return $main_page = view('admin.master.PrepareVoterList.voter_list_section.main_page',compact('mainpagedetails','voterssrnodetails', 'totalpage', 'main_page_type', 'is_suppliment', 'rsDataListRemarks', 'showsrnotext', 'voterssrnotext', 'polling_booth_area'));    
    }
    
       
}

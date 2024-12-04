<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrepareVidhansabhaList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepareVidhansabhaList:generate {district_id} {assembly_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'prepareVidhansabhaList generate';

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
        $ac_id = $this->argument('assembly_id'); 
        
        $rs_ac_name = DB::select(DB::raw("select `code`, `name_e` from `assemblys` where `id` = $ac_id limit 1;"));
        $rs_ac_name = reset($rs_ac_name);
        $ac_name = $rs_ac_name->code.' - '.$rs_ac_name->name_e;

        $rs_VoterListProcessed = DB::select(DB::raw("select * from `vidhansabha_list` where `district_id` = $district_id and `assembly_id` = $ac_id and `status` = 0 limit 1;"));

        foreach ($rs_VoterListProcessed as $key => $VoterListProcessed) {
            $rs_update = DB::select(DB::raw("update `vidhansabha_list` set `status` = 2 where `id` = $VoterListProcessed->id limit 1;"));
            
            $dirpath = Storage_path() . $VoterListProcessed->folder_path;
            @mkdir($dirpath, 0755, true);

            $path=Storage_path('fonts/');
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir']; 
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata']; 
            
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

            $rs_ac_parts = DB::select(DB::raw("select distinct `assembly_part_id` from `voters_new_mod_del` where `assembly_id` = $ac_id and `status` <> 3 order by `assembly_part_id`;"));
              
            $html = view('admin.master.PrepareVoterList.vidhansabhaList.start_pdf');

            $html = $html.'</style></head><body>';

            
            $mpdf_photo->WriteHTML($html);
            

            $voter_per_page = 27;
            $partcount = 1;
            $totalpage=0;
            echo "Processing Vidhansabha :: ".$ac_name." \n";
            foreach ($rs_ac_parts as $val_ac_part) {
                
                $rs_part_no = DB::select(DB::raw("select * from `assembly_parts` where `id` = $val_ac_part->assembly_part_id limit 1;"));
                $part_no = $rs_part_no[0]->part_no;    


                if ($partcount>1){
                    $mpdf_photo->WriteHTML('<pagebreak>');
                    // if(fmod($totalpage, 2)==1){
                    //     $mpdf_photo->WriteHTML('<pagebreak>');
                    // }    
                }

                $partcount++;

                $voterReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`voter_card_no`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, 4 as `data_list_id` From `voters_new_mod_del` `v` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`assembly_part_id` = $val_ac_part->assembly_part_id and `v`.`status` = 0 Order By `v`.`sr_no`;"));

                $voterDeletedReports = DB::select(DB::raw("select `v`.`id`, `v`.`sr_no`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`voter_card_no`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, 4 as `data_list_id` From `voters_new_mod_del` `v` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`assembly_part_id` = $val_ac_part->assembly_part_id and `v`.`status` = 2 Order By `v`.`sr_no`;"));

                        
                $votercount = count($voterReports);
                $voterdeletedcount = count($voterDeletedReports);

                $totalnewrows = (int)($votercount/3);
                if ($totalnewrows*3<$votercount){$totalnewrows++;}

                $totaldeletedrows = (int)($voterdeletedcount/3);
                if ($totaldeletedrows*3<$voterdeletedcount){$totaldeletedrows++;}
                        
                $totalRows = $totalnewrows + $totaldeletedrows;
                $totalpage = (int)($totalRows/9);
                if ($totalpage*9<$totalRows){$totalpage++;}
                // $totalpage++;

                $print_photo= 1;
                if ($votercount>0){
                    $SuchiType = 'घटक - 1 : परिवर्धन सूचि';
                    $PrintedRows = 0;
                    $cpageno = 0;
                    $main_page=$this->prepareVoterDetail($voterReports, $totalpage, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $ac_name, $part_no, $print_photo);
                    $mpdf_photo->WriteHTML($main_page);
                }

                $print_photo = 0;
                if ($voterdeletedcount>0){
                    $SuchiType = 'घटक - 2 : विलोपन सूचि';
                    $PrintedRows = $totalnewrows;
                    $cpageno = (int)($PrintedRows/9);
                    // $cpageno++;
                    $main_page=$this->prepareVoterDetail($voterDeletedReports, $totalpage, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $ac_name, $part_no, $print_photo);
                    $mpdf_photo->WriteHTML($main_page);
                }

                    
                if ($totalRows>0){
                    $main_page=$this->prepareWardEndDetail($totalpage, $votercount, $voterdeletedcount, $totalnewrows, $totaldeletedrows, 9, $totalpage);
                    $mpdf_photo->WriteHTML($main_page);
                }
                   
            }


            $mpdf_photo->WriteHTML('</body></html>');
            
            $filepath = Storage_path() . $VoterListProcessed->folder_path . $VoterListProcessed->file_path;
            $mpdf_photo->Output($filepath, 'F');

            $newId=DB::select(DB::raw("Update `vidhansabha_list` set `status` = 1 where `id` = $VoterListProcessed->id;"));

        }

              
    }


    public function prepareVoterDetail($voterReports, $totalpage, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $ac_name, $part_no, $print_photo)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.vidhansabhaList.voter_detail',compact('voterReports', 'totalpage', 'voter_per_page', 'SuchiType', 'PrintedRows', 'cpageno', 'ac_name', 'part_no', 'print_photo'));    
    }

    public function prepareWardEndDetail($totalpage, $votercount, $voterdeletedcount, $totalnewrows, $totaldeletedrows, $rowsPerPage, $filelastpageno)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.vidhansabhaList.ward_end_suppliment',compact('totalpage', 'votercount', 'voterdeletedcount', 'totalnewrows', 'totaldeletedrows', 'rowsPerPage', 'filelastpageno'));    
    }

    

    // public function prepareDeletedVoterSuppliment($voterReports, $mainpagedetails, $totalpage,$printphoto, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks)
    // {
        
    //     return $main_page=view('admin.master.PrepareVoterList.supplimentDatalistwise.deleted_voter_supplement',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'SuchiType', 'PrintedRows', 'cpageno', 'rsDataListRemarks'));    
    // }

    
    // public function prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $main_page_type, $is_suppliment, $rsDataListRemarks)
    // {
    //     return $main_page = view('admin.master.PrepareVoterList.voter_list_section.main_page',compact('mainpagedetails','voterssrnodetails', 'totalpage', 'main_page_type', 'is_suppliment', 'rsDataListRemarks'));    
    // }
    
       
}

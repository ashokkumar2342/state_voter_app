<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Model\AssemblyPart;

class CheckPhotoQuality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:photoquality {part_no} {from_sr_no} {to_sr_no} {data_list_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check:photoquality';

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
        $part_no = $this->argument('part_no'); 
        $from_sr_no = $this->argument('from_sr_no'); 
        $to_sr_no = $this->argument('to_sr_no');
        $data_list_id = $this->argument('data_list_id');


        $rs_part_slno_part_data_list = DB::select(DB::raw("select * from `voters` where `assembly_part_id` = $part_no and `sr_no` >= $from_sr_no and `sr_no` <= $to_sr_no and `data_list_id` = $data_list_id order by `sr_no`;"));
        // $rs_voters = DB::select(DB::raw("select * from `voters` where `assembly_part_id` = $part_no and `sr_no` >= $from_sr_no and `sr_no` <= $to_sr_no and `data_list_id` = 5 order by `sr_no`;"));
        
        // $rs_assembly = DB::select(DB::raw("select * from `assemblys` where `id` = $assembly limit 1;"));
        // $rs_assembly = reset($rs_assembly);

        // $rs_part_assembly = DB::select(DB::raw("select * from `assembly_parts` where `id` = $part_no limit 1;"));
        // $rs_part_assembly = reset($rs_part_assembly);

        
        foreach ($rs_part_slno_part_data_list as $key => $val_records) {
            $rs_voters = DB::select(DB::raw("select * from `voters` where `id` = $val_records->id limit 1;"));
            echo "Checking ".$rs_voters[0]->id."\n";

            $dirpath = Storage_path() . '/photo_check';
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

            

            $html = view('admin.master.PrepareVoterList.voter_list_section.start_pdf');

            $html = $html.'</style></head><body>';

            
            $mpdf_photo->WriteHTML($html);
            

            $main_page=$this->prepareVoterDetail($rs_voters);
            $mpdf_photo->WriteHTML($main_page);

            
            $mpdf_photo->WriteHTML('</body></html>');
            
            
            $filepath = $dirpath . '/check_photo.pdf';
            $mpdf_photo->Output($filepath, 'F');
        }
        
      
    }


    // public function prepareWardEndSuppliment($mainpagedetails, $totalpage, $votercount, $votermodifiedcount, $voterdeletedcount, $totalnewrows, $totalmodifiedrows, $totaldeletedrows)
    // {
        
    //     return $main_page=view('admin.master.PrepareVoterList.voter_list_section.ward_end_suppliment',compact('mainpagedetails', 'totalpage', 'votercount', 'votermodifiedcount', 'voterdeletedcount', 'totalnewrows', 'totalmodifiedrows', 'totaldeletedrows'));    
    // }

    public function prepareVoterDetail($voterReports)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.voter_list_section.photo_check',compact('voterReports'));    
    }

    // public function prepareVoterDetailSuppliment($voterReports, $mainpagedetails, $totalpage,$printphoto, $SuchiType, $PrintedRows)
    // {
        
    //     return $main_page=view('admin.master.PrepareVoterList.voter_list_section.voter_detail_suppliment',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'SuchiType', 'PrintedRows'));    
    // }

    
    // public function prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $main_page_type, $is_suppliment)
    // {
    //     return $main_page = view('admin.master.PrepareVoterList.voter_list_section.main_page',compact('mainpagedetails','voterssrnodetails', 'totalpage', 'main_page_type', 'is_suppliment'));    
    // }
    
       
}

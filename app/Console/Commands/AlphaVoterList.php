<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AlphaVoterList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AlphaVoterList:generate {district_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AlphaVoterList generate';

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
        ini_set('max_execution_time', '3600');
        ini_set('memory_limit','999M');
        ini_set("pcre.backtrack_limit", "100000000");
        $district_id = $this->argument('district_id');
        
        
        $rs_village_list = DB::select(DB::raw("select `id` from `villages` where `districts_id` = $district_id and `blocks_id` >= 4 and `zp_ward_id` > 0 order by `blocks_id`, `name_e`;"));
        
        foreach ($rs_village_list as $key => $val_villages){
            $this->alphaSortVillageList($val_villages->id);
        }

        echo "Processing complete \n";
    
    }


    
    public function alphaSortVillageList($village_id)
    { 
        $vil_id = $village_id;
        $village_name = "";
        $block_name = "";
        $district_name = "";
        $rs_fetch = DB::select(DB::raw("select `dt`.`name_e` as `d_name`, `bl`.`name_e` as `b_name`, `vil`.`name_e` as `v_name` from `villages` `vil` inner join `blocks_mcs` `bl` on `bl`.`id` = `vil`.`blocks_id` inner join `districts` `dt` on `dt`.`id` = `vil`.`districts_id` where `vil`.`id` = $vil_id limit 1;"));
        $village_name = $rs_fetch[0]->v_name;
        $block_name = $rs_fetch[0]->b_name;
        $district_name = $rs_fetch[0]->d_name;

        $rs_records = DB::select(DB::raw("select `vt`.`name_e`, `vt`.`father_name_e`, `wv`.`ward_no`, `pb`.`booth_no`, `vt`.`print_sr_no` from `voters` `vt` inner join `polling_booths` `pb` on `pb`.`id` = `vt`.`booth_id` inner join `ward_villages` `wv` on `wv`.`id` = `vt`.`ward_id` where `vt`.`village_id` = $vil_id and `vt`.`status` <> 2 order by `vt`.`name_e`;"));
        
        echo "Processing Voter List :: ".$block_name." - ".$village_name."\n";

        $path=Storage_path('fonts/');
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir']; 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata']; 
        $mpdf = new \Mpdf\Mpdf([
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
               'pagenumPrefix' => '',
              'pagenumSuffix' => '',
              'nbpgPrefix' => ' कुल ',
              'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
        ]); 
              
        $html = view('admin.alphaVoterList.report',compact('rs_records','village_name','block_name', 'district_name'));
        
        $mpdf->WriteHTML($html); 

        $dirpath = Storage_path()."/".$district_name."/". $block_name."/";
        @mkdir($dirpath, 0755, true);
        chmod($dirpath, 0755);

        $filepath = Storage_path()."/".$district_name."/". $block_name."/".$village_name.".pdf";
        $mpdf->Output($filepath, 'F');
        chmod($filepath, 0755);
        
    }
           
}

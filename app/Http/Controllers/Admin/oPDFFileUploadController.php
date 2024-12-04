<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Model\District; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; 

class PDFFileUploadController extends Controller
{
  public function index()
  {   $Districts=District::orderBy('name_e','ASC')->get();
      return view('admin.pdf_file_upload.index',compact('Districts'));  
  }
  public function Upload(Request $request)
  {   
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
    $html=view('admin.pdf_file_upload.pdf_link');  
	$mpdf->WriteHTML($html); 
  	$mpdf->Output();
	  	 
	    
  }  
}

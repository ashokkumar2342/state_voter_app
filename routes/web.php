<?php
use App\Helper\MyFuncs;
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
 
});
Route::get('download-voter-list', 'Admin\FrontController@downloadVoterList')->name('front.download.voter.list');
Route::get('stateWiseDistrict', 'Admin\FrontController@stateWiseDistrict')->name('front.stateWiseDistrict');
Route::get('DistrictWiseBlock', 'Admin\FrontController@DistrictWiseBlock')->name('front.DistrictWiseBlock');
Route::get('BlockWiseVoterListType', 'Admin\FrontController@BlockWiseVoterListType')->name('front.BlockWiseVoterListType');
Route::post('tableShow', 'Admin\FrontController@tableShow')->name('front.tableShow');
Route::get('download/{path}/{condition}', 'Admin\FrontController@download')->name('front.download');


Route::get('search-voter', 'Admin\FrontController@searchVoter')->name('front.search.voter');
 




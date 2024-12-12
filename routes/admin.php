<?php
	Route::group(['middleware' => ['preventBackHistory','web']], function() {
		Route::get('login', 'Auth\LoginController@login')->name('admin.login'); 
		Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout.get');
		Route::get('logout_time', 'Auth\LoginController@logout')->name('admin.logout_time.get');
		Route::get('refreshcaptcha', 'Auth\LoginController@refreshCaptcha')->name('admin.refresh.captcha');
		Route::post('login-post', 'Auth\LoginController@loginPost')->name('admin.login.post');

		// Route::get('/', 'Auth\LoginController@index')->name('admin.home');
		// Route::get('search-voter', 'Auth\LoginController@searchVoter')->name('admin.search.voter'); 
		// Route::get('search-voter-form/{id}', 'Auth\LoginController@searchVoterform')->name('admin.search.voter.form'); 
		// Route::get('search-dis-block', 'Auth\LoginController@searchDisBlock')->name('admin.search.dis.block'); 
		// Route::get('search-block-village', 'Auth\LoginController@searchBlockVillage')->name('admin.search.block.village'); 
		// Route::post('search-voter-filter/{id}', 'Auth\LoginController@searchVoterFilter')->name('admin.search.voter.folter'); 
		// Route::get('admin-password/reset', 'Auth\ForgetPasswordController@sendResetLinkEmail')->name('admin.password.email');
		// Route::get('passwordreset/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
		// Route::get('forget-password', 'Auth\LoginController@forgetPassword')->name('admin.forget.password');
		// Route::post('forget-password-send-link', 'Auth\LoginController@forgetPasswordSendLink')->name('admin.forget.password.send.link');
		// Route::post('reset-password', 'Auth\LoginController@resetPassword')->name('admin.reset.password');
	});


Route::group(['middleware' => ['preventBackHistory','admin','web']], function() {
	Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard'); //OK ---
	// Route::get('token', 'DashboardController@passportTokenCreate')->name('admin.token');
	// Route::get('profile', 'DashboardController@proFile')->name('admin.profile');
	// Route::get('profile-show', 'DashboardController@proFileShow')->name('admin.profile.show');
	// Route::get('profile-show/{profile_pic}', 'DashboardController@proFilePhotoShow')->name('admin.profile.photo.show'); 
	// Route::post('profile-update', 'DashboardController@profileUpdate')->name('admin.profile.update');
	// Route::post('password-change', 'DashboardController@passwordChange')->name('admin.password.change');
	// Route::get('profile-photo', 'DashboardController@profilePhoto')->name('admin.profile.photo');
	// Route::post('upload-photo', 'DashboardController@profilePhotoUpload')->name('admin.profile.photo.upload');
	// Route::get('photo-refrash', 'DashboardController@profilePhotoRefrash')->name('admin.profile.photo.refrash');
	//---------------account-----------------------------------------

	// 	  // ---------------Report----------------------------------------
 	Route::group(['prefix' => 'report'], function() {
     	Route::get('mdi', 'ReportController@master_data_index')->name('admin.report.master_data_index');
     	
     	Route::get('misc_rep', 'ReportController@misc_rep_index')->name('admin.report.misc_rep_index');
     	
     	Route::get('report-formControls', 'ReportController@formControlShow')->name('admin.report.formControl.show');

     	Route::post('show', 'ReportController@show')->name('admin.report.show');
 	});

	Route::prefix('account')->group(function () {
		//User List (1)
		Route::get('list', 'AccountController@usr_lst_index')->name('admin.account.list');	//OK Done----
		Route::get('status/{account}', 'AccountController@change_status')->name('admin.account.status'); // OK ----------
		
		// Route::get('edit/{account}', 'AccountController@usr_edit')->name('admin.account.edit');	//OK--	 
		// Route::post('update/{account}', 'AccountController@update')->name('admin.account.edit.post');	//OK ----------

		//Create New User (2)
	    Route::get('create_form', 'AccountController@create_form')->name('admin.account.form');	//OK Done-----
	    Route::post('store', 'AccountController@store')->name('admin.account.post');//OK----
		

		// Change Password (8)
		Route::get('change-password', 'AccountController@changePassword')->name('admin.account.change.password');	//OK Done----
		Route::post('change-password-store', 'AccountController@changePasswordStore')->name('admin.account.change.password.store');	//OK----


		// Route::post('list-user-generate', 'AccountController@listUserGenerate')->name('admin.account.list.user.generate');
		// Route::get('access', 'AccountController@access')->name('admin.account.access');
		// Route::get('hot-menu', 'AccountController@accessHotMenu')->name('admin.account.access.hotmenu');
		// Route::get('menuTable', 'AccountController@menuTable')->name('admin.account.menuTable');
		// Route::get('access/hotmenu', 'AccountController@accessHotMenuShow')->name('admin.account.access.hotmenuTable');
		// Route::post('access-store', 'AccountController@accessStore')->name('admin.userAccess.add');
		// Route::post('access-hot-menu-store', 'AccountController@accessHotMenuStore')->name('admin.userAccess.hotMenuAdd');
		
		
		// Route::get('delete/{account}', 'AccountController@destroy')->name('admin.account.delete');	//OK------------
		
		
		// Route::get('r--status/{account}', 'AccountController@rstatus')->name('admin.account.r_status');	 
		// Route::get('w-status/{account}', 'AccountController@wstatus')->name('admin.account.w_status');	 
		// Route::get('d-status/{account}', 'AccountController@dstatus')->name('admin.account.d_status');
		// Route::get('minu/{account}', 'AccountController@minu')->name('admin.account.minu');	


		// Route::get('role', 'AccountController@defaultRolePermission')->name('admin.account.role');  //OK Done----
		// Route::get('role-menu', 'AccountController@roleMenuTable')->name('admin.account.roleMenuTable'); 	//OK----------
		// Route::post('role-menu-store', 'AccountController@roleMenuStore')->name('admin.roleAccess.subMenu.save');	//OK----------
		// Route::get('role-quick-menu-view', 'AccountController@roleQuickView')->name('admin.roleAccess.quick.view');		//OK Done------------
		// Route::get('defult-role-menu-show', 'AccountController@defultRoleQuickMenuShow')->name('admin.account.role.default.quickMenu');	//OK-----------
		// Route::post('default-role-quick-menu-store', 'AccountController@defaultRoleQuickStore')->name('admin.roleAccess.quick.role.menu.store');	//OK-----------

		// Route::get('class-access', 'AccountController@classAccess')->name('admin.account.classAccess');

		// District Assign to User (3)
		Route::get('DistrictsAssign', 'AccountController@DistrictsAssign')->name('admin.account.DistrictsAssign');	//OK Done------------
		Route::get('StateDistrictsSelect', 'AccountController@StateDistrictsSelect')->name('admin.account.StateDistrictsSelect'); 	//OK-----------
		Route::post('DistrictsAssignStore', 'AccountController@DistrictsAssignStore')->name('admin.Master.DistrictsAssignStore');	//OK---------
		Route::get('DistrictsAssignDelete/{id}', 'AccountController@DistrictsAssignDelete')->name('admin.Master.DistrictsAssignDelete');	//OK---------

		// Block/MC Assign to User (4)
		Route::get('BlockAssign', 'AccountController@BlockAssign')->name('admin.account.BlockAssign');		//OK Done-------
		Route::get('DistrictBlockAssign', 'AccountController@DistrictBlockAssign')->name('admin.account.DistrictBlockAssign'); 	//OK---------
		Route::post('DistrictBlockAssignStore', 'AccountController@DistrictBlockAssignStore')->name('admin.Master.DistrictBlockAssignStore');	//OK---------
		Route::get('DistrictBlockAssignDelete/{id}', 'AccountController@DistrictBlockAssignDelete')->name('admin.Master.DistrictBlockAssignDelete');	//OK--------

		// Village/MC Assign to User (5)
		Route::get('VillageAssign', 'AccountController@VillageAssign')->name('admin.account.VillageAssign'); 	//OK Done------------
		Route::get('DistrictBlockVillageAssign', 'AccountController@DistrictBlockVillageAssign')->name('admin.account.DistrictBlockVillageAssign'); //OK-------
		Route::post('DistrictBlockVillageAssignStore', 'AccountController@DistrictBlockVillageAssignStore')->name('admin.Master.DistrictBlockVillageAssignStore');		//OK-------
		Route::get('DistrictBlockVillageAssignDelete/{id}', 'AccountController@DistrictBlockVillageAssignDelete')->name('admin.Master.DistrictBlockVillageAssignDelete');	//OK-----------

		// Reset Password (9)
		Route::get('reset-password', 'AccountController@resetPassWord')->name('admin.account.reset.password'); 
		Route::post('reset-password-change', 'AccountController@resetPassWordChange')->name('admin.account.reset.password.change'); 


		
		// Route::get('menu-ordering', 'AccountController@menuOrdering')->name('admin.account.menu.ordering'); 
		// Route::get('menu-ordering-store', 'AccountController@menuOrderingStore')->name('admin.account.menu.ordering.store'); 
		// Route::get('submenu-ordering-store', 'AccountController@subMenuOrderingStore')->name('admin.account.submenu.ordering.store'); 
		// Route::get('menu-filter/{id}', 'AccountController@menuFilter')->name('admin.account.menu.filte'); 
		// Route::post('menu-report', 'AccountController@menuReport')->name('admin.account.menu.report'); 
		// Route::get('user-menu-assign-report/{id}', 'AccountController@defaultUserMenuAssignReport')->name('admin.account.user.menu.assign.report'); 
		// Route::post('default-user-role-report-generate/{id}', 'AccountController@defaultUserRolrReportGenerate')->name('admin.account.default.user.role.report.generate'); 
		// Route::get('class-user-assign-report-generate/{user_id}', 'AccountController@ClassUserAssignReportGenerate')->name('admin.account.class.user.assign.report.generate'); 
		
						
		// Route::get('status/{minu}', 'AccountController@minustatus')->name('admin.minu.status'); 
	});
	// Route::prefix('user-report')->group(function () {
	// 	    Route::get('/', 'UserReportController@index')->name('admin.user.report');
	// 	    Route::get('report-type-filter', 'UserReportController@reportTypeFilter')->name('admin.user.report.type.filter');
	// 	    Route::post('filter', 'UserReportController@filter')->name('admin.user.report.filter'); 
	// 	});
	 
		//---------------minu-----------------------------------------	
	// Route::prefix('minu')->group(function () {
	    
	// 	Route::get('status/{minu}', 'MinuController@status')->name('admin.minu.status');	 
	// 	Route::get('r--status/{minu}', 'MinuController@rstatus')->name('admin.minu.r_status');	 
	// 	Route::get('w-status/{minu}', 'MinuController@wstatus')->name('admin.minu.w_status');	 
	// 	Route::get('d-status/{minu}', 'MinuController@dstatus')->name('admin.minu.d_status');
	// 	Route::get('minu/{minu}', 'MinuController@minu')->name('admin.minu.minu');
	// 	Route::post('menu-permission-check', 'MinuController@menuPermissionCheck')->name('admin.account.menu.permission.check'); 	 
	// });
	 
	 // 

    Route::group(['prefix' => 'Master'], function() {
//     	//-states-//
// 	    Route::get('/', 'MasterController@index')->name('admin.Master.index');	//OK Done-------   
// 	    Route::post('Store/{id?}', 'MasterController@store')->name('admin.Master.store');	
// 	    //OK------	   
// 	    Route::get('Edit{id}', 'MasterController@edit')->name('admin.Master.edit'); //OK-----
// 	    Route::get('Delete{id}', 'MasterController@delete')->name('admin.Master.delete'); //OK---


//         //-districts-//
	    Route::get('districts', 'MasterController@districts')->name('admin.Master.districts');
	    Route::get('districts-table', 'MasterController@DistrictsTable')->name('admin.master.districts.table');
	    Route::post('districts-store{id}', 'MasterController@DistrictsStore')->name('admin.master.districts.store');
	    Route::get('districts-edit/{id}', 'MasterController@DistrictsEdit')->name('admin.Master.districts.edit');	//OK--------
	    // Route::get('districts-delete/{id}', 'MasterController@DistrictsDelete')->name('admin.Master.district.delete');	//OK---------
	    Route::get('districts-zpWard/{d_id}', 'MasterController@DistrictsZpWard')->name('admin.Master.districts.zpWard');	//OK--------
	    Route::post('districts-zpWardStore/{d_id}', 'MasterController@DistrictsZpWardStore')->name('admin.Master.districts.zpWardStore');	//OK----------


// 	    //-z-p-ward//
//         Route::get('ZilaParishad', 'MasterController@ZilaParishad')->name('admin.Master.ZilaParishad');		//OK Done------
        
//         Route::post('ZilaParishadStore', 'MasterController@ZilaParishadStore')->name('admin.Master.ZilaParishadStore');		

//         Route::get('ZilaParishadTable', 'MasterController@ZilaParishadTable')->name('admin.Master.ZilaParishadTable');	//OK---------------
//         Route::get('ZilaParishadEdit/{id}', 'MasterController@ZilaParishadEdit')->name('admin.Master.ZilaParishadEdit');	//OK---------------
//         Route::post('ZilaParishadUpdate/{id}', 'MasterController@ZilaParishadUpdate')->name('admin.Master.ZilaParishadUpdate');		//OK-----------
//         Route::get('ZilaParishadDelete/{id}', 'MasterController@ZilaParishadDelete')->name('admin.Master.ZilaParishadDelete');		//OK-----------
//         //-p-s-ward//
//         Route::get('PanchayatSamiti', 'MasterController@PanchayatSamiti')->name('admin.Master.PanchayatSamiti');		//OK Done----------

//         Route::post('PanchayatSamitiStore', 'MasterController@PanchayatSamitiStore')->name('admin.Master.PanchayatSamitiStore');
        
//         Route::get('PanchayatSamitiTable', 'MasterController@PanchayatSamitiTable')->name('admin.Master.PanchayatSamitiTable');		//OK---------------
//         Route::get('PanchayatSamitiEdit/{id}', 'MasterController@PanchayatSamitiEdit')->name('admin.Master.PanchayatSamitiEdit');		//OK--------------
//         Route::post('PanchayatSamitiUpdate/{id}', 'MasterController@PanchayatSamitiUpdate')->name('admin.Master.PanchayatSamitiUpdate');	//OK--------------
//         Route::get('PanchayatSamitiDelete/{id}', 'MasterController@PanchayatSamitiDelete')->name('admin.Master.PanchayatSamitiDelete');		//OK
	    
// 	    //-block-mcs-type//
// 	    Route::get('BlockMCSType', 'MasterController@BlockMCSType')->name('admin.Master.BlockMCSType');		//OK Done-----
// 	    Route::get('BlockMCSTypeEdit/{id}', 'MasterController@BlockMCSTypeEdit')->name('admin.Master.BlockMCSTypeEdit'); 	//OK-----------
// 	    Route::post('BlockMCSTypeUpdate/{id}', 'MasterController@BlockMCSTypeUpdate')->name('admin.Master.BlockMCSTypeUpdate');		//OK------------  
	    
// 	    //-block-mcs-//
	    Route::get('block-mcs', 'MasterController@blockMCS')->name('admin.Master.blockmcs');
	    Route::get('block-mcs-table', 'MasterController@blockMCSTable')->name('admin.master.block.mcs.table');
	    Route::post('block-mcs-store{id}', 'MasterController@blockMCSStore')->name('admin.master.block.mcs.store');
	    Route::get('block-mcs-edit/{id}', 'MasterController@blockMCSEdit')->name('admin.master.block.mcs.edit');
	    Route::get('block-mcs-delete/{id}', 'MasterController@blockMCSDelete')->name('admin.master.block.mcs.delete'); 
	    Route::get('block-mcs-psWard/{b_id}', 'MasterController@blockMCSpsWard')->name('admin.master.block.mcs.psWard');
	    Route::post('block-mcs-psWardStore/{b_id}', 'MasterController@blockMCSpsWardStore')->name('admin.master.block.mcs.psWard.store');
	    
// 	    //-village--//
	    Route::get('village', 'MasterController@village')->name('admin.Master.village'); //OK Done--
	    Route::get('village-table', 'MasterController@villageTable')->name('admin.Master.village.table');
	    Route::post('village-store{id}', 'MasterController@villageStore')->name('admin.Master.village.store');
	    Route::get('village-edit/{id}', 'MasterController@villageEdit')->name('admin.Master.village.edit');
	    Route::get('village-delete/{id}', 'MasterController@villageDelete')->name('admin.Master.village.delete');
	    Route::get('village-ward-add/{v_id}', 'MasterController@villageWardAdd')->name('admin.Master.village.ward.add');

	    // Route::get('villageexportsampale', 'MasterController@villageSampleExport')->name('admin.Master.villageexportsampale'); 

// 	    //- Ward village--//
	    Route::get('ward', 'MasterController@villageWard')->name('admin.Master.ward');  
	    Route::get('wardTable', 'MasterController@villageWardTable')->name('admin.Master.ward.table');
	    Route::post('ward-store', 'MasterController@wardStore')->name('admin.Master.ward.store');
	    Route::get('ward-delete/{id}', 'MasterController@villageWardDelete')->name('admin.Master.ward.delete');
	    
// 	    //-Assembly--//
	    Route::get('assembly', 'MasterController@Assembly')->name('admin.Master.Assembly');
	    Route::get('assembly-table', 'MasterController@AssemblyTable')->name('admin.Master.AssemblyTable');
	    Route::post('assembly-store{id}', 'MasterController@AssemblyStore')->name('admin.Master.Assembly.store');    
	    Route::get('assembly-edit/{id}', 'MasterController@AssemblyEdit')->name('admin.Master.Assembly.edit');
	    Route::get('assembly-delete/{id}', 'MasterController@AssemblyDelete')->name('admin.Master.Assembly.delete');
	    Route::get('assembly-part-add/{id}', 'MasterController@AssemblyPartAdd')->name('admin.Master.AssemblyPart.add');

// 	    //-Assembly Part--//
	    Route::get('AssemblyPart', 'MasterController@AssemblyPart')->name('admin.Master.AssemblyPart');
	    Route::get('AssemblyPartTable', 'MasterController@AssemblyPartTable')->name('admin.Master.AssemblyPartTable');
	    Route::post('assembly-part-store', 'MasterController@AssemblyPartStore')->name('admin.Master.AssemblyPart.store');
	    Route::get('assembly-part-delete/{id}', 'MasterController@AssemblyPartDelete')->name('admin.Master.AssemblyPart.delete');


// 	    //-Mapping---//
	    Route::get('MappingVillageAssemblyPart', 'MasterController@MappingVillageAssemblyPart')->name('admin.Master.MappingVillageAssemblyPart');
	    Route::get('MappingVillageAssemblyPartFilter', 'MasterController@MappingVillageAssemblyPartFilter')->name('admin.Master.MappingVillageAssemblyPartFilter');
	    Route::get('MappingVillageAssemblyPartTable', 'MasterController@MappingVillageAssemblyPartTable')->name('admin.Master.MappingVillageAssemblyPartTable');
	    Route::get('MappingAssemblyWisePartNo', 'MasterController@AssemblyWisePartNoUnmapped')->name('admin.Master.MappingAssemblyWisePartNo');
	    Route::post('MappingVillageAssemblyPartStore', 'MasterController@MappingVillageAssemblyPartStore')->name('admin.Master.MappingVillageAssemblyPartStore');
	    Route::get('MappingVillageAssemblyPartRemove/{id}', 'MasterController@MappingVillageAssemblyPartRemove')->name('admin.Master.MappingVillageAssemblyPartRemove');

	    Route::get('mapping-ac-part-with-panchayat', 'MasterController@MappingAcPartWithPanchayat')->name('admin.Master.mapping.ac.part.with.panchayat');
	    Route::get('mapping-district-wise-ac', 'MasterController@mappingDistrictWiseAssembly')->name('admin.Master.mapping.district.wise.ac');
	    Route::get('AssemblyWiseAllPartNo', 'MasterController@AssemblyWisePartNoAll')->name('admin.Master.AssemblyWiseAllPartNo');
	    Route::get('mapping-ac-part-wise-table', 'MasterController@MappingAcPartVillage')->name('admin.Master.mapping.ac.part.wise.table');
	    Route::post('mapping-ac-part-wise-store', 'MasterController@AcPartVillageMappingStore')->name('admin.Master.mapping.ac.part.store');


// 	    //-mapping-zp-ward---//
// 	    Route::get('MappingVillageToZPWard', 'MasterController@MappingVillageToZPWard')->name('admin.Master.MappingVillageToZPWard');	//----OK Done-----
// 	    Route::get('districtwiseZPWard', 'MasterController@districtwiseZPWard')->name('admin.Master.districtwiseZPWard');		//OK------------
// 	    Route::get('districtOrZpwardWiseVillage', 'MasterController@districtOrZpwardWiseVillage')->name('admin.Master.districtOrZpwardWiseVillage');	//OK----------
// 	    Route::post('MappingVillageToZPWardStore', 'MasterController@MappingVillageToZPWardStore')->name('admin.Master.MappingVillageToZPWardStore');	//OK----------
	    
// 	    //mapping-PS-ward////
// 	    Route::get('MappingVillageToPSWard', 'MasterController@MappingVillageWardToPSWard')->name('admin.Master.MappingVillageToPSWard');		//OK Done--------------
// 	    Route::get('blockwisePsWard', 'MasterController@blockwisePsWard')->name('admin.Master.blockwisePsWard');	//OK-------------
// 	    Route::get('BlockOrPSwardWiseVillage', 'MasterController@BlockOrPSwardWiseVillage')->name('admin.Master.BlockOrPSwardWiseVillage');		//OK-----------
// 	    Route::post('MappingVillageToPSWardStore', 'MasterController@MappingVillageToPSWardStore')->name('admin.Master.MappingVillageToPSWardStore');	//OK-------------

	    
// 	    //mapping-booth-ward////
	    Route::get('MappingBoothWard', 'MasterController@MappingBoothWard')->name('admin.Master.MappingBoothWard');
	    Route::get('MappingVillageWiseBooth', 'MasterController@MappingVillageWiseBooth')->name('admin.Master.MappingVillageWiseBooth');
	    Route::get('MappingVillageOrBoothWiseWard', 'MasterController@MappingVillageOrBoothWiseWard')->name('admin.Master.MappingVillageOrBoothWiseWard');
	    Route::post('MappingBoothWardStore', 'MasterController@MappingBoothWardStore')->name('admin.Master.MappingBoothWardStore');

//         //mapping-ward-booth////
	    Route::get('MappingWardBooth', 'MasterController@MappingWardBooth')->name('admin.Master.MappingWardBooth');
	    Route::get('MappingWardBoothTable', 'MasterController@MappingWardBoothTable')->name('admin.Master.MappingWardBoothTable');
	    Route::get('MappingWardBoothSelectBooth', 'MasterController@MappingWardBoothSelectBooth')->name('admin.Master.MappingWardBoothSelectBooth');
	    Route::post('MappingWardBoothStore', 'MasterController@MappingWardBoothStore')->name('admin.Master.MappingWardBoothStore');
	    Route::get('MappingWardBoothEdit/{id}', 'MasterController@MappingWardBoothEdit')->name('admin.Master.MappingWardBoothEdit');


// 	    //mapping-ward-with-multiple-booth////
	  	Route::get('MappingWardWithMultipleBooth', 'MasterController@MappingWardWithMultipleBooth')->name('admin.Master.MappingWardWithMultipleBooth');		//OK Done--------
	  	Route::get('MappingWardWithMultipleBoothWardWiseBooth', 'MasterController@MappingWardWithMultipleBoothWardWiseBooth')->name('admin.Master.MappingWardWithMultipleBoothWardWiseBooth');	//OK--------
	  	Route::post('MappingWardWithMultipleBoothStore', 'MasterController@MappingWardWithMultipleBoothStore')->name('admin.Master.MappingWardWithMultipleBoothStore');		//OK---------

// 	  	//mapping_acpart_booth_wardwise////
// 	  	Route::get('mapping-acpart-booth-wardwise', 'MasterController@mappingAcpartBoothWardwise')->name('admin.Master.mapping.acpart.booth.wardwise');		//OK Done--------
// 	  	Route::get('mapping-acpart-booth-wardwisetable', 'MasterController@mappingAcpartBoothWardwiseTable')->name('admin.Master.mapping.acpart.booth.wardwisetable');		//OK Done--------
// 	  	Route::post('mapping-acpart-booth-wardwiseStore', 'MasterController@mappingAcpartBoothWardwiseStore')->name('admin.Master.mapping.acpart.booth.wardwiseStore');		//OK Done--------
	  	
	     
// 	     //Ward Bandi Voter Entry////
	    Route::get('ward-bandi', 'MasterController@wardBandi')->name('admin.Master.WardBandi');
	    Route::get('ward-bandi-form', 'MasterController@wardBandiFrom')->name('admin.Master.WardBandiFilter');
	    Route::get('WardBandiFilterAssemblyPart', 'MasterController@wardBandiFilterAssemblyPart')->name('admin.Master.WardBandiFilterAssemblyPart');
	    Route::get('WardBandiFilterward', 'MasterController@wardBandiFilterward')->name('admin.Master.WardBandiFilterward');
	    Route::post('ward-bandi-store', 'MasterController@wardBandiStore')->name('admin.Master.WardBandiStore');
	    Route::get('remove-voter/{id}', 'MasterController@removeVoter_wardbandi')->name('admin.Master.removeVoter_wardbandi');		//OK-----------
	    Route::get('WardBandiReport', 'MasterController@WardBandiReport')->name('admin.Master.WardBandiReport');
	    Route::post('WardBandiReportGenerate', 'MasterController@WardBandiReportGenerate')->name('admin.Master.WardBandiReportGenerate');	//OK-------

// 	    //-----------------ward-bandi-with-booth--------------------------//
	    Route::get('ward-bandi-booth', 'MasterController@wardBandiWithBooth')->name('admin.Master.WardBandiWithBooth');
	    Route::get('villageWiseAssemblyWard', 'MasterController@villageWiseAssemblyWard')->name('admin.Master.VillageWiseAssemblyWard');
	    Route::get('ward-wise-booth', 'MasterController@wardWiseBooth')->name('admin.Master.WardWiseBooth');
	    Route::get('booth-Wise-totalMappedWard', 'MasterController@boothWiseTotalMappedWard')->name('admin.Master.BoothWiseTotalMappedWard');
	    Route::get('assemblywisevoterMapped', 'MasterController@assemblywisevoterMapped')->name('admin.Master.AssemblywisevoterMapped');
	    Route::post('wardBandiWithBoothStore', 'MasterController@wardBandiWithBoothStore')->name('admin.Master.WardBandiWithBoothStore');


// 	    Route::get('change-voter-with-ward', 'MasterController@changeVoterWithWard')->name('admin.Master.change.voter.with.ward');		//OK Done-------------
// 	    Route::get('change-voter-with-ward-table', 'MasterController@changeVoterWithWardTable')->name('admin.Master.change.voter.with.ward.table');	   //OK--------------
// 	    Route::get('change-voter-village-wise-ward', 'MasterController@changeVotervillageWiseWard')->name('admin.Master.change.voter.village.wise.ward');	//OK------
// 	    Route::post('change-voter-with-ward-store', 'MasterController@changeVoterWithWardStore')->name('admin.Master.change.voter.with.ward.store');	//OK--------
	    Route::get('change-voter-with-ward-restore/{id}/{ward_id}', 'MasterController@changeVoterWithWardReStore')->name('admin.Master.change.voter.with.ward.restore');	//OK----------	    
	    Route::get('change-voter-with-ward-report', 'MasterController@changeVoterWithWardReport')->name('admin.Master.change.voter.with.ward.report');		//OK------------
	    Route::post('change-voter-with-ward-report-pdf', 'MasterController@changeVoterWithWardReportPdf')->name('admin.Master.change.voter.with.ward.report.pdf');	//OK----------


// 	    Route::get('delete-suppliment-ward-voter', 'MasterController@deleteSupplimentVoterWard')->name('admin.Master.delete.voter.ward');	//OK Done-------------------
// 	    Route::get('delete-suppliment-ward-voter-showform', 'MasterController@showformDeleteSupplimentVoterWard')->name('admin.Master.delete.voter.ward.showform');	//OK-------------------
// 	    Route::post('delete-suppliment-ward-voter-submit', 'MasterController@submitDeleteSupplimentVoterWard')->name('admin.Master.delete.voter.ward.submit');	//OK-------------------
             	
// 	    Route::get('add-suppliment-ward-voter', 'MasterController@addSupplimentVoterWard')->name('admin.Master.add.voter.ward');	//OK-----------
// 	    Route::get('add-suppliment-ward-voter-showform', 'MasterController@showFormAddSupplimentVoterWard')->name('admin.Master.add.voter.ward.showform');	//OK-----------
// 	    Route::post('add-suppliment-ward-voter-submit', 'MasterController@addSupplimentVoterWardSubmit')->name('admin.Master.add.voter.ward.submit');	//OK-----------
// 	    Route::get('add-suppliment-ward-voter-table/{ward_id?}/{booth_id?}', 'MasterController@addSupplimentVoterWardtable')->name('admin.Master.add.voter.with.ward.table');	//OK-----------
// 	    Route::get('delete-added-suppliment-ward-voter-table/{id}/{ward_id}', 'MasterController@addedSupplimentVoterWardDelete')->name('admin.Master.add.voter.with.ward.delete');	//OK-----------


// 	    Route::get('add-suppliment-voter-with-ward-report', 'MasterController@addSupplimentVoterWithWardReport')->name('admin.Master.add.voter.with.ward.report');	//OK--------
// 	    Route::post('add-suppliment-voter-with-ward-report-pdf', 'MasterController@addVoterWithWardReportPdf')->name('admin.Master.add.voter.with.ward.report.pdf');	//OK--------	
// 	    Route::post('add-suppliment-voter-with-ward-booth-report-pdf', 'MasterController@addVoterWithWardBoothReportPdf')->name('admin.Master.add.voter.with.ward.booth.report.pdf');	//OK--------	

// 	    Route::get('add-suppliment-voter-with-ward-booth-report', 'MasterController@addSupplimentVoterWithWardBoothReport')->name('admin.Master.add.voter.with.ward.booth.report');	//OK--------
             	
	    Route::get('delete-suppliment-voter-ward-booth', 'MasterController@deleteSupplimentVoterWardBooth')->name('admin.Master.delete.voter.ward.booth');
	    Route::get('showform-suppliment-voter-ward-booth', 'MasterController@showformSupplimentVoterWardBooth')->name('admin.Master.showform.voter.ward.booth');	//OK----------------
	    Route::post('submit-suppliment-voter-ward-booth', 'MasterController@submitSupplimentVoterWardBooth')->name('admin.Master.submit.voter.ward.booth');		//OK----------

// 	    Route::get('add-suppliment-ward-voter-booth', 'MasterController@addSupplimentVoterWardBooth')->name('admin.Master.add.voter.ward.booth');	//OK--------
// 	    Route::get('add-suppliment-ward-voter-booth-showform', 'MasterController@showFormAddSupplimentVoterWardBooth')->name('admin.Master.add.voter.ward.booth.showform');		//OK---------
// 	    Route::post('add-suppliment-ward-voter-booth-submit', 'MasterController@SubmitAddSupplimentVoterWardBooth')->name('admin.Master.add.voter.ward.booth.submit');	//OK-------------

// 	    Route::get('change-voter-with-ward-excel', 'MasterController@changeVoterWithWardExcel')->name('admin.Master.change.voter.with.ward.excel');
// 	    Route::get('change-voter-with-ward-sample', 'MasterController@changeVoterWithWardSample')->name('admin.Master.change.voter.with.ward.sample');
// 	    Route::post('change-voter-with-ward-excel-store', 'MasterController@changeVoterWithWardExcelStore')->name('admin.Master.change.voter.with.ward.excel.store');

	    Route::get('change-voter-ward-with-booth', 'MasterController@changeVoterWardWithBooth')->name('admin.Master.change.voter.ward.with.booth');
	    Route::get('change-voter-village-wise-ward-booth', 'MasterController@changeVotervillageWiseWardBooth')->name('admin.Master.change.voter.village.wise.ward.booth');
	    Route::get('change-voter-ward-with-booth-table', 'MasterController@changeVoterWardWithBoothTable')->name('admin.Master.change.voter.ward.with.booth.table');
	    Route::post('change-voter-ward-with-booth-store', 'MasterController@changeVoterWardWithBoothStore')->name('admin.Master.change.voter.ward.with.booth.store');
	    Route::get('change-voter-ward-with-booth-report', 'MasterController@changeVoterWardWithBoothReport')->name('admin.Master.change.voter.ward.with.booth.report');
	    Route::post('change-voter-ward-with-booth-report.pdf', 'MasterController@changeVoterWardWithBoothReportPdf')->name('admin.Master.change.voter.ward.with.booth.report.pdf');
	     
	     	   
// 	    //-----------------onchange-----------------------------//
//	    Route::get('stateWiseDistrict', 'MasterController@stateWiseDistrict')->name('admin.Master.stateWiseDistrict');  	//OK----------- 
	    Route::get('DistrictWiseBlock/{print_condition?}', 'MasterController@DistrictWiseBlock')->name('admin.Master.DistrictWiseBlock');
	    Route::get('BlockWiseVillage', 'MasterController@BlockWiseVillage')->name('admin.Master.BlockWiseVillage');	
	    Route::get('BlockWiseVoterListType', 'MasterController@BlockWiseVoterListType')->name('admin.Master.BlockWiseVoterListType');




// 	    //-----------------gender-----------------------------//
// 	    Route::get('gender', 'MasterController@gender')->name('admin.Master.gender'); //OK Done----
// 	    Route::get('gender-edit/{id}', 'MasterController@genderEdit')->name('admin.Master.gender.edit');	//OK -----------------
// 	    Route::post('gender-update/{id}', 'MasterController@genderUpdate')->name('admin.Master.gender.update'); 	//OK------------


// 	    //----------------------------------------------//
	    Route::get('booth', 'MasterController@booth_form')->name('admin.Master.booth');
	    Route::get('booth-table', 'MasterController@boothTable')->name('admin.Master.booth.table');
	    Route::post('booth-store/{id}', 'MasterController@boothStore')->name('admin.Master.booth.store');
	    Route::get('booth-edit/{id}', 'MasterController@boothEdit')->name('admin.Master.booth.edit');
	    Route::get('booth-delete/{id}', 'MasterController@boothDelete')->name('admin.Master.booth.delete');
	 
// 	    //----------------Poll Day Time   
	    Route::get('pollingDayTime', 'MasterController@pollingDayTime')->name('admin.Master.pollingDayTime');
	    Route::get('pollingDayTimeList', 'MasterController@pollingDayTimeList')->name('admin.Master.pollingDayTimeList');
	    Route::post('pollingDayTimeStore', 'MasterController@pollingDayTimeStore')->name('admin.Master.pollingDayTimeStore');
	    Route::get('pollingDayTimesignature/{path}', 'MasterController@pollingDayTimesignature')->name('admin.Master.pollingDayTimesignature');

	    Route::get('voter-slip-notes', 'MasterController@voterSlipNotes')->name('admin.Master.voter.slip.notes');
	    Route::get('voter-slip-notes-show', 'MasterController@voterSlipNotesShow')->name('admin.Master.voter.slip.notes.show');
	    Route::post('voter-slip-notes-store/{id}', 'MasterController@voterSlipNotesStore')->name('admin.Master.voter.slip.notes.store');
	    Route::get('voter-slip-notes-edit/{id}', 'MasterController@voterSlipNotesEditForm')->name('admin.Master.voter.slip.notes.edit');
	    Route::get('voter-slip-notes-delete/{id}', 'MasterController@voterSlipNotesDelete')->name('admin.Master.voter.slip.notes.delete');
	     
	});
    Route::group(['prefix' => 'VoterDetails'], function() {
           Route::get('index', 'VoterDetailsController@index')->name('admin.voter.details');	//OK---------
           
           Route::get('districtWiseAssembly', 'VoterDetailsController@districtWiseAssembly')->name('admin.voter.districtWiseAssembly');		//OK-------------

//            Route::get('districtWiseVillage', 'VoterDetailsController@districtWiseVillage')->name('admin.voter.districtWiseVillage');

//            Route::get('AssemblyWisePartNo', 'VoterDetailsController@AssemblyWisePartNo')->name('admin.voter.AssemblyWisePartNo');	//OK------------
           
           Route::get('VillageWiseWard', 'VoterDetailsController@VillageWiseWard')->name('admin.voter.VillageWiseWard');		//OK---------
           Route::get('VillageWiseWardAll', 'MasterController@VillageWiseWardAll')->name('admin.voter.VillageWiseWardAll');		//OK---------
           Route::get('VillageWiseAcParts', 'VoterDetailsController@VillageWiseAcParts')->name('admin.voter.VillageWiseAcParts');		//OK---------

           Route::get('VillageWiseVoterList', 'VoterDetailsController@VillageWiseVoterList')->name('admin.voter.VillageWiseVoterList');	//OK---------


           Route::get('calculateAge', 'VoterDetailsController@calculateAge')->name('admin.voter.calculateAge');	//OK---------
           Route::get('NameConvert/{condition_type}', 'VoterDetailsController@NameConvert')->name('admin.voter.NameConvert');	//OK-----
           Route::post('store', 'VoterDetailsController@store')->name('admin.voter.details.store');		//OK-----

//            Route::get('voterEdit/{id}', 'VoterDetailsController@voterListEdit')->name('admin.voter.voteredit');
//            Route::post('voterUpdate/{id}', 'VoterDetailsController@voterUpdate')->name('admin.voter.voterUpdate');
//            Route::get('voterDelete/{id}', 'VoterDetailsController@voterDelete')->name('admin.voter.voterDelete');

//     //--------------------Delete----------Delete--------delete----------------------------//       
//            Route::get('DeteleAndRestore', 'VoterDetailsController@DeteleAndRestore')->name('admin.voter.DeteleAndRestore');
//             Route::post('DeteleAndRestoreShow', 'VoterDetailsController@DeteleAndRestoreShow')->name('admin.voter.DeteleAndRestoreShow');
//            Route::get('DeteleAndRestoreDetele/{id}', 'VoterDetailsController@DeteleAndRestoreDetele')->name('admin.voter.DeteleAndRestoreDetele');
//            Route::get('DeteleAndRestoreRestore/{id}', 'VoterDetailsController@DeteleAndRestoreRestore')->name('admin.voter.DeteleAndRestoreRestore');
//     //-
//            Route::get('VoterDetailsModify', 'VoterDetailsController@VoterDetailsModify')->name('admin.voter.VoterDetailsModify');
//            Route::post('VoterDetailsModifyShow', 'VoterDetailsController@VoterDetailsModifyShow')->name('admin.voter.VoterDetailsModifyShow');
//            Route::get('VoterDetailsModifyEdit/{id}', 'VoterDetailsController@VoterDetailsModifyEdit')->name('admin.voter.VoterDetailsModifyEdit');
//            Route::post('VoterDetailsModifyStore/{id}', 'VoterDetailsController@VoterDetailsModifyStore')->name('admin.voter.VoterDetailsModifyStore');
//            Route::get('VoterDetailsModifyReset/{id}', 'VoterDetailsController@VoterDetailsModifyReset')->name('admin.voter.VoterDetailsModifyReset');
            


//     //-------prepare-voter-list--------------prepare-voter-list-----///
           
//            Route::get('PrepareVoterListPanchayat', 'VoterDetailsController@PrepareVoterListPanchayat')->name('admin.voter.PrepareVoterListPanchayat');		//OK Done--------
           Route::get('VillageWiseWardMultiple', 'VoterDetailsController@VillageWiseWardMultiple')->name('admin.voter.VillageWiseWardMultiple');	//OK------------

//            // Route::post('PrepareVoterListGenerate', 'VoterDetailsController@PrepareVoterListGenerate')->name('admin.voter.PrepareVoterListGenerate');

           Route::post('PrepareVoterListGenerate', 'PrepareVoterListController@PrepareVoterListGenerate')->name('admin.voter.GenerateVoterListAll'); 	//OK--------
//            Route::get('PrepareVoterListMunicipal', 'VoterDetailsController@PrepareVoterListMunicipal')->name('admin.voter.PrepareVoterListMunicipal');		//OK Done-----------

//            Route::post('PrepareVoterListMunicipalGenerate', 'VoterDetailsController@PrepareVoterListMunicipalGenerate')->name('admin.voter.PrepareVoterListMunicipalGenerate');

           Route::get('PrepareVoterListBoothWise', 'VoterDetailsController@PrepareVoterListBoothWise')->name('admin.voter.PrepareVoterListBoothWise');		//OK Done-----------
            
           Route::get('VoterListDownload', 'VoterDetailsController@VoterListDownload')->name('admin.voter.VoterListDownload');
           Route::get('BlockWiseDownloadTable', 'VoterDetailsController@BlockWiseDownloadTable')->name('admin.voter.BlockWiseDownloadTable');
           Route::get('VoterListDownloadPDF/{path}/{condition}', 'VoterDetailsController@VoterListDownloadPDF')->name('admin.voter.VoterListDownloadPDF');
           Route::get('processing-status', 'VoterDetailsController@processingStatus')->name('admin.voter.processing.status');		//OK ------------


//            Route::get('VidhansabhaListDownload', 'VoterDetailsController@VidhanSabhaListDownload')->name('admin.voter.VidhanSabhaListDownload');		//OK Done------------
//            Route::get('DistrictWiseVidhanDownloadTable', 'VoterDetailsController@DistrictWiseVidhanDownloadTable')->name('admin.voter.DistrictWiseVidhanDownloadTable');	//OK -------------
//            Route::get('VidhanSabhaListDownloadPDF/{path}', 'VoterDetailsController@VidhanListDownloadPDF')->name('admin.voter.VidhanSabhaListDownloadPDF');		//OK ------------
           
           
            
    });
// 	Route::group(['prefix' => 'BoothVoterList'], function() {
//            Route::get('/', 'BoothVoterListController@index')->name('admin.booth.voter.list');	//OK---------
//            Route::get('block-wise-booth-list', 'BoothVoterListController@blockWiseBoothList')->name('admin.booth.voter.list.block.wise.booth.list');	//OK------------
//            Route::post('booth-voter-list-process', 'BoothVoterListController@BoothVoterListProcess')->name('admin.booth.voter.list.process'); //OK-----
//            Route::get('booth-voter-list-download/{id}', 'BoothVoterListController@boothVoterListDownload')->name('admin.booth.voter.list.download');	//OK---------
//            Route::get('booth-voter-list-download-wop/{id}', 'BoothVoterListController@boothVoterListDownloadWOP')->name('admin.booth.voter.list.download.wop');		//OK-------------
//     });
    Route::group(['prefix' => 'PrepareVoterSlip'], function() {
           Route::get('index', 'PrepareVoterSlipController@index')->name('admin.prepare.voter.slip');	//OK--- 
           Route::post('PrepareVoterSlipGenerate', 'PrepareVoterSlipController@PrepareVoterSlipGenerate')->name('admin.prepare.voter.slip.generate');
           
           Route::get('PrepareVoterSlipDownload', 'PrepareVoterSlipController@PrepareVoterSlipDownload')->name('admin.prepare.voter.slip.download');
           Route::get('PrepareVoterSlipDownloadResult', 'PrepareVoterSlipController@PrepareVoterSlipDownloadResult')->name('admin.prepare.voter.slip.download.result');
           Route::get('PrepareVoterSlipResultDownload/{id}', 'PrepareVoterSlipController@PrepareVoterSlipResultDownload')->name('admin.prepare.voter.slip.result.download');
           
//            Route::get('village-wise-ward', 'PrepareVoterSlipController@villageWiseWard')->name('admin.prepare.voter.slip.village.wise.ward');
//            Route::get('village-wise-booth', 'PrepareVoterSlipController@villageWiseBooth')->name('admin.prepare.voter.slip.village.wise.booth');

    });
//     Route::group(['prefix' => 'Report'], function() {
//            Route::get('PrintVoterList', 'ReportController@PrintVoterList')->name('admin.report.PrintVoterList');
//            Route::post('PrintVoterListGenerate', 'ReportController@PrintVoterListGenerate')->name('admin.report.PrintVoterListGenerate');

//            Route::get('Report', 'ReportController@ReportIndex')->name('admin.report.Report');	//OK Done-----------

//            Route::get('villageWiseBooth', 'ReportController@villageWiseBooth')->name('admin.report.village.wise.booth');	//OK-----------
//            Route::get('destrict-wise-zp-ward', 'ReportController@districtwiseZPWard')->name('admin.report.destrict.wise.zp.ward');	//OK-----------
//            Route::get('block-wise-ps-ward', 'ReportController@blockwisePSWard')->name('admin.report.block.wise.ps.ward');	//OK-----------

//            Route::post('StatisticalReportGenerate', 'ReportController@StatisticalReportGenerate')->name('admin.report.ReportGenerate');		//OK----------------

//            Route::get('ReportGeneratePDF', 'ReportController@ReportGeneratePDF')->name('admin.report.ReportGeneratePDF');
//            //---duplicate-voter--//
//            Route::get('duplicate-voter', 'ReportController@duplicateVoter')->name('admin.report.duplicate.voter');
//            Route::get('duplicate-voter-cardno', 'ReportController@duplicateVoterCardNo')->name('admin.report.duplicate.voter.cardno');
//            Route::get('duplicate-voter-table', 'ReportController@duplicateVoterTable')->name('admin.report.duplicate.voter.table');
//            Route::get('duplicate-voter-delete/{id}', 'ReportController@duplicateVoterdelete')->name('admin.report.duplicate.voter.delete');
//            //---check-voter-status-//
//            Route::get('check-voter-status', 'ReportController@checkVoterStatus')->name('admin.report.check.voter.status');
//            Route::post('check-voter-status-search', 'ReportController@checkVoterStatusSearch')->name('admin.report.check.voter.status.search');
	 	 	
//     });
    Route::group(['prefix' => 'VoterListMaster'], function() {
           Route::get('vlm-index', 'MasterController@voterListIndex')->name('admin.VoterListMaster.index');
           Route::get('vlm-tale', 'MasterController@voterListTable')->name('admin.VoterListMaster.table');
           Route::post('vlm-store/{id}', 'MasterController@storeVoterListType')->name('admin.VoterListMaster.store');
           Route::get('vlm-edit/{id}', 'MasterController@show_votermaster_editform')->name('admin.VoterListMaster.edit');
           Route::get('vlm-default/{id}', 'MasterController@setVoterListTypeDefault')->name('admin.VoterListMaster.default');

//             Route::get('voter-import-type', 'MasterController@voterImportType')->name('admin.Voter.import.type');
//             Route::post('voter-import-type-store/{id?}', 'MasterController@voterImportTypeStore')->name('admin.Voter.import.type.store');
//             Route::get('import-edit/{id}', 'MasterController@setVoterImortTypeEdit')->name('admin.voterImportType.edit'); 		//OK----------
//             Route::get('import-type-default/{id}', 'MasterController@setVoterImortTypeDefault')->name('admin.voterImportType.default'); 		//OK----------

//            Route::get('VoterListDefaultValue', 'VoterListMasterController@VoterListDefaultValue')->name('admin.VoterListMaster.VoterListDefaultValue');           
//            Route::post('VoterListDefaultValueStore/{id?}', 'VoterListMasterController@VoterListDefaultValueStore')->name('admin.VoterListMaster.VoterListDefaultValueStore');           
	 	 
    });
//     Route::group(['prefix' => 'import'], function() {
//            Route::get('', 'ImportExportController@index')->name('admin.import.index'); //ok Done----
//            Route::get('sample-help-file', 'ImportExportController@sampleHelpFile')->name('admin.import.sample.help.file'); //ok----
//            Route::get('show-previous-upload-data', 'ImportExportController@showPreviousUpload')->name('admin.import.show.previous.upload'); //ok----
//            Route::post('store', 'ImportExportController@store')->name('admin.import.store'); //ok----

//            Route::post('importVote', 'ImportExportController@importVote')->name('admin.import.importVote');

//            Route::get('DistrictExportSample', 'ImportExportController@DistrictExportSample')->name('admin.import.DistrictExportSample');
//            Route::get('DistrictImportForm', 'ImportExportController@DistrictImportForm')->name('admin.import.DistrictImportForm');
//            Route::post('DistrictImportStore', 'ImportExportController@DistrictImportStore')->name('admin.import.DistrictImportStore');

//            Route::get('AssemblyExportSample', 'ImportExportController@AssemblyExportSample')->name('admin.import.AssemblyExportSample');
//            Route::get('AssemblyImportForm', 'ImportExportController@AssemblyImportForm')->name('admin.import.AssemblyImportForm');
//            Route::post('AssemblyImportStore', 'ImportExportController@AssemblyImportStore')->name('admin.import.AssemblyImportStore');

//            Route::get('BlockExportSample', 'ImportExportController@BlockExportSample')->name('admin.import.BlockExportSample');
//            Route::get('BlockImportForm', 'ImportExportController@BlockImportForm')->name('admin.import.BlockImportForm');
//            Route::post('BlockImportStore', 'ImportExportController@BlockImportStore')->name('admin.import.BlockImportStore');

//            Route::get('VillageExportSample', 'ImportExportController@VillageExportSample')->name('admin.import.VillageExportSample');
//            Route::get('VillageImportForm', 'ImportExportController@VillageImportForm')->name('admin.import.VillageImportForm');
//            Route::post('VillageImportStore', 'ImportExportController@VillageImportStore')->name('admin.import.VillageImportStore');
                     
// 	 	  Route::get('VillageWardExportSample', 'ImportExportController@VillageWardExportSample')->name('admin.import.VillageWardExportSample');
// 	 	  Route::get('VillageWardImportForm', 'ImportExportController@VillageWardImportForm')->name('admin.import.VillageWardImportForm');
// 	 	  Route::post('VillageWardImportStore', 'ImportExportController@VillageWardImportStore')->name('admin.import.VillageWardImportStore');
//     });       
    Route::group(['prefix' => 'Database'], function() {
//                Route::get('Connection', 'DatabaseConnectionController@DatabaseConnection')->name('admin.database.connection');		//OK Done--------------
//                Route::post('ConnectionStore', 'DatabaseConnectionController@ConnectionStore')->name('admin.database.conection.store');	//OK-------------

//                Route::get('getdata', 'DatabaseConnectionController@getData')->name('admin.database.conection.getData');	

               Route::get('getTable', 'DatabaseConnectionController@getTable')->name('admin.database.conection.getTable');		//OK Done----------
               Route::get('assemblyWisePartNo', 'DatabaseConnectionController@assemblyWisePartNo')->name('admin.database.conection.assemblyWisePartNo');	//OK--------
               Route::post('tableRecordStore', 'DatabaseConnectionController@tableRecordStore')->name('admin.database.conection.tableRecordStore');		//OK-------

//                 Route::get('imagestore', 'DatabaseConnectionController@imageStore')->name('admin.database.conection.imagestore');
//                 Route::get('process', 'DatabaseConnectionController@process')->name('admin.database.conection.process');

                Route::get('processDelete/{ac_id}/{part_id}', 'DatabaseConnectionController@processDelete')->name('admin.database.conection.processDelete');	//OK-------


//                 Route::get('MysqlDataTransfer', 'DatabaseConnectionController@MysqlDataTransfer')->name('admin.export.MysqlDataTransfer');	//OK--------
//                 Route::get('MysqlDataTransferDistrictWiseBlock', 'DatabaseConnectionController@MysqlDataTransferDistrictWiseBlock')->name('admin.export.MysqlDataTransferDistrictWiseBlock');	

//                 Route::get('MysqlDataTransferBlockWiseVillage', 'DatabaseConnectionController@MysqlDataTransferBlockWiseVillage')->name('admin.database.conection.MysqlDataTransferBlockWiseVillage');
//                 Route::get('MysqlDataTransferVillageWiseWard', 'DatabaseConnectionController@MysqlDataTransferVillageWiseWard')->name('admin.database.conection.MysqlDataTransferVillageWiseWard');


//                 Route::post('MysqlDataTransferStore', 'DatabaseConnectionController@MysqlDataTransferStore')->name('admin.database.conection.MysqlDataTransferStore');	//OK-------
                
        });
    Route::group(['prefix' => 'UnlockVoterList'], function() {
//                Route::get('/', 'PrepareVoterListController@UnlockVoterList')->name('admin.UnlockVoterList.index');	//OK Done-----------------
               Route::post('unlock', 'PrepareVoterListController@unlockVoterListUnlock')->name('admin.UnlockVoterList.unlock');	//OK---------
//                Route::get('mc', 'PrepareVoterListController@UnlockVoterListMc')->name('admin.UnlockVoterList.mc');	//OK Done---------
               Route::get('booth', 'PrepareVoterListController@UnlockVoterListBooth')->name('admin.UnlockVoterList.booth');	//OK Done-----------

 	});
//  	Route::group(['prefix' => 'check-photo-quality'], function() {
//                Route::get('/', 'PrepareVoterListController@checkPhotoQuality')->name('admin.check.photo.quality');	//OK Done-----------------
//                Route::post('store', 'PrepareVoterListController@checkPhotoQualityStore')->name('admin.check.photo.quality.store');	//OK Done-----------------
//                Route::get('all-ac', 'PrepareVoterListController@checkPhotoQualityAllAC')->name('admin.check.photo.quality.all.ac');	//OK Done-----------------
//                Route::post('all-ac-store', 'PrepareVoterListController@checkPhotoQualityAsmbStore')->name('admin.check.photo.quality.all.ac.store');	//OK Done-----------------
               

//  	});

//  	Route::group(['prefix' => 'last-voter-srno-ward'], function() {
//                Route::get('last-voter-srno-ward', 'MasterController@lastVoterSrNoWard')->name('admin.last.voter.srno.ward');	//OK Done-----------------
//                Route::get('last-voter-srno-ward-list', 'MasterController@lastVoterSrNoWardList')->name('admin.last.voter.srno.ward.list');	//OK Done-----------------
//                Route::get('last-voter-srno-ward-update', 'MasterController@lastVoterSrNoWardUpdate')->name('admin.last.voter.srno.ward.update');//OK 
               
               

//  	});
//  	Route::group(['prefix' => 'last-voter-srno-booth'], function() {
//                Route::get('last-voter-srno-booth', 'MasterController@lastVoterSrNoBooth')->name('admin.last.voter.srno.booth');	//OK Done-----------------
//                Route::get('last-voter-srno-booth-list', 'MasterController@lastVoterSrNoBoothList')->name('admin.last.voter.srno.booth.list');	//OK Done-----------------
//                Route::get('last-voter-srno-booth-update', 'MasterController@lastVoterSrNoBoothUpdate')->name('admin.last.voter.srno.booth.update');//OK 
//  	}); 
//  	Route::group(['prefix' => 'new-voter-ward-wise'], function() {
//                Route::get('new-voter-ward-wise', 'MasterController@newVoterWardWise')->name('admin.new.voter.ward.wise');	//OK Done-----------------
//                Route::get('new-voter-ward-wise-form', 'MasterController@newVoterWardWiseForm')->name('admin.new.voter.ward.wise.form');	//OK Done-----------------
//                Route::get('new-voter-ward-wise-table', 'MasterController@newVoterWardWiseTable')->name('admin.new.voter.ward.wise.table');	//OK Done-----------------
//                Route::post('new-voter-ward-wise-save', 'MasterController@newVoterWardWisesave')->name('admin.new.voter.ward.wise.save');	//OK Done-----------------
//                Route::get('new-voter-ward-wise-delete/{id}/{ward_id}', 'MasterController@newVoterWardWisedelete')->name('admin.new.voter.ward.wise.delete');//OK 
//                Route::get('new-voter-ward-wise-report', 'MasterController@newVoterWardWisereport')->name('admin.new.voter.ward.wise.report');	//OK Done-----------------
//                Route::post('new-voter-ward-wise-report-gene', 'MasterController@newVoterWardWisereportgenerate')->name('admin.new.voter.ward.wise.report.generete');
               
//  	});
//  	Route::group(['prefix' => 'mark-delete-voter'], function() {
//                Route::get('mark-delete-voter', 'MasterController@markDeleteVoter')->name('admin.mark.delete.voter');	//OK Done----------------- 
//                Route::get('mark-delete-voter-form', 'MasterController@markDeleteVoterForm')->name('admin.mark.delete.voter.form');	//OK Done----------------- 
//                Route::get('mark-delete-voter-table', 'MasterController@markDeleteVotertable')->name('admin.mark.delete.voter.table');	//OK Done----------------- 
//                Route::get('mark-delete-voter-update/{voter_id}/{village_id}', 'MasterController@markDeleteVoterupdate')->name('admin.mark.delete.voter.update');	//OK 
//                Route::post('mark-delete-voter-store', 'MasterController@markDeleteVoterStore')->name('admin.mark.delete.voter.store');	//OK Done
//                Route::get('mark-delete-voter-restore/{voter_id}/{village_id}', 'MasterController@markDeleteVoterRestore')->name('admin.mark.delete.voter.restore');	//OK 
//                Route::get('mark-delete-voter-report', 'MasterController@markDeleteVoterReport')->name('admin.mark.delete.voter.report');	//OK 
//                Route::post('mark-delete-voter-report-gene', 'MasterController@markDeleteVoterReportGenerate')->name('admin.mark.delete.voter.report.generate');	//OK 
//  	});
//  	Route::group(['prefix' => 'mark-modification-voter'], function() {
//                Route::get('mark-modification-voter', 'MasterController@markModificationVoter')->name('admin.mark.modification.voter');	//OK Done----------------- 
//                Route::get('mark-modification-voter-form', 'MasterController@markModificationVoterform')->name('admin.mark.modification.voter.from');	//OK 
//                Route::get('mark-modification-voter-table', 'MasterController@markModificationVoterTable')->name('admin.mark.modification.voter.table');	//OK 
//                Route::get('mark-modification-voter-update/{voter_id}/{village_id}', 'MasterController@markModificationupdate')->name('admin.mark.modification.voter.update');
//                Route::post('mark-modification-voter-store', 'MasterController@markModificationVoterStore')->name('admin.mark.modification.voter.store');	//OK Done
//                Route::get('mark-modification-voter-restore/{voter_id}/{village_id}', 'MasterController@markModificationVoterRestore')->name('admin.mark.modification.voter.restore');	//OK Done
//                Route::get('mark-modification-voter-report', 'MasterController@markModificationVoterReport')->name('admin.mark.modification.voter.report');	//OK 
//                Route::post('mark-modification-voter-report-gene', 'MasterController@markModificationVoterReportGenerate')->name('admin.mark.modification.voter.report.generate');	//OK 
               
//  	});
//  	Route::group(['prefix' => 'new-voter-booth-wise'], function() {
//                Route::get('new-voter-booth-wise', 'MasterController@newVoterBoothWise')->name('admin.new.voter.booth.wise');//OK 
//                Route::get('new-voter-booth-wise-form', 'MasterController@newVoterBoothWiseForm')->name('admin.new.voter.booth.wise.form');//OK 
//                Route::get('new-voter-booth-wise-table', 'MasterController@newVoterBoothWiseTable')->name('admin.new.voter.booth.wise.table');//OK 
//                Route::post('new-voter-booth-wise-store', 'MasterController@newVoterBoothWiseStore')->name('admin.new.voter.booth.wise.store');//OK 
//                Route::get('new-voter-booth-wise-delete/{voter_id}/{ward_id}', 'MasterController@newVoterBoothWiseDelete')->name('admin.new.voter.booth.wise.delete');//OK 
//                Route::get('new-voter-booth-wise-report', 'MasterController@newVoterBoothWiseReport')->name('admin.new.voter.booth.wise.report');//OK 
//                Route::post('new-voter-booth-wise-report-generate', 'MasterController@newVoterBoothWiseReportGenerate')->name('admin.new.voter.booth.wise.report.generate');//OK 
               
               
//  	});
//  	Route::group(['prefix' => 'mark-delete-voter-booth-wise'], function() {
//                Route::get('mark-delete-voter-booth-wise', 'MasterController@markDeleteVoterBoothWise')->name('admin.mark.delete.voter.booth.wise');//OK 
//                Route::get('mark-delete-voter-booth-wise-form', 'MasterController@markDeleteVoterBoothWiseForm')->name('admin.mark.delete.voter.booth.form');//OK 
//                Route::get('mark-delete-voter-booth-wise-table', 'MasterController@markDeleteVotertableboothwise')->name('admin.mark.deletebooth.voter.table');//OK 
//                Route::get('mark-delete-booth-voter-update/{voter_id}/{village_id}', 'MasterController@markDeleteboothVoterupdate')->name('admin.mark.delete.booth.voter.update');	//OK 
//                Route::post('mark-delete-booth-voter-store', 'MasterController@markDeleteboothVoterStore')->name('admin.mark.delete.booth.voter.store');	//OK Done
//                Route::get('mark-delete-booth-voter-restore/{voter_id}/{village_id}', 'MasterController@markDeleteboothVoterRestore')->name('admin.mark.delete.booth.voter.restore');	//OK 
//                Route::get('mark-delete-voter-booth-wise-report', 'MasterController@markDeleteVotertableboothwiseReport')->name('admin.mark.deletebooth.voter.report');//OK 
//                Route::post('mark-delete-voter-booth-report-generate', 'MasterController@markDeleteVoterBoothReportGenerate')->name('admin.mark.delete.voter.booth.report.generate');	//OK 
               
               
//  	});
//  	Route::group(['prefix' => 'mark-modification-voter-booth-wise'], function() {
//                Route::get('mark-modification-voter-booth-wise', 'MasterController@markModificationVoterBoothWise')->name('admin.mark.modification.voter.booth.wise');//OK 
//                Route::get('mark-modification-voter-booth-form', 'MasterController@markModificationVoterBoothForm')->name('admin.mark.modification.voter.booth.form');//OK 
//                Route::get('mark-modification-voter-booth-table', 'MasterController@markModificationVoterBoothTable')->name('admin.mark.modification.voter.booth.table');//OK
//                Route::get('mark-modification-voter-booth-update/{voter_id}/{village_id}', 'MasterController@markModificationVoterBoothUpdate')->name('admin.mark.modification.voter.booth.update');//OK 
//                Route::post('mark-modification-voter-booth-store', 'MasterController@markModificationVoterBoothUpStore')->name('admin.mark.modification.voter.booth.store');//OK 
//                Route::get('mark-modification-voter-booth-restore/{voter_id}/{village_id}', 'MasterController@markModificationVoterBoothUpRestore')->name('admin.mark.modification.voter.booth.restore');//OK 
//                Route::get('mark-modification-voter-booth-report', 'MasterController@markModificationVoterBoothUpReport')->name('admin.mark.modification.voter.booth.report');//OK 
//                Route::post('mark-modification-voter-booth-report-generate', 'MasterController@markModificationVoterBoothUpReportGenerate')->name('admin.mark.modification.voter.booth.report.generate');//OK 
               
               
               
//  	});
//  	Route::group(['prefix' => 'prepare-voter-list-supplimentDatalistwise'], function() {
//  				//---------------ward-wise----------------------------
//                Route::get('prepare-voter-list-supplimentDatalistwise', 'PrepareVoterListController@prepareVoterListSupplimentDatalistwise')->name('admin.prepare.voter.list.supplimentDatalistwise');//OK 
//                Route::post('prepare-voter-list-supplimentDatalistwise-store', 'PrepareVoterListController@prepareVoterListSupplimentDatalistwiseStore')->name('admin.prepare.voter.list.supplimentDatalistwise.store');//OK 
//                //---------------booth-wise----------------------------
//                Route::get('prepare-voter-list-supplimentDatalistBoothwise', 'PrepareVoterListController@prepareVoterListSupplimentDatalistBoothwise')->name('admin.prepare.voter.list.supplimentDatalistBoothwise');//OK 
//                Route::post('prepare-voter-list-supplimentDatalistBoothwise-store', 'PrepareVoterListController@prepareVoterListSupplimentDatalistwiseBoothStore')->name('admin.prepare.voter.list.supplimentDatalistBoothwise.store');//OK 
               
               
               
//  	});

//  	Route::group(['prefix' => 'import-from-sql-server'], function() {
//  				//---------------ward-wise----------------------------
//                Route::get('/', 'ImportFromSqlServerController@index')->name('admin.import.from.sql.server');//OK 
//                Route::get('sql-server-districtWiseBlock', 'ImportFromSqlServerController@sqlServerDistrictWiseBlock')->name('admin.sql.server.districtWiseBlock');//OK 
//                Route::get('sql-server-BlockWiseWillage', 'ImportFromSqlServerController@sqlServerBlockWiseWillage')->name('admin.sql.server.BlockWiseWillage');//OK 
//                Route::post('sql-server-data-transfer', 'ImportFromSqlServerController@sqlServerDataTransfer')->name('admin.sql.server.DataTransfer');
//  	});
//  	Route::group(['prefix' => 'import-from-mysql-server'], function() {
//  				//---------------ward-wise----------------------------
//                Route::get('mySQLServer', 'ImportFromSqlServerController@mySQLServer')->name('admin.import.from.mysql.server');//OK
//                Route::get('mysql-server-districtWiseBlock', 'ImportFromSqlServerController@mySQLServerDistrictWiseBlock')->name('admin.mysql.server.districtWiseBlock');//OK
//                Route::get('mysql-server-BlockWiseWillage', 'ImportFromSqlServerController@mySQLServerBlockWiseWillage')->name('admin.mysql.server.BlockWiseWillage');//OK
//                Route::post('mysql-server-data-transfer', 'ImportFromSqlServerController@mySQLServerDataTransfer')->name('admin.mysql.server.DataTransfer');  
               
//  	});

//  	Route::group(['prefix' => 'colonydetail-ward'], function() {
//                Route::get('colonydetail-ward', 'MasterController@colonyDetail')->name('admin.master.ward.colonydetail');	//OK Done-----------------
//                Route::get('colonydetail-ward-list', 'MasterController@colonyDetailWardList')->name('admin.master.list.ward.colonydetail');	//OK Done-----------------
//                Route::get('colonydetail-ward-update', 'MasterController@colonyDetailWardUpdate')->name('admin.master.update.ward.colonydetail');//OK 
               
               

//  	});

 	Route::group(['prefix' => 'claim-obj-ac-part-srno'], function() {
//                Route::get('change-ward', 'MasterController@claimObjAcPartSrnoChangeWard')->name('admin.claim.obj.ac.part.srno.changeWard');//OK
//                Route::get('change-ward-form', 'MasterController@claimObjAcPartSrnoChangeWardForm')->name('admin.claim.obj.ac.part.srno.changeWardForm');//OK--------
//                Route::get('change-ward-table', 'MasterController@claimObjAcPartSrnoChangeWardTable')->name('admin.claim.obj.ac.part.srno.changeWardTable');	//OK-----
//                Route::post('change-ward-form-Store', 'MasterController@claimObjAcPartSrnoChangeWardFormStore')->name('admin.claim.obj.ac.part.srno.changeWardFormStore'); //OK-----

               Route::get('change-voter-ward-with-acpart-report', 'MasterController@reportClaimObjWardACPart')->name('admin.Master.change.voter.ward.with.acpart.report');	 	//OK---------------
//                Route::post('change-voter-with-ward-acpart-report-pdf', 'MasterController@changeVoterWithWardACPartReportPdf')->name('admin.Master.change.voter.with.ward.acpart.report.pdf');	//OK----------

               Route::get('add-new-voter', 'MasterController@claimObjAcPartEpicNoAddNewVoter')->name('admin.claim.obj.ac.part.addnewvoter');//OK
               Route::get('add-new-voter-form', 'MasterController@claimObjAcPartEpicAddWardForm')->name('admin.claim.obj.ac.part.epicno.addnewvoter.form');	//OK-------------
               Route::get('addvoter-ward-table', 'MasterController@claimObjAcPartEpicAddVoterWardTable')->name('admin.claim.obj.ac.part.srno.addvoterWardTable');	//OK-----
               Route::post('addnew-ward-form-Store', 'MasterController@addNewVoterDataFromServer')->name('admin.claim.obj.ac.part.epic.addNewVoteWardFormStore'); //OK-----

               
               

// //-------------------Delete Voter Ac- Part No. Wise--------------------------------------------
               Route::get('delete-voter', 'MasterController@claimObjAcPartSrnoDeleteVoter')->name('admin.claim.obj.ac.part.srno.deleteVoter');//OK
               Route::get('delete-voter-form', 'MasterController@claimObjAcPartSrnoDeleteVoterForm')->name('admin.claim.obj.ac.part.srno.deleteVoterForm');
               Route::get('delete-voter-table', 'MasterController@claimObjAcPartSrnoDeleteVoterFormTable')->name('admin.claim.obj.ac.part.srno.deleteVoterFormTable');
               Route::post('delete-voter-store', 'MasterController@claimObjAcPartSrnoDeleteVoterStore')->name('admin.claim.obj.ac.part.srno.deleteVoterStore');	//OK---------


	    

// //-------------------change-booth--------------------------------------------

               Route::get('change-booth', 'MasterController@claimObjAcPartSrnoChangeBooth')->name('admin.claim.obj.ac.part.srno.changebooth');
               Route::get('change-booth-form', 'MasterController@claimObjAcPartSrnoChangeBoothForm')->name('admin.claim.obj.ac.part.srno.changeBoothForm');
               Route::get('change-booth-table', 'MasterController@claimObjAcPartSrnoChangeBoothTable')->name('admin.claim.obj.ac.part.srno.changeBoothTable');
               Route::post('change-booth-form-Store', 'MasterController@claimObjAcPartSrnoChangeBoothFormStore')->name('admin.claim.obj.ac.part.srno.changeBoothFormStore');
               
               
               
 	});

//  	Route::group(['prefix' => 'vidhansabha'], function() {
//  		Route::get('index', 'PrepareVidhansabhaListController@index')->name('admin.prepare.vidhansabha.List.index');
//  		Route::post('submit', 'PrepareVidhansabhaListController@submit')->name('admin.prepare.vidhansabha.List.submit');
//  	});

//  	Route::group(['prefix' => 'backup-management'], function() {
//  		Route::get('index', 'BackupManagementController@index')->name('admin.backup.management.imdex');
//  		Route::post('submit', 'BackupManagementController@submit')->name('admin.backup.management.submit');
//  	});
 });
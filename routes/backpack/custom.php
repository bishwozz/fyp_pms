<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

$roles = 'superadmin|clientadmin|admin|reception|doctor|lab_admin|lab_technician|lab_technologist|referral|finance';

Route::group(
    [
        'namespace'  => 'App\Http\Controllers',
        'middleware' => config('backpack.base.web_middleware', 'web'),
        'prefix'     => config('backpack.base.route_prefix'),
    ],
    function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
        Route::get('/', 'AdminController@redirect')->name('backpack');
    });



Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin\CoreMaster',
], function () { // custom admin routes
    Route::crud('mst-fed-province', 'MstFedProvinceCrudController');
    Route::crud('mst-fed-district', 'MstFedDistrictCrudController');
    Route::crud('mst-fed-local-level-type', 'MstFedLocalLevelTypeCrudController');
    Route::crud('mst-fed-local-level', 'MstFedLocalLevelCrudController');

    Route::crud('mst-nepali-month', 'MstNepaliMonthCrudController');
    Route::crud('mst-fiscal-year', 'MstFiscalYearCrudController');
    Route::crud('mst-gender', 'MstGenderCrudController');
    Route::crud('app-setting', 'AppSettingCrudController');
    Route::crud('mstcountry', 'MstCountryCrudController');
    Route::crud('mst-lab-sample', 'MstLabSampleCrudController');
    Route::crud('mst-lab-method', 'MstLabMethodCrudController');
});


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('appclient', 'AppClientCrudController');
    Route::crud('patient', 'PatientCrudController');
    Route::crud('patient-appointment', 'PatientAppointmentCrudController');
    Route::crud('mst-religion', 'MstReligionCrudController');
    Route::get('/patient', 'PatientCrudController@getAllPatients');
    Route::get('/patient/search_patient','PatientCrudController@searchPatient');
    
    Route::get('/patient/list-all-patients','PatientCrudController@listAllPatients');
    
    Route::crud('referral', 'ReferralCrudController');

    Route::crud('emergency-patient', 'EmergencyPatientCrudController');
    Route::get('/emergency-patient', 'EmergencyPatientCrudController@getAllPatients');
    Route::get('/emergency-patient/search_patient','EmergencyPatientCrudController@searchPatient');

    Route::get('reports', 'ReportController@index');
    Route::get('reports/{report_url}', 'ReportController@getData');
    Route::get('reports/print', 'ReportController@getData');
    Route::post('getreportdata', 'ReportController@getLmsReportData');
    Route::get('getreportdata', 'ReportController@getLmsReportData');
    
    Route::get('getexceldata', 'ReportController@getLmsReportData');

    Route::crud('mst-bank', 'MstBankCrudController');
    

    Route::get('excel-upload', 'ExcelUploadController@index');
    Route::post('excel-upload', 'ExcelUploadController@excelUpload')->name('excel-upload');

    Route::get('/patient-appointment/{patient_id}/approve', 'PatientAppointmentCrudController@patientApprove');
    Route::post('/patient-appointment/approve-save', 'PatientAppointmentCrudController@patientApproveSave');


    

});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin\HrMaster',
], function () { // custom admin routes

	 Route::crud('hrmstemployees', 'HrMstEmployeesCrudController');
     Route::crud('hrmstdepartments', 'HrMstDepartmentsCrudController');
     Route::crud('hrmstdepartments/{department_id}/hrmstsubdepartments', 'HrMstSubDepartmentsCrudController');

});

//patient billing routes
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin\Billing',
], function () { // custom admin routes
    Route::crud('/billing/patient-billing/{custom_param?}', 'PatientBillingCrudController');
    Route::get('/billing/get-patient-info','PatientBillingCrudController@getPatientInfo');

    Route::post('/billing/patient-billing/{custom_param?}/store-bill', 'PatientBillingCrudController@storeBill');
    Route::get('/billing/patient-billing/{custom_param?}/{bill_id}/bill-cancel-view', 'PatientBillingCrudController@billCancelView');
    Route::post('/billing/patient-billing/{custom_param?}/update-bill-cancel-status', 'PatientBillingCrudController@updateBillCancelStatus');
    
    Route::get('/billing/patient-billing/{custom_param?}/{bill_id}/due-collection-view', 'PatientBillingCrudController@dueCollectionView');
    Route::post('/billing/patient-billing/{custom_param?}/update-due-collection', 'PatientBillingCrudController@updateDueCollection');


    Route::get('/billing/patient-billing/{custom_param?}/lab-items','PatientBillingCrudController@loadLabItems');
    Route::get('/billing/patient-billing/{custom_param?}/get-item-rate','PatientBillingCrudController@getItemRate');
    Route::get('/billing/patient-billing/{custom_param?}/getReferalData','PatientBillingCrudController@getReferalData');
    Route::get('/billing/patient-billing/{custom_param?}/get-referral-data','PatientBillingCrudController@loadReferralData');
    Route::get('/billing/patient-billing/{custom_param?}/{lab_id}/generate_sales_bill/{name?}' ,'PatientBillingCrudController@printSalesDetailBill');

});


//lab routes
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin\Lab',
], function () { // custom admin routes

    Route::crud('lab/lab-mst-categories', 'LabMstCategoriesCrudController');
    Route::crud('lab/lab-mst-categories/{lab_category_id}/lab-mst-items', 'LabMstItemsCrudController');
    Route::crud('lab/lab-panel', 'LabPanelCrudController');
    Route::get('lab-panel/fetch-lab-category-items', 'LabPanelCrudController@fetchLabCategoryItemsAndGroups');
    
    Route::crud('lab/lab-group', 'LabGroupCrudController');
    Route::get('lab-group/fetch-lab-category-items', 'LabGroupCrudController@fetchLabCategoryItems');

    Route::crud('lab-patient-test-data/{custom_param?}', 'LabPatientTestDataCrudController');
    Route::post('lab/sample-collect', 'LabPatientTestDataCrudController@collectSample');

    Route::crud('lab/result-entry/{custom_param?}', 'ResultEntryCrudController');
    Route::post('lab/store-result', 'ResultEntryCrudController@storeResult');

    Route::crud('lab/dispatch-result', 'DispatchResultCrudController');
    Route::post('lab/{lab_test_id}/dispatch', 'DispatchResultCrudController@dispatchResult');
    Route::crud('lab/interpretation', 'InterpretationCrudController');

    Route::crud('lab/lab-items', 'LabItemsCrudController');
});




//patient routes
Route::group([
    'prefix'     => 'patient',
    'middleware' => ['web'],
    'namespace'  => 'App\Http\Controllers\Auth',
], function () { // custom admin routes

    Route::get('patient-login', 'LoginController@showPatientLoginForm');
    Route::post('patient-login', 'LoginController@login')->name('patient-login');

    Route::get('patient-logout', 'LoginController@logout')->name('patient-logout');
    Route::post('patient-logout', 'LoginController@logout');

});
//patient routes
Route::group([
    'prefix'     => 'patient',
    'middleware' => ['web',config('backpack.base.middleware_key', 'patient'),"role:patient"],
    'namespace'  => 'App\Http\Controllers\Patient',
], function () { // custom admin routes

    Route::get('patient-dashboard','PatientDashboardController@index');
    Route::get('get-report-list','PatientDashboardController@getReportList');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin\Log',

], function () { // custom admin Log routes

    Route::crud('session_log', 'SessionLogCrudController');
    Route::crud('activity_log', 'ActivityLogCrudController');
});



Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin\Pms',
], function () {
		Route::crud('/mstunit', 'MstUnitCrudController');
		Route::crud('/mstbrand', 'MstBrandCrudController');

		Route::crud('/mstcategory', 'MstCategoryCrudController');
		Route::crud('/mstpharmaceutical', 'MstPharmaceuticalCrudController');
		Route::crud('/mstsupplier', 'MstSupplierCrudController');
	  
		Route::crud('/item', 'ItemCrudController');
		  
		Route::crud('/inventory', 'InventoryCrudController');
		Route::get('/inventory/printReport', 'InventoryCrudController@printInventoryReport');

 	    Route::crud('/mstgenericname', 'MstGenericNameCrudController');
 	 // this should be the last line donot remove this

});
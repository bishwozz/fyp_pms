<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Pms\MstSequenceCrudController;

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
});


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('appclient', 'AppClientCrudController');

    Route::get('reports', 'ReportController@index');
    Route::get('reports/{report_url}', 'ReportController@getData');
    Route::get('reports/print', 'ReportController@getData');
    Route::post('getreportdata', 'ReportController@getLmsReportData');
    Route::get('getreportdata', 'ReportController@getLmsReportData');
    Route::crud('mst-bank', 'MstBankCrudController');


});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'),"role:$roles"],
    'namespace'  => 'App\Http\Controllers\Admin\HrMaster',
], function () { // custom admin routes

	 Route::crud('hrmstemployees', 'HrMstEmployeesCrudController');

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


//session
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

		Route::crud('/sales', 'SalesCrudController');
        Route::get('/sales/get-item-info','SalesCrudController@getItemsInfo');
    	Route::get('/sales/items','SalesCrudController@loadItems');
    	Route::get('/sales/get-item-rate','SalesCrudController@getItemRate');

		Route::post('/sales/store-bill', 'SalesCrudController@storeBill');
    	Route::get('/sales/{bill_id}/bill-cancel-view', 'SalesCrudController@billCancelView');
    	Route::post('/sales/update-bill-cancel-status', 'SalesCrudController@updateBillCancelStatus');

		Route::get('/sales/check-item-qty','SalesCrudController@checkItemsQty');

		Route::crud('/purchase-order-detail', 'PurchaseOrderDetailCrudController');
		Route::post('/purchase-order-detail/{order_id}', 'PurchaseOrderDetailCrudController@update')->name('purchase.order-edit');


        Route::get('/mst-sequence/sequence-code-check', [MstSequenceCrudController::class, 'sequenceCodeCheck'])->name('sequence.code-check');
        Route::post('/mst-sequence/inline-create', [MstSequenceCrudController::class, 'inlineStore'])->name('sequence.inlineStore');

        Route::get('get-contact-details/{detail}', 'PurchaseOrderDetailCrudController@getContactDetails')->name('custom.contact-details');
        Route::get('po-item-details/{item}', 'PurchaseOrderDetailCrudController@poDetails')->name('custom.po-details');
        Route::get('purchase-history-details/{id}/{to}/{from}', 'PurchaseOrderDetailCrudController@purchaseOrderHistoryDetails')->name('custom.poh-details');

		Route::crud('/purchase-return', 'PurchaseReturnCrudController');


        Route::get('get-batch/{itemId}', 'SalesCrudController@getBatchItem')->name('custom.get-batch');
        Route::get('get-batch-detail/{itemId}/{batchId}', 'SalesCrudController@getBatchDetail')->name('custom.get-batch-detail');
        Route::get('get-batch-item-detail/{itemId}/{batchNo}', 'PurchaseReturnCrudController@getBatchDetail')->name('custom.get-batch-item-detail');

 	 // this should be the last line donot remove this

        Route::crud('stock-entry', 'StockEntryCrudController');
        Route::get('stock-item/{item}', 'StockEntryCrudController@stockItem')->name('custom.stock-item');
        Route::get('search-stock/{id}/{to}/{from}', 'StockEntryCrudController@StockItemHistory')->name('custom.stock-item-search');
    Route::crud('stock-items', 'StockItemsCrudController');


    Route::get('get-total/{item}', 'SalesCrudController@stockItem')->name('custom.get-total');
    Route::get('sales-bill-history/{detail}/{to}/{from}', 'SalesCrudController@getSalesHistoryDetails')->name('custom.sales-bill-history');
    Route::get('sales/{id}/edit', 'SalesCrudController@edit')->name('custom.sales-edit');
    Route::post('sale-barcode-details/{stockItem}/{batchId}', 'SalesCrudController@barcodeSessionStore')->name('custom.sale-barcode');


    Route::get('search-stock/{id}/{to}/{from}', 'StockEntriesCrudController@StockItemHistory')->name('custom.stock-item-search');
    Route::post('stock-barcode-details/{stockItem}', 'StockEntriesCrudController@barcodeSessionStore')->name('custom.stock-barcode');
    Route::get('stock-barcode-details/flush/{key}', 'StockEntriesCrudController@barcodeSessionFlush')->name('custom.stock-barcode-flush');

        //?? API Route to get customers in Modal's select

        Route::get('/api/customer/{id}', 'MstCustomerCrudController@getCustomerDetailById')->name('api.customerDetail');


            // Bulk Upload for Items (Excel Upload)
    Route::post('mst-item/excel-import', 'ItemCrudController@itemEntriesExcelImport')
    ->name('item.importExcel');
    //Notifications for Stock Minimum Alert of mst item
    Route::get('stock-notification', 'ItemCrudController@loadNotification')->name('stock.notification.load');
    Route::get('stock-notification/{id}/mark-as-read', 'ItemCrudController@markNotification')->name('stock.notification.markread');
    Route::get('stock-notification/check', 'ItemCrudController@checkNotification')->name('stock.notification.check');
    Route::get('stock-notification/show', 'ItemCrudController@showNotifications')->name('stock.notification.show');

    });

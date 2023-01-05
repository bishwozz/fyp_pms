<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/admin');
});
Route::get('home', function () {
    return redirect('/admin');
});
Route::get('/district/{id}', 'App\Http\Controllers\DependentDropdownController@getdistrict');
Route::get('/local_level/{id}', 'App\Http\Controllers\DependentDropdownController@getlocal_level');
Route::get('/getdistrictlocallevel', 'App\Http\Controllers\DependentDropdownController@getdistrictlocallevel');


Route::get('api/district/{province_id}', 'App\Http\Controllers\Api\ProvinceDistrictController@index');
Route::get('api/locallevel/{district_id}', 'App\Http\Controllers\Api\DistrictLocalLevelController@index');
Route::get('api/department/{department_id}', 'App\Http\Controllers\Api\DrpartmentSubDepartmentController@index');

Route::get('/lab-patient-test-data/{test_id}/print-test-report', 'App\Http\Controllers\ReportPrintController@printTestReport');
// Route::get('/patient-dashboard', 'App\Http\Controllers\Patient\PatientDashboardController@index');

Route::get('send', 'PatientAppointmentCrudController@sendNotification');


Route::view('/a', 'customAdmin.stockEntry.form');


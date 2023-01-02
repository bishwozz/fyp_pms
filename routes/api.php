<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'app/v1',
            'namespace'=>'App\Http\Controllers\Api'],
            function () {
    Route::get('/employee-list','ApiController@getEmployeeList');
    Route::post('/save-appointment','ApiController@saveAppointment');
});


Route::post('district/{province_id}', 'App\Http\Controllers\Api\ProvinceDistrictController@index');
Route::post('locallevel/{district_id}', 'App\Http\Controllers\Api\DistrictLocalLevelController@index');
Route::post('department/{department_id}', 'App\Http\Controllers\Api\DrpartmentSubDepartmentController@index');
Route::post('doctor/{department_id}', 'App\Http\Controllers\Api\DepartmentDoctorController@index');

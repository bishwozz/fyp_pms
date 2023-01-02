<?php

/*
|--------------------------------------------------------------------------
| Backpack\Base Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\Base package.
|
*/

Route::group(
[
    'namespace'  => 'App\Http\Controllers\Auth',
    'middleware' => 'web',
    'prefix'     => config('backpack.base.route_prefix'),
],
function () {
    // if not otherwise configured, setup the auth routes
    if (config('backpack.base.setup_auth_routes')) {
        // Authentication Routes...
        Route::get('login', 'LoginController@showLoginForm')->name('backpack.auth.login');
        Route::post('login', 'LoginController@login');
        Route::get('logout', 'LoginController@logout')->name('backpack.auth.logout');
        Route::post('logout', 'LoginController@logout');

        // Registration Routes...
        Route::get('register', 'RegisterController@showRegistrationForm')->name('backpack.auth.register');
        Route::post('register', 'RegisterController@register');

        // Password Reset Routes...
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('backpack.auth.password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('backpack.auth.password.reset.token');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backpack.auth.password.email')->middleware('backpack.throttle.password.recovery:'.config('backpack.base.password_recovery_throttle_access'));
    }

    // if not otherwise configured, setup the dashboard routes
    if (config('backpack.base.setup_dashboard_routes')) {
        Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
        Route::get('/', 'AdminController@redirect')->name('backpack');
    }

    // if not otherwise configured, setup the "my account" routes
    if (config('backpack.base.setup_my_account_routes')) {
        Route::get('edit-account-info', 'MyAccountController@getAccountInfoForm')->name('backpack.account.info');
        Route::post('edit-account-info', 'MyAccountController@postAccountInfoForm')->name('backpack.account.info.store');
        Route::post('change-password', 'MyAccountController@postChangePasswordForm')->name('backpack.account.password');
    }
});

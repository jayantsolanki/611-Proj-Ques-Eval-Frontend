<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
//Home
Route::any('/', [
	'as' => 'home',
	function () {
    	//return view('register/home');
    	return redirect()->route('login');
	}
]);

Route::POST('auth/user_login',[
	'as' => 'user_login',
	'uses' => 'Auth\UserAuthController@authenticate'
]);

//login page
Route::any('/login', [
	'as' => 'login',
	'uses' => 'Auth\UserAuthController@login'
]);

Route::any('/logout', [
	'as' => 'logout',
	'uses' => 'Auth\UserAuthController@login'
]);

Route::any('auth/forgotpass',[
	'as' => 'forgotpass',
	function(){
		return view('registration/forgotpass');
	}
]);

Route::any('register/forgotpassprocess',[
	'as' => 'forgotpassprocess',
	'uses' => 'Auth\UserAuthController@forgotpassProcess'
]);

// create account
Route::any('/createAccountPage', [
	'as' => 'createAccountPage',
	function(){
		return view('registration/register');
	}
]);

Route::any('/createAccount', [
	'as' => 'createAccount',
	'uses' => 'Auth\RegisterController@create'
]);

Route::GET('admin/dashboard',[
		'as' => 'adminHome',
		'uses' => 'Admin\AdminController@showDashboard'
	]);

Route::GET('member/dashboard',[
	'as' => 'memberHome',
	'uses' => 'Members\MembersController@showDashboard'
]);

Route::GET('member/profile',[
	'as' => 'memberProfile',
	'uses' => 'Members\MembersController@showProfile'
]);

Route::group(['middleware' => 'auth'], function (){

	

});

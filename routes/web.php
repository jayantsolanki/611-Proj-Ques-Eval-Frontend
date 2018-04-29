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


/********************Home*****************************/

Route::any('/',[
	'as' => 'loginLand',
	'uses' => 'Auth\UserAuthController@loginLand'
]);

/*****************Authentication Controller*************/
Route::POST('auth/user_login',[
	'as' => 'user_login',
	'uses' => 'Auth\UserAuthController@authenticate'
]);

Route::any('/login', [
	'as' => 'login',
	'uses' => 'Auth\UserAuthController@login'
]);

Route::any('/logout', [
	'as' => 'logout',
	'uses' => 'Auth\UserAuthController@logout'
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

/*****************Account Controller*************/
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

/*****************Admin Controller*************/
Route::any('admin/dashboard',[
		'as' => 'adminHome',
		'middleware' => 'auth',
		'uses' => 'Admin\AdminController@showDashboard'
]);

Route::any('admin/profile',[
	'as' => 'adminProfile',
	'middleware' => 'auth',
	'uses' => 'Admin\AdminController@showProfile'
]);

Route::any('admin/editProfile',[
	'as' => 'adminEditProfile',
	'middleware' => 'auth',
	'uses' => 'Admin\AdminController@editProfile'
]);

Route::any('admin/userManage',[
	'as' => 'userManage',
	'middleware' => 'auth',
	'uses' => 'Admin\AdminController@userManage'
]);
/*****************Member Controller*************/
Route::any('member/dashboard',[
	'as' => 'memberHome',
	'middleware' => 'auth',
	'uses' => 'Members\MembersController@showDashboard'
]);

Route::any('member/profile',[
	'as' => 'memberProfile',
	'middleware' => 'auth',
	'uses' => 'Members\MembersController@showProfile'
]);

Route::any('member/editProfile',[
	'as' => 'memberEditProfile',
	'middleware' => 'auth',
	'uses' => 'Members\MembersController@editProfile'
]);

/*****************Question Controller*************/

Route::group(['middleware' => 'auth'], function (){

	

});

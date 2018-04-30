<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\UserDetails;

use App\Http\Controllers\Controller;

class AdminController extends Controller{

 	public function showDashboard(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		return view('admin.home')->with('userDetails', Auth::user());
 	}
 	public function showProfile(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		return view('admin.profile')->with('userDetails', Auth::user());
 	}
 	public function editProfile(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		return view('admin.editProfile')->with('userDetails', Auth::user());
 	}
 	public function userManage(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		// add all the current users data here
 		return view('admin.userManagement')->with('userDetails', Auth::user());
 	}
}
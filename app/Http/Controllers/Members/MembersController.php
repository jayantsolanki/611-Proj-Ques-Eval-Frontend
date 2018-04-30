<?php

namespace App\Http\Controllers\Members;
use Auth;
use App\UserDetails;

use App\Http\Controllers\Controller;

class MembersController extends Controller{

 	public function showDashboard(){
 		if(Auth::user()->role != 1){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		return view('members.home')->with('userDetails', Auth::user());
 	}
 	public function showProfile(){
 		if(Auth::user()->role != 1){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		return view('members.profile')->with('userDetails', Auth::user());
 	}
 	public function editProfile(){
 		if(Auth::user()->role != 1){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		return view('members.editProfile')->with('userDetails', Auth::user());
 	}
}

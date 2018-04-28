<?php

namespace App\Http\Controllers\Members;
use Auth;
use App\UserDetails;

use App\Http\Controllers\Controller;

class MembersController extends Controller{

 	public function showDashboard(){
 		return view('members.home')->with('userDetails', Auth::user());
 	}
}
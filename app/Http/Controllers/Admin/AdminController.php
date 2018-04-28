<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\UserDetails;

use App\Http\Controllers\Controller;

class AdminController extends Controller{

 	public function showDashboard(){
 		return view('admin.home')->with('userDetails', Auth::user());
 	}
}
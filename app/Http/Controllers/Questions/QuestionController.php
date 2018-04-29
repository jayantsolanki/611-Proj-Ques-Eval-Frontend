<?php

namespace App\Http\Controllers\Question;
use Auth;
// use App\UserDetails;

use App\Http\Controllers\Controller;

class QuestionController extends Controller{

 	public function showViewer(){
 		return view('members.home')->with('userDetails', Auth::user());
 	}

 	public function showEditor(){
 		return view('members.profile')->with('userDetails', Auth::user());
 	}
 	public function showStats(){
 		return view('members.editProfile')->with('userDetails', Auth::user());
 	}
}
}
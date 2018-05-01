<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\UserDetails;
use App\UserFullDetails;
use App\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Hash;
use DB;

class AdminController extends Controller{

 	public function showDashboard(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
        $inactiveUsers = Login::where('active',0)->count();
 		return view('admin.home')->with('userDetails', Auth::user())->with('inactiveUsers', $inactiveUsers);
 	}
 	public function showProfile(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
        $inactiveUsers = Login::where('active',0)->count();
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		// return $userdetails;
 		return view('admin.profile')->with('userDetails', $userdetails)->with('inactiveUsers', $inactiveUsers);
 	}
 	public function editProfile(){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
        $inactiveUsers = Login::where('active',0)->count();
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		return view('admin.editProfile')->with('userDetails', $userdetails)->with('inactiveUsers', $inactiveUsers);
 	}

    /*****************Manage user profile******************/
 	public function userManage(Request $data){
 		if(Auth::user()->role != 2){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		if(sizeof($data->all())>0){
 			$rules = [
	            'selectUser' => 'required'
	        ];
	        $messages = [   
	            'selectUser.required' =>  'Please select the profile to be edited'     
	        ];

            $validator = Validator::make($data->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->route('userManage')->withErrors($validator)->withInput($data->all());
            }
            $inactiveUsers = Login::where('active',0)->count();
            if(Auth::user()->user_id == $data->selectUser){
                return redirect()->route('userManage')->with('error',"You are not allowed to configure your own account")->with('inactiveUsers', $inactiveUsers);
            }
            //hack
 			$user = 'userRole'.$data->selectUser;
 			$data -> userRole = $data -> $user;
            $user = 'userActive'.$data->selectUser;
            $data -> userActive = $data -> $user;
            $userdetails = UserDetails::where('id',$data->selectUser)->first();
 			// return $data->selectUser;
 			DB::transaction(function($data) use ($data){
	            $userdetails = UserDetails::where('id',$data->selectUser)->first();
	            $userdetails -> role =  $data->userRole;
	            $userdetails ->save();

	            $oldLogin = Login::where('user_id',$data->selectUser)->first();
	        	$oldLogin -> role =  $data->userRole;
                $oldLogin -> active =  $data->userActive;
	            $oldLogin ->save();
                $data->name = $userdetails->name;
	        });//end of transaction
            $inactiveUsers = Login::where('active',0)->count();
	        return redirect()->route('userManage')->with("success","User profile ".$data->name." updated successfully")->with('inactiveUsers', $inactiveUsers);

 		}
 		// add all the current users data here
 		// $userdetails = UserDetails::get();
        $userdetails = UserDetails::select('user_details.id','name','user_details.email','user_details.role','active')
       ->join('login','login.user_id','=','user_details.id')
       ->get();
        // return $userdetails;
        $inactiveUsers = Login::where('active',0)->count();
 		return view('admin.userManagement')->with('userDetails', $userdetails)->with('inactiveUsers', $inactiveUsers);
 	}
 	/**
     * Update user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function update(Request $data)
    {
        // return $data->all() ;
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required'
        ];
        $messages = [   
            'name.required' =>  'Full Name is compulsory',
            'email.required' =>  'Email ID is compulsory',
            'email.email'        =>  'Email ID is not in proper format',
            'password.required' =>  'Password is compulsory',
            'gender.required' =>  'Gender is compulsory'
            
        ];
        $inactiveUsers = Login::where('active',0)->count();
        $validator = Validator::make($data->all(), $rules, $messages);
        // return (string)$validator->fails();
        if ($validator->fails()) {
            return redirect()->route('adminEditProfile')->withErrors($validator)->withInput($data->all())->with('inactiveUsers', $inactiveUsers);
        }
        // checking if new email is already in the database or not
        if(Auth::user()->email!=$data->email){
	        	$rules = [
	            'email' => 'unique:login'
	        ];
	        $messages = [   
	            'email.required' =>  'Email ID is compulsory'
	        ];

	        $validator = Validator::make($data->all(), $rules, $messages);
	        // return (string)$validator->fails();
	        if ($validator->fails()) {
	            return redirect()->route('adminEditProfile')->withErrors($validator)->withInput($data->all())->with('inactiveUsers', $inactiveUsers);
	        }
        }
        //check if security question is changed
        if($data->secques!=0){
	        	$rules = [
	            'secans' => 'required|max:255'
	        ];
	        $messages = [   
	            'secans.required' =>  'Please provide answer for the security question'
	        ];

	        $validator = Validator::make($data->all(), $rules, $messages);
	        // return (string)$validator->fails();
	        if ($validator->fails()) {
	            return redirect()->route('adminEditProfile')->withErrors($validator)->withInput($data->all())->with('inactiveUsers', $inactiveUsers);
	        }
        }
        // Check currentpassword and storedpassword match. Use Hash
        $oldpassword = $data->oldpassword;
        if(!(Hash::check($oldpassword, Auth::user()->password))) {
            return redirect()->route('adminEditProfile')->with('error','Incorrect Current Password')->withInput($data->all())->with('inactiveUsers', $inactiveUsers);
        }
        //save new password
        if($data->password!=null){
	        	$rules = [
	            'password' => 'min:6|confirmed'
	        ];
	        $messages = [   
	            'password.confirmed' =>  'Please retype same password in the last field'
	        ];

	        $validator = Validator::make($data->all(), $rules, $messages);
	        // return (string)$validator->fails();
	        if ($validator->fails()) {
	            return redirect()->route('adminEditProfile')->withErrors($validator)->withInput($data->all())->with('inactiveUsers', $inactiveUsers);
	        }
        }

        DB::transaction(function($data) use ($data){
            $userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
            $userdetails -> name =  $data['name'];
            $userdetails -> email =  $data['email'];
            $userdetails -> gender =  $data['gender'];
            if($data->secques!=0){
            	$userdetails -> securityQuestion =  $data['secques'];
            	$userdetails -> securityAnswer =  bcrypt($data['secans']);
            }
            $userdetails ->save();

            $oldLogin = Login::where('user_id',Auth::user()->user_id)->first();
            $oldLogin -> email =  $data['email'];
            if($data->password!='')
            	$oldLogin -> password =  bcrypt($data['password']);
            $oldLogin ->save();
        });//end of transaction
        return redirect()->route('adminProfile')->with("success","Account updated successfully")->with('inactiveUsers', $inactiveUsers);
    }
}
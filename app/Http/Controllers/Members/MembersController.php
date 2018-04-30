<?php

namespace App\Http\Controllers\Members;
use Auth;
use App\UserDetails;
use Hash;
use DB;
use App\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		return view('members.profile')->with('userDetails', $userdetails);
 	}
 	public function editProfile(){
 		if(Auth::user()->role != 1){
 			return redirect()->route('login')->with('error', 'Unauthorised Access');
 		}
 		$userdetails = UserDetails::where('id',Auth::user()->user_id)->first();
 		return view('members.editProfile')->with('userDetails', $userdetails);
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

        $validator = Validator::make($data->all(), $rules, $messages);
        // return (string)$validator->fails();
        if ($validator->fails()) {
            return redirect()->route('memberEditProfile')->withErrors($validator)->withInput($data->all());
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
	            return redirect()->route('memberEditProfile')->withErrors($validator)->withInput($data->all());
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
	            return redirect()->route('memberEditProfile')->withErrors($validator)->withInput($data->all());
	        }
        }
        // Check currentpassword and storedpassword match. Use Hash
        $oldpassword = $data->oldpassword;
        if(!(Hash::check($oldpassword, Auth::user()->password))) {
            return redirect()->route('memberEditProfile')->with('error','Incorrect Current Password')->withInput($data->all());;
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
	            return redirect()->route('memberEditProfile')->withErrors($validator)->withInput($data->all());
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
        return redirect()->route('memberProfile')->with("success","Account updated successfully");
    }
}

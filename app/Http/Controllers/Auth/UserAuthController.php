<?php
namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Session;
use DateTime;
use App\Login;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAuthController extends Controller{
    /*
    |-------------------------------------------------------------------------
    | Function:     loginLand
    | Input:        Null
    | Output:       Display the login page
    | Logic:        Used to direct to login page
    |
    */
    public function loginLand(){

        if(Auth::check()){
            //authenticated successfully, now cheeck for roles and activity
            //return redirect()->route('reg_home');  //route for dashboard
            if(Auth::user()->active == 0){// for deactivated user
                // return Auth::user();
                return redirect()->route('login')->with('error', 'Your account is not active, please contact the Admin');
            }
            if(Auth::user()->active == 2){// for deactivated user
                // return Auth::user();
                return redirect()->route('login')->with('error', 'Your account has been blacklisted, please contact the Admin');
            }
            if(Auth::user()->role == 1 && Auth::user()->active == 1){// for normal user
                // return Auth::user();
                return redirect()->route('showMemberDashboard');
            }
            if(Auth::user()->role == 2 && Auth::user()->active == 1){//for admin
                // return Auth::user();
                return redirect()->route('showAdminDashboard');
            }
            else{
                return redirect()->route('login')->with('error', 'Not a user.');
            }
        }

        return redirect()->route('login')->with('info', 'Please enter your username and password');
    }

    /*
    |-------------------------------------------------------------------------
    | Function:     authenticate
    | Input:        Request
    | Output:       Check credentials
    | Logic:        Used to direct to homepage
    |
    */
	public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password, 'active' => 1])) {
            // Authentication passed...
            if(Auth::user()->role == 1){// for normal user
                // return Auth::user();
                return redirect()->route('showMemberDashboard');
            }
            if(Auth::user()->role == 2 ){//for admin
                // return Auth::user();
                return redirect()->route('showAdminDashboard');
            }else{
                return redirect()->route('login')->with('error', 'Unknown role given to you, please contact Admin');
            }
        } 
        else if (Auth::attempt(['email' => $request->username, 'password' => $request->password, 'active' => 0])) {
            // Authentication passed...
            return redirect()->route('login')->with('error', 'Your account is not active, please contact the Admin');
        }
        else if (Auth::attempt(['email' => $request->username, 'password' => $request->password, 'active' => 2])) {
            // Authentication passed...
            return redirect()->route('login')->with('error', 'Your account has been blacklisted, please contact the Admin');
        }
        else {
            Auth::logout();
			Session::flush();
        	return redirect()->route('login')->with('error', 'Please type your correct credentials.');
        }
    }//end of method

    //Change password
    public function changepass(Request $request){

        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'repeatPassword'    =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('changepass_land')->withErrors($validator,'changePassForm')->withInput();
        }

        /* Check currentpassword and storedpassword match. Use Hash */
        $oldpassword = $request->oldPassword;
        if(!(Hash::check($oldpassword, Auth::user()->password))) {
            return redirect()->route('changepass_land')->with('error','Incorrect Current Password.');
        }

        /* Check newPassword and repeatPassword are Equal. */
        $newpassword = $request->newPassword;
        $repeatpassword = $request->repeatPassword;
        if ($newpassword != $repeatpassword){
            return redirect()->route('changepass_land')->with('error', 'New Password, Confirm Password doesn\'t match.');
        }

        if(strlen($newpassword) < 8){
            return redirect()->route('changepass_land')->with('error','New Password should be at least 8 characters long.');
        }

        /* Check user exist in DB */
        $user = Logins::where('username', Auth::user()->username)->first();
        if(!$user) {
            return redirect()->route('changepass_land')->with('error','Unable to change password. User not found');
        }

        //$user->password = $newpassword;
        $user->password = Hash::make($request->newPassword);
        $user->change_count = $user->change_count + 1;

        if(!$user->save()){
            return redirect()->route('changepass_land')->with('error','Unable to save the information. Please try again');
        }
        //Display Success
        return redirect()->route('login')->with('success', 'Password changed successfully.');
    }//changepass

    //forgot password check
    public function forgotpassProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('forgotpass')->withErrors($validator,'forgotPassForm')->withInput();
        }

        /* Validation of username/emailid */
        $username = $request->username;
        $user = Logins::where('username', $username)->first();
        if(!$user) {
            return redirect()->route('forgotpass')->with('error', 'This email is not registered with us.')->withInput();
        }

        return redirect()->route('login');
    }//end of forgotpassProcess

    //process and set new password
    public function setPasswordProcess(Request $request){

        // if(!Session::has('forgotpwd_username')){
        //     $messages = ['Unable to set new password. Pleasetry again'];
        //     return redirect()->route('login')->with('error',$messages);
        // }

        $username = Session::get('forgotpwd_username');

        /* Validation of input data */
        $rules = [
            'newPassword'       =>  'required',
            'repeatPassword'    =>  'required'
        ];

        $messages = [
            'newPassword.required'      =>  'New Password is compulsory.',
            'repeatPassword.required'   =>  'Confirm Password is compulsory.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()){
            return redirect()->route('setnewpass')->withErrors($validator, 'setNewPassForm')->withInput();
        }

        /* Check newPassword and repeatPassword are Equal. */
        $newpassword = $request->newPassword;
        $repeatpassword = $request->repeatPassword;
        if ($newpassword != $repeatpassword){
            return redirect()->route('setnewpass')->with('error','Password, Confirm Password doesn\'t match.')->withInput();
        }

        if(strlen($newpassword) < 8){
            return redirect()->route('setnewpass')->with('error', 'Password cannot be less than 8 characters.')->withInput();
        }
        /* Save new password in database */
        $userrecord = Logins::where('username', $username)->first();
        $userrecord->password = Hash::make($newpassword);
        $userrecord->active = 1;
        $userrecord->forgot_count = $userrecord->forgot_count + 1;

        if(!$userrecord->save()){
            return redirect()->route('login')->with('error', 'Unable to save the information. Please try again');
        }
        //Display Success
        return redirect()->route('login')->with('success', 'Password reset successfull. Please Login below.');
    }

    //logout
    public function logout(){
    	Auth::logout();
		Session::flush();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }//end of logout

    public function login(){
        return view('login');
    }
}//end of Class
<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Mail;
use Session;
use DateTime;
use App\Models\Logins;
use App\Models\Notice;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pracTest\PracTest_Login;//for the practice test database update
use App\Models\selectionTest\SelectionTest_Login;//for the selection test

class eYRCAuthController extends Controller{
	public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator,'loginForm')->withInput();
        }

        if (Auth::attempt(['username' => $request->email, 'password' => $request->password, 'active' => 1])) {
            // Authentication passed...
            if(Auth::user()->active == -2){
            	Auth::logout();
				Session::flush();
            	return redirect()->route('login')->with('error', 'Your account is yet to be verified. Please click on the \'Activate and Login\' button in the Registration email sent to you. If you did not receive your Registration email, please ask your team leader to resend you the Registration email from their Profile Page.');
            }

            $user = Logins::where('username', Auth::user()->username)->first();
            $user->login_count = $user->login_count + 1;
            $user->last_login = new DateTime;
            $user->save();

            session(['xyz' => $request->password]);
            //Session::put('xyz',$pwd);

            if(Auth::user()->role == 3){
                //return redirect()->route('showTimeSlot');
                return redirect()->route('statusLegalDocs');
            }elseif(Auth::user()->role == 1 || Auth::user()->role == 2){
                //return redirect()->route('profile_home');
                return redirect()->route('showHome');
            }else{
                return redirect()->route('login')->with('error', 'Not a user.');
            }
        } else {
        	$user = Logins::where(['username' => $request->email, 'active' => 0])->first();
            if(count($user) > 0){
                Auth::logout();
                Session::flush();
                return redirect()->route('login')->with('error', 'You have tried to reset your password. But seems like you forgot to set your new password. Please check your reset password e-mail.');
            }
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
            return redirect()->route('changepass_land')->with('error','Unable to change password. Please contact us at helpdesk@e-yantra.org');
        }

        //$user->password = $newpassword;
        $user->password = Hash::make($request->newPassword);
        $user->change_count = $user->change_count + 1;

        if(!$user->save()){
            return redirect()->route('changepass_land')->with('error','Unable to save the information. Please contact us at helpdesk@e-yantra.org via email about the issue');
        }

        //password change for practice test
        /*$pracTest_user = PracTest_Login::where('username', Auth::user()->username)->first();
        if(count($pracTest_user)){
            $pracTest_user->password = Hash::make($request->newPassword);
            $pracTest_user->save();
        }*/
        ///////selection test password
        /*$selectionTest_user = SelectionTest_Login::where('username', Auth::user()->username)->first();
        if(count($selectionTest_user)){
            $selectionTest_user->password = Hash::make($request->newPassword);
            $selectionTest_user->save();
        }*/
        //////////
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

        if($user->active == -2){
            return redirect()->route('forgotpass')->with('error','Your e-mail is yet to be verify by clicking on the "Activate and Login" button in the Registration email sent by e-Yantra. If you did not receive your Registration email, please ask your team leader to resend the email from his/her Profile Page. Please verify your account before using Forgot Password facility.');
        }

        /* Generate token and store in DB */
        $token = '';
        if($user->token != '' && !empty($user->token)){
            $token = $user->token;
        } else {
            $token = md5(str_random(50));
            $user->token = $token;
            $user->active = 0;

            if(!$user->save()){
                return redirect()->route('forgotpass')->with('error','Unable to save the information. Please contact us at helpdesk@e-yantra.org via email about the issue')->withInput();
            }
        }
        /* send a nice email to user */
        $emailSubj = "eYRC-2016: Forgot Password";
        Mail::queue('email.forgotPass',  array('username' => $username, 'token' => $token), function($message) use($username, $emailSubj)
        {
            $message->to($username)->subject($emailSubj);
            $message->cc('admin@e-yantra.org');
        });
        //Display Success
        $messages = "A mail containing further instructions has been sent to ".$username.". Please check it to reset your password.";

        return redirect()->route('forgotpass')->with('success', $messages);
    }//end of forgotpassProcess

    //Verify the password token
    public function verifyPassToken($username, $token) {
        /* Validate username and token */
        $userrecord = Logins::where(['username'=>$username, 'token'=>$token])->first();
        if(!$userrecord || ($userrecord->token != $token)) {
            // Redirect to login page with error message.
            $messages = ['Unable to set new password. Please contact us at support@e-yantra.org via email about the issue'];
            return redirect()->route('forgotpass')->with('error','Unable to set new password. Please contact us at support@e-yantra.org via email about the issue');
        }
        /* Emailid, Token verified. redirect user to set password page. */
        Session::put('forgotpwd_username', $username);
        return redirect()->route('setnewpass');
    }

    //show setpassword page
    public function setPasswordLand() {
        if(!Session::has('forgotpwd_username')){
            $messages = ['Unable to set new password. Please contact us at helpdesk@e-yantra.org via email about the issue'];
            return redirect()->route('login')->with('error', $messages);
        }

        return view('register.setpass');
    }

    //process and set new password
    public function setPasswordProcess(Request $request){

        if(!Session::has('forgotpwd_username')){
            $messages = ['Unable to set new password. Please contact us at helpdesk@e-yantra.org via email about the issue'];
            return redirect()->route('login')->with('error',$messages);
        }

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
        $userrecord->token = Null;
        $userrecord->active = 1;
        $userrecord->forgot_count = $userrecord->forgot_count + 1;

        if(!$userrecord->save()){
            return redirect()->route('login')->with('error', 'Unable to save the information. Please contact us at helpdesk@e-yantra.org via email about the issue');
        }
        //password change for practice test
        /*$pracTest_user = PracTest_Login::where('username', $username)->first();
        if(count($pracTest_user)){
            $pracTest_user->password = Hash::make($newpassword);
            $pracTest_user->save();
        }*/
        ///////////////
        //selection test password change
        /*$selectionTest_user = SelectionTest_Login::where('username', $username)->first();
        if(count($selectionTest_user)){
            $selectionTest_user->password = Hash::make($newpassword);
            $selectionTest_user->save();
        }*/
        ////////
        //Display Success
        return redirect()->route('login')->with('success', 'Password reset successfull. Please Login below.');
    }

    //logout
    public function logout(){
    	Auth::logout();
		Session::flush();
        return redirect()->route('login')->with('success', 'You have successfully logged out.');
    }//end of logout

    public function login(){
        $data = Notice::all();
        return view('register/login')->with('notice', $data);
    }
}//end of Class
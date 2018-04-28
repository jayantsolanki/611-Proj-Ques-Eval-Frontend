<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Login;
use App\UserDetails;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => 'required|max:255',
    //         'email' => 'required|email|max:255|unique:users',
    //         'password' => 'required|min:6|confirmed',
    //         'gender' => 'required',
    //         'secques' => 'required',
    //         'secans' => 'required|max:255'
    //     ]);
    // }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $data)
    {
        // return $data->all() ;
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:login',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required',
            'secques' => 'required',
            'secans' => 'required|max:255'
        ];
        $messages = [   
            'name.required' =>  'Full Name is compulsory',
            'email.required' =>  'Email ID is compulsory',
            'email.email'        =>  'Email ID is not in proper format',
            'password.required' =>  'Password is compulsory',
            'gender.required' =>  'Gender is compulsory',
            'secques.required' =>  'Security question is compulsory',
            'secans.required' =>  'Please provide answer for the security question'
        ];

        $validator = Validator::make($data->all(), $rules, $messages);
        // return (string)$validator->fails();
        if ($validator->fails()) {
            return redirect()->route('createAccountPage')->withErrors($validator)->withInput($data->all());
        }
        DB::transaction(function($data) use ($data){
            $newUser = new UserDetails;
            $newUser -> name =  $data['name'];
            $newUser -> email =  $data['email'];
            $newUser -> role =  1;
            $newUser -> gender =  $data['gender'];
            $newUser -> securityQuestion =  $data['secques'];
            $newUser -> securityAnswer =  bcrypt($data['secans']);
            $newUser ->save();

            $newLogin = new Login;
            $newLogin -> user_id =  $newUser->id;
            $newLogin -> email =  $data['email'];
            $newLogin -> password =  bcrypt($data['password']);
            $newLogin -> role =  1;
            $newLogin -> active =  1;
            $newLogin ->save();
        });//end of transaction
        return redirect()->route('login');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Login extends Authenticatable
{
	// use Authenticatable;
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'login';
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = ['email', 'password'];
    /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
}

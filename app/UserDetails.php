<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\QuestionMaster;

class UserDetails extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'user_details';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['securityQuestion', 'securityAnswer'];

	public function info()
	{
	    return $this->hasMany('App\QuestionMaster');
	}
}

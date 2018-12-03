<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\UserDetails;

class QuestionMaster extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'question_master';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	public function user()
	{
	    return $this->belongsTo('App\UserDetails');
	}
}

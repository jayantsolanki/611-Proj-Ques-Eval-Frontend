<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpQuesMap extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'exp_ques_set';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();
}

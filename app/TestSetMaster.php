<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\UserDetails;

class TestSetMaster extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'test_set_master';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

}

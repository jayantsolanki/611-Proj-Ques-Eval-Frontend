<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseCatalogue extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'database_catalogue';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();
}

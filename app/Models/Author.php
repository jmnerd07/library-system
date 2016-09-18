<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
	protected $casts = [
		'description'=>'string',
		'user_id_creator'=>'integer',
		'user_id_modifier'=>'integer'
	];

	public function changeLogs()
	{
		return $this->hasMany('App\Models\Author', 'record_id', 'id');
	}
}

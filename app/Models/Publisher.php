<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $casts = [
    	'user_id_creator'=>'integer',
    	'user_id_modifier'=>'integer',
    	'record_id'=>'integer',
    	'id'=>'integer'
    ];
}

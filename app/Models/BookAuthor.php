<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
	protected $table =  'books_authors';
	public $timestamps = FALSE;
	protected $hidden = [
            'user_id_creator', 'created_at', 'id','book_id','author_id'
        ];
    public function details()
    {
    	return $this->belongsTo('App\Models\Author', 'author_id','id');
    }
    public function book()
    {
    	return $this->belongsTo('App\Models\Book', 'book_id');
    }

}

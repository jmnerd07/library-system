<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
	protected $table =  'books_authors';
	public $timestamps = FALSE;
	protected $hidden = [
            'user_id_creator', 'created_at', 'id','book_id',/*'author_id'*/
        ];
    protected $casts = [
            'book_id'=>'integer',
            'author_id'=>'integer'
        ];
    public function details()
    {
    	return $this->belongsTo('App\Models\Author', 'author_id','id')
            ->where('authors.record_id', null);
    }
    public function book()
    {
    	return $this->belongsTo('App\Models\Book', 'book_id', 'id');
    }

}

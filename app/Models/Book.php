<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = "books";
    protected $fillable = []; // Please fill this up
    protected $casts = [
            'author_id'=>'integer',
            'publisher_id'=>'integer',
            'date_published'=>'date',
            'created_at'=>'datetime',
            'available'=>'boolean',
            'count'=>'integer',
            'pages'=>'integer'
        ];
    protected $hidden = [
            'user_id_modifier'
        ];

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];
    public function booksGenres()
    {
        return $this->hasMany('App\Models\BookGenre', 'book_id', 'id');
    }
    public function authorList()
    {
        return $this->hasMany('App\Models\BookAuthor', 'book_id', 'id');
    }
    public function publisher()
    {
        return $this->hasOne('App\Models\Publisher', 'id', 'publisher_id');
    }
    public function changeLogs()
    {
        return $this->hasMany('App\Models\Book', 'record_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = "books";
    protected $fillable = []; // Please fill this up
    public function booksGenres()
    {
        return $this->hasMany('App\Models\BookGenre', 'book_id', 'id');
    }
    public function author()
    {
        return $this->hasOne('App\Models\Author', 'id', 'author_id');
    }
    public function publisher()
    {
        return $this->hasOne('App\Models\Publisher', 'id', 'publisher_id');
    }
}

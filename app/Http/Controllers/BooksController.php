<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::where('record_id',NULL)->orderBy('title', 'ASC')->get();

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book = new Book();
        $action_route = 'books.save_new';
        return view('books._books_form', compact('book', 'action_route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->validate(
                $request, 
                [
                    'title' => 'required',
                    'author' => 'required',
                    'publisher'=>'required',
                    'isbn' => "required|max:13|min:13|regex:/^[1-9][0-9]{12}$/"
                ],
                [
                    'title.required'=>'Book title is required',
                    'author.required' => 'Book author is required',
                    'isbn.required' => 'ISBN is required',
                    'isbn.max' => 'ISBN must be exactly 13 characters',
                    'isbn.min' => 'ISBN must be exactly 13 characters',
                    'isbn.regex'=>'ISBN must contain numbers only.',
                    'publisher.required'=>'Publisher is required'
                ]
            )
            ->errors()->add('submitted', 1)
            ->errors()->add('desc', ($request->description !== "" ? TRUE : FALSE) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

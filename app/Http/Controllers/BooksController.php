<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\BookAuthor;
use App\Http\Requests;
use Auth;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::where('record_id',NULL)->orderBy('name', 'ASC')->get();
        $books->load('authorList.details');
        $books->load('publisher');
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
        $actionRoute = 'books.saveNew';
        return view('books._books_form', compact('book', 'actionRoute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate inputs
        $this->validate(
            $request, 
            [
                'title' => 'required',
                'author' => 'required',
                'publisher'=>'required',
                'format'=> 'required',
                'pages'=>'required|numeric|min:1',
                'isbn' => "required|max:13|min:13|regex:/^[1-9][0-9]{12}$/",
                'date_published'=>'required|date'
            ],
            [
                'title.required'=>'Book title is required',
                'author.required' => 'Book author is required',
                'isbn.required' => 'ISBN is required',
                'isbn.max' => 'ISBN must be exactly 13 characters',
                'isbn.min' => 'ISBN must be exactly 13 characters',
                'isbn.regex'=>'ISBN must contain numbers only.',
                'format.required'=>'Book format is required',
                'pages.required'=>'Number of pages is required',
                'pages.numeric'=>'Number of pages must be a valid number',
                'pages.min'=>'Number of pages must be a valid number',
                'publisher.required'=>'Publisher is required',
                'date_published.required'=>'Date published is required',
                'date_published.date'=>'Invalide date'
            ]
        );
        $authorData = ['error'=>FALSE, 'message'=>'Author exists'];
        $publisherData = ['error'=>FALSE, 'message'=>'Publisher exists'];
        
        // If author text field contains author that is not yet saved in the database, create new author first
        if($request->author_id == 0)
        {
            $authorData = $this->_saveNewAuthor($request);
        }

        //if author creation is error
        if($authorData['error'])
        {
            return ($request->ajax() ? response()->json($authorData) : redirect()->route('books.new')->with('error',$authorData['message']) );
        }

        // if author was successfully created
        if($authorData['error'] == FALSE && $request->author_id == 0)
        {
            $request->author_id = $authorData['author']['id'];
            $request->author = $authorData['author']['name'];
        }

        // If author text field contains publisher that is not yet saved in the database, create new publisher first
        if($request->publisher_id == 0)
        {
            $publisherData = $this->_saveNewPublisher($request);
        }

        // if publisher creation is error

        if($publisherData['error'])
        {
            return ($request->ajax() ? response()->json($publisherData) : redirect()->route('books.new')->with('error',$publisherData['message']) );
        }

        // if publisher was successfully created
        if($publisherData['error'] == FALSE && $request->publisher_id == 0)
        {
            $request->publisher_id = $publisherData['publisher']['id'];
            $request->publisher = $publisherData['publisher']['name'];
        }

        $newBook = $this->_saveNewBook($request);
        if(!$newBook['error'])
        {
            $bookAuthor = new BookAuthor();
            $bookAuthor->book_id = $newBook['book']->id;
            $bookAuthor->author_id = $request->author_id;
            $bookAuthor->user_id_creator = Auth::id();
            $bookAuthor->save();
        }
        return redirect()
                ->route($newBook['error'] ? 'books.new' : 'books.home')
                ->with('notify',$newBook);
    }

    private function _saveNewBook(Request $request)
    {
        $book = new Book();
        $book->name = $request->title;
        $book->description = $request->description;
        $book->publisher_id = $request->publisher_id;
        $book->date_published = $request->date_published;
        $book->isbn = $request->isbn;
        $book->user_id_creator = Auth::id();
        $book->count = 0;
        $book->available = FALSE;
        $isSuccess = $book->save();
        if($isSuccess && $book->id)
        {
            $copyBook = new Book();
            $copyBook->name = $book->name;
            $copyBook->description = $book->description;
            $copyBook->publisher_id = $book->publisher_id;
            $copyBook->date_published = $book->date_published;
            $copyBook->isbn = $book->isbn;
            $copyBook->user_id_creator = $book->user_id_creator;
            $copyBook->count = $book->count;
            $copyBook->available = $book->available;
            $copyBook->record_id = $book->id;
            $copyBook->save();

            return ['error'=>FALSE,'message'=>'New book successfully created.', 'book'=>$book];
        }
        return ['error'=>TRUE, 'message'=>'Error: no copy row created.', 'data'=>array()];    
    }

    private function _saveNewAuthor(Request $request)
    {
        $author = new Author();
        $author->name = $request->author;
        $author->description = $request->description;
        $author->user_id_creator = Auth::id();
        $author->updated_at = NULL;
        $isSuccess = $author->save();

        if($isSuccess && $author->id)
        {        
            $copyAuthor = new Author();
            $copyAuthor->name = $author->name;
            $copyAuthor->record_id = $author->id;
            $copyAuthor->description = $author->description;
            $copyAuthor->user_id_creator = $author->user_id_creator;
            $copyAuthor->updated_at = NULL;
            $copyAuthor->save();

            return ['error'=>FALSE,'message'=>'New author successfully created.', 'author'=>array('name'=>$author->name, 'id'=>$author->id)];
        }
        return ['error'=>TRUE, 'message'=>'Error: no copy row created.', 'author'=>array()];    
    }

    private function _saveNewPublisher(Request $request)
    {
        $publisher = new Publisher();
        $publisher->name = $request->publisher;
        $publisher->description = $request->description;
        $publisher->user_id_creator = Auth::id();
        $publisher->updated_at = NULL;
        $isSuccess = $publisher->save();

        if($isSuccess && $publisher->id)
        {        
            $copyPublisher = new Publisher();
            $copyPublisher->name = $publisher->name;
            $copyPublisher->description = $publisher->description;
            $copyPublisher->record_id = $publisher->id;
            $copyPublisher->user_id_creator = $publisher->user_id_creator;
            $copyPublisher->updated_at = NULL;
            $copyPublisher->save();

            return ['error'=>FALSE,'message'=>'New publisher successfully created.', 'publisher'=>array('name'=>$publisher->name, 'id'=>$publisher->id)];
        }
        return ['error'=>TRUE, 'message'=>'Error: no copy row created.', 'publisher'=>array()];    
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

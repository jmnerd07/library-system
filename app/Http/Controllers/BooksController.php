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
        $books = Book::where('record_id', null)->orderBy('name', 'ASC')->get();
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
        $book->load('authorList.details');
        $book->load('publisher');
        $actionRoute = 'books.saveNew';
        $pageTitle = 'Books - New';
        return view('books._books_form', compact('book', 'actionRoute', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
                'pages'=>'required|numeric|min:0',
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
                'date_published.date'=>'Invalid date'
            ]
        );
        $authorData = ['error'=>false, 'message'=>'Author exists'];
        $publisherData = ['error'=>false, 'message'=>'Publisher exists'];
        
        // If author text field contains author that is not yet saved in the database, create new author first
        if ($request->author_id == 0) {
            $authorData = $this->saveNewAuthor($request);
        }

        //if author creation is error
        if ($authorData['error']) {
            return ($request->ajax() ?
                    response()->json($authorData) :
                    redirect()->route('books.new')->with('error', $authorData['message']) );
        }

        // if author was successfully created
        if ($authorData['error'] == false && $request->author_id == 0) {
            $request->author_id = $authorData['author']['id'];
            $request->author = $authorData['author']['name'];
        }

        // If author text field contains publisher that is not yet saved in the database, create new publisher first
        if ($request->publisher_id == 0) {
            $publisherData = $this->saveNewPublisher($request);
        }

        // if publisher creation is error
        if ($publisherData['error']) {
            return ($request->ajax() ?
                    response()->json($publisherData) :
                    redirect()->route('books.new')->with('error', $publisherData['message']) );
        }

        // if publisher was successfully created
        if ($publisherData['error'] == false && $request->publisher_id == 0) {
            $request->publisher_id = $publisherData['publisher']['id'];
            $request->publisher = $publisherData['publisher']['name'];
        }

        $newBook = $this->saveNewBook($request);
        if (!$newBook['error']) {
            $bookAuthor = new BookAuthor();
            $bookAuthor->book_id = $newBook['book']->id;
            $bookAuthor->author_id = $request->author_id;
            $bookAuthor->user_id_creator = Auth::id();
            $bookAuthor->save();
        }
        return redirect()
                ->route($newBook['error'] ? 'books.new' : 'books.home')
                ->with('notify', $newBook);
    }

    private function saveNewBook(Request $request)
    {
        $book = new Book();
        $book->name = $request->title;
        $book->description = $request->description;
        $book->publisher_id = $request->publisher_id;
        $book->date_published = $request->date_published;
        $book->isbn = $request->isbn;
        $book->user_id_creator = Auth::id();
        $book->format = $request->format;
        $book->pages = $request->pages;
        $book->count = 0;
        $book->available = false;
        $isSuccess = $book->save();
        if ($isSuccess && $book->id) {
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
            $copyBook->format = $book->format;
            $copyBook->pages = $book->pages;
            $copyBook->save();

            return [
                'error'=>false,
                'message'=>'New book successfully created.',
                'book'=>$book
            ];
        }
        return ['error'=>true, 'message'=>'Error: no copy row created.', 'data'=>array()];
    }

    private function saveNewAuthor(Request $request)
    {
        $author = new Author();
        $author->name = $request->author;
        $author->description = $request->description;
        $author->user_id_creator = Auth::id();
        $author->updated_at = null;
        $isSuccess = $author->save();

        if ($isSuccess && $author->id) {
            $copyAuthor = new Author();
            $copyAuthor->name = $author->name;
            $copyAuthor->record_id = $author->id;
            $copyAuthor->description = $author->description;
            $copyAuthor->user_id_creator = $author->user_id_creator;
            $copyAuthor->updated_at = null;
            $copyAuthor->save();

            return [
                'error'=>false,'message'=>'New author successfully created.',
                'author'=>array(
                    'name'=>$author->name,
                    'id'=>$author->id
                )
            ];
        }
        return [
            'error'=>true,
            'message'=>'Error: no copy row created.',
            'author'=>array()
        ];
    }

    private function saveNewPublisher(Request $request)
    {
        $publisher = new Publisher();
        $publisher->name = $request->publisher;
        $publisher->description = $request->description;
        $publisher->user_id_creator = Auth::id();
        $publisher->updated_at = null;
        $isSuccess = $publisher->save();

        if ($isSuccess && $publisher->id) {
            $copyPublisher = new Publisher();
            $copyPublisher->name = $publisher->name;
            $copyPublisher->description = $publisher->description;
            $copyPublisher->record_id = $publisher->id;
            $copyPublisher->user_id_creator = $publisher->user_id_creator;
            $copyPublisher->updated_at = null;
            $copyPublisher->save();

            return [
                'error'=>false,
                'message'=>'New publisher successfully created.',
                'publisher'=>array(
                    'name'=>$publisher->name,
                    'id'=>$publisher->id
                )
            ];
        }
        return [
            'error'=>true,
            'message'=>'Error: no copy row created.',
            'publisher'=>array()
        ];
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::where('id', $id)->where('record_id', null)->first();
        if (!$book) {
            return redirect()->route('books.home')->with('error', 'Record not found. Please contact administrator.');
        }
        $book->load('authorList.details');
        $book->load('publisher');

        $actionRoute = 'books.updateSave';
        $pageTitle = 'Books - Edit';
        return view('books._books_form', compact('actionRoute', 'book', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $this->validate(
            $request,
            [
                'title' => 'required',
                'author' => 'required',
                'publisher'=>'required',
                'format'=> 'required',
                'pages'=>'required|numeric|min:0',
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
        $id = $request->id;
        $book = Book::where('id', $id)->where('record_id', null)->first();
        if (!$book) {
            return redirect()->route('books.home')->with('error', 'Record not found. Please contact administrator.');
        }
        $book->load('authorList.details');
        $book->load('publisher');

        $publisherData = ['error'=>false, 'message'=>'Publisher exists'];
        
        // If author text field contains publisher that is not yet saved in the database, create new publisher first
        if ($request->publisher_id == 0) {
            $publisherData = $this->saveNewPublisher($request);
        }

        // if publisher creation is error

        if ($publisherData['error']) {
            return ($request->ajax() ?
                    response()->json($publisherData) :
                    redirect()->route('books.new')->with('error', $publisherData['message']) );
        }

        // if publisher was successfully created
        if ($publisherData['error'] == false && $request->publisher_id == 0) {
            $request->publisher_id = $publisherData['publisher']['id'];
            $request->publisher = $publisherData['publisher']['name'];
        }

        $authorData = ['error'=>false, 'message'=>'Author exists'];
        
        // If author text field contains author that is not yet saved in the database, create new author first
        if ($request->author_id == 0) {
            $authorData = $this->saveNewAuthor($request);
        }

        //if author creation is error
        if ($authorData['error']) {
            return ($request->ajax() ?
                    response()->json($authorData) :
                    redirect()->route('books.new')->with('error', $authorData['message']) );
        }

        // if author was successfully created
        if ($authorData['error'] == false && $request->author_id == 0) {
            $request->author_id = $authorData['author']['id'];
            $request->author = $authorData['author']['name'];
        }

        $this->saveUpdateBookAuthor($request, $book);
        $this->saveUpdateAuthor($request, $book);

        return redirect()
            ->route('books.home')
            ->with('notify', $this->saveUpdateBook($request, $book));
    }

    /**
     * Updates BookAuthor table
     *
     * @param  Request $request
     * @param  Book &$book
     * @return
     */
    private function saveUpdateBookAuthor(Request $request, Book &$book)
    {
        $book->authorList[0]->author_id = $request->author_id;
        $isSuccess = $book->authorList[0]->save();
        $book->load('authorList.details');
    }

    /**
     * Updates Author table
     *
     * @param  Request $request
     * @param  Book &$book
     * @return
     */
    private function saveUpdateAuthor(Request $request, Book &$book)
    {
        $author = $book->authorList[0]->details;
        $authorUpdatedAt = $author->updated_at;
        $author->name = $request->author;
        $isSuccess = $author->save();
        if ($isSuccess && $author->updated_at != $authorUpdatedAt) {
            $copyAuthor = new Author();
            $copyAuthor->name = $author->name;
            $copyAuthor->record_id = $author->id;
            $copyAuthor->description = $author->description;
            $copyAuthor->user_id_creator = $author->user_id_creator;
            $copyAuthor->updated_at = null;
            $copyAuthor->save();
        }

        return [
            'error'=>$isSuccess,'message'=>'Author successfully updated.',
            'author'=>array(
                'name'=>$author->name,
                'id'=>$author->id
            )
        ];
    }

    /**
     * Updates publsher of the book
     *
     * @param  Request $request
     * @param  Book &$book
     * @return
     */
    private function saveUpdatePublisher(Request $request, Book &$book)
    {
        $publisher = $book->publisher;
        $publisher->name = $request->publisher;
        $publisher->description = $request->description;
        $publisher->user_id_creator = Auth::id();
        $isSuccess = $publisher->save();

        if ($isSuccess && $publisher->id) {
            $copyPublisher = new Publisher();
            $copyPublisher->name = $publisher->name;
            $copyPublisher->description = $publisher->description;
            $copyPublisher->record_id = $publisher->id;
            $copyPublisher->user_id_creator = $publisher->user_id_creator;
            $copyPublisher->updated_at = null;
            $copyPublisher->save();
        }

        return [
            'error'=>false,
            'message'=>'Publisher successfully updated.',
            'publisher'=>array(
                'name'=>$publisher->name,
                'id'=>$publisher->id
            )
        ];
    }

    private function saveUpdateBook(Request $request, Book &$book)
    {
        $book->name = $request->title;
        $book->description = $request->description;
        $book->publisher_id = $request->publisher_id;
        $book->date_published = $request->date_published;
        $book->isbn = $request->isbn;
        $book->user_id_creator = Auth::id();
        $book->format = $request->format;
        $book->pages = $request->pages;
        $book->count = 0;
        $book->available = false;
        $isSuccess = $book->save();
        if ($isSuccess && $book->id) {
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
            $copyBook->format = $book->format;
            $copyBook->pages = $book->pages;
            $copyBook->save();
        }

        return [
            'error'=>false,
            'message'=>'Book successfully updated.',
            'book'=>$book
        ];
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

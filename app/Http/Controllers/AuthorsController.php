<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Author;
use Auth;
class AuthorsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate(
      $request,
      [
        'author_name'=> 'required|min:5'
      ],
      [
        'author_name.required'=>'Author name is required. Author not saved.',
        'author_name.min'=>'Author name must be minimum of 5 characters'
      ]
    );
    if($request->ajax())
    {
      return response()->json($this->_saveNewAuthor($request));   
    }
  }

  private function _saveNewAuthor(Request $request)
  {
      $author = new Author();
      $author->name = $request->author_name;
      $author->user_id_creator = Auth::id();
      $author->updated_at = NULL;
      $isSuccess = $author->save();

      if($isSuccess && $author->id)
      {        
        $copyAuthor = new Author();
        $copyAuthor->name = $author->name;
        $copyAuthor->record_id = $author->id;
        $copyAuthor->user_id_creator = $author->user_id_creator;
        $author->updated_at = NULL;
        $copyAuthor->save();

        return ['error'=>FALSE,'message'=>'New author successfully created.', 'author'=>array('name'=>$author->name, 'id'=>$author->id)];
      }
      return ['error'=>TRUE, 'message'=>'Error: no copy row created.', 'author'=>array()];

  
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

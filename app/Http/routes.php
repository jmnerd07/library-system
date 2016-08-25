<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',[
	'as'=>'dashboard',
	'uses'=>'HomeController@index']);

Route::auth();

Route::get('/home', 'HomeController@index');
Route::group(['prefix'=>'management'], function() {
	Route::group(['prefix' => 'books'], function () {
		Route::get('/', [
				"as" => "books.home",
				"middleware"=>"web",
				"uses" => "BooksController@index"
		]);
		Route::get('/new', [
				"as" => "books.new",
				"middleware" => "web",
				"uses" => "BooksController@create"
		]);
		Route::post('/save_new', [
				"as" => "books.save_new",
				"middleware" => "web",
				"uses" => "BooksController@store"
		]);
		Route::post('/update_save', [
			'as' => 'books.update_save',
			'middleware'=> 'web',
			'uses'=>'BooksController@update'
		]);
		Route::get('/modify/{id?}', [
				"as" => "books.edit",
				"middleware"=> "web",
				"uses" => "BooksController@edit"
		]);
	});

	Route::group(['prefix'=>'genres'], function() {
		Route::get('/', [
			"as"=>"genres.home",
			"uses"=> "GenresController@index",
			"middleware"=>"web"
		]);
		Route::get('/new', [ 
			"as"	=> "genres.new",
			"middleware"=>"web",
			"uses"=>"GenresController@create"
		]);
		Route::get('/modify/{id?}', [ 
			"as"	=> "genres.edit",
			"middleware"=>"web",
			"uses"=>"GenresController@edit"
		]);
		Route::group(['prefix'=>'/async'], function(){ 
			Route::post('/new-genre', [
				'as'=>'genres.async.newGenre',
				'middleware'=>'web',
				'uses'=>'GenresController@store'
			]);
			Route::post('/edit-genre', [
				'as'=>'genres.async.editGenre',
				'middleware'=>'web',
				'uses'=>'GenresController@edit'
			]);
			Route::post('/modify-genre', [
				'as'=>'genre.async.modifyGenre',
				'middleware'=>'web',
				'uses'=>'GenresController@update'
			]);
			Route::post('/list', [
					'as'=>'genres.async.list',
					'middleware'=>'web',
					'uses'=>'GenresController@index'
				]);
		});
	});

	Route::group(['prefix'=>'author'], function(){
		Route::group(['prefix'=>'/async'], function() {
			Route::get('/all',['as'=>'author.async.all', function(){
				return \App\Models\Author::where('record_id', NULL)->orderBy('name')->get();
			}]);
			Route::post('/new-author', [
				'as'=>'author.async.newAuthor',
				"middleware"=> "web",
				'uses'=>'AuthorsController@store'
			]);
		});
	});
	Route::group(['prefix'=>'publisher'], function(){
		Route::group(['prefix'=>'/async'], function() {
			Route::get('/all',['as'=>'publisher.async.all', function(){
				return \App\Models\Publisher::where('record_id', NULL)->orderBy('name')->get();
			}]);
			Route::post('/new-publisher', [
				'as'=>'publisher.async.newPublisher',
				"middleware"=> "web",
				'uses'=>'PublishersController@store'
			]);
		});
	});
});

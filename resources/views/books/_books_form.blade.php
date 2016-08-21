@extends("master_management")
@section('title','Books - New')
@section("content")
<div class="books-form">
	<h2>Books
		<small> - New Book</small>
	</h2>

	{{ Form::model($book, array('route'=>$action_route)) }}
	<fieldset>
		<div class="form-group row {{ ( $errors->has() ? ($errors->has('title') ? 'has-danger' : 'has-success') : '' )  }}">
			<label for="book-title" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Book Title</label>
			<div class="col-sm-10">
				{{ Form::text("title", $book->title, ['class'=>'form-control'.( $errors->has() ? ($errors->has('title') ? ' form-control-danger' : ' form-control-success') : '' ),'id'=>'book-title', 'type'=>'text', 'placeholder'=>'Book Title']) }}
				@if($errors->has('title'))
					<small class="text-danger">{{ $errors->first('title')  }}</small>
				@endif
			</div>
		</div>
		<div class="form-group row {{ ( $errors->has() ? ($errors->has('author') ? 'has-danger' : 'has-success') : '' )  }}">
			<label for="book-author" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Author</label>
			<div class="col-sm-10" data-ng-controller="AuthorsController">
				{{ Form::text("author", Request::old('author'),  ['data-ng-model'=>'search_author','data-ng-value'=>'search_author','data-ng-focus'=>'hide_author_suggestions = false',"data-ng-init"=>'hide_author_suggestions = true;search_author="'.(Request::old('author') ? Request::old('author') : ($book->author ? $book->author->author_name: '')).'"' , 'class'=>'form-control author-name-search'.( $errors->has() ? ($errors->has('author') ? ' form-control-danger' : ' form-control-success') : '' ),'id'=>'book-author', 'type'=>'text', 'placeholder'=>'Search authors']) }}
				{{ Form::hidden("author_id", Request::old('author_id'), [ 'data-ng-model'=>'authorId', 'data-ng-value'=>'authorId', 'data-ng-init'=>'authorId="'.(Request::old('author_id') ? Request::old('author_id') : ($book->author_id ? $book->author_id : -1)) .'"']) }}
				<div class="authors-autosuggest" data-ng-authors-list="authors" data-selected-author-id="authorId" data-ng-author-keyword="search_author"  data-ng-hide="hide_author_suggestions" >

				</div>

				@if($errors->has('author'))
					<small class="text-danger">{{ $errors->first('author')  }}</small>
				@endif
			</div>
		</div>
		<div class="form-group row {{ ( $errors->has() ? ($errors->has('publisher') ? 'has-danger' : 'has-success') : '' )  }}">
			<label for="book-publisher" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Publisher</label>

			<div class="col-sm-10" data-ng-controller="PublishersController">
				{{ Form::text("publisher", Request::old('publisher'),  ['data-ng-model'=>'search_publisher','data-ng-value'=>'search_publisher','data-ng-focus'=>'hide_publisher_suggestions = false',"data-ng-init"=>'hide_publisher_suggestions = true;search_publisher="'.(Request::old('publisher') ? Request::old('publisher') : ($book->publisher ? $book->publisher->name: '' )).'"' , 'class'=>'form-control publisher-name-search'.( $errors->has() ? ($errors->has('publisher') ? ' form-control-danger' : ' form-control-success') : '' ),'id'=>'book-publisher', 'type'=>'text', 'placeholder'=>'Search publishers']) }}
				{{ Form::hidden("publisher_id", Request::old('publisher_id'), [ 'data-ng-model'=>'publisherId', 'data-ng-value'=>'publisherId', 'data-ng-init'=>'publisherId="'.(Request::old('publisher_id') ? Request::old('publisher_id') : ($book->publisher_id ? $book->publisher_id : -1) ) .'"']) }}
				<div class="publishers-autosuggest" data-ng-publishers-list="publishers" data-selected-publisher-id="publisherId" data-ng-publisher-keyword="search_publisher"  data-ng-hide="hide_publisher_suggestions" >

				</div>

				@if($errors->has('publisher'))
					<small class="text-danger">{{ $errors->first('publisher')  }}</small>
				@endif
			</div>
		</div>
		<div class="form-group row {{ ( $errors->has() ? ($errors->has('date_published') ? 'has-danger' : 'has-success') : '' )  }}">
			<label for="book-date-published" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Date Published</label>
			<div class="col-sm-10">
				{{ Form::date("date_published", $book->date_published, ['class'=>'form-control'.( $errors->has() ? ($errors->has('date_published') ? ' form-control-danger' : ' form-control-success') : '' ),'id'=>'book-date-published', 'type'=>'text', 'placeholder'=>'Date Published']) }}
				@if($errors->has('date_published'))
					<small class="text-danger">{{ $errors->first('date_published')  }}</small>
				@endif
			</div>
		</div>
		<div class="form-group row {{ ( $errors->has() ? ($errors->has('isbn') ? 'has-danger' : 'has-success') : '' )  }}">
			<label for="book-isbn" class="col-sm-2 form-control-label"><span class="text-danger">*</span> ISBN</label>

			<div class="col-sm-10">
				{{ Form::text("isbn", $book->isbn, ['class'=>'form-control'.( $errors->has() ? ($errors->has('isbn') ? ' form-control-danger' : ' form-control-success') : '' ),'id'=>'book-isbn', 'type'=>'text', 'placeholder'=>'ISBN']) }}
				@if($errors->has('isbn'))
					<small class="text-danger">{{ $errors->first('isbn')  }}</small>
				@endif
			</div>
		</div>
		<div class="form-group row {{ ( $errors->has() ? ( $errors->first('desc') ? 'has-success' : '') : '' )  }}">
			{{ Form::label("book-description", "Book Description", ['class'=> 'col-sm-2 form-control-label']) }}
			<div class="col-sm-10">
				{{ Form::textarea("description", $book->description, ['class'=>'form-control '.( $errors->has() ? ( $errors->first('desc') ? 'form-control-success' : '') : '' ),'id'=>'book-description', 'type'=>'text', 'placeholder'=>'Book Description']) }}
			</div>
		</div>
		@if($action_route == 'books.update_save')
			{{ Form::hidden('id', $book->id, []) }}
		@endif
		<div class="pull-right">
			<button type="submit" name="new_book_save" class="btn btn-primary">
				<span class="glyphicon glyphicon-floppy-disk"></span> Save
			</button>
			<a href="{{ route('books.home') }}" class="btn btn-default">Cancel</a> 
		</div>
	</fieldset>
	{{ Form::close() }}
@stop
@section('user_js')
		{{ Html::script('js/react-components/authors-auto-suggest-list.react.js') }}
		{{ Html::script('js/react-components/publishers-auto-suggest-list.react.js') }}
		{{ Html::script('js/services/author.service.js') }}
		{{ Html::script('js/services/publisher.service.js') }}
		{{ Html::script('js/directives/authors-list.directive.js') }}
		{{ Html::script('js/directives/publishers-list.directive.js') }}
		{{ Html::script('js/controllers/authors.controller.js') }}
		{{ Html::script('js/controllers/publishers.controller.js') }}
</div>
@stop
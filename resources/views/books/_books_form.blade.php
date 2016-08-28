@extends("master_management")
@section('title','Books - New')
@section("content")
<div class="books-form">
    <h2>Books
        <small> - New Book</small>
    </h2>
    @if( session('error') )
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route($actionRoute) }}" accept-charset="UTF-8" class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('title') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-title" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Book Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control{{ ( $errors->has() ? ($errors->has('title') ? ' form-control-danger' : ' form-control-success') : '' ) }}" id="book-id" placeholder="Book Title" value="{{ old('title') }}"/>
                @if($errors->has('title'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="text-danger">{{ $errors->first('title')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('author') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-author" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Author</label>
            <div class="col-sm-10" data-ng-controller="AuthorsController">                
                <input type="text" name="author" data-ng-model="search_author", data-ng-value="search_author", data-ng-focus="hide_author_suggestions = false" data-ng-init="hide_author_suggestions = true;search_author='{{  (old('author') ? old('author') : ($book->author ? $book->author->name: '')) }}'" class="form-control author-name-search" id="book-author" placeholder="Search authors" />
                {{ Form::hidden("author_id", $book->dauthor_id, [ 'data-ng-model'=>'authorId', 'data-ng-value'=>'authorId', 'data-ng-init'=>'authorId="'.(old('author_id') ? old('author_id') : ($book->author_id ? $book->author_id : 0)) .'"']) }}
                <input type="hidden" name="author_id" data-ng-model="authorId" data-ng-value="authorId" data-ng-init="authorId={{ (old('author_id') ? old('author_id') : 0) }}" />
                <div class="authors-autosuggest" data-ng-authors-list="authors" data-selected-author-id="authorId" data-ng-author-keyword="search_author"  data-ng-hide="hide_author_suggestions" >

                </div>

                @if($errors->has('author'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="help-block">{{ $errors->first('author')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('publisher') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-publisher" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Publisher</label>

            <div class="col-sm-10" data-ng-controller="PublishersController">
                <input type="text" name="publisher" data-ng-model="search_publisher" data-ng-value="search_publisher" data-ng-focus="hide_publisher_suggestions = false;" data-ng-init="hide_publisher_suggestions = true; search_publisher='{{ (old('publisher') ? old('publisher') : ($book->publisher ? $book->publisher->name: '' )) }}'" class="form-control publisher-name-search" id="book-publisher" placeholder="Search publishers" />
                <input type="hidden" name="publisher_id" data-ng-model="publisherId" data-ng-value="publisherId" data-ng-init="publisherId={{ (old('publisher_id') ? old('publisher_id') : 0) }}" />
                <div class="publishers-autosuggest" data-ng-publishers-list="publishers" data-selected-publisher-id="publisherId" data-ng-publisher-keyword="search_publisher"  data-ng-hide="hide_publisher_suggestions" >

                </div>

                @if($errors->has('publisher'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="help-block">{{ $errors->first('publisher')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('date_published') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-date-published" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Date Published</label>
            <div class="col-sm-10">
                <input type="date" name="date_published" class="form-control" id="book-date-published" value="{{ old('date_published') }}" placeholder="Date Published" />
                @if($errors->has('date_published'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="help-block">{{ $errors->first('date_published')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('isbn') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-isbn" class="col-sm-2 form-control-label"><span class="text-danger">*</span> ISBN</label>

            <div class="col-sm-10">
                <input type="text" name="isbn" value="{{ old('isbn') }}" class="form-control" id="book-isbn" placeholder="ISBN">
                @if($errors->has('isbn'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="help-block">{{ $errors->first('isbn')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('pages') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-pages" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Number of pages</label>

            <div class="col-sm-10">
                <input type="text" name="pages" value="{{ old('pages') }}" class="form-control" id="book-pages" placeholder="Number of pages">
                @if($errors->has('pages'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="help-block">{{ $errors->first('pages')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ($errors->has('format') ? 'has-error' : 'has-success') : '' )  }}">
            <label for="book-format" class="col-sm-2 form-control-label"><span class="text-danger">*</span> Format</label>

            <div class="col-sm-10">
                <select name="format" class="form-control" id="book-format">
                    <option value="">-- Select Book Format --</option>
                    <option value="PAPERBACK">PAPERBACK</option>
                    <option value="HARDCOVER">HARDCOVER</option>
                    <option value="MASS MARKET">MASS MARKET</option>
                </select>
                @if($errors->has('format'))
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span class="help-block">{{ $errors->first('format')  }}</span>
                @endif
            </div>
        </div>
        <div class="form-group has-feedback {{ ( $errors->has() ? ( $errors->first('desc') ? 'has-success' : '') : '' )  }}">
            {{ Form::label("book-description", "Book Description", ['class'=> 'col-sm-2 form-control-label']) }}
            <div class="col-sm-10">
                <textarea class="form-control" id="book-description" placeholder="Book description">{{ old('description') }}</textarea>
            </div>
        </div>
        @if($actionRoute == 'books.update_save')
            <input type="hidden" name="id" value="{{ $bookId }}">
        @endif
        <div class="pull-right">
            <button type="submit" name="new_book_save" class="btn btn-primary">
                <span class="glyphicon glyphicon-floppy-disk"></span> Save
            </button>
            <a href="{{ route('books.home') }}" class="btn btn-default">Cancel</a> 
        </div>
        <small class="pull-left text-danger">* Required fields</small>
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
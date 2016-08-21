@extends('master_management')
@section('title', 'Books')
@section("content")
	<h2 class="sub-header">Books</h2>
	@if(session("status"))
		<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			{{ session('status')  }}
		</div>
	@endif
	<div class="pull-xs-right">
		<a href="{{ route('books.new') }}" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span> Add new book</a> 
	</div>
	<div class="table-responsive">

		@unless($books->count())
			<p class="text-danger">No books found.</p>
		@else
			<div class="table-responsive">
				<table class="table table-hover">
					<thead class="thead-inverse">
					<tr>
						<th>Title</th>
						<th>Author</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@foreach($books as $book)
						<tr>
							<td>{{ $book->title }}</td>
							<td>{{ $book->author->author_name }}</td>
							<td>
								{{ link_to_route("books.edit","Edit",[ 'id'=>$book->id], ['class'=>'btn btn-primary btn-sm']) }}
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
						</tr>
					</tfoot>
				</table>
			</div>

		@endunless

	</div>
@stop
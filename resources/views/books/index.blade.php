@extends('master_management')
@section('title', 'Books')
@section("content")
	<h2 class="sub-header">Books</h2>
	
    @if( session('notify') )
        @if(!session('notify')['error'])
            <div class="alert alert-success">
                {{ session('notify')['message'] }}
            </div>
        @endif
    @endif
	<div class="table-responsive">			
		<div class="pull-right">
			<a href="{{ route('books.new') }}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Add new book</a> 
		</div>
		@unless($books->count())
			<p class="text-danger">No books found.</p>
		@else
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
							<td>{{ $book->name }}</td>
							<td>
							@forelse($book->authorList as $key => $author)
								<div>{{ $author->details->name }}</div>
							@empty
								-
							@endforelse
							</td>
							<td>
							<div class="btn-group">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="glyphicon glyphicon-option-vertical"></span>
								</button>
								<ul class="dropdown-menu">
									<li>
										<a href="{{ route('books.edit',['id'=>$book->id]) }}">
											<span class="glyphicon glyphicon-pencil"></span> 
											Edit
										</a>
									</li>
								</ul>
							</div>
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
		@endunless

	</div>
@stop
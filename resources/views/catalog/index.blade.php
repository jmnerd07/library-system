<?php phpinfo(); ?>
@extends("master_catalog")

@section("content")

	@unless($books->count())
		<p class="text-danger">No books found.</p>
	@else
		@foreach($books->chunk(4) as $group)
		<div class="card-deck-wrapper">
			<div class="card-deck">
			@foreach($group as $book)
				<div class="card">
					<img class="card-img-top" src="{{ url('images/no-preview-available.png') }}" alt="Card image cap">
					<div class="card-block">
						<h4 class="card-title">{{ $book->title }}</h4>
						<cite title="Source Title" class="text-muted">{{ $book->author }}</cite>
						<p class="card-text">{{ $book->description }}</p>
					</div>
				</div>
			@endforeach
			</div>
		</div>
		@endforeach
	@endunless

@stop
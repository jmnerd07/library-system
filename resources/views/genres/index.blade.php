@extends('master_management')
@section("content")
	<div data-ng-controller="GenresController">
		<h2 class="sub-header">Genres</h2>
		@if(session("status"))
			<div class="alert alert-success alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				{{ session('status')  }}
			</div>
		@endif
		<div class="pull-xs-right">
			<button class="btn btn-primary btn-sm ng-create-new-genre" data-link-save-create="{{ route('genres.async.newGenre') }}" data-toggle="modal" data-target="#modal-box">Add new genre</button>
		</div>
		<div class="table-responsive">

			@unless($genres->count())
				<p class="text-danger">No genres found.</p>
			@else
					
				<div class="table-responsive">
					<table class="table table-hover">
						<thead class="thead-inverse">
						<tr>
							<th>Genre</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						@foreach($genres as $genre)
							<tr>
								<td data-genre-id="{{ $genre->id }}" data-ng-click="hideSubGenres{{ $genre->id }} = !hideSubGenres{{ $genre->id }}" data-ng-init="hideSubGenres{{ $genre->id }} = true;" data-ng-model="hideSubGenres{{ $genre->id }}">		
									@if($genre->subGenres->count() > 0)
										<span><b data-ng-bind="hideSubGenres{{ $genre->id }} ? '&#43;' : '&#8722;' "></b>&nbsp;</span>
									@endif
									{{ $genre->name }}
									@if($genre->subGenres->count() > 0)
										<span class="label label-default" data-placement="bottom" data-toggle="tooltip" title="Number of sub-genres">{{ number_format($genre->subGenres->count()) }}</span>
									@endif
									</label>
								</td>
								<td>
									<div class="btn-group">
										<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										</button>
										<div class="dropdown-menu">
											<a href="#" data-genre-id="{{$genre->id}}" data-toggle="modal" data-target="#modal-box" class="dropdown-item ng-button-edit-genre">Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#">Remove</a>
										</div>
									</div>
								</td>
							</tr>
							@if($genre->subGenres)
								@foreach($genre->subGenres->all() as $subGenre)
								<tr data-parent-genre-id="{{ $subGenre->parent_genre_id }}" data-ng-hide="hideSubGenres{{ $genre->id }}">
									<td>
										<div style="padding-left: 3em;">{{ $subGenre->name }}</div>
									</td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											</button>
											<div class="dropdown-menu">
												<a href="#" data-genre-id="{{$subGenre->id}}" data-toggle="modal" data-target="#modal-box" class="dropdown-item ng-button-edit-genre">Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#">Remove</a>
											</div>
										</div>
									</td>
								</tr>
								@endforeach
							@endif
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
	</div>
	@section('user_js')
		{{ Html::script('js/filters/variable-values.filter.js') }}
		{{ Html::script('js/controllers/genres.controller.js') }}
		{{ Html::script('js/directives/genres-dom.directive.js') }}
	@endsection
@stop
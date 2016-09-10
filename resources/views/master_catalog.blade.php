<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Book Management System</title>
	{{ Html::style('vendor/bootstrap/css/bootstrap.min.css') }}
	{{ Html::style('vendor/bootstrap/css/dashboard.css') }}
	{{ Html::style('css/styles.css') }}
</head>
<body>
<nav class="navbar navbar-dark bg-inverse navbar-fixed-top">
	<a class="navbar-brand" href="#">BooksSystem</a>

</nav>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar catalog">
			{{ Form::open(array('route'=>'catalog.index')) }}
				<ul class="nav nav-pills nav-stacked">
					<li class="nav-item active">
					
						<div class="col-lg-12">
							<div class="input-group">
								<input type="text" name="keyword" value="" class="form-control" placeholder="Search for...">

								<span class="input-group-btn">
									<button type="submit" class="btn btn-secondary" type="button">Go!</button>
								</span>
								@if(session('result')):
									<code>About {{ $books->count() }} result(s)</code>
								@endif
							</div>
						</div>
					</li>
				</ul>
			{{ Form::close() }}
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			@yield('content')
		</div>
	</div>
</div>

</body>
</html>
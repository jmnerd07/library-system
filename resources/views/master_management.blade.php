<!DOCTYPE html>
<html lang="en" data-ng-app="BookManagementApp">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | Book Management System</title>

    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700"> --}}

    <!-- Styles -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"> --}}
    <link href="{{ asset('vendors/twbs-bootstrap/css/bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/nprogress/css/nprogress.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <!--[if lt IE 9]>
    <script src="../assets/js/ie8-responsive-file-warning.js"></script>
    <![endif]-->
     
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="app-layout" class="{{ (Auth::guest() ? '': 'nav-md') }}">
@if(Auth::guest())
    
    <div style="margin-top:5%;"></div>
    @yield('content')
@else 
    <div class="container body">
        <div class="main_container">
            {{-- Sidebar --}}
            
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                          <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>LS!</span></a>
                        </div>

                        <div class="clearfix"></div>
                        @include('layouts.sidebar-menu-profile')
                        @include('layouts.sidebar-menu')
                        @include('layouts.sidebar-footer')
                    </div>
                </div>
                @include('layouts.top-nav')
            {{-- /Sidebar --}}
            {{-- Top Navigation --}}
            {{-- /Top Navigation --}}
            
            {{-- Page Content --}}
            <div class="right_col" role="main">
                <div class="row">
                    <div class="page-title">

                        {{-- <div class="title_right">
                            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Go!</button>
                                    </span>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                {{-- <div class="x_title">
                                    <h2>Plain Page</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Settings 1</a>
                                                </li>
                                                <li><a href="#">Settings 2</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div> --}}
                                <div class="x_content">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Page Content --}}
            {{-- footer content --}}
            <footer>
                <div class="pull-right">
                    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
                </div>
                <div class="clearfix"></div>
            </footer>
            {{-- /footer content  --}}
        </div>
    </div>
 @endif

    

    <!-- JavaScripts -->
    <script type="text/javascript" src="{{ asset('vendors/jquery/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/twbs-bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/fastclick/fastclick.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/nprogress/js/nprogress.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/angular/angular.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/library/react.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/factories/modal-box.factory.js') }}"></script>
    @yield('user_js')
    @include('layouts.modal-box-template')
</body>
</html>
var elixir = require('laravel-elixir');
	//gulp = require('gulp')

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	var bootstrapPath = 'node_modules/bootstrap-sass/assets';
	var fontAwesomePath = 'node_modules/font-awesome/';
    mix.sass('bootstrap.scss','public/vendors/twbs-bootstrap/css/bootstrap.css')
    	.copy(bootstrapPath + '/fonts/bootstrap', 'public/vendors/twbs-bootstrap/fonts/bootstrap')
    	.copy(bootstrapPath + '/javascripts/bootstrap.min.js', 'public/vendors/twbs-bootstrap/js')
    	/* font-awesome */
    	.sass('font-awesome.scss','public/vendors/font-awesome/css/font-awesome.css')
    	.copy(fontAwesomePath + '/fonts/', 'public/vendors/font-awesome/fonts/' )
    	/* jQuery */
    	.copy('node_modules/jquery/dist/', 'public/vendors/jquery/')

    	/* Fastclick */
    	.copy('node_modules/fastclick/lib/','public/vendors/fastclick/')
    	/* NProgress */
    	.copy('node_modules/nprogress/nprogress.css','public/vendors/nprogress/css')
    	.copy('node_modules/nprogress/nprogress.js','public/vendors/nprogress/js')
    	/* Custom js */
        .copy('node_modules/angular/', 'public/vendors/angular')
    	.scriptsIn('resources/assets/js', 'public/js/custom.js')

        /* Angular files */
        .copy('resources/assets/js/', 'public/js/')

        /* Custom CSS */
        .sass([
                'app/global.scss',
                'app/books/books_form.partial.scss'
            ],'resources/assets/css/app.css')
        .styles([
                'custom.css',
                'app.css'
            ],'public/css/styles.css')
        .version('public/css/styles.css')
        /* Reactjs */
        //.browserify('resources/assets/js/library/react.js','public/js/library/react.js')
    	/*.version(['public/vendors/twbs-bootstrap/css/bootstrap.css'
    		,'public/vendors/font-awesome/css/font-awesome.css'
    		,'public/vendors/font-awesome/fonts/'])*/
    	
});
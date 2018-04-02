let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .copy('node_modules/font-awesome/fonts', 'public/fonts')
   .copy('node_modules/jquery/dist', 'public/js/jquery')
   .copy('node_modules/bootstrap-sass/assets/javascripts', 'public/js/bootstrap') 
   .copy('node_modules/select2/dist/css', 'public/css/select2') 
   .copy('node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js', 'public/js/datetimepicker') 
   .copy('node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.js', 'public/js/datetimepicker') 
   .copy('node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css', 'public/css/datetimepicker'); ;

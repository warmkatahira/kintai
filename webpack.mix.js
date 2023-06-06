const mix = require('laravel-mix');
 
 mix.js('resources/js/app.js', 'public/js')
 .sass('resources/sass/theme.scss', 'public/css')
 .sass('resources/sass/navigation.scss', 'public/css')
 .autoload({
     "jquery": [ '$', 'window.jQuery' ],
 })
 .postCss('resources/css/app.css', 'public/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
 ]);
var elixir = require('laravel-elixir');

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

elixir(function (mix) {
    mix.styles(
        [
            '/css/jquery-ui.css',
            '/themes/sportsjun/css/style.css',
            '/themes/sportsjun/css/font-awesome.css',
            '/themes/sportsjun/css/stylesheet.css',
        ], 'public/themes/sportsjun/css/base.css', 'public');

        mix.styles(
        [
            '/themes/sportsjun/css/owl.carousel.css',
            '/themes/sportsjun/css/custom.css',
            '/themes/sportsjun/css/backslider.css',
            '/themes/sportsjun/css/old.css',
            '/themes/sportsjun/css/style_add.css'
        ], 'public/themes/sportsjun/css/all.css', 'public'
    );

        mix.version([
            '/themes/sportsjun/css/bootstrap.css',
            '/themes/sportsjun/css/base.css',
        '/themes/sportsjun/css/all.css'],'public/build');
    mix.copy('public/themes/','public/build/themes');

});

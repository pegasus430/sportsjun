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
    //theme
    mix.styles(
        [
            '/css/jquery-ui.css',
            '/themes/sportsjun/css/style.css',
            '/themes/sportsjun/css/font-awesome.css',
            '/themes/sportsjun/css/stylesheet.css',
        ], 'public/gen/themes/sportsjun/css/base.css', 'public');

    mix.styles(
        [
            '/themes/sportsjun/css/owl.carousel.css',
            '/themes/sportsjun/css/custom.css',
            '/themes/sportsjun/css/backslider.css',
            '/themes/sportsjun/css/old.css',
            '/themes/sportsjun/css/style_add.css'
        ], 'public/gen/themes/sportsjun/css/all.css', 'public'
    );


    //base
    mix.styles([
            '/home/css/font-awesome.css',
            '/home/css/themetypo.css',
            '/home/css/style.css',
            '/home/css/widget.css',
            '/home/css/color.css',
            '/home/css/flexslider.css',
            '/home/css/owl.carousel.css',
            '/home/css/jquery.bxslider.css',
            '/home/css/prettyphoto.css',
            '/home/css/responsive.css',

            '/themes/sportsjun/css/owl.carousel.css',
            'home/css/el/*.css',
            'home/css/search/*.css',
            'home/css/media/*.css',


        ], 'public/gen/home/css/home_mix.css', 'public'
    );

    mix.scripts([
        '/home/js/jquery.flexslider.js',
        '/home/js/owl.carousel.min.js',
        '/home/js/jquery.countdown.js',
        '/home/js/waypoints-min.js',
        '/home/js/jquery.bxslider.min.js',
        '/home/js/bootstrap-progressbar.js',
        '/home/js/jquery.accordion.js',
        '/home/js/jquery.circlechart.js',
        '/home/js/jquery.prettyphoto.js',
    ], 'public/gen/home/js/lib_mix.js','public');

    mix.scripts([
        '/home/js/sj.global.js',
        '/home/js/sj.user.js',
        '/home/js/kode_pp.js',
        '/home/js/functions.js',
        'home/js/sportsjun.js'
    ],'public/gen/home/js/home_mix.js','public');



    mix.version([
        '/themes/sportsjun/css/bootstrap.css',
        'gen/themes/sportsjun/css/base.css',
        'gen/themes/sportsjun/css/all.css',
        'gen/home/css/home_mix.css',
        'gen/home/js/lib_mix.js',
        'gen/home/js/home_mix.js',
        'js/admin.scripts.js'
    ], 'public/build');
    mix.copy('public/themes/', 'public/build/gen/themes');
    mix.copy('public/home/', 'public/build/gen/home');

});

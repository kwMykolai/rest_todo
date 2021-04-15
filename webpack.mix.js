const mix = require('laravel-mix');
mix.setPublicPath('public');
mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');
mix.copy('resources/view/*', 'public/view');
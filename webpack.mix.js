const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
 const folder = {
    src: "resources/", // source files
    dist: "public/", // build files
    dist_assets: "public/assets/" //build assets files
};

mix.sass('resources/scss/bootstrap.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/bootstrap.css");
mix.sass('resources/scss/icons.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/icons.css");
mix.sass('resources/scss/app.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/app.css");
mix.sass('resources/scss/app-dark.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/app-dark.css");

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .webpackConfig(require('./webpack.config'));

mix.browserSync({
    proxy: "http://127.0.0.1:8000",
    open: false
});

mix.disableNotifications();

if (mix.inProduction()) {
    mix.version();
}

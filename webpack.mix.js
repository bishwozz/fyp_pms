const mix = require('laravel-mix');

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

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .styles(
        [
            "public/packages/backpack/base/css/bundle.css",
            "public/packages/source-sans-pro/source-sans-pro.css",
            "public/packages/line-awesome/css/line-awesome.min.css",
            "public/packages/select2/dist/css/select2.min.css",
            "public/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css",
            "public/packages/dataTables-custom/css/dataTables.bootstrap4.min.css",
            "public/packages/dataTables-custom/css/select.dataTables.min.css",
            "public/packages/bootstrap-iconpicker/icon-fonts/font-awesome-4.7.0/css/font-awesome.min.css",
            'public/packages/jquery-ui/css/jquery-ui.css',
            "public/css/nepali.datepicker.v2.2.min.css",
            "public/css/jquery.fancybox.min.css",
            "public/css/tautocomplete.css",
        ],
        "public/css/vendor.css"
    )
    .scripts(
        [
            'public/js/jquery-3.3.1.min.js',
            "public/packages/backpack/base/js/bundle.js",
            "public/packages/select2/dist/js/select2.full.min.js",
            "public/packages/moment/min/moment.min.js",
            "public/js/fancybox.v3.5.7.min.js",
            "public/packages/dataTables-custom/js/jquery.dataTables.min.js",
            'node_modules/gasparesganga-jquery-loading-overlay/dist/loadingoverlay.min.js',
            "public/packages/dataTables-custom/js/dataTables.bootstrap4.min.js",
            "public/packages/dataTables-custom/js/dataTables.select.min.js",
            'node_modules/jquery-validation/dist/jquery.validate.min.js',
            'public/packages/jquery-ui/js/jquery-ui.js',
            "public/js/nepali.datepicker.v2.2.min.js",
            "public/js/date_helper.js",
            "public/js/tautocomplete.js",
        ],
        "public/js/vendor.js"
    )
    .version();

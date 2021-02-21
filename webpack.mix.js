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

mix
    /* CSS */
    .sass('resources/assets/sass/main.scss', 'public/css/codebase.css')
    .sass('resources/assets/sass/codebase/themes/corporate.scss', 'public/css/themes/')
    .sass('resources/assets/sass/codebase/themes/earth.scss', 'public/css/themes/')
    .sass('resources/assets/sass/codebase/themes/elegance.scss', 'public/css/themes/')
    .sass('resources/assets/sass/codebase/themes/flat.scss', 'public/css/themes/')
    .sass('resources/assets/sass/codebase/themes/pulse.scss', 'public/css/themes/')

    /* JS */
    // .js('resources/assets/js/laravel/app.js', 'public/js/laravel.app.js')
    // .js('resources/assets/js/laravel/plugins.js', 'public/js/laravel.app.js')
    .js('resources/assets/js/codebase/app.js', 'public/js/codebase.app.js')

    /* Global JS */
    .styles([
        'node_modules/@fullcalendar/core/main.css',
        'node_modules/@fullcalendar/daygrid/main.css',
        'node_modules/@fullcalendar/list/main.css',
        'node_modules/@fullcalendar/resource-common/main.css',
        'node_modules/@fullcalendar/resource-daygrid/main.css',
        'node_modules/@fullcalendar/resource-timegrid/main.css',
        'node_modules/@fullcalendar/resource-timeline/main.css',
        'node_modules/@fullcalendar/timegrid/main.css',
        'node_modules/@fullcalendar/timeline/main.css',
        'public/js/plugins/datatables/jquery.dataTables.min.css',
        'public/js/plugins/datatables/dataTables.bootstrap4.css',
        'public/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css',
        'public/js/plugins/datatables/select.dataTables.min.css',
        'public/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css',
        'public/js/plugins/dropify/css/dropify.min.css',
        'public/js/plugins/select2/css/select2.min.css',
        'public/js/plugins/select2/css/select2-bootstrap.min.css',
        'public/js/plugins/select2/css/customsearch.css',
        'public/js/plugins/sweetalert2/sweetalert2.min.css',
        'public/js/plugins/jquery-confirm/css/jquery-confirm.min.css',
        'public/js/plugins/jquery-auto-complete/jquery.auto-complete.min.css',
        'public/js/plugins/magnific-popup/magnific-popup.css',
        'public/js/plugins/simplemde/simplemde.min.css',
    ], 'public/css/all.css')

    .scripts([
        'public/js/plugins/jquery-validation/jquery.validate.min.js',
        'public/js/plugins/jquery-validation/additional-methods.js',
        'public/js/plugins/jquery-validation/localization/messages_id.js',
        'public/js/plugins/jquery-validation/additional-methods.min.js',
        'public/js/plugins/datatables/jquery.dataTables20.min.js',
        'public/js/plugins/datatables/dataTables.bootstrap4.min.js',
        'public/js/plugins/datatables/buttons/dataTables.buttons.min.js',
        'public/js/plugins/datatables/buttons/buttons.flash.min.js',
        'public/js/plugins/datatables/buttons/buttons.print.min.js',
        'public/js/plugins/datatables/buttons/buttons.colVis.min.js',
        'public/js/plugins/datatables/buttons/jszip.min.js',
        'public/js/plugins/datatables/buttons/pdfmake.min.js',
        'public/js/plugins/datatables/buttons/vfs_fonts.js',
        'public/js/plugins/datatables/buttons/buttons.html5.min.js',
        'public/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.js',
        'public/js/plugins/datatables/dataTables.select.min.js',
        'public/js/plugins/datatables/handle.js',
        'public/js/plugins/datatables/handlebars.js',
        'public/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'public/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js',
        'public/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js',
        'public/js/plugins/dropify/js/dropify.min.js',
        'public/js/plugins/select2/js/select2.full.min.js',
        'public/js/plugins/select2/js/i18n/id.js',
        'public/js/plugins/masked-inputs/jquery.maskedinput.min.js',
        'public/js/plugins/sweetalert2/sweetalert2.min.js',
        'public/js/plugins/jquery-confirm/js/jquery-confirm.min.js',
        'public/js/plugins/bootstrap-notify/bootstrap-notify.min.js',
        'public/js/plugins/numeralis/numeral.min.js',
        'public/js/plugins/numeralis/rupiah.js',
        'public/js/plugins/numeralis/terbilang.js',
        'public/js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js',
        'public/js/plugins/jquery-auto-complete/jquery.auto-complete.min.js',
        'public/js/plugins/easy-pie-chart/jquery.easypiechart.min.js',
        'public/js/plugins/chartjs/Chart.bundle.min.js',
        'public/js/plugins/highcharts/highcharts.js',
        'public/js/plugins/magnific-popup/jquery.magnific-popup.min.js',
        'public/js/plugins/simplemde/simplemde.min.js',
        'node_modules/@fullcalendar/core/main.js',
        'node_modules/@fullcalendar/core/locales/id.js',
        'node_modules/@fullcalendar/daygrid/main.js',
        'node_modules/@fullcalendar/list/main.js',
        'node_modules/@fullcalendar/timegrid/main.js',
        'node_modules/@fullcalendar/timeline/main.js',
        'node_modules/@fullcalendar/resource-common/main.js',
        'node_modules/@fullcalendar/resource-daygrid/main.js',
        'node_modules/@fullcalendar/resource-timegrid/main.js',
        'node_modules/@fullcalendar/resource-timeline/main.js',
    ], 'public/js/all.js')

    /* Tools */
    .browserSync('localhost:8000')
    .disableNotifications()

    /* Options */
    .options({
        processCssUrls: false
    });

const mix = require('laravel-mix');
 
 mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/theme.scss', 'public/css')
    .sass('resources/sass/navigation.scss', 'public/css')
    .sass('resources/sass/scroll.scss', 'public/css')
    .css('resources/css/clock.css', 'public/css')
    .js('resources/js/clock.js', 'public/js')
    .js('resources/js/checkbox.js', 'public/js')
    .js('resources/js/punch/punch_begin_type.js', 'public/js')
    .js('resources/js/punch/punch_common.js', 'public/js')
    .js('resources/js/punch/punch_complete_popup.js', 'public/js')
    .css('resources/css/punch_complete_popup.css', 'public/css')
    .js('resources/js/punch/punch_finish.js', 'public/js')
    .js('resources/js/punch/punch_finish_input.js', 'public/js')
    .js('resources/js/punch/punch_finish_tab.js', 'public/js')
    .css('resources/css/punch_finish_tab.css', 'public/css')
    .js('resources/js/search_date_period.js', 'public/js')
    .js('resources/js/kintai_mgt/kintai_detail.js', 'public/js')
    .js('resources/js/kintai_mgt/kintai_mgt.js', 'public/js')
    .js('resources/js/employee_mgt/employee_create.js', 'public/js')
    .js('resources/js/employee_mgt/employee_update.js', 'public/js')
    .autoload({
        "jquery": [ '$', 'window.jQuery' ],
    })
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
 ]);
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        hmr: {
            host: 'localhost',
        },
    },
	plugins: [
		laravel([
			'resources/css/app.css',
			'resources/js/app.js',
            'resources/sass/theme.scss',
            'resources/sass/navigation.scss',
            'resources/sass/scroll.scss',
            'resources/css/clock.css',
            'resources/js/clock.js',
            'resources/js/checkbox.js',
            'resources/js/punch/punch_begin_type.js',
            'resources/js/punch/punch_common.js',
            'resources/js/punch/punch_complete_popup.js',
            'resources/css/punch_complete_popup.css',
            'resources/js/punch/punch_finish.js',
            'resources/js/punch/punch_finish_input.js',
            'resources/js/punch/punch_finish_tab.js',
            'resources/css/punch_finish_tab.css',
            'resources/js/search_date_period.js',
            'resources/js/kintai_mgt/kintai_detail.js',
            'resources/js/kintai_mgt/kintai_mgt.js',
            'resources/js/employee_mgt/employee_create.js',
            'resources/js/employee_mgt/employee_update.js',
            'resources/js/customer_mgt/customer_create.js',
            'resources/js/customer_mgt/customer_update.js',
            'resources/js/customer_group_mgt/customer_group_create.js',
            'resources/js/customer_group_mgt/customer_group_update.js',
		]),
	],
});
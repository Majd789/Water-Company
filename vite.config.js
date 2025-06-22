import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                
                'resources/sass/createoffice.scss',
                'resources/sass/home.scss',
                'resources/sass/login.scss',
                'resources/sass/welcome.scss',
            ],
            refresh: true,
        }),
    ],
});

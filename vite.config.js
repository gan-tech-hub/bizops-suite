import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    base: 'https://bizops-suite.onrender.com/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/reservations.js',],
            refresh: true,
        }),
    ],
});

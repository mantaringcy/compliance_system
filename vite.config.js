import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        headers: {
          'Content-Security-Policy': "script-src 'self' 'unsafe-inline';"
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js'
                ],
            refresh: true,
        }),
    ],
});

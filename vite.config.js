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
            input: ['resources/css/app.css', 
                    'resources/css/alert.css', 
                    'resources/css/auth-app.css', 
                    'resources/css/badge.css', 
                    'resources/css/form.css', 
                    'resources/css/modal.css', 
                    'resources/css/theme-switch.css', 
                    'resources/css/theme-colors.css', 
                    'resources/css/toast.css', 
                    'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

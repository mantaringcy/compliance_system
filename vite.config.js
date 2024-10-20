import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                    'resources/css/auth-app.css', 
                    'resources/css/form.css', 
                    'resources/css/modal.css', 
                    'resources/css/theme-switch.css', 
                    'resources/css/theme-colors.css', 
                    'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

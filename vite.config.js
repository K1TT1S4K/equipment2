import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/equipment_add.js',
            ],
            refresh: [`resources/views/**/*`],
        }),
    ],
    server: {
        cors: true,
    },
});

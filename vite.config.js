import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',        // Supaya bisa diakses dari LAN
        port: 5173,
        hmr: {
            host: '192.168.1.26',  // IP komputer kamu yang menjalankan vite dev server
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

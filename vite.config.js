import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/beranda.js'],
            refresh: true,
        }),
    ],

    // server: {
    //     host: '192.168.10.250',
    //     port: 5173,
    //     cors: true,
    //     proxy: {
    //       '/api': {
    //         target: 'http://192.168.10.250:8000',
    //         changeOrigin: true,
    //         secure: false,
    //       },
    //     },
    //   }
});

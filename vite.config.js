import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],

    server: {
        host: "0.0.0.0", // Tambahkan ini agar Vite bisa diakses lewat IP
        hmt: {
            host: '192.168.0.109'
        }
    },

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

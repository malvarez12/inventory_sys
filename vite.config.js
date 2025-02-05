import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from "vite-plugin-static-copy";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/styles.css', 'resources/js/app.js'],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                // Styles (Tabler CSS)
                {
                    src: [
                        'node_modules/@tabler/core/dist/css/tabler.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-flags.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-payments.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-vendors.min.css',
                        'node_modules/@tabler/core/dist/css/demo.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-social.min.css',
                    ],
                    dest: '../dist/css'
                },
                // Scripts (Tabler JS)
                {
                    src: [
                        'node_modules/@tabler/core/dist/js/demo-theme.min.js',
                        'node_modules/@tabler/core/dist/js/tabler.min.js',
                        'node_modules/@tabler/core/dist/js/demo.min.js',
                    ],
                    dest: '../dist/js'
                },
                // Libraries
                {
                    src: 'node_modules/@tabler/core/dist/libs/*',
                    dest: '../dist/libs'
                },
                // Fonts (your custom fonts)
                {
                    src: 'resources/fonts/metropolis/*', // Ruta actualizada para tus fuentes
                    dest: '../dist/fonts/metropolis'
                },
                // Images (your custom images)
                {
                    src: 'resources/img/backgrounds/*', // Ruta actualizada para tus imágenes
                    dest: '../dist/img/backgrounds'
                },
            ]
        })
    ],
});

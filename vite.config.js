import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    optimizeDeps: {
        include: [
            '@chenfengyuan/vue-qrcode',
            'leaflet',
            '@vue-leaflet/vue-leaflet'
        ],
        force: true, // force re-bundle on server start
    },
    define: {
        global: 'globalThis',
    },
    assetsInclude: ['**/*.png', '**/*.jpg', '**/*.jpeg', '**/*.gif', '**/*.svg'],
    ssr: {
        noExternal: ['@inertiajs/vue3'],
    },
});

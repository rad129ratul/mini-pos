import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            
            '@': path.resolve(__dirname, './resources/js'),
            
            '@components': path.resolve(__dirname, './resources/js/components'),
            '@views': path.resolve(__dirname, './resources/js/views'),
            '@store': path.resolve(__dirname, './resources/js/store'),
            '@services': path.resolve(__dirname, './resources/js/services'),
            '@router': path.resolve(__dirname, './resources/js/router'),
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
    build: {
        sourcemap: false,
        
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': ['vue', 'vue-router', 'pinia', 'axios'],
                    'bootstrap': ['bootstrap', '@popperjs/core'],
                },
            },
        },
        
        chunkSizeWarningLimit: 1000,
    },
    optimizeDeps: {
        include: [
            'vue',
            'vue-router',
            'pinia',
            'axios',
            'bootstrap',
            '@popperjs/core',
        ],
    },
});
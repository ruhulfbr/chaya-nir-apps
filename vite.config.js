import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                // invextry backend admin assets
                'resources/frontend/main.js'
            ],
            refresh: true,
        }),
    ],
});

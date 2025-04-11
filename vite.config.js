import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Components from 'unplugin-vue-components/vite';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';

export default defineConfig(({ mode }) => {
    const isLocal = process.env.APP_ENV === 'local';
    const host = isLocal ? process.env.VITE_APP_DOMAIN : 'localhost';

    return {
        plugins: [
            laravel({
                input: 'resources/js/app.js',
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
            Components({
                resolvers: [
                    PrimeVueResolver(),
                ],
            }),
        ],
        server: {
            host: host,
        },
    };
});

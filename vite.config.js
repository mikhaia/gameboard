import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

const watchUsePolling = process.env.VITE_WATCH_USE_POLLING === 'true' || process.env.DOCKER === 'true';
const watchInterval = Number(process.env.VITE_WATCH_INTERVAL || 250);

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: Number(process.env.VITE_PORT || 5173),
        strictPort: true,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
            port: Number(process.env.VITE_PORT || 5173),
            clientPort: Number(process.env.VITE_PORT || 5173),
        },
        watch: {
            usePolling: watchUsePolling,
            interval: watchInterval,
            awaitWriteFinish: {
                stabilityThreshold: 200,
                pollInterval: 100,
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.styl',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        vue()
    ],
});

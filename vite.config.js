import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony'

export default defineConfig({
    plugins: [
        symfonyPlugin(),
    ],
    root: '.',
    base: '/build/',
    build: {
        manifest: true,
        emptyOutDir: true,
        assetsDir: 'assets',
        outDir: 'public/build',
        rollupOptions: {
            input: {
                app: './assets/app.js'
            }
        }
    },
    server: {
        port: 5173,
        strictPort: true,
        cors: true
    }
})
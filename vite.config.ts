import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import symfonyPlugin from 'vite-plugin-symfony'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig({
    plugins: [
        vue(),
        symfonyPlugin(),
    ],
    root: '.',
    base: '/build/',    
    build: {
        manifest: true,
        emptyOutDir: true,
        outDir: './public/build',
        assetsDir: 'assets',
        rollupOptions: {
            input: {
                app: './assets/app.ts'
            }
        }
    },
    server: {
        port: 5173,
        strictPort: true,
        cors: true
    },
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./assets', import.meta.url))
        }
    }
})

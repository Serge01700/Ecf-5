import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    port: 3000,
    open: true,
    proxy: {
      '/api': 'http://localhost:8000'
    }
  },
  build: {
    outDir: '../backend/public/build',
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: './src/main.js',
      output: {
        entryFileNames: 'app.js'
      }
    }
  }
})
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
  assetsInclude: ['**/*.png'],
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
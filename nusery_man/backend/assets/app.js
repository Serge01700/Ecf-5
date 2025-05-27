/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'core-js/stable';
import 'regenerator-runtime/runtime';
import { createApp } from 'vue'
import App from '../../frontend/src/App.vue'
import router from '../../frontend/src/router'

createApp(App).use(router).mount('#vue-app')

import './styles/app.css';

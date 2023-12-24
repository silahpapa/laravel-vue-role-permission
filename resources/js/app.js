import { createApp } from 'vue'

import './bootstrap';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';
import router from './router/index.js'
import App from '../app/App.vue'
import AppLayout from '../app/layouts/app/AppLayout.vue'
import AuthLayout from '../app/layouts/auth/authLayout.vue'
const app = createApp(App)
app.use(router)
app.component('AppLayout',AppLayout)
app.component('AuthLayout',AuthLayout)
app.mount('#app')


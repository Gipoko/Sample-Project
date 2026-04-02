import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// vue
import { createApp } from 'vue';
import DashboardApp from './components/DashboardApp.vue';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('vue-dashboard');

    if (el) {
        createApp(DashboardApp).mount('#vue-dashboard');
    }
});
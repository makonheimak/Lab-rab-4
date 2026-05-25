import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import './styles.css';

const isDesktop = window.innerWidth >= 980;
const isDesktopFrame = window.location.pathname === '/desktop' || window.self !== window.top;

if (isDesktop && !isDesktopFrame) {
  router.replace('/desktop');
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js');
  });
}

createApp(App).use(store).use(router).mount('#app');

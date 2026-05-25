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

if (import.meta.env.DEV && 'serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then((registrations) => {
    registrations.forEach((registration) => registration.unregister());
  });
  if ('caches' in window) {
    caches.keys().then((keys) => keys.forEach((key) => caches.delete(key)));
  }
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js');
  });
}

createApp(App).use(store).use(router).mount('#app');

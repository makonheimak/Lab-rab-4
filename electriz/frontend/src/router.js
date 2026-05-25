import { createRouter, createWebHistory } from 'vue-router';
import WelcomePage from './views/WelcomePage.vue';
import ProductsPage from './views/ProductsPage.vue';
import ProductPage from './views/ProductPage.vue';
import CartPage from './views/CartPage.vue';
import CheckoutPage from './views/CheckoutPage.vue';
import ProfilePage from './views/ProfilePage.vue';
import NewsListPage from './views/NewsListPage.vue';
import NewsPage from './views/NewsPage.vue';
import DesktopPage from './views/DesktopPage.vue';
import PaymentsPage from './views/PaymentsPage.vue';
import ChatPage from './views/ChatPage.vue';
import AuthCallbackPage from './views/AuthCallbackPage.vue';

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'welcome', component: WelcomePage },
    { path: '/products', name: 'products', component: ProductsPage },
    { path: '/products/:id', name: 'product', component: ProductPage },
    { path: '/cart', name: 'cart', component: CartPage },
    { path: '/checkout', name: 'checkout', component: CheckoutPage },
    { path: '/profile', name: 'profile', component: ProfilePage },
    { path: '/news', name: 'news', component: NewsListPage },
    { path: '/news/:id', name: 'news-item', component: NewsPage },
    { path: '/payments', name: 'payments', component: PaymentsPage },
    { path: '/chat', name: 'chat', component: ChatPage },
    { path: '/auth/callback', name: 'auth-callback', component: AuthCallbackPage },
    { path: '/desktop', name: 'desktop', component: DesktopPage },
  ],
});

<template>
  <div class="app-shell">
    <header v-if="$route.name !== 'desktop'" class="topbar">
      <router-link to="/" class="brand">
        <span class="brand-icon">E</span>
        <span>ElectrizShop</span>
      </router-link>
      <router-link to="/cart" class="cart-pill">Корзина: {{ cartCount }}</router-link>
    </header>

    <main class="page">
      <router-view />
    </main>

    <nav v-if="$route.name !== 'desktop'" class="bottom-nav">
      <router-link to="/products">Каталог</router-link>
      <router-link to="/news">Новости</router-link>
      <router-link to="/cart">Корзина</router-link>
      <router-link to="/payments">Платежи</router-link>
      <router-link to="/chat">Чат</router-link>
      <router-link to="/profile">Профиль</router-link>
    </nav>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const cartCount = computed(() => store.getters.cartCount);

onMounted(() => {
  store.dispatch('loadCatalog');
  store.dispatch('loadCart');
});
</script>

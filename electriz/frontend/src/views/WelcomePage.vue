<template>
  <section class="grid">
    <div class="hero">
      <h1>Техника для учебы, работы и дома</h1>
      <p>Каталог электроники с корзиной, заказами, новостями, авторизацией, PWA-режимом и демонстрацией push-уведомлений.</p>
      <div class="actions">
        <router-link to="/products" class="btn">Открыть каталог</router-link>
        <button class="btn secondary" @click="notify">Проверить push</button>
      </div>
    </div>

    <h2 class="section-title">Категории</h2>
    <div class="grid">
      <router-link
        v-for="category in categories"
        :key="category.id"
        class="card"
        :to="{ path: '/products', query: { category_id: category.id } }"
      >
        <strong>{{ category.name }}</strong>
      </router-link>
    </div>

    <h2 class="section-title">Последние новости</h2>
    <router-link v-for="item in news.slice(0, 2)" :key="item.id" class="card" :to="`/news/${item.id}`">
      <strong>{{ item.title }}</strong>
      <p class="muted">{{ item.body }}</p>
    </router-link>
  </section>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const categories = computed(() => store.state.categories);
const news = computed(() => store.state.news);

async function notify() {
  if (!('Notification' in window)) return;
  const permission = await Notification.requestPermission();
  if (permission === 'granted') {
    new Notification('TechNovaShop', {
      body: 'Push-уведомления в PWA работают',
      icon: '/icons/icon-192.svg',
    });
  }
}
</script>

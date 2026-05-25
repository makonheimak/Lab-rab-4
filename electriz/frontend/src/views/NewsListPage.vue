<template>
  <section class="grid">
    <h1 class="section-title">Новости магазина</h1>
    <router-link v-for="item in news" :key="item.id" class="card" :to="`/news/${item.id}`">
      <strong>{{ item.title }}</strong>
      <p class="muted">{{ item.body }}</p>
    </router-link>
  </section>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const news = computed(() => store.state.news);
</script>

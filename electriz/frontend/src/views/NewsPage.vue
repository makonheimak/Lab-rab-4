<template>
  <section v-if="item" class="grid">
    <div class="cover">NEWS</div>
    <h1 class="section-title">{{ item.title }}</h1>
    <p class="muted">{{ item.body }}</p>
    <router-link to="/news" class="btn secondary">Все новости</router-link>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import api from '../api';

const route = useRoute();
const item = ref(null);

onMounted(async () => {
  const response = await api.get(`/news/${route.params.id}`);
  item.value = response.data.data || response.data;
});
</script>

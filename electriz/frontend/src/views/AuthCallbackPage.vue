<template>
  <section class="grid">
    <h1 class="section-title">Вход через соцсеть</h1>
    <div class="card">{{ message }}</div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const message = ref('Завершаем авторизацию...');

onMounted(async () => {
  if (route.query.token) {
    localStorage.setItem('token', route.query.token);
    message.value = 'Вход выполнен. Откройте профиль для загрузки данных.';
    await router.push('/profile');
    return;
  }

  message.value = 'Токен авторизации не получен.';
});
</script>

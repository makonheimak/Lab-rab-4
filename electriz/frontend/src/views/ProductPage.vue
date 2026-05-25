<template>
  <section v-if="product" class="grid">
    <div class="cover">{{ product.brand || 'Tech' }}</div>
    <h1 class="section-title">{{ product.name }}</h1>
    <p class="muted">{{ product.description }}</p>
    <p v-if="message" class="card">{{ message }}</p>
    <div class="card grid">
      <span>Категория: {{ product.category?.name || 'Без категории' }}</span>
      <span>Артикул: {{ product.sku }}</span>
      <span>Остаток: {{ product.stock }}</span>
      <span class="price">{{ formatPrice(product.price) }}</span>
    </div>
    <button class="btn" @click="add">Добавить в корзину</button>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import api from '../api';

const route = useRoute();
const router = useRouter();
const store = useStore();
const product = ref(null);
const message = ref('');

function formatPrice(value) {
  return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 }).format(value);
}

async function add() {
  if (!store.getters.isAuth) {
    router.push('/profile');
    return;
  }

  try {
    await store.dispatch('addToCart', product.value);
    router.push('/cart');
  } catch (error) {
    message.value = error.response?.data?.message || 'Не удалось добавить товар в корзину.';
  }
}

onMounted(async () => {
  const response = await api.get(`/products/${route.params.id}`);
  product.value = response.data.data || response.data;
});
</script>

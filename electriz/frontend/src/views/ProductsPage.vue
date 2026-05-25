<template>
  <section class="grid">
    <h1 class="section-title">Каталог товаров</h1>
    <label class="field">
      <span>Поиск</span>
      <input v-model="search" placeholder="Например, iPhone, Samsung, зарядка" @input="load" />
    </label>
    <label class="field">
      <span>Категория</span>
      <select v-model="categoryId" @change="load">
        <option value="">Все категории</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
      </select>
    </label>

    <p v-if="message" class="card">{{ message }}</p>
    <p v-else-if="!products.length" class="card">По вашему запросу ничего не найдено.</p>

    <article v-for="product in products" :key="product.id" class="card product-card">
      <div class="product-image">{{ product.brand || 'Tech' }}</div>
      <div class="grid">
        <router-link :to="`/products/${product.id}`"><strong>{{ product.name }}</strong></router-link>
        <span class="muted">{{ product.category?.name }} · остаток {{ product.stock }}</span>
        <span class="price">{{ formatPrice(product.price) }}</span>
        <button class="btn" @click="add(product)">В корзину</button>
      </div>
    </article>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const router = useRouter();
const search = ref(route.query.search || '');
const categoryId = ref(route.query.category_id || '');
const message = ref('');
const products = computed(() => store.state.products);
const categories = computed(() => store.state.categories);

function formatPrice(value) {
  return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 }).format(value);
}

function load() {
  const query = {};
  if (search.value.trim()) query.search = search.value.trim();
  if (categoryId.value) query.category_id = categoryId.value;
  message.value = '';
  router.replace({ path: '/products', query });
  store.dispatch('loadCatalog', query);
}

async function add(product) {
  if (!store.getters.isAuth) {
    router.push('/profile');
    return;
  }

  try {
    await store.dispatch('addToCart', product);
    message.value = `Товар "${product.name}" добавлен в корзину.`;
  } catch (error) {
    if (error.response?.status === 401) {
      message.value = 'Сессия истекла. Войдите в профиль заново.';
      await router.push('/profile');
      return;
    }

    message.value = error.response?.data?.message || 'Не удалось добавить товар в корзину.';
  }
}

onMounted(() => {
  if (route.query.search || route.query.category_id) load();
});
</script>

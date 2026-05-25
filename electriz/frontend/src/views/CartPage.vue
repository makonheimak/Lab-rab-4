<template>
  <section class="grid">
    <h1 class="section-title">Корзина</h1>
    <div v-if="!isAuth" class="card">
      <p>Для работы с корзиной нужно войти в профиль.</p>
      <router-link to="/profile" class="btn">Войти</router-link>
    </div>
    <div v-else-if="cart.length === 0" class="card">Корзина пока пустая.</div>
    <article v-for="item in cart" :key="item.id" class="card grid">
      <strong>{{ item.product?.name }}</strong>
      <span class="muted">{{ formatPrice(item.product?.price) }} за штуку</span>
      <label class="field">
        <span>Количество</span>
        <input type="number" min="1" :value="item.quantity" @change="update(item, $event.target.value)" />
      </label>
      <button class="btn danger" @click="remove(item.id)">Удалить</button>
    </article>
    <div v-if="cart.length" class="card grid">
      <strong>Итого: {{ formatPrice(total) }}</strong>
      <router-link to="/checkout" class="btn">Перейти к оплате</router-link>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const cart = computed(() => store.state.cart);
const total = computed(() => store.getters.cartTotal);
const isAuth = computed(() => store.getters.isAuth);

function formatPrice(value) {
  return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 }).format(value || 0);
}

function update(item, quantity) {
  store.dispatch('updateCartItem', { id: item.id, quantity: Number(quantity) });
}

function remove(id) {
  store.dispatch('removeCartItem', id);
}

onMounted(() => store.dispatch('loadCart'));
</script>

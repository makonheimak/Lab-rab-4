<template>
  <section class="grid">
    <h1 class="section-title">История платежей</h1>
    <div v-if="!isAuth" class="card">
      <p>Для просмотра платежей нужно войти в профиль.</p>
      <router-link to="/profile" class="btn">Войти</router-link>
    </div>
    <div v-else-if="payments.length === 0" class="card">Платежей пока нет.</div>
    <article v-for="payment in payments" :key="payment.id" class="card grid">
      <strong>Чек {{ payment.receipt_number }}</strong>
      <span class="muted">Заказ #{{ payment.order_id }} · {{ payment.provider }} · {{ payment.status }}</span>
      <span class="price">{{ formatPrice(payment.amount) }}</span>
      <button class="btn secondary" @click="openReceipt(payment.id)">Открыть PDF-чек</button>
    </article>
    <div v-if="payments.length" class="card">
      <strong>Расходы по платежам</strong>
      <div class="spend-chart">
        <div v-for="payment in payments" :key="payment.id" class="spend-row">
          <span>#{{ payment.order_id }}</span>
          <div class="spend-bar"><i :style="{ width: barWidth(payment.amount) }"></i></div>
          <span>{{ formatPrice(payment.amount) }}</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const payments = computed(() => store.state.payments);
const isAuth = computed(() => store.getters.isAuth);
const max = computed(() => Math.max(...payments.value.map((item) => Number(item.amount)), 1));

function formatPrice(value) {
  return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 }).format(value || 0);
}

function barWidth(value) {
  return `${Math.max(8, Math.round((Number(value) / max.value) * 100))}%`;
}

async function openReceipt(id) {
  const token = localStorage.getItem('token');
  const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/payments/${id}/receipt`, {
    headers: { Authorization: `Bearer ${token}` },
  });
  const blob = await response.blob();
  window.open(URL.createObjectURL(blob), '_blank');
}

onMounted(() => {
  if (isAuth.value) store.dispatch('loadPayments');
});
</script>

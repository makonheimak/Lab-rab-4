<template>
  <section class="grid">
    <h1 class="section-title">Оплата заказа</h1>
    <div class="card grid">
      <strong>Сумма: {{ formatPrice(total) }}</strong>
      <label class="field">
        <span>Адрес доставки</span>
        <textarea v-model="form.address" rows="3" placeholder="Город, улица, дом, квартира"></textarea>
      </label>
      <label class="field">
        <span>Способ оплаты</span>
        <select v-model="form.payment_method">
          <option value="card">Карта через Stripe sandbox</option>
          <option value="cash">Наличными при получении</option>
        </select>
      </label>
      <label class="field">
        <span>Комментарий</span>
        <textarea v-model="form.comment" rows="2" placeholder="Удобное время доставки"></textarea>
      </label>
      <button class="btn" @click="submit">Подтвердить заказ</button>
    </div>
    <div v-if="message" class="notice">{{ message }}</div>
    <router-link v-if="message" to="/payments" class="btn secondary">История платежей</router-link>
  </section>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
const total = computed(() => store.getters.cartTotal);
const message = ref('');
const form = reactive({ address: '', payment_method: 'card', comment: '' });

function formatPrice(value) {
  return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 }).format(value || 0);
}

async function submit() {
  if (!store.getters.isAuth) {
    router.push('/profile');
    return;
  }
  await store.dispatch('createOrder', form);
  message.value = 'Заказ создан. Для лабораторной работы создана запись оплаты Stripe sandbox и PDF-чек.';
}
</script>

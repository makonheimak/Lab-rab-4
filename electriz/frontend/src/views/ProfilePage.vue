<template>
  <section class="grid">
    <h1 class="section-title">Профиль</h1>

    <div v-if="status" class="notice">{{ status }}</div>
    <div v-if="error" class="card error">{{ error }}</div>

    <form v-if="!isAuth" class="card grid" @submit.prevent="submitLogin">
      <label class="field">
        <span>Email</span>
        <input v-model="login.email" type="email" required />
      </label>
      <label class="field">
        <span>Пароль</span>
        <input v-model="login.password" type="password" required />
      </label>
      <button class="btn" :disabled="busy">{{ busy ? 'Вход...' : 'Войти' }}</button>
      <button class="btn secondary" type="button" :disabled="busy" @click="quickRegister">Создать демо-аккаунт</button>
      <div class="social-grid">
        <a class="btn secondary" href="http://localhost:8000/api/auth/google">Google</a>
        <a class="btn secondary" href="http://localhost:8000/api/auth/github">GitHub</a>
        <a class="btn secondary" href="http://localhost:8000/api/auth/vkontakte">VK</a>
      </div>
      <p class="muted">Социальный вход работает после заполнения OAuth-ключей в `.env` backend.</p>
    </form>

    <form v-else class="card grid" @submit.prevent="save">
      <label class="field">
        <span>ФИО</span>
        <input v-model="profile.name" />
      </label>
      <label class="field">
        <span>Телефон</span>
        <input v-model="profile.phone" />
      </label>
      <label class="field">
        <span>Адрес</span>
        <textarea v-model="profile.address" rows="3"></textarea>
      </label>
      <button class="btn" :disabled="busy">{{ busy ? 'Сохранение...' : 'Сохранить' }}</button>
      <router-link to="/payments" class="btn secondary">История платежей</router-link>
      <router-link to="/chat" class="btn secondary">Чат с администратором</router-link>
      <button class="btn danger" type="button" :disabled="busy" @click="logout">Выйти</button>
    </form>

    <article v-for="order in orders" :key="order.id" class="card">
      <strong>Заказ #{{ order.id }}</strong>
      <p class="muted">Сумма: {{ formatPrice(order.total) }} · статус: {{ order.status }}</p>
    </article>
  </section>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watchEffect } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
const isAuth = computed(() => store.getters.isAuth);
const orders = computed(() => store.state.orders);
const login = reactive({ email: 'demo@example.com', password: 'password' });
const profile = reactive({ name: '', phone: '', address: '' });
const busy = ref(false);
const error = ref('');
const status = ref('');

watchEffect(() => {
  if (store.state.user) {
    profile.name = store.state.user.name || '';
    profile.phone = store.state.user.phone || '';
    profile.address = store.state.user.address || '';
  }
});

onMounted(async () => {
  if (localStorage.getItem('token') && !store.state.user) {
    try {
      await store.dispatch('loadProfile');
    } catch {
      localStorage.removeItem('token');
    }
  }
  if (store.getters.isAuth) await store.dispatch('loadOrders');
});

function formatPrice(value) {
  return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', maximumFractionDigits: 0 }).format(value || 0);
}

async function submitLogin() {
  busy.value = true;
  error.value = '';
  status.value = '';
  try {
    await store.dispatch('login', login);
    await store.dispatch('loadOrders');
    status.value = 'Вход выполнен.';
    await router.push('/products');
  } catch (e) {
    error.value = e?.response?.data?.message || 'Не удалось войти.';
  } finally {
    busy.value = false;
  }
}

async function quickRegister() {
  busy.value = true;
  error.value = '';
  try {
    await store.dispatch('register', {
      name: 'Покупатель TechNovaShop',
      email: `demo${Date.now()}@example.com`,
      password: 'password',
    });
    status.value = 'Демо-аккаунт создан.';
    await router.push('/products');
  } catch (e) {
    error.value = e?.response?.data?.message || 'Не удалось создать демо-аккаунт.';
  } finally {
    busy.value = false;
  }
}

async function save() {
  busy.value = true;
  error.value = '';
  try {
    await store.dispatch('updateProfile', profile);
    status.value = 'Профиль сохранен.';
  } catch (e) {
    error.value = e?.response?.data?.message || 'Не удалось сохранить профиль.';
  } finally {
    busy.value = false;
  }
}

async function logout() {
  busy.value = true;
  await store.dispatch('logout');
  busy.value = false;
  await router.push('/');
}
</script>

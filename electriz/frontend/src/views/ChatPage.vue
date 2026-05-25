<template>
  <section class="grid">
    <h1 class="section-title">Чат с администратором</h1>
    <div v-if="!isAuth" class="card">
      <p>Для отправки сообщения нужно войти в профиль.</p>
      <router-link to="/profile" class="btn">Войти</router-link>
    </div>
    <template v-else>
      <div class="chat-box card">
        <p v-if="messages.length === 0" class="muted">Сообщений пока нет.</p>
        <div v-for="message in messages" :key="message.id" class="chat-message" :class="message.sender">
          <strong>{{ message.sender === 'admin' ? 'Администратор' : 'Вы' }}</strong>
          <span>{{ message.message }}</span>
        </div>
      </div>
      <form class="card grid" @submit.prevent="send">
        <label class="field">
          <span>Сообщение</span>
          <textarea v-model="text" rows="3" required placeholder="Напишите вопрос по заказу или товару"></textarea>
        </label>
        <button class="btn">Отправить</button>
      </form>
    </template>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const text = ref('');
const messages = computed(() => store.state.chatMessages);
const isAuth = computed(() => store.getters.isAuth);

async function send() {
  await store.dispatch('sendChatMessage', text.value);
  text.value = '';
}

onMounted(() => {
  if (isAuth.value) store.dispatch('loadChatMessages');
});
</script>

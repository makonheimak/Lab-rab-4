import { createStore } from 'vuex';
import api from './api';

const unwrap = (response) => response.data.data || response.data;

export default createStore({
  state: {
    user: JSON.parse(localStorage.getItem('user') || 'null'),
    products: [],
    categories: [],
    news: [],
    cart: [],
    orders: [],
    payments: [],
    chatMessages: [],
    loading: false,
  },
  getters: {
    isAuth: (state) => Boolean(state.user && localStorage.getItem('token')),
    cartTotal: (state) => state.cart.reduce((sum, item) => sum + Number(item.product?.price || 0) * item.quantity, 0),
    cartCount: (state) => state.cart.reduce((sum, item) => sum + item.quantity, 0),
  },
  mutations: {
    setLoading(state, value) {
      state.loading = value;
    },
    setUser(state, user) {
      state.user = user;
      if (user) localStorage.setItem('user', JSON.stringify(user));
      else localStorage.removeItem('user');
    },
    setProducts(state, products) {
      state.products = products;
    },
    setCategories(state, categories) {
      state.categories = categories;
    },
    setNews(state, news) {
      state.news = news;
    },
    setCart(state, cart) {
      state.cart = cart;
    },
    setOrders(state, orders) {
      state.orders = orders;
    },
    setPayments(state, payments) {
      state.payments = payments;
    },
    setChatMessages(state, messages) {
      state.chatMessages = messages;
    },
  },
  actions: {
    async loadCatalog({ commit }, params = {}) {
      commit('setLoading', true);
      try {
        const [products, categories, news] = await Promise.all([
          api.get('/products', { params }),
          api.get('/categories'),
          api.get('/news'),
        ]);
        commit('setProducts', unwrap(products));
        commit('setCategories', unwrap(categories));
        commit('setNews', unwrap(news));
      } finally {
        commit('setLoading', false);
      }
    },
    async login({ commit, dispatch }, payload) {
      const response = await api.post('/login', payload);
      localStorage.setItem('token', response.data.token);
      commit('setUser', response.data.user);
      await dispatch('loadCart');
      return response.data;
    },
    async register({ commit }, payload) {
      const response = await api.post('/register', payload);
      localStorage.setItem('token', response.data.token);
      commit('setUser', response.data.user);
      commit('setCart', []);
      return response.data;
    },
    async loadProfile({ commit }) {
      const response = await api.get('/user');
      commit('setUser', unwrap(response));
    },
    async updateProfile({ commit }, payload) {
      const response = await api.put('/user', payload);
      commit('setUser', unwrap(response));
    },
    async logout({ commit }) {
      try {
        await api.post('/logout');
      } finally {
        localStorage.removeItem('token');
        commit('setUser', null);
        commit('setCart', []);
        commit('setOrders', []);
        commit('setPayments', []);
        commit('setChatMessages', []);
      }
    },
    async loadCart({ commit, getters }) {
      if (!getters.isAuth) {
        commit('setCart', []);
        return;
      }

      try {
        const response = await api.get('/cart');
        commit('setCart', unwrap(response));
      } catch (error) {
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          commit('setUser', null);
          commit('setCart', []);
          return;
        }

        throw error;
      }
    },
    async addToCart({ dispatch }, product) {
      await api.post('/cart', { product_id: product.id, quantity: 1 });
      await dispatch('loadCart');
    },
    async updateCartItem({ dispatch }, { id, quantity }) {
      await api.put(`/cart/${id}`, { quantity });
      await dispatch('loadCart');
    },
    async removeCartItem({ dispatch }, id) {
      await api.delete(`/cart/${id}`);
      await dispatch('loadCart');
    },
    async createOrder({ dispatch }, payload) {
      const response = await api.post('/orders', payload);
      await dispatch('loadCart');
      await dispatch('loadPayments');
      return unwrap(response);
    },
    async loadOrders({ commit, getters }) {
      if (!getters.isAuth) return;
      const response = await api.get('/orders');
      commit('setOrders', unwrap(response));
    },
    async loadPayments({ commit, getters }) {
      if (!getters.isAuth) return;
      const response = await api.get('/payments');
      commit('setPayments', unwrap(response));
    },
    async loadChatMessages({ commit, getters }) {
      if (!getters.isAuth) return;
      const response = await api.get('/chat/messages');
      commit('setChatMessages', unwrap(response));
    },
    async sendChatMessage({ dispatch }, message) {
      await api.post('/chat/messages', { message });
      await dispatch('loadChatMessages');
    },
  },
});

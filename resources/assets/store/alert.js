import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const alertStore = new Vuex.Store({
  state: {
    show: false,
    message: ''
  },
  mutations: {
    show (state, message) {
      state.show = true;
      state.message = message;
    },
    hidden (state) {
      state.show = false;
    }
  }
});

export default alertStore;

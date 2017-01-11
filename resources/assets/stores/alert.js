import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const alertStore = new Vuex.Store({
  state: {
    show: false
  },
  mutations: {
    show (state) {
      state.show = true;
    },
    hidden (state) {
      state.show = false;
    }
  }
});

export default alertStore;

let interval = null;

const state = {
  open: false,
  type: '',
  message: {},
};

const mutations = {
  change (state, { open = false, type = '', message = {} }) {
    state.open = open;
    state.type = type;
    state.message = message;
  },
};

const actions = {
  'alert-open' ({ commit }, { type, message, ms = 3000 }) {
    commit('change', { open: true, type, message });
    clearInterval(interval);
    interval = setInterval(() => {
      commit('change', { open: false });
      clearInterval(interval);
    }, ms);
  },
  'alert-close' ({ commit }) {
    commit('change', { open: false });
    clearInterval(interval);
  }
};

export default { state, mutations, actions };

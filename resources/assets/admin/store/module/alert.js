let interval = null;

const state = {
  open: false,
  type: '',
  message: '',
  defaultMessage: '',
};

const mutations = {
  change (state, { open = false, type = '', message = '', defaultMessage = '😢发生错误咯' }) {
    state.open = open;
    state.type = type;
    state.message = message || defaultMessage;
    state.defaultMessage = defaultMessage;
  },
};

const actions = {
  'alert-open' ({ commit }, { type, message, defaultMessage = '😢发生错误咯', ms = 3000 }) {
    commit('change', { open: true, type, message, defaultMessage });
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

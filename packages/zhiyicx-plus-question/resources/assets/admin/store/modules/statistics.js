const defaultCount = {
  question: 0,
  answer: 0,
  invitation: 0,
  public: 0,
  iamount: 0,
  pamount: 0,
  reward: 0
};

export default {

  state: {
    all: {...defaultCount},
    today: {...defaultCount},
    yesterday: {...defaultCount},
    week: {...defaultCount}
  },

  mutations: {
    statistics (state, { type, statistic }) {
      state[type] = statistic;
    }
  },

  actions: {
    statistics ({ commit }, call) {
      call(
        (type, statistic = defaultCount) => commit('statistics', { type, statistic })
      );
    }
  }

};

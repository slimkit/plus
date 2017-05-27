import { MANAGES_SET } from '../types';
import { MANAGES_GET } from '../getter-types';

const state = {
  manages: []
};

const mutations = {
  [MANAGES_SET](state, manages) {
    state.manages = manages;
  }
};

const getters = {
  [MANAGES_GET]: state => state.manages
};

const actions = {
  [MANAGES_SET]: (context, cb) => cb(
    manages => context.commit(MANAGES_SET, manages),
    context
  ),
};

export default {
  state,
  mutations,
  getters,
  actions
};

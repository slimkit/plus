// The module of The auth user.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
// --------------------------

import { USER_UPDATE, USER_DELETE } from '../types';

// Use the server in data.
const logged = window.TS.logged;
const user = window.TS.user;

// sync window data.
const syncWindow = user => {
  window.TS.logged = !!user;
  window.TS.user = user;
};

// create state.
const state = {
  logged,
  user
};

// create mutations.
const mutations = {
  // The func is update user state.
  [USER_UPDATE] (state, user) {
    state.logged = !!user;
    state.user = {
      ...state.user,
      ...user
    };
    syncWindow(state.user);
  },
  // The func is down user (delete user state).
  [USER_DELETE] (state) {
    state.logged = false;
    state.user = null;
    syncWindow(state.user);
  }
};

// Created actions.
const actions = {
  // Create update auth user func.
  [USER_UPDATE]: (context, cb) => cb(
    user => context.commit(USER_UPDATE, user),
    context
  ),
  // Create delete auth user func.
  [USER_DELETE]: (context, cb) => cb(
    () => context.commit(USER_DELETE),
    context
  )
};

// Created getters.
const getters = {
  logged: ({ logged }) => logged,
  user: ({ user }) => user
};

// create store.
const userStore = {
  state,
  mutations,
  getters,
  actions
};

export default userStore;

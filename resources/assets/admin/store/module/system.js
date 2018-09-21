import { SETTINGS_SYSTEM_UPDATE } from '../types';

const state = {};

const mutations = {
  [SETTINGS_SYSTEM_UPDATE] (state, site) {
    Object.keys(site).forEach(key => {
      state[key] = site[key];
    });
  }
};

const siteSystem = {
  state,
  mutations
};

export default siteSystem;
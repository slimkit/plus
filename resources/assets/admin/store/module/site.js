/**
 * The store of settings site info.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import {
  SETTINGS_SITE_UPDATE
} from '../types';
// import { SETTINGS_SITE } from '../getter-types';

const state = {
  name: '',
  keywords: '',
  description: '',
  icp: ''
};

const mutations = {
  [SETTINGS_SITE_UPDATE] (state, site) {
    Object.keys(site).forEach(key => {
      state[key] = site[key];
    });
  }
};

const siteStore = {
  state,
  mutations
};

export default siteStore;

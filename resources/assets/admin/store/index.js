// The file is store.
//
// @author Seven Du <shiweidu@outlook.com>
// @homepage http://medz.cn
// ---------------------------------------

import Vue from 'vue';
import Vuex from 'vuex';

// modules.
import user from './module/user';
import site from './module/site';

Vue.use(Vuex);

const modules = {
  user,
  site
};

const store = new Vuex.Store({
  modules
});

export default store;

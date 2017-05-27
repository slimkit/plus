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
import area from './module/area';
import manages from './module/manages';

Vue.use(Vuex);

const modules = {
  user,
  site,
  area,
  manages
};

const store = new Vuex.Store({
  modules
});

export default store;

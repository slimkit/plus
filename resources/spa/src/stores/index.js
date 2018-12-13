import Vue from 'vue'
import Vuex from 'vuex'
import actions from './action'
import getters from './getters'
import mutations from './mutations'

import { detectOS } from '@/util/'
import lstore from '@/plugins/lstore/'

import modules from './module/'
import EASEMOB from './easemob/index.js'

Vue.use(Vuex)

const state = {
  loginStatus: lstore.hasData('H5_ACCESS_TOKEN'),

  CONFIG: lstore.getData('BOOTSTRAPPERS') || {},

  /* 终端信息 */
  BROWSER: detectOS(),

  /* 当前登录用户信息 */
  CURRENTUSER: lstore.getData('H5_CUR_USER') || {},

  /* 当前选择的标签 临时数据 */
  CUR_SELECTED_TAGS: [],

  // 定位信息
  POSITION: lstore.getData('H5_CURRENT_POSITION') || {},

  /**
   * 用户信息
   */
  USERS: lstore.getData('H5_USERS') || {},

  // 用户认证信息
  USER_VERIFY: {
    category: {},
    data: {},
    files: [],
  },

  // 文章点赞、打赏信息
  article: {
    likers: [],
    rewarders: [],
  },

}

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  // modules
  modules: {
    ...modules,
    EASEMOB,
  },
})

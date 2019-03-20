import * as api from '@/api/user'
import lstore from '@/plugins/lstore/lstore'

const state = {
  recommend: lstore.getData('plus_user_recommend') || [], // 推荐用户
  friends: [], // 好友
}

const TYPES = {
  SAVE_RECOMMEND: 'SAVE_RECOMMEND',
  SAVE_FRIENDS: 'SAVE_FRIENDS',
}

const mutations = {
  [TYPES.SAVE_RECOMMEND] (state, users) {
    state.recommend = users
    lstore.setData('plus_user_recommend', users)
  },
  [TYPES.SAVE_FRIENDS] (state, users) {
    state.friends = users
  },
}

const actions = {
  async getUserList ({ commit }, payload) {
    if (!payload.id) return Promise.resolve([])
    const { data } = await api.getUserList(payload)
    commit('SAVE_USER', data, { root: true })
    return data
  },

  async getRecommendUsers ({ commit }) {
    const { data } = await api.findUserByType('recommends')
    commit(TYPES.SAVE_RECOMMEND, data)
  },

  /**
   * 获取好友列表
   *
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   * @returns {boolean} noMore
   */
  async getUserFriends ({ commit }, payload) {
    const { data } = await api.getUserFriends(payload)
    commit(TYPES.SAVE_FRIENDS, data)
    return data.length < 15
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}

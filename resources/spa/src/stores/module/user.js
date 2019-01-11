import * as api from '@/api/user'
import lstore from '@/plugins/lstore/lstore'

const state = {
  recommend: lstore.getData('plus_user_recommend') || [], // 推荐用户
}

const TYPES = {
  SAVE_RECOMMEND: 'SAVE_RECOMMEND',
}

const mutations = {
  [TYPES.SAVE_RECOMMEND] (state, users) {
    state.recommend = users
    lstore.setData('plus_user_recommend', users)
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
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
}

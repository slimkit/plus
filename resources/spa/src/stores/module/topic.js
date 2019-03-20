import * as api from '@/api/topic'
import { limit } from '@/api'

export const TYPES = {
  SAVE_TOPIC_LIST: 'SAVE_TOPIC_LIST',
}

const state = {
  hotList: [],
  newList: [],
}

const getters = {}

const mutations = {
  [TYPES.SAVE_TOPIC_LIST] (state, payload) {
    let { list, type, reset = false } = payload
    type = type === 'hot' ? 'hotList' : 'newList'
    if (reset) {
      state[type] = list
    } else {
      state[type].push(...list)
    }
  },
}

const actions = {
  /**
   * 获取动态列表
   *
   * @author mutoe <mutoe@foxmail.com>
   * @returns {boolean} noMore?
   */
  async fetchTopicList ({ commit }, payload) {
    const { params = {}, reset, type } = payload
    if (type === 'hot') params.only = 'hot'
    const { data: list = [] } = await api.getTopicList(params)
    commit(TYPES.SAVE_TOPIC_LIST, { list, type, reset })
    return list.length < limit
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}

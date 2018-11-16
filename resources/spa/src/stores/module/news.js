import * as api from '@/api/news'

const state = {}

const getters = {}

const mutations = {}

const actions = {
  /**
   * 获取资讯列表
   * @author mutoe <mutoe@foxmail.com>
   * @returns {api.NewsObject[]}
   */
  async getNewsList (state, params) {
    Object.assign(params, { limit: 10 })
    const { data } = await api.getNewsList(params)
    return data || []
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}

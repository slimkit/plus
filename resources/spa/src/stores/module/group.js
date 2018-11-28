import * as api from '@/api/group'
import lstore from '@/plugins/lstore/lstore'

export const TYPES = {
  SAVE_GROUP: 'SAVE_GROUP',
  SAVE_GROUP_CATES: 'SAVE_GROUP_CATES',
}

const state = {
  categories: lstore.getData('GROUP_CATES') || [], // 圈子分类
  current: {}, // 当前查看的圈子
  protocol: '',
}

const getters = {}

const mutations = {
  [TYPES.SAVE_GROUP] (state, group) {
    state.current = group
  },

  // 保存圈子分类列表
  [TYPES.SAVE_GROUP_CATES] (state, cates) {
    state.categories = cates
    lstore.setData('GROUP_CATES', cates)
  },
}

const actions = {
  /**
   * 获取我加入的圈子
   * @author mutoe <mutoe@foxmail.com>
   * @returns {api.GroupObject[]}
   */
  async getMyGroups (store, payload) {
    const { data } = await api.getMyGroups(payload)
    return data
  },

  /**
   * 获取圈子
   * @author mutoe <mutoe@foxmail.com>
   * @returns
   */
  async getGroups (store, payload) {
    const { type, limit, offset = 0 } = payload

    const { data } = ['recommend', 'random'].includes(type)
      ? await api.getRecommendGroups({
        type: type === 'random' ? 'random' : undefined,
        limit,
        offset,
      })
      : []
    return data
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}

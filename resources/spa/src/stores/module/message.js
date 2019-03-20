import _ from 'lodash'
import * as api from '@/api/message'
import * as groupApi from '@/api/group'

export default {
  namespaced: true,

  state: {
    system: {
      first: {
        data: {},
      },
    },
    comment: {},
    like: {},
    follow: {},
    user: {},
    audits: {
      feedCommentPinned: [],
      newsCommentPinned: [],
      postPinned: [],
      postCommentPinned: [],
      groupJoined: [],
    },
  },

  getters: {
    unreadMessage: state => Object.keys(_.pick(state, ['comment', 'like', 'system']))
      .reduce((sum, key) => sum + ((state[key]).badge || 0), 0),

    unreadAudits: state => Object.keys(state.audits)
      .reduce((sum, key) => sum + _.filter(state.audits[key], item => item.status === 0).length, 0),
  },

  mutations: {
    SAVE_NOTIFICATIONS (state, notifications) {
      for (const key in _.pick(state, ['comment', 'like', 'system'])) {
        state[key] = notifications[key]
      }
    },
    SAVE_AUDIT (state, { type, list, append = false }) {
      if (append) {
        state.audits[type].push(...list)
      } else {
        state.audits[type] = list
      }
    },
    SAVE_USER (state, user) {
      state.user = user
    },
  },

  actions: {
    async getNotificationStatistics ({ commit }) {
      const { data } = await api.getNotificationStatistics()
      commit('SAVE_NOTIFICATIONS', data)
    },

    async getUnreadCount ({ commit }) {
      const { data } = await api.getUnreadCounts()
      commit('SAVE_USER', data)
    },

    async getGroupJoinedList ({ commit }, payload = {}) {
      const { data: list } = await groupApi.getGroupAudits(payload)
      commit('SAVE_AUDIT', { type: 'groupJoined', list })
    },

    async getAllUnreadCount ({ dispatch }) {
      dispatch('getNotificationStatistics')
      dispatch('getUnreadCount')
    },
  },
}

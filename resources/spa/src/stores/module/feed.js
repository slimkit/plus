import * as api from '@/api/feeds'
import lstore from '@/plugins/lstore/lstore'
import Vue from 'vue'

export const TYPES = {
  SAVE_FEED_LIST: 'SAVE_FEED_LIST',
  SAVE_PINNED_LIST: 'SAVE_PINNED_LIST',
  UPDATE_SINGLE_FEED: 'UPDATE_SINGLE_FEED',
}

const state = {
  list: {
    hot: lstore.getData('FEED_LIST_HOT') || [], // 热门动态列表
    new: lstore.getData('FEED_LIST_NEW') || [], // 最新动态
    follow: lstore.getData('FEED_LIST_FOLLOW') || [], // 关注列表
    pinned: lstore.getData('FEED_LIST_PINNED') || [], // 置顶列表
  },
}

const getters = {
  pinned (state) {
    return state.list.pinned
  },
  hot (state) {
    return state.list.hot
  },
  new (state) {
    return state.list.new
  },
  follow (state) {
    return state.list.follow
  },
}

const mutations = {
  [TYPES.SAVE_FEED_LIST] (state, payload) {
    const { type, data, refresh = false } = payload
    const list = refresh ? data : [...state.list[type], ...data]
    state.list[type] = list
    lstore.setData(`FEED_LIST_${type.toUpperCase()}`, list)
  },

  [TYPES.UPDATE_SINGLE_FEED] (state, payload) {
    const { data, type, index } = payload
    const { list: { [type]: feedList = [] } = {} } = state
    Vue.set(feedList, index, data)
  },

  [TYPES.SAVE_PINNED_LIST] (state, payload) {
    const { list } = payload
    state.list.pinned = list
    lstore.setData('FEED_LIST_PINNED', list)
  },
}

const actions = {
  /**
   * 更新单条动态
   * @param commit
   * @param state
   * @param payload
   */
  updateSingleFeed ({ commit, state }, payload) {
    const { list: { pinned: pinnedFeeds, new: newFeeds, hot: hotFeeds, follow: followFeeds } } = state
    const { id = 0, data } = payload
    let pinnedFeed = pinnedFeeds.find(feed => (feed.id === id))
    if (pinnedFeed >= 0) {
      const pinned = Object.assign({}, { ...pinnedFeed, ...data })
      commit(TYPES.UPDATE_SINGLE_FEED, { feed: pinned, index: pinnedFeed, type: 'pinned' })
    }
    let newFeed = newFeeds.findIndex(feed => (feed.id === id))
    if (newFeed >= 0) {
      const feed = Object.assign({}, { ...newFeeds[newFeed], ...data })
      commit(TYPES.UPDATE_SINGLE_FEED, { type: 'new', data: feed, index: newFeed })
    }
    let hotFeed = hotFeeds.findIndex(feed => (feed.id === id))
    if (hotFeed >= 0) {
      const hot = Object.assign({}, { ...hotFeeds[hotFeed], ...data })
      commit(TYPES.UPDATE_SINGLE_FEED, { type: 'hot', data: hot, index: hotFeed })
    }
    let followFeed = followFeeds.findIndex(feed => (feed.id === id))
    if (followFeed >= 0) {
      const follow = Object.assign({}, { ...followFeeds[followFeed], ...data })
      commit(TYPES.UPDATE_SINGLE_FEED, { type: 'follow', data: follow, index: followFeed })
    }
  },
  /**
   * 获取最新动态列表
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   */
  async getNewFeeds ({ commit }, payload) {
    const { after, refresh = false } = payload
    const { data } = await api.getFeeds({ type: 'new', after })
    const { feeds = [], pinned = [] } = data
    commit(TYPES.SAVE_PINNED_LIST, { list: pinned })
    commit(TYPES.SAVE_FEED_LIST, { type: 'new', data: feeds, refresh })
    return feeds
  },
  /**
   * 获取热门动态列表
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   */
  async getHotFeeds ({ commit }, payload) {
    const { after, refresh = false } = payload
    const { data } = await api.getFeeds({ type: 'hot', hot: after })
    const { feeds = [], pinned = [] } = data
    commit(TYPES.SAVE_PINNED_LIST, { list: pinned })
    commit(TYPES.SAVE_FEED_LIST, { type: 'hot', data: feeds, refresh })
    return feeds
  },
  /**
   * 获取关注动态列表
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   */
  async getFollowFeeds ({ commit }, payload) {
    const { after, refresh = false } = payload
    const { data } = await api.getFeeds({ type: 'follow', after })
    const { feeds = [] } = data
    commit(TYPES.SAVE_FEED_LIST, { type: 'follow', data: feeds, refresh })
    return feeds
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}

import * as api from "@/api/feeds";
import lstore from "@/plugins/lstore/lstore";

export const TYPES = {
  SAVE_FEED_LIST: "SAVE_FEED_LIST",
  SAVE_PINNED_LIST: "SAVE_PINNED_LIST"
};

const state = {
  list: {
    hot: lstore.getData("FEED_LIST_HOT") || [], // 热门动态列表
    new: lstore.getData("FEED_LIST_NEW") || [], // 最新动态
    follow: lstore.getData("FEED_LIST_FOLLOW") || [], // 关注列表
    pinned: lstore.getData("FEED_LIST_PINNED") || [] // 置顶列表
  }
};

const getters = {
  pinned(state) {
    return state.list.pinned;
  },
  hot(state) {
    return state.list.hot;
  },
  new(state) {
    return state.list.new;
  },
  follow(state) {
    return state.list.follow;
  }
};

const mutations = {
  [TYPES.SAVE_FEED_LIST](state, payload) {
    const { type, data, refresh = false } = payload;
    const list = refresh ? data : [...state.list[type], ...data];
    state.list[type] = list;
    lstore.setData(`FEED_LIST_${type.toUpperCase()}`, list);
  },

  [TYPES.SAVE_PINNED_LIST](state, payload) {
    const { list } = payload;
    state.list.pinned = list;
    lstore.setData("FEED_LIST_PINNED", list);
  }
};

const actions = {
  /**
   * 获取最新动态列表
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   */
  async getNewFeeds({ commit }, payload) {
    const { after, refresh = false } = payload;
    const { data } = await api.getFeeds({ type: "new", after });
    const { feeds = [], pinned = [] } = data;
    commit(TYPES.SAVE_PINNED_LIST, { list: pinned });
    commit(TYPES.SAVE_FEED_LIST, { type: "new", data: feeds, refresh });
    return feeds;
  },
  /**
   * 获取热门动态列表
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   */
  async getHotFeeds({ commit }, payload) {
    const { after, refresh = false } = payload;
    const { data } = await api.getFeeds({ type: "hot", hot: after });
    const { feeds = [], pinned = [] } = data;
    commit(TYPES.SAVE_PINNED_LIST, { list: pinned });
    commit(TYPES.SAVE_FEED_LIST, { type: "hot", data: feeds, refresh });
    return feeds;
  },
  /**
   * 获取关注动态列表
   * @author mutoe <mutoe@foxmail.com>
   * @param {*} payload
   */
  async getFollowFeeds({ commit }, payload) {
    const { after, refresh = false } = payload;
    const { data } = await api.getFeeds({ type: "follow", after });
    const { feeds = [] } = data;
    commit(TYPES.SAVE_FEED_LIST, { type: "follow", data: feeds, refresh });
    return feeds;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};

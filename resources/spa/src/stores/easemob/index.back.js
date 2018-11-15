//import _ from "lodash";

import Api from "@/api/api.js";
import WebIM from "@/vendor/easemob/index.js";
import WebIMDB from "@/vendor/easemob/WebIMDB.js";

//import lstore from "@/plugins/lstore/lstore.js";

import { getUserInfoById } from "@/api/user.js";

const state = {
  status: 0 /* 0: 掉线, 1: 已连接 */,
  chatList: [],

  currentChatRoom: null,
  currentChatRoomMessages: [],
  /**
   * 环信 连接状态
   * @type {Boolean}
   */
  connecting: "连接中..."
};

const getters = {
  /**
   * 消息未读数
   * @author jsonleex <jsonlseex@163.com>
   * @return {Number}
   */
  hasUnreadChat(state) {
    let count = 0;
    state.chatList.forEach(chat => {
      count += chat.unreadCount;
    });
    return count;
  }
};

const actions = {
  async EASEMOB_OPEN({ state, commit }, uid) {
    if (state.status === 0 && !WebIM.conn.isOpened()) {
      const {
        data: { im_pwd_hash: token } = { data: { im_pwd_hash: "" } }
      } = await Api.get("easemob/password", {
        validateStatus: s => s === 201
      });
      commit("EASEMOB_STATUS", 0);
      commit("EASEMOB_CONNECTING", "连接中...");
      WebIM.conn.open({
        user: uid,
        pwd: token,
        apiUrl: WebIM.config.apiURL,
        appKey: WebIM.config.appkey
      });
    }
    return Promise.resolve(true);
  },

  initChats({ commit }) {
    WebIMDB.getChats().then(chats => {
      commit("initChats", chats);
    });
  },
  /**
   * 新增文本消息
   * @author jsonleex <jsonlseex@163.com>
   */
  addTextMessage({ state, rootState, dispatch }, message) {
    const UID = rootState.CURRENTUSER.id;

    const { type, from, to } = message;
    const bySelef = from == UID;
    getUserInfoById(from).then(user => {
      WebIMDB.addMessage(
        { ...message, time: +new Date(), bySelef, user },
        type === "chat"
          ? state.currentChatRoom.from != from
          : state.currentChatRoom.from != to
      ).then((/*res*/) => {
        /**
         * 添加消息惠后, 触发页面更新
         */
        dispatch("initChats");
        dispatch("getCurrentChatMessages");
      });
    });
  },

  setCurrentChatRoom({ /*commit, */ dispatch /*, state*/ }, chatRoom) {
    dispatch("getCurrentChatMessages", chatRoom);
  },

  getCurrentChatMessages({ commit, state }, chatRoom) {
    const room = chatRoom || state.currentChatRoom;
    chatRoom && chatRoom.from && commit("setCurrentChatRoom", chatRoom);
    room &&
      room.from &&
      WebIMDB.fetchMessage(room.from, room.type).then(data => {
        commit("setCurrentMessages", data);
      });
  }
};

const mutations = {
  EASEMOB_STATUS(state, status) {
    state.status = status;
  },
  EASEMOB_CONNECTING(state, status) {
    state.connecting = status;
  },
  initChats(state, list) {
    state.chatList = list;
  },
  setCurrentChatRoom(state, chatRoom = null) {
    state.currentChatRoom = chatRoom;
  },
  setCurrentMessages(state, messages) {
    state.currentChatRoomMessages = messages;
  }
};

export default { state, getters, actions, mutations };

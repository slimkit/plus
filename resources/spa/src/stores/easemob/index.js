import AppDB from '@/vendor/easemob/AppDB.js'

const state = {
  status: 0,
  chatRooms: [],
}

const actions = {
  initChatRooms ({ commit, rootState: { CURRENTUSER } }) {
    return new Promise((resolve, reject) => {
      AppDB.init(CURRENTUSER.id)
        .GetChatRooms()
        .then(rooms => {
          commit('initChatRooms', rooms)
          resolve(rooms)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
}

const mutations = {
  initChatRooms (state, rooms) {
    state.chatRooms = rooms
  },
}

const getters = {
  unreadChat ({ chatRooms }) {
    return chatRooms.some(room => room.unreadCount)
  },
  getRoomById: ({ chatRooms }) => id => {
    return chatRooms.filter(item => item.id === id)
  },
}

export default { state, getters, actions, mutations }

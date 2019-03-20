import { limit } from '@/api'
import dataBase from '@/util/database.js'
export default {
  // 初始化聊天列表
  async INIT_MESSAGE ({ commit, rootState }) {
    const ROOMS = {}
    const mid = rootState.CURRENTUSER.id
    const res = await dataBase.transaction('rw?', 'room', () => {
      return dataBase.room
        .where('del')
        .notEqual(1)
        .and(room => {
          return room.mid === mid
        })
        .desc()
        .limit(50)
        .sortBy('last_message_time')
    })
    res.forEach(room => {
      ROOMS[`room_${room.id}`] = room
    })
    commit('UPDATE_ROOMS', ROOMS)
  },

  // 初始化聊天消息列表
  async INIT_ROOMS ({ dispatch, commit, state }) {
    await dispatch('INIT_MESSAGE')
    const ids = Object.values(state.ROOMS).map(r => r.id)
    const MESSAGES = {}
    for (let i = 0; i < ids.length; i++) {
      const message = await dataBase.transaction('rw', 'message', () => {
        return dataBase.message
          .where('cid')
          .equals(ids[i])
          .desc()
          .limit(limit)
          .sortBy('time')
      })
      MESSAGES[`room_${ids[i]}`] = message
    }
    commit('UPDATE_MESSAGE', MESSAGES)
  },

  // 获取房间/新建
  async GET_ROOM_BY_UID_MID ({ dispatch }, { uid, mid }) {
    const room = await dataBase.room
      .where('[mid+uid]')
      .equals([mid, uid])
      .first()
    if (typeof room === 'undefined') {
      await dataBase.room.add({
        mid,
        uid,
        del: 0,
        group: 0,
        type: 'chat',
        title: '' + mid + uid,
      })
      const result = await dispatch('GET_ROOM_BY_UID_MID', { uid, mid })
      return result
    }
    return room
  },

  // 存储单条消息
  async PUSH_NEW_MESSAGE ({ dispatch }, msg) {
    // 1. get cid by uid+mid
    const room = await dispatch('GET_ROOM_BY_UID_MID', {
      uid: msg.uid,
      mid: msg.mid,
    })

    // 2. 完善房间数据结构
    room.last_message_time = msg.time
    room.last_message_txt = msg.txt
    room.name = msg.name

    // 3. 完善消息数据结构
    msg.cid = room.id
    msg.id = msg.easemob_mid || msg.id
    // 4. 更新存储
    await dataBase.room.update(room.id, room)
    await dataBase.message.add(msg)

    // 5. 更新 vuex
    dispatch('INIT_ROOMS')
  },
}

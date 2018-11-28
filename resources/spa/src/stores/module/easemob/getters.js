export default {
  // 获取所有vuex中的房间
  getRooms: state => {
    const { ROOMS } = state
    return ROOMS
  },
  // 通过cid获取房间存储消息
  getMegsByCid: state => cid => {
    return state[cid].messages
  },
}

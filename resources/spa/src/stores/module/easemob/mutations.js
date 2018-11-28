export default {
  // 追加单个房间
  PUSH_ROOM (state, room) {
    let rooms = state.ROOMS
    rooms = { ...rooms, ...{ [`room_${room.id}`]: room } }
    state.ROOMS = { ...rooms }
  },
  UPDATE_ROOMS (state, ROOMS) {
    state.ROOMS = ROOMS
  },
  UPDATE_MESSAGE (state, MESSAGES) {
    state.MESSAGES = MESSAGES
  },
}

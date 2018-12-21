export default {
  hasUnreadMessage (state) {
    const unread = state.NEW_UNREAD_COUNT
    unread.at = 0 // TODO: at 功能尚未开发
    return Object.values(unread).reduce((sum, item) => sum + item, 0)
  },
}

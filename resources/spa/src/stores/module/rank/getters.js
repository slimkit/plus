export default {
  getUsersByType: state => type => {
    return state[type]
  },
}

export default {
  getUserById: ({ USERS }) => id => {
    return USERS[`user_${id}`] || {}
  },
}

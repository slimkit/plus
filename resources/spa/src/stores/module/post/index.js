export default {
  state: {
    composeText: '',
    composePhoto: [],
  },
  mutations: {
    UPDATE_compose (state, txt) {
      state.composeText = txt || ''
    },
    UPDATE_composePhoto (state, pics) {
      pics && pics.length
        ? (state.composePhoto = pics.map(pic => {
          const obj = {}
          const { id, amountType, amount } = pic
          id && (obj.id = id)
          amount && (obj.amount = amount)
          amountType && (obj.type = amountType)
          return obj
        }))
        : (state.composePhoto = [])
    },
  },
  actions: {
    updateCompose ({ commit }, txt) {
      return commit('UPDATE_compose', txt)
    },
    updateComposePhoto ({ commit }, pics) {
      return commit('UPDATE_composePhoto', pics)
    },
  },
  getters: {
    compose (state) {
      return state.composeText
    },
    composePhoto (state) {
      return state.composePhoto
    },
  },
}

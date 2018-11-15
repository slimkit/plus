import * as api from "@/api/user";

const actions = {
  async getUserList({ commit }, payload) {
    if (!payload.id) return Promise.resolve([]);
    const { data } = await api.getUserList(payload);
    for (const user of data) {
      commit("SAVE_USER", user, { root: true });
    }
    return data;
  }
};

export default {
  namespaced: true,
  actions
};

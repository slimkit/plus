import alertStore from '../stores/alert';

export const show = () => alertStore.commit('show');
export const hidden = () => alertStore.commit('hidden');

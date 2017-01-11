import alertStore from '../stores/alert';

export const show = message => alertStore.commit('show', message);
export const hidden = () => alertStore.commit('hidden');

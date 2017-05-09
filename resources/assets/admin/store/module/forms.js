/**
 * Admin form's store.
 */
import { FORM_ALL } from '../getter-types';
import { FORM_SET } from '../types';
import lodash from 'lodash';

const state = {
  forms: {}
};

const mutations = {
  [FORM_SET](state, forms) {
    state.forms = forms;
  }
};

const getters = {
  [FORM_ALL]: state => state.forms,
};

const actions = {
  [FORM_SET]: (context, cb) => cb(
    forms => {
      // context.commit(SETTINGS_AREA_APPEND, item)
      let newForms = {};
      lodash.forEach(forms, item => {
        const { data, form, name, save, type } = item;
        const [ rootName, childrenName ] = name.split('/');

        if (! newForms[rootName]) {
          newForms[rootName] = {};
        }

        newForms[rootName][childrenName] = {
          data, form, save, type
        };

        context.commit(FORM_SET, newForms);
      });
    },
    context
  ),
};

export default {
  actions,
  state,
  getters,
  mutations,
};

/**
 * The store of settings area margin.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import { SETTINGS_AREA_APPEND, SETTINGS_AREA_CHANGEITEM } from '../types';
import { SETTINGS_AREA } from '../getter-types';

const state = {
  list: []
};

const mutations = {
  [SETTINGS_AREA_APPEND] (state, item) {
    state.list = [
      ...state.list,
      item
    ];
  },
  [SETTINGS_AREA_CHANGEITEM] (state, item) {
    const list = state;
    state.list = list.map((_item) => {
      if (_item.id === item.id) {
        return {
          ..._item,
          ...item
        };
      }

      return _item;
    });
  }
};

const getters = {
  [SETTINGS_AREA]: state => state.list
};

const actions = {
  [SETTINGS_AREA_APPEND]: (context, cb) => cb(
    item => context.commit(SETTINGS_AREA_APPEND, item),
    context
  ),
  [SETTINGS_AREA_CHANGEITEM]: (context, cb) => cb(
    item => context.commit(SETTINGS_AREA_CHANGEITEM, item),
    context
  )
};

const areaStore = {
  state,
  mutations,
  getters,
  actions
};

export default areaStore;

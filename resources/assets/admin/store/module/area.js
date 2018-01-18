/**
 * The store of settings area margin.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import { SETTINGS_AREA_APPEND, SETTINGS_AREA_CHANGEITEM, SETTINGS_AREA_CHANGE, SETTINGS_AREA_DELETE } from '../types';
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
    const list = state.list;
    state.list = list.map((_item) => {
      if (_item.id === item.id) {
        return {
          ..._item,
          ...item
        };
      }

      return _item;
    });
  },
  [SETTINGS_AREA_CHANGE] (state, list) {
    state.list = [...list];
  },
  [SETTINGS_AREA_DELETE] (state, id) {
    let list = [];
    state.list.forEach(area => {
      if (area.id !== id) {
        list.push(area);
      }
    });

    state.list = list;
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
  ),
  [SETTINGS_AREA_CHANGE]: (context, cb) => cb(
    list => context.commit(SETTINGS_AREA_CHANGE, list),
    context
  ),
  [SETTINGS_AREA_DELETE]: (context, cb) => cb(
    id => context.commit(SETTINGS_AREA_DELETE, id),
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

const defaultState = {
  type: 'success',
  role: 'alert',
  closeButton: false,
  timeout: {
    is: false,
    ms: 3000,
  },
  open: false,
  node: '',
};

// let alertStore = {
//   debug: true,

//   state: Object.assign({}, defaultState),

//   alert(settings = {}) {
//     alertStore.state = Object.assign({}, defaultState, settings);
//     console.log(alertStore);
//   },

//   // alert: (settings = {}) => {
//   //   console.log(this);
//   //   // this.state = Object.assign({}, defaultState, settings);
//   // },

//   alertClose: () => Object.assign({}, defaultState, {
//     opne: false,
//   }),

// };

// export default alertStore;

import Vuex from 'vuex';

const alertStore = new Vuex.Store({
  state: defaultState,

  getters: {
    doneTypeName: (state, getters) => {

    },
  },

});

export default alertStore;

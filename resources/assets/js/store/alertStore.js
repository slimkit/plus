let alertStore = {
  debug: true,

  state: {
    type: 'success',
    role: 'alert',
    closeButton: false,
    timeout: {
      is: false,
      ms: 3000,
    },
    open: false,
    node: '',
  },

  alert({open = false, node = '', ...settings}) {
    // todo
  },

  alertClose() {
    // todo
  }

};

export default alertStore;
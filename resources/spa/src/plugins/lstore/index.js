import lstore from './lstore.js'
lstore.install = Vue => {
  window.$lstore = Vue.prototype.$lstore = lstore
}
export default lstore

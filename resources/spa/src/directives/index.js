import gifPlay from './gifPlay'

const autosize = el => {
  const originalHeight = el.style.height
  el.style.height = ''
  let endHeight = el.scrollHeight
  if (el.scrollHeight === 0) {
    el.style.height = originalHeight
    return
  }
  el.style.height = endHeight + 'px'
}

export default {
  gifPlay,
  txtautosize: {
    inserted (el) {
      autosize(el)
    },
    update (el) {
      autosize(el)
    },
    unbind () {},
  },
}

// 播放 GIF
import _ from 'lodash'
import bus from '@/bus'

const gif = bus.$data.gif

const options = {
  threshold: [0, 0.3, 0.5, 0.7, 1],
}

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting && entry.intersectionRatio > 0.75) {
      if (entry.target.querySelectorAll('.gif:not(.need-pay)').length) {
        entry.target.classList.add('playing-feed')
      }
    } else {
      entry.target.classList.remove('playing-feed')
    }
  })
}, options)

function onInserted (el, binding) {
  for (let i = 0; i < el.children.length; i++) {
    const child = el.children[i]
    observer.observe(child)
  }
}

const onScroll = _.throttle(() => {
  const playEl = document.querySelector('.playing-feed') || {}

  const { feedId } = playEl.dataset || {}
  if (feedId && feedId !== gif.feedId) {
    gif.feedId = feedId
    const playingEls = playEl.querySelectorAll('[data-gif-duration]:not(.need-pay)')
    gif.elList = playingEls
  }
}, 200)

function onBind () {
  window.addEventListener('scroll', onScroll)
}

function onUnbind () {
  window.removeEventListener('scroll', onScroll)
}

export default {
  bind: onBind,
  inserted: onInserted,
  update: onInserted,
  unbind: onUnbind,
}

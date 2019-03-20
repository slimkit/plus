/* eslint-disable no-extend-native */
import bus from '@/bus'

// IntersectionObserver polyfill for iOS
import './intersectionObserver'

// Promise.prototype.finally polyfill for iOS
if (typeof Promise.prototype.finally !== 'function') {
  Promise.prototype.finally = function (callback) {
    let P = this.constructor
    return this.then(
      value => P.resolve(callback()).then(() => value),
      reason =>
        P.resolve(callback()).then(() => {
          throw reason
        })
    )
  }
}

// 给 Div Element 增加 播放和停止的功能（用于播放GIF图像）
if (!HTMLDivElement.prototype.play) {
  HTMLDivElement.prototype.play = function () {
    if (!this.dataset.gifBlobUrl) {
      return console.warn('节点不含有 GIF 信息, 无法播放', this) // eslint-disable-line no-console
    }
    this.parentElement.classList.add('playing')
    this.style.backgroundImage = `url(${this.dataset.gifBlobUrl})`

    bus.$data.gif.timer = setTimeout(() => {
      this.stop()
      bus.$data.gif.index++
    }, +this.dataset.gifDuration)
  }
  HTMLDivElement.prototype.stop = function () {
    if (!this.dataset.gifBlobUrl) {
      return console.warn('节点不含有 GIF 信息, 无法停止', this) // eslint-disable-line no-console
    }
    this.parentElement.classList.remove('playing')
    this.style.backgroundImage = `url(${this.dataset.gifFirstFrame})`
  }
}

/**
 * 图片懒加载 vue 指令
 *     @author jsonleex <jsonlseex@163.com>
 */

function test () {}
test()
export default (Vue, options = {}) => {
  // 数组item remove方法
  /* eslint-disable no-extend-native */
  if (!Array.prototype.remove) {
    Array.prototype.remove = function (item) {
      if (!this.length) return
      var index = this.indexOf(item)
      if (index > -1) {
        this.splice(index, 1)
        return this
      }
    }
  }
  // 自定义配置
  const { offset, placeholeder } = options
  // 默认参数
  const settings = {
    offset: offset || 70,
    lazyLoad: false,
    default:
      placeholeder ||
      'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQQAAAEEAQMAAAD0xthJAAAAA1BMVEXMzMzKUkQnAAAAH0lEQVRo3u3BMQEAAADCIPuntsROYAAAAAAAAAAAEDgiiAAB2NF59gAAAABJRU5ErkJggg==',
  }
  const listenList = []
  const imageCatcheList = []

  const isAlredyLoad = imageSrc => {
    if (imageCatcheList.indexOf(imageSrc) > -1) {
      return true
    } else {
      return false
    }
  }
  // 检测图片是否可以加载，如果可以则进行加载
  const isCanShow = item => {
    const ele = item.ele
    const src = item.src
    // const windowHeight = window.innerHight

    // 图片距离页面顶部的距离
    const top = ele.getBoundingClientRect().top

    // offset 默认 70
    if (top + settings.offset < window.innerHeight) {
      const image = new Image()
      image.src = src
      image.onload = () => {
        ele.src = src
        const s = image.naturalHeight / image.naturalWidth
        if (s > 3 || s < 0.3) {
          ele.parentNode.classList.add('long')
        }
        imageCatcheList.push(src)
        listenList.remove(item)
      }
      image.onerror = () => {
        ele.src = settings.default
        listenList.remove(item)
      }
      return true
    } else {
      return false
    }
  }

  const onListenScroll = () => {
    window.addEventListener('scroll', () => {
      const length = listenList.length
      for (let i = 0; i < length; i++) {
        isCanShow(listenList[i])
      }
    })
  }
  // Vue 指令最终的方法
  const addListener = (ele, binding) => {
    // 绑定的图片地址
    const imageSrc = binding.value
    // 如果已经加载过，则无需重新加载，直接将src赋值
    if (isAlredyLoad(imageSrc)) {
      ele.src = imageSrc
      return false
    }
    const item = {
      ele: ele,
      src: imageSrc,
    }
    // 图片显示默认的图片
    ele.src = settings.default
    // 再看看是否可以显示此图片
    if (isCanShow(item)) {
      return
    }
    // 否则将图片地址和元素均放入监听的lisenList里
    listenList.push(item)

    // 然后开始监听页面scroll事件
    onListenScroll()
  }

  Vue.directive('lazyload', {
    inserted: addListener,
    updated: addListener,
  })
}

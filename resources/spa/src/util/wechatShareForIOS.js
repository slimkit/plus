import { parse, stringify } from 'querystring'

/**
 * 为 ios 下的微信准备的刷新方法
 * 因为在 ios 的微信下页面跳转后分享出去的链接地址还是原来落地页的地址
 * 所以在每次地址跳转后使用 reload 方法重新加载地址
 * refer https://github.com/slimkit/plus-small-screen-client/issues/579
 *
 * !! 如果微信端修复了这个问题请移除此文件并全局搜索调用此文件的地方删除之 !!
 *
 * @author mutoe <mutoe@foxmail.com>
 */

/**
 * 浏览器标准刷新方法
 * @param num 值为0，为了兼容vue-router的API
 */
const go = num => {
  if (num === 0) {
    location.reload(true)
  }
}

/**
 * 判断是否在IOS中
 */
const isIOS = () => /(iPhone|iPad|iPod)/i.test(navigator.userAgent)

/**
 * 获取时间戳
 */
const getNowTimeStamp = () => `${Date.now()}`

/**
 * 处理url，获得更新时间戳之后的url
 */
const getUrl = () => {
  let { origin, pathname, search, hash } = location
  if (search) {
    const searchObj = parse(search.slice(1, search.length))
    searchObj.jxytime = getNowTimeStamp()
    search = `?${stringify(searchObj)}`
  } else {
    search = `?jxytime=${getNowTimeStamp()}`
  }
  return `${origin}${pathname}${search}${hash}`
}

/**
 * 防止缓存，强制刷新
 * @param router vue-router实例
 */
function reload (router = { go: go }) {
  if (isIOS()) {
    // 为了兼容IOS
    location.replace(getUrl())
  } else {
    router.go(0)
  }
}

export default reload

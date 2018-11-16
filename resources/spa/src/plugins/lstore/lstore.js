/**
 * lstore
 *
 * 轻量级 localStorage 封装
 * 支持 sessionStorage
 * 支持 Cookie
 *
 * @author jsonleex <jsonlseex@163.com>
 */
export default {
  isSupported: (() => {
    try {
      return (
        window.sessionStorage.setItem('PlusTest', 'lalala'),
        window.sessionStorage.removeItem('PlusTest'),
        true
      )
    } catch (err) {
      // eslint-disable-next-line
      console.warn("你的浏览器不支持本地存储");
      return false
    }
  })(),
  /**
   * Set data
   * @author jsonleex <jsonlseex@163.com>
   * @param  {String}   key
   * @param  {Any}      value
   * @param  {Boolean}  session 是否启用 sessionStorage 默认 false
   */
  setData (key, value, session = false) {
    const store = session ? 'sessionStorage' : 'localStorage'
    value = JSON.stringify(value)
    this.isSupported && window[store] && (window[store][key] = value)
  },
  getData (key, session = false) {
    const store = session ? 'sessionStorage' : 'localStorage'
    if (this.hasData(key, session)) {
      try {
        return JSON.parse(window[store][key])
      } catch (error) {
        return window[store][key]
      }
    }
    return null
  },
  /**
   * 判断 local[key] 是否存在
   * @author jsonleex <jsonlseex@163.com>
   * @param  {String}  key
   * @param  {Boolean} session
   * @return {Boolean}
   */
  hasData (key, session = false) {
    const store = session ? 'sessionStorage' : 'localStorage'
    try {
      if (window[store] && window[store][key]) return true
    } catch (error) {
      throw new Error(error)
    }
    return false
  },
  removeData (key, session = false) {
    const store = session ? 'sessionStorage' : 'localStorage'
    this.isSupported &&
      window[store] &&
      window[store][key] &&
      window[store].removeItem(key)
  },
  clearData (session = false) {
    const store = session ? 'sessionStorage' : 'localStorage'
    this.isSupported && window[store] && window[store].clear()
  },
  /**
   * 添加 item 至 local[key]
   *
   * 默认为追加，当 index 值为 "start" 时，表示插入 local[key] 的首位
   *
   * @author jsonleex <jsonlseex@163.com>
   * @param  {Strinf}   key
   * @param  {Any}      value
   * @param  {String}   index  值为 "start" 时，表示插入 local[key] 的首位
   * @param  {Boolean}  session
   */
  addItem (key, value, index, session = false) {
    const store = session ? 'sessionStorage' : 'localStorage'
    if (this.isSupported && window[store]) {
      const data = this.hasData(key, session) ? this.getData(key, session) : []
      if (index && index === 'start') {
        data.unshift(value)
      } else {
        data.push(value)
        this.setData(key, data, session)
      }
    }
  },
  /**
   * 获取 local[key] 指定项的值
   *
   * @author jsonleex <jsonlseex@163.com>
   * @param  {String}  key
   * @param  {Number}  index 指定项下标值
   * @param  {Boolean} session
   */
  getItem (key, index, session = false) {
    const data = this.getData(key, session)
    return data && data[index] ? data[index] : null
  },
  getCookie (key) {
    const reg = new RegExp('(^| )' + key + '=([^;]*)(;|$)')
    let cookie
    return document.cookie.length > 0 && (cookie = document.cookie.match(reg))
      ? unescape(cookie[2])
      : null
  },
  /**
   * 设置 cookie
   * @author jsonleex <jsonlseex@163.com>
   * @param  {String} key
   * @param  {String} value
   * @param  {Number} day 过期时间(单位：天)
   */
  setCookie (key, value, day) {
    const exp = new Date() + 24 * day * 60 * 60 * 1000
    document.cookie = `${key}=${escape(value)};expires=${exp.toGMTString()}`
    // domain=${lallla};
  },
  clearCookie (key) {
    this.setCookie(key, '', -1)
  },
}

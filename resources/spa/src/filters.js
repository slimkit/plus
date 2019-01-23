import plueMessageBundle from 'plus-message-bundle'
import { transTime } from '@/util'
import i18n from '@/i18n'

/**
 * ThinkSNS Plus 消息解析器，获取顶部消息.
 *
 * @param {Object} message
 * @param {String} defaultMessage
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function plusMessageFirst (message, defaultMessage) {
  return plueMessageBundle(message, defaultMessage).getMessage()
}

/**
 * 过滤 XSS
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {string} value
 * @returns {string}
 */
export function escapeHTML (value) {
  if (typeof value !== 'string') {
    return value
  }
  return value.replace(/[&<>`"'/]/g, function (result) {
    return {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '`': '&#x60;',
      '"': '&quot;',
      "'": '&#x27;',
      '/': '&#x2f;',
    }[result]
  })
}

/**
 * ThinkSNS Plus 消息解析器，获取顶部消息.
 *
 * @param {Object} message
 * @param {String} defaultMessage
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function plusMessageAnalyze (message, defaultMessage) {
  return plueMessageBundle(message, defaultMessage).getMessage()
}

/**
 * 格式化时间
 * @author jsonleex <jsonlseex@163.com>
 * @param  {Object} date
 * @param  {String} fmt
 * @return {String}
 */
export function formatDate (date, fmt = 'yyyy/MM/dd hh:mm') {
  date = new Date(date)
  const o = {
    'M+': date.getMonth() + 1,
    'd+': date.getDate(),
    'h+': date.getHours(),
    'm+': date.getMinutes(),
    's+': date.getSeconds(),
    'q+': Math.floor((date.getMonth() + 3) / 3),
    S: date.getMilliseconds(),
  }
  if (/(y+)/.test(fmt)) {
    fmt = fmt.replace(
      RegExp.$1,
      (date.getFullYear() + '').substr(4 - RegExp.$1.length)
    )
  }
  for (const k in o) {
    if (new RegExp('(' + k + ')').test(fmt)) {
      fmt = fmt.replace(
        RegExp.$1,
        RegExp.$1.length === 1 ? o[k] : ('00' + o[k]).substr(('' + o[k]).length)
      )
    }
  }
  return fmt
}

/**
 * 祖鲁时间和本地时间之间的时差 (单位:毫秒)
 * @returns {number} timezone offset
 */
export const timeOffset = new Date().getTimezoneOffset() * 60 * 1000
export const addTimeOffset = date => {
  date = new Date(date).getTime() - timeOffset
  return new Date(date).toLocaleString('chinese', { hour12: false })
}

export const time2tips = date => {
  if (typeof date === 'string') {
    date = transTime(date)
  }
  const time = new Date(date)
  const offset = (new Date().getTime() - time) / 1000
  if (offset < 60) return i18n.t('date.in_minute')
  if (offset < 3600) return i18n.t('date.minutes_ago', { min: ~~(offset / 60) })
  if (offset < 3600 * 24) return i18n.t('date.hours_ago', { hour: ~~(offset / 3600) })
  // 根据 time 获取到 "16:57"
  let timeStr, dateStr
  try {
    timeStr = time.toTimeString().match(/^\d{2}:\d{2}/)[0]
    dateStr = time
      .toLocaleDateString() // > "2018/10/19"
      .replace(/^\d{4}\/(\d{2})\/(\d{2})/, '$1-$2') // > 10-19
  } catch (e) {
    return offset
  }
  if (offset < 3600 * 24 * 2) return i18n.t('date.yesterday', { time: timeStr })
  if (offset < 3600 * 24 * 9) return i18n.t('date.days_ago', { day: ~~(offset / 3600 / 24) })
  // 根据 time 06-19
  return dateStr
}

/**
 * 格式化数字
 *     @author jsonleex <jsonlseex@163.com>
 */
export const formatNum = (a = 0) => {
  return (
    a > 0 &&
      (a > 99999999 && (a = Math.floor(a / 1e8) + '亿'),
      a > 9999 &&
        (a =
          a > 99999999
            ? Math.floor(a / 1e8) + '亿'
            : Math.floor(a / 1e4) + '万')),
    a
  )
}

/**
 * Markdown to text fiter.
 *
 * @param {string} markdown
 * @return {string}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function markdownText (markdown) {
  return require('./util/markdown').syntaxTextAndImage(markdown).text
}

/**
 * Internationization label
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {String|String[]} params
 * @param {string} keypath
 * @returns
 */
export function t (params, keypath) {
  if (!keypath) return i18n.t(params)
  if (['string', 'number'].includes(typeof params)) params = [params]
  if (!(params instanceof Array)) return ''
  return i18n.t(keypath, params)
}

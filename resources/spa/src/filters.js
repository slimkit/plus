import xss from 'xss'
import plueMessageBundle from 'plus-message-bundle'
import i18n from '@/i18n'
import { transTime } from '@/util'

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
  const options = {}
  return xss(value, options)
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
  if (typeof date === 'string') {
    date = date.replace(/-/g, '/') // for safari
    // match 2018/10/17 01:48:52"
    if (date.match(/^\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}:\d{2}$/)) {
      // 如果匹配到服务器返回的时间是非标准格式的祖鲁时间，需要进行本地化
      date = +new Date(date) - timeOffset
    }
  }
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
      (date.getFullYear() + '').substr(4 - RegExp.$1.length),
    )
  }
  for (const k in o) {
    if (new RegExp('(' + k + ')').test(fmt)) {
      fmt = fmt.replace(
        RegExp.$1,
        RegExp.$1.length === 1 ? o[k] : ('00' + o[k]).substr(('' + o[k]).length),
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
  if (offset < 3600 * 24) {
    return i18n.t('date.hours_ago',
      { hour: ~~(offset / 3600) })
  }
  // 根据 time 获取到 "16:57"
  let timeStr, dateStr
  try {
    timeStr = time.toTimeString().match(/^\d{2}:\d{2}/)[0]
    dateStr = time.toLocaleDateString() // > "2018/10/19"
      .replace(/^\d{4}\/(\d{2})\/(\d{2})/, '$1-$2') // > 10-19
  } catch (e) {
    console.warn('time2tips error: ', { date, time }) // eslint-disable-line no-console
    return ''
  }
  if (offset < 3600 * 24 * 2) return i18n.t('date.yesterday', { time: timeStr })
  if (offset < 3600 * 24 * 9) {
    return i18n.t('date.days_ago',
      { day: ~~(offset / 3600 / 24) })
  }

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

/**
 * 系统消息提示文字解析
 * @param {Object} data
 */
export function getNotificationDisplay (data) {
  let body
  switch (data.type) {
    case 'reward':
      return i18n.t('message.system.reward_user', { user: data.sender.name })
    case 'user-certification':
      return i18n.t(
        `message.system.certificate[${data.state !== 'reject' ? 1 : 0}]`,
        { reason: data.contents })
    case 'user-currency:cash':
      return i18n.t(
        `message.system.user_cash[${data.state === 'rejected' ? 1 : 0}]`,
        { reason: data.contents })
    case 'user-cash':
      return i18n.t(
        `message.system.user_cash_wallet[${data.state === 'rejected' ? 1 : 0}]`,
        { reason: data.contents })

    case 'reward:feeds':
      return i18n.t('message.system.reward_feed', { user: data.sender.name })
    case 'pinned:feeds':
      return i18n.t('message.system.pinned_feed_by_admin')
    case 'pinned:feed/comment':
      body = data.comment.contents
      if (body.length > 12) body = body.slice(0, 12) + '...'
      return i18n.t(
        `message.system.pinned_feed_comment[${data.state === 'passed'
          ? 0
          : 1}]`, { comment: body })
    case 'delete:feed/comment':
      return i18n.t('message.system.feed_comment_deleted',
        { comment: data.comment.contents })

    case 'reward:news':
      return i18n.t('message.system.reward_news', {
        news: data.news.title,
        user: data.sender.name,
        amount: data.amount + data.unit,
      })
    case 'pinned:news/comment':
      body = data.comment.contents
      if (body.length > 12) body = body.slice(0, 12) + '...'
      return i18n.t(
        `message.system.pinned_news_comment[${data.state !== 'reject'
          ? 1
          : 0}]`, { news: data.news.title, comment: body })
    case 'news:reject':
      return data.contents
    case 'news:audit':
      return data.contents
    case 'qa:answer-adoption':
    case 'question:answer':
      body = data.question.subject
      if (body.length > 12) body = body.slice(0, 12) + '...'
      return i18n.t('message.system.qa_adopt', { answer: body })
    case 'qa:reward':
      return i18n.t('message.system.reward_qa', { user: data.sender.name })
    case 'qa:invitation':
      return i18n.t('message.system.qa_invitation',
        { user: data.sender.name, question: data.question.subject })
    case 'qa:question-topic:accept':
      return i18n.t('message.system.qa_topic_passed',
        { topic: data.topic.name })
    case 'qa:question-topic:reject':
      return i18n.t('message.system.qa_topic_reject',
        { topic: data.topic_application.name })
    case 'qa:question-excellent:accept':
      return i18n.t('message.system.qa_excellent[0]',
        { question: data.application.question.subject })
    case 'qa:question-excellent:reject':
      return i18n.t('message.system.qa_excellent[1]',
        { question: data.application.question.subject })

    case 'group:join':
      if (data.state) {
        return i18n.t(
          `message.system.group_join[${data.state === 'passed' ? 0 : 1}]`,
          { group: data.group.name })
      }
      return i18n.t('message.system.group_join[2]',
        { group: data.group.name, user: data.user.name })
    case 'group:transform':
      return i18n.t('message.system.group_transform',
        { user: data.user.name, group: data.group.name })
    case 'group:post-reward':
      return i18n.t('message.system.reward_post',
        { user: data.sender.name, post: data.post.title })
    case 'group:comment-pinned':
    case 'group:send-comment-pinned':
      return i18n.t(
        `message.system.pinned_post_comment[${data.state !== 'reject'
          ? 1
          : 0}]`, { post: data.post.title })
    case 'group:post-pinned':
      return i18n.t(
        `message.system.pinned_post[${data.state !== 'rejected' ? 0 : 1}]`,
        { post: data.post.title })
    case 'group:pinned-admin':
      return i18n.t('message.system.pinned_post_by_admin',
        { post: data.post.title })
    case 'group:report-comment':
      return i18n.t('message.system.report_post_comment', {
        user: data.sender.name,
        group: data.group.name,
        post: data.post.title,
        comment: data.comment.contents,
      })
    case 'group:report-post':
      return i18n.t('message.system.report_post', {
        user: data.sender.name,
        group: data.group.name,
        post: data.post.title,
      })
    case 'group:report':
      return i18n.t('message.system.group_report',
        { status: data.state === 'rejected' ? '拒绝了' : '通过了' })
    case 'group:audit':
      return data.contents
    case 'report':
      return i18n.t('message.system.report') + ': ' + data.subject
    case 'feed:topic:create:passed':
      return i18n.t('message.system.feed_topic_passed',
        { name: data.topic.name })
    case 'feed:topic:create:failed':
      return i18n.t('message.system.feed_topic_failed',
        { name: data.topic.name })
  }
}

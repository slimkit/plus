import plueMessageBundle from 'plus-message-bundle'

// see http://numeraljs.com
// import numeral from 'numeral';

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
 * 钱单位格式化.
 *
 * @param {Number|Float} value
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
// export function money(value) {
//   return numeral(value).format('0,0.00');
// };

/**
 * 千分位过滤器.
 *
 * @param {Number|Float} value
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
// export function thousands(value) {
//   return numeral(value).format('0,0');
// };

/**
 * 本地时间.
 *
 * @param {String} value
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function localDate (value) {
  if (value.charAt(value.length - 1) === 'Z') {
    return (new Date(`${value}`)).toLocaleString(navigator.language, { hour12: false })
  }
  return (new Date(`${value}Z`)).toLocaleString(navigator.language, { hour12: false })
}

/**
 * Local date to UTC.
 *
 * @param {String} value
 * @return {String}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function localDateToUTC (value) {
  const dateRepo = new Date(value)
  const fullYear = dateRepo.getUTCFullYear()
  const month = dateRepo.getUTCMonth() + 1
  const date = dateRepo.getUTCDate()

  return `${fullYear}-${month}-${date}`
}

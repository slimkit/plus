/**
 * WebIMConfig
 * @type {Object}
 */
const getUrl = () =>
{
  let apiUrl = (window.location.protocol === 'https:' ? 'https:' : 'http:') + process.env.VUE_APP_EASEMOB_API_URL
  let xmppUrl = 'im-api-v2.easemob.com/ws'
  if (window.location.href.indexOf(process.env.VUE_APP_API_HOST) !== -1) {
    apiUrl = (window.location.protocol === 'https:' ? 'https:' : 'http:') + process.env.VUE_APP_EASEMOB_API_URL
    xmppUrl = (window.location.protocol === 'https:' ? 'https:' : 'http:') + '//im-api-v2.easemob.com/ws'
  }

  return {
    apiUrl: apiUrl,
    xmppUrl: xmppUrl,
  }
}
const config = {
  /*
   * XMPP server
   * 对于在console.easemob.com创建的appKey，固定为该值
   */
  xmppURL: getUrl().xmppUrl,

  /*
   * Backend REST API URL
   */
  // apiURL: (location.protocol === 'https:' ? 'https:' : 'http:') + '//a1.easemob.com',
  // ios must be https!!! by lwz
  apiURL: getUrl().apiUrl,

  /*
   * Application AppKey
   */
  appkey: process.env.VUE_APP_EASEMOB_APP_KEY,

  /*
   * Whether to use HTTPS
   * @parameter {Boolean} true or false
   */
  https: false,

  /*
   * isMultiLoginSessions
   * true: A visitor can sign in to multiple webpages and receive messages at all the webpages.
   * false: A visitor can sign in to only one webpage and receive messages at the webpage.
   * 是否开启多页面同步收消息，注意，需要先联系商务开通此功能
   */
  isMultiLoginSessions: false,

  /**
   * When a message arrived, the receiver send an ack message to the
   * sender, in order to tell the sender the message has delivered.
   * See call back function onReceivedMessage
   * 是否发送已读回执
   */
  delivery: true,

  /**
   * 自动出席
   * 如设置为 false，则表示离线，无法收消息，
   * 需要在登录成功后手动调用 conn.setPresence() 才可以收消息
   * @type {Boolean}
   */
  isAutoLogin: true,

  /**
   * 是否打开调试模式
   * 为 true 时可在console中查看log
   * @type {Boolean}
   */
  isDebug: process.env.VUE_APP_EASEMOB_ISDEBUG !== 'false',

  /**
   * 本地存储
   * @type {Boolean}
   */
  enableLocalStorage: true,

  /**
   * 断线重连最大次数
   * @type {Number}
   */
  autoReconnectNumMax: 2,

  /**
   * 断线重连时间间隔
   * @type {Number}
   */
  autoReconnectInterval: 2,

  // heartBeatWait: 4500 // 使用webrtc（视频聊天）时发送心跳包的时间间隔，单位ms
}

export default config

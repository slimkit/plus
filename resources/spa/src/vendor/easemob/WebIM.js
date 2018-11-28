import websdk from 'easemob-websdk'
import WebIMConfig from './WebIMConfig.js'

const WebIM = window.WebIM || {}

WebIM.config = WebIMConfig

WebIM.conn = new websdk.connection({
  isAutoLogin: false,
  https: WebIM.config.https,
  url: WebIM.config.xmppURL,
  apiUrl: WebIM.config.apiURL,
  delivery: WebIM.config.delivery,
  isStropheLog: WebIM.config.isStropheLog,
  autoReconnectNumMax: WebIM.config.autoReconnectNumMax,
  isMultiLoginSessions: WebIM.config.isMultiLoginSessions,
  autoReconnectInterval: WebIM.config.autoReconnectInterval,
  isDebug: WebIM.config.isDebug,
})

export default WebIM

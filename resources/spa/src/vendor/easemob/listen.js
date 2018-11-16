import bus from '@/bus.js'
import vuex from '@/stores'
import WebIM from './WebIM.js'
import AppDB from './AppDB.js'
import lstore from '@/plugins/lstore'

import { generateMessage } from './index.js'

WebIM.conn.listen({
  /**
   * 监听连接事件
   * @author jsonleex <jsonlseex@163.com>
   */
  onOpened (/* msg */) {
    try {
      /** ************ 连接成功后 **************/
      /**
       * c初始化 数据表
       */
      AppDB.init(lstore.getData('H5_CUR_USER').id)

      WebIM.conn.setPresence()
    } catch (e) {
      // eslint-disable-next-line
      console.warn(e);
    }
  },
  onClosed: msg => {
    // eslint-disable-next-line
    console.info("与聊天服务器断开连接", msg);
  },
  /**
   * 错误捕获
   * @author jsonleex <jsonlseex@163.com>
   */
  onError: err => {
    // eslint-disable-next-line
    console.error("聊天模块又双叒叕报错啦:", err);
  },
  /**
   * 纯文本信息
   * @author jsonleex <jsonlseex@163.com>
   */
  onTextMessage: message => {
    generateMessage(message).then(({ msg, room }) => {
      /**
       * 存 $_MESSAGES
       */
      AppDB.addMessage(msg).then(() => {
        AppDB.addChatRoom(room).then(() => {
          vuex.dispatch('initChatRooms')
          bus.$emit('UpdateRoomMessages')
        })
      })
    })
  },
  onPictureMessage: message => {
    process.env.NODE_ENV !== 'production' &&
      // eslint-disable-next-line
      console.info("onPictureMessage", message);
  },

  onReceivedMessage: message => {
    // eslint-disable-next-line
    process.env.NODE_ENV !== "production" && console.info(message);
  },

  onPresence: msg => {
    // eslint-disable-next-line
    process.env.NODE_ENV !== "production" && console.info(msg);
  },
})

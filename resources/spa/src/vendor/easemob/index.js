import bus from '@/bus.js'
import vuex from '@/stores'

import WebIM from './WebIM.js'
import './listen.js'

import AppDB, { ChatRoom, ChatMessage } from './AppDB.js'

import lstore from '@/plugins/lstore/'

import Api from '@/api/api.js'
import { getUserInfoById } from '@/api/user.js'
const isLogged = lstore.hasData('H5_ACCESS_TOKEN')
const currentUser = lstore.getData('H5_CUR_USER')

/**
 * 初始化 indexedDB
 */
isLogged &&
  currentUser &&
  currentUser.id &&
  AppDB.init(currentUser.id) &&
  vuex.dispatch('initChatRooms') &&
  process.env.NODE_ENV === 'development' &&
  console.log("初始化 indexedDB") // eslint-disable-line no-console

export function openWebIM () {
  const CURUSER = vuex.state.CURRENTUSER
  const status = vuex.state.EASEMOB.status
  if (
    CURUSER.id &&
    status === 0 &&
    !WebIM.conn.isOpened()
  ) {
    Api.get('easemob/password', {
      validateStatus: s => s === 201,
    }).then(({ data: { im_pwd_hash: token } }) => {
      const options = {
        user: CURUSER.id,
        pwd: token,
        apiUrl: WebIM.config.apiURL,
        appKey: WebIM.config.appkey,
      }
      WebIM.conn.open(options)
    })
  }
}

/**
 * 发起一个聊天
 * @author jsonleex <jsonlseex@163.com>
 */
export function startSingleChat (option) {
  return new Promise(async (resolve, reject) => {
    const user = option.hasOwnProperty('id')
      ? option
      : await getUserInfoById(option)
    if (user && user.id) {
      const { id, avatar, name } = user
      const room = new ChatRoom({
        id,
        name,
        avatar,
        type: 'chat',
        info: user,
      })

      AppDB.addChatRoom(room).then(res => {
        vuex.dispatch('initChatRooms').then(() => {
          resolve(res)
        })
      })
    } else {
      reject('当前用户不存在或已删除')
    }
  })
}

export async function generateMessage (message) {
  const { to, from, type, delay } = message
  const bySelf = lstore.getData('H5_CUR_USER').id == from
  const info =
    type === 'chat' ? await getUserInfoById(from) : await getGroupInfo(to)
  const user =
    from === 'admin'
      ? { name: '系统通知', id: 0 }
      : await getUserInfoById(from)
  const msgOptions = {
    source: message,
    ...message,
    bySelf: bySelf,
    isUnread: !bySelf,
    time: delay ? +new Date(delay) : +new Date(),
    info,
    user,
  }
  const roomOption = {
    id: type === 'chat' ? (bySelf ? to : from) : to,
    type,
    info,
    user,
    name: info.name,
    latest: message,
    unreadCount: bySelf ? 0 : 1,
    avatar: type === 'chat' ? info.avatar : info.group_face,
  }

  return {
    room: new ChatRoom(roomOption),
    msg: new ChatMessage(msgOptions),
  }
}

export function getGroupInfo (gid) {
  return new Promise(resolve => {
    Api.get(`/easemob/group?im_group_id=${gid}`)
      .then(({ data: [group] = [{}] }) => {
        resolve(group)
      })
      .catch(err => {
        // eslint-disable-next-line
        console.warn("获取群组信息失败", err);
        resolve({})
      })
  })
}

export function clearUnread(id) {
  AppDB.upDateChatRoom({ id }, { unreadCount: 0 })
}

export function sendTextMessage ({ to, from, body, type }) {
  return new Promise((resolve, reject) => {
    /**
     * 生成 消息 ID
     */

    const id = WebIM.conn.getUniqueId()
    /**
     * 创建文本消息
     */
    const message = new WebIM.message('txt', id) // 创建文本消息

    message.set({
      to,
      msg: body,
      chatType: 'chatRoom',
      roomType: false,
      success (id, serverMsgId) {
        const mess = {
          id: serverMsgId,
          type,
          to,
          from,
          source: { data: body },
        }
        generateMessage(mess).then(({ msg: $message }) => {
          /**
           * 存 $_MESSAGES
           */
          AppDB.addMessage($message).then(() => {
            AppDB.upDateChatRoom(
              { id: to },
              {
                latest: $message.source,
                unreadCount: 0,
              }
            ).then(() => {
              vuex.dispatch('initChatRooms')
              bus.$emit('UpdateRoomMessages')
              resolve(true)
            })
          })
        })
      },
      fail (e) {
        reject(e)
      },
    })

    type === 'chat' && (message.body.chatType = 'singleChat')
    type === 'groupchat' && message.setGroup('groupchat')

    WebIM.conn.send(message.body)
  })
}
export default WebIM

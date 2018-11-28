import Dexie from 'dexie'

const DB_VESION = 2
const AppDB = {
  /**
   * 初始化数据表
   * @author jsonleex <jsonlseex@163.com>
   * @param  {Number} UID
   */
  init (UID) {
    if (this.db && this.db.name === `TSP_${UID}`) {
      return this
    }
    const db = new Dexie(`TSP_${UID}`)
    db.version(DB_VESION).stores({
      messages: 'id, from, to, type, isUnread, bySelf',
      chats: 'id, type, latest, info, name, avatar',
    })

    this.db = db
    this.installed = true

    this.$_CHATS = db.table('chats')
    this.$_MESSAGES = db.table('messages')
    return this
  },

  /**
   * 获取聊天列表
   * @author jsonleex <jsonlseex@163.com>
   */
  GetChatRooms () {
    const $_CHATS = this.$_CHATS
    return new Promise((resolve, reject) => {
      $_CHATS
        .toCollection()
        .reverse()
        .sortBy('time')
        .then(res => {
          resolve(res.map(item => new ChatRoom(item)))
        })
        .catch(err => {
          reject(err)
        })
    })
  },

  /**
   * 添加聊天房间
   * @author jsonleex <jsonlseex@163.com>
   * @param  {ChatRoom} room
   */
  addChatRoom (room) {
    const $_CHATS = this.$_CHATS
    return new Promise((resolve, reject) => {
      if (room instanceof ChatRoom) {
        $_CHATS
          .where('id')
          .equals(room.id)
          .count()
          .then(count => {
            if (count > 0) {
              this.upDateChatRoom(
                {
                  id: room.id,
                },
                room
              ).then(res => {
                resolve(res)
              })
            } else {
              $_CHATS
                .add(room)
                .then(res => {
                  resolve(res)
                })
                .catch(err => {
                  reject(err)
                })
            }
          })
      } else {
        reject(
          new Error(
            "[AppDB.addChatRoom] 'room' should be an instance of ChatRoom"
          )
        )
      }
    })
  },

  /**
   * 更新聊天室
   * @author jsonleex <jsonlseex@163.com>
   * @param  {ChatRoom} room
   */
  upDateChatRoom (room, status = {}) {
    const $_CHATS = this.$_CHATS
    return new Promise((resolve, reject) => {
      $_CHATS
        .where('id')
        .equals(room.id)
        .modify({ ...room, ...status })
        .then(() => {
          resolve(room.id)
        })
        .catch(err => {
          reject(err)
        })
    })
  },

  /**
   * 查询消息列表
   * @author jsonleex <jsonlseex@163.com>
   * @param  {Number} id
   * @param  {String} chatType
   * @param  {Number} offset
   * @param  {Number} limit
   */
  getMessages (id, chatType = 'chat', offset = 0, limit = 20) {
    const $_MESSAGES = this.$_MESSAGES
    return new Promise((resolve, reject) => {
      $_MESSAGES
        .where('type')
        .equals(chatType)
        .filter(item => {
          if (item.error) {
            return false
          }
          if (chatType === 'chat') {
            return item.from == id || item.to == id
          } else {
            return item.to == id
          }
        })
        .reverse()
        .offset(offset)
        .limit(limit)
        .sortBy('time')
        .then(res => {
          resolve(res.reverse())
        })
        .catch(err => {
          reject(err)
        })
    })
  },

  addMessage (message) {
    const $_MESSAGES = this.$_MESSAGES
    return new Promise((resolve, reject) => {
      if (message instanceof ChatMessage) {
        $_MESSAGES
          .where('id')
          .equals(message.id)
          .count()
          .then(res => {
            if (res === 0) {
              $_MESSAGES.add(message).then(messageId => {
                resolve(messageId)
              })
            }
          })
      } else {
        reject(
          new Error(
            "[AppDB.addMessage] 'message' should be an instance of ChatMessage"
          )
        )
      }
    })
  },

  readMessage (chatType, id) {
    const $_MESSAGES = this.$_MESSAGES
    const key = chatType === 'chat' ? 'from' : 'to'
    return new Promise((resolve, reject) => {
      $_MESSAGES
        .where({ type: chatType, [key]: id, isUnread: 1 })
        .modify({ isUnread: 0 })
        .then(res => {
          resolve(res)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
}

export class ChatRoom {
  // "id", "type", "latest", "info", "name", "avatar"
  constructor ({
    id,
    info,
    user,
    type,
    name = '群聊',
    latest = null,
    avatar = 'https://avatars0.githubusercontent.com/u/25883665?s=40&v=4',
    unreadCount = 0,
    time = +new Date(),
  }) {
    this.id = id + ''
    this.type = type
    this.info = info
    this.user = user
    this.name = name
    this.avatar = avatar
    this.latest = latest
    this.unreadCount = unreadCount
    this.time = latest ? latest.time || time : time
  }

  /**
   * 获取 ChatRoom 消息列表
   * @author jsonleex <jsonlseex@163.com>
   * @param  {Number} offset
   * @param  {Number} limit
   */
  messages (offset = 0, limit = 20) {
    return new Promise((resolve, reject) => {
      AppDB.getMessages(this.id, this.type, offset, limit)
        .then(data => {
          resolve(data)
        })
        .catch(err => {
          reject(err)
        })
    })
  }

  /**
   * 筛选 ChatRoom 未读消息列表
   * @author jsonleex <jsonlseex@163.com>
   */
  unreadMessage () {
    return this.messages().then(messages => {
      const unreadMessages = messages.filter(item => item.isUnread > 0)
      this.count(unreadMessages.length)
      return unreadMessages
    })
  }

  /**
   * 一键阅读 ChatRoom 中所有未读消息
   * @author jsonleex <jsonlseex@163.com>
   */
  readAllMessage () {
    return new Promise((resolve, reject) => {
      AppDB.readMessage(this.type, this.id)
        .then(data => {
          resolve(data)
        })
        .catch(err => {
          reject(err)
        })
    })
  }

  count (val) {
    val && (this.unreadCount = val)
    return ~~this.unreadCount
  }
}

export class ChatMessage {
  // "id", "from", "to", "type", "isUnread", "bySelf"
  constructor ({
    id,
    to,
    from,
    type = 'chat',
    isUnread = 1,
    bySelf = 0,
    time = +new Date(),
    source = {},
    info,
    user,
  }) {
    this.to = to
    this.time = time
    this.user = user
    this.from = from
    this.type = type
    this.info = info
    this.source = source
    this.bySelf = bySelf
    this.isUnread = isUnread
    this.id = id || `${to}${from}${+new Date()}`
  }
}

window.AppDB = AppDB
export default AppDB

var easemob = {}
easemob.window = null
easemob = {
  /*初始化*/
  init: function () {
    easemob.conn = new WebIM.connection({
      isMultiLoginSessions: WebIM.config.isMultiLoginSessions,
      https: typeof WebIM.config.https === 'boolean'
        ? WebIM.config.https
        : location.protocol === 'https:',
      url: WebIM.config.xmppURL,
      heartBeatWait: WebIM.config.heartBeatWait,
      autoReconnectNumMax: WebIM.config.autoReconnectNumMax,
      autoReconnectInterval: WebIM.config.autoReconnectInterval,
      apiUrl: WebIM.config.apiURL,
      isAutoLogin: true
    })

    easemob.conn.listen({
      /*连接成功回调
       如果isAutoLogin设置为false，那么必须手动设置上线，否则无法收消息
       手动上线指的是调用conn.setPresence(); 如果conn初始化时已将isAutoLogin设置为true
       则无需调用conn.setPresence();*/
      onOpened: function (message) {
      },
      /*收到文本消息*/
      onTextMessage: function (message) {
        easemob.storeMes(message)
      },
      onPictureMessage: function (message) {
        console.log(message)
      },
      /*失败回调*/
      onError: function (message) {
        console.log(message)
      }
    })

    easemob.database()

    /*获取IM账号密码*/
    if ($.cookie('im_passwd') === undefined) {
      axios.get('/api/v2/easemob/password/')
        .then(function (response) {
          easemob.password = response.data.im_pwd_hash
          $.cookie('im_passwd', easemob.password, 1)
          easemob.login()
        })
        .catch(function (error) {
          showError(error.response.data)
        })
    } else {
      easemob.password = $.cookie('im_passwd')
      easemob.login()
    }

    /*获取用户信息并设置会话*/
    if ($.cookie('easemob_uids') === undefined) {
      var easemob_uids = []
      easemob_uids.push(TS.MID)
      window.TS.dataBase.transaction('rw?', window.TS.dataBase.room,
        function () {
          window.TS.dataBase.room
            .filter(function (item) {
              return (item.mid == TS.MID)
            })
            .reverse()
            .toArray().then(function (items) {
            var uids = _.map(items, 'uid')
            _.forEach(uids, function (value, key) {
              if (typeof (value) === 'string') {
                easemob_uids = _.unionWith(easemob_uids, value.split(','))
              } else {
                easemob_uids.push(parseInt(value))
              }
            })

            easemob.updateUsers(easemob_uids)
          })
        })
    }
    easemob.users = _.keyBy(TS.EASEMOB_USERS, 'id')
    easemob.setOuterCon()
  },

  // 更新用户信息
  updateUsers: function (id) {
    var users = getUserInfo(id)
    easemob.users = _.mergeWith(_.keyBy(users, 'id'), easemob.users)
    $.cookie('easemob_uids', _.keys(easemob.users).join(','), 1)
  },

  /*接收消息存储*/
  storeMes: function (message) {
    /*群聊*/
    if (message.type == 'groupchat') {
      window.TS.dataBase.room.where('[mid+group]')
        .equals([TS.MID, message.to])
        .first(function (item) {
          var dbMsg = {}
          dbMsg.id = message.id
          dbMsg.time = (new Date()).valueOf()
          dbMsg.type = message.type
          dbMsg.mid = TS.MID
          dbMsg.uid = message.from
          dbMsg.touid = message.to
          dbMsg.read = 0
          dbMsg.txt = message.sourceMsg

          /*不存在创建会话*/
          if (item === undefined) {
            // 获取成员
            var options = {
              pageNum: 1,
              pageSize: 1000,
              groupId: message.to,
              success: function (res) {
                var group_uids = []
                _.forEach(res.data, function (value, key) {
                  value.member === undefined ? group_uids.unshift(
                    parseInt(value.owner)) : group_uids.push(
                    parseInt(value.member))
                })

                // 获取群组信息
                var options = {
                  groupId: message.to,
                  success: function (res) {
                    var room = {
                      group: message.to,
                      title: res.data[0].name,
                      type: 'groupchat',
                      mid: TS.MID,
                      uid: group_uids.join(','),
                      last_message_time: dbMsg.time,
                      last_message_txt: message.sourceMsg,
                      del: 0
                    }
                    window.TS.dataBase.room.add(room).then(function (i) {
                      /* 插入聊天信息 */
                      dbMsg.cid = i
                      window.TS.dataBase.message.add(dbMsg)

                      // 更新用户
                      easemob.updateUsers(group_uids)

                      /*创建会话*/
                      room.id = i
                      easemob.setNewCon(room)
                    })
                  },
                  error: function (e) {
                    console.log(e)
                  }
                }
                easemob.conn.getGroupInfo(options)
              },
              error: function (e) {
                console.log(e)
              }
            }
            easemob.conn.listGroupMember(options)
            /*存在修改会话内容*/
          } else {
            window.TS.dataBase.room.where('[mid+uid]')
              .equals([TS.MID, message.from])
              .modify({
                last_message_time: dbMsg.time,
                last_message_txt: message.sourceMsg
              })

            if (item.del == 1) {
              window.TS.dataBase.room.where('[mid+uid]')
                .equals([TS.MID, message.from])
                .modify({
                  del: 0
                })
              /*设置会话*/
              easemob.setNewCon(item)
            }
            dbMsg.cid = item.id
            dbMsg.read = (easemob.cid == dbMsg.cid && $('.chat_dialog').length >
              0) ? 1 : 0
            window.TS.dataBase.message.add(dbMsg)
            easemob.updateUsers([message.from])

          }

          /*若聊天窗口为打开状态*/
          if ($('.chat_dialog').length > 0) {
            /*当前会话，添加消息*/
            if (easemob.cid == dbMsg.cid) easemob.setMes(dbMsg.txt, dbMsg.uid)
            easemob.updateLastMes(dbMsg.cid, dbMsg.txt)
          }
        })
    } else {
      /*单聊*/
      /*查询会话是否存在*/
      window.TS.dataBase.room.where('[mid+uid]')
        .equals([TS.MID, message.from])
        .first(function (item) {
          var dbMsg = {}
          dbMsg.id = message.id
          dbMsg.time = (new Date()).valueOf()
          dbMsg.type = message.type
          dbMsg.mid = TS.MID
          dbMsg.uid = message.from
          dbMsg.touid = message.to
          dbMsg.read = 0
          dbMsg.txt = message.sourceMsg

          /*不存在创建会话*/
          if (item === undefined) {
            // 更新用户
            easemob.updateUsers([message.from])
            var room = {
              title: easemob.users[message.from].name,
              type: 'chat',
              mid: TS.MID,
              uid: message.from,
              last_message_time: dbMsg.time,
              last_message_txt: message.sourceMsg,
              del: 0
            }
            window.TS.dataBase.room.add(room).then(function (i) {
              /* 插入聊天信息 */
              dbMsg.cid = i
              window.TS.dataBase.message.add(dbMsg)

              // 创建会话
              room.id = i
              easemob.setNewCon(room)
            })
            /*存在修改会话内容*/
          } else {
            window.TS.dataBase.room.where('[mid+uid]')
              .equals([TS.MID, message.from])
              .modify({
                last_message_time: dbMsg.time,
                last_message_txt: message.sourceMsg
              })

            if (item.del == 1) {
              window.TS.dataBase.room.where('[mid+uid]')
                .equals([TS.MID, message.from])
                .modify({
                  del: 0
                })
              /*设置会话*/
              easemob.setNewCon(item)
            }
            dbMsg.cid = item.id
            dbMsg.read = (easemob.cid == dbMsg.cid && $('.chat_dialog').length >
              0) ? 1 : 0
            window.TS.dataBase.message.add(dbMsg)

          }

          /*若聊天窗口为打开状态*/
          if ($('.chat_dialog').length > 0) {
            /*当前会话，添加消息*/
            if (easemob.cid == dbMsg.cid) easemob.setMes(dbMsg.txt, dbMsg.uid)
            easemob.updateLastMes(dbMsg.cid, dbMsg.txt)
          }
        })
    }
  },

  /*IM登录*/
  login: function () {
    var options = {
      apiUrl: WebIM.config.apiURL,
      user: TS.MID,
      pwd: easemob.password,
      appKey: WebIM.config.appkey
    }
    easemob.conn.open(options)
  },

  /*创建本地数据库*/
  database: function () {
    /*创建本地存储*/
    var db = new Dexie('TS_EASEMOB')
    db.version(1).stores({
      /*message*/
      message: 'id, time, cid, type, mid, uid, touid, txt, read, [cid+read]',

      /*room*/
      room: '++id, group, title, type, mid, uid, last_message_time, last_message_txt, del, [mid+del], [mid+uid], [mid+group]'
    })

    window.TS.dataBase = db

    /*添加IM小助手*/
    if (TS.BOOT['im:helper']) {
      window.TS.dataBase.transaction('rw?', window.TS.dataBase.room,
        function () {
          _.forEach(TS.BOOT['im:helper'], function (value, key) {
            window.TS.dataBase.room.where('[mid+uid]')
              .equals([TS.MID, value['uid']])
              .first(function (item) {
                if (item === undefined) {
                  var room = {
                    title: value['name'],
                    type: 'chat',
                    mid: TS.MID,
                    uid: value['uid'],
                    last_message_time: (new Date()).valueOf(),
                    last_message_txt: '',
                    del: 0
                  }
                  window.TS.dataBase.room.add(room)
                }
              })
          })
        })
    }
  },

  /*设置右侧会话*/
  setOuterCon: function () {
    var _this = this

    window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, function () {
      window.TS.dataBase.room
        .orderBy('last_message_time')
        .filter(function (item) {
          return (item.mid == TS.MID && item.del == 0)
        })
        .reverse()
        .toArray().then(function (items) {
        _.forEach(items, function (value, key) {
          var title = value.type == 'groupchat'
            ? value.title
            : _this.users[value.uid]['name']
          var avatar = value.type == 'groupchat' ? TS.SITE_URL +
            '/assets/pc/images/logo.png' : getAvatar(_this.users[value.uid],
            50)
          var to = value.type == 'groupchat' ? value.group : value.uid

          var sidehtml = '<dd class="ms_chat" id="ms_chat_' + value.id +
            '" data-cid="' + value.id + '" data-name="' + title +
            '"><a href="javascript:;" onclick="easemob.openChatDialog(0, ' +
            value.id + ', ' + to + ')"><img src="' + avatar + '"/></a></dd>'

          $('#ms_fixed').append(sidehtml)
        })
      })
    })
  },

  /*设置弹出会话*/
  setInnerCon: function () {
    var _this = this
    window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, function () {
      window.TS.dataBase.room
        .orderBy('last_message_time')
        .filter(function (item) {
          return (item.mid == TS.MID && item.del == 0)
        })
        .reverse()
        .toArray().then(function (items) {
        _.forEach(items, function (value, key) {
          var title = value.type == 'groupchat'
            ? value.title
            : _this.users[value.uid]['name']
          var avatar = value.type == 'groupchat' ? TS.SITE_URL +
            '/assets/pc/images/logo.png' : getAvatar(_this.users[value.uid],
            50)
          var to = value.type == 'groupchat' ? value.group : value.uid

          if (value.del == 0 || value.id == easemob.cid) {
            var css = value.id == easemob.cid
              ? 'class="room_item current_room"'
              : 'class="room_item"'
            var last_message_txt = value.last_message_txt == undefined
              ? ''
              : value.last_message_txt
            var html = '<li ' + css +
              ' class="room_item" data-type="0" data-uid="' + to +
              '" data-cid="' + value.id + '" data-name="' + title +
              '" id="chat_' + value.id + '">'
              +
              '<div class="chat_delete"><a href="javascript:;" onclick="easemob.delCon(' +
              value.id + ', ' + value.uid +
              ')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-delbtn1"></use></svg></a></div>'
              + '<div class="chat_left_icon">'
              + '<img src="' + avatar + '" class="chat_svg">'
              + '</div>'
              + '<div class="chat_item">'
              + '<span class="chat_ms_span">' + title + '</span>'
              + '<div>' + last_message_txt + '</div>'
              + '</div>'
              + '</li>'

            if (value.id == easemob.cid) {
              $('#chat_left_message ul').prepend(html)
              easemob.listMes(easemob.cid)
              $('#chat_text').focus()
            } else {
              $('#chat_left_message ul').append(html)
            }
          }

          /*设置为未删除*/
          if (value.del == 1 && value.id == easemob.cid) {
            window.TS.dataBase.room.where('id').equals(value.id).modify({
              del: 0
            })
          }
        })
      })
    })
  },

  /*设置新会话*/
  setNewCon: function (room) {
    var _this = this
    var title = room.type == 'groupchat'
      ? room.title
      : _this.users[room.uid]['name']
    var avatar = room.type == 'groupchat' ? TS.SITE_URL +
      '/assets/pc/images/logo.png' : getAvatar(_this.users[room.uid], 50)
    var to = room.type == 'groupchat' ? room.group : room.uid

    var sidehtml = '<dd class="ms_chat" id="ms_chat_' + room.id +
      '" data-cid="' + room.id + '" data-name="' + title +
      '"><a href="javascript:;" onclick="easemob.openChatDialog(0, ' + room.id +
      ', ' + to + ')"><img src="' + avatar + '"/></a></dd>'

    $('#ms_pinneds').after(sidehtml)

    if ($('.chat_dialog').length > 0) {
      var last_message_txt = room.last_message_txt == undefined
        ? ''
        : room.last_message_txt

      var html = '<li class="room_item" data-type="0" data-cid="' + room['id'] +
        '" data-uid="' + to + '" data-name="' + room['title'] + '" id="chat_' +
        room['id'] + '">'
        +
        '<div class="chat_delete"><a href="javascript:;" onclick="easemob.delCon(' +
        room['id'] + ', ' + room.uid +
        ')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-delbtn1"></use></svg></a></div>'
        + '<div class="chat_left_icon">'
        + '<img src="' + avatar + '" class="chat_svg">'
        + '</div>'
        + '<div class="chat_item">'
        + '<span class="chat_ms_span">' + title + '</span>'
        + '<div>' + last_message_txt + '</div>'
        + '</div>'
        + '</li>'
      $('#chat_left_message ul').prepend(html)
    }
  },

  /*创建会话*/
  createCon: function (uid) {
    checkLogin()
    if (!TS.EASEMOB_KEY) return false
    easemob.updateUsers([uid])
    uid = uid.toString()

    window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, function () {
      window.TS.dataBase.room.where('[mid+uid]')
        .equals([TS.MID, uid])
        .first(function (item) {
          /*不存在创建会话*/
          if (item === undefined) {
            var room = {
              type: 'chat',
              title: easemob.users[uid]['name'],
              mid: TS.MID,
              uid: uid,
              last_message_time: (new Date()).valueOf(),
              last_message_txt: '',
              del: 0
            }

            window.TS.dataBase.room.add(room).then(function (i) {
              var sidehtml = '<dd class="ms_chat" id="ms_chat_' + i +
                '" data-cid="' + i + '" data-name="' +
                easemob.users[uid]['name'] +
                '"><a href="javascript:;" onclick="easemob.openChatDialog(0, ' +
                i + ', ' + uid + ')"><img src="' +
                getAvatar(easemob.users[uid], 50) + '"/></a></dd>'
              $('#ms_pinneds').after(sidehtml)
              easemob.cid = i
              easemob.openChatDialog(0, i, uid)
            })
            /* 存在修改会话内容*/
          } else {
            if (item.del == 1) {
              window.TS.dataBase.room.where('[mid+uid]')
                .equals([TS.MID, uid])
                .modify({
                  del: 0
                })
            }
            easemob.openChatDialog(0, item.id, uid)
          }
        })
    })
  },

  /*删除会话*/
  delCon: function (cid) {
    cancelBubble()
    var chat = $('#chat_' + cid)
    var title = chat.data('name')

    /*查找下个会话*/
    if (chat.next().length > 0) {
      var next_cid = chat.next().eq(0).data('cid')
      var next_uid = chat.next().eq(0).data('uid')
    } else if (chat.prev('.room_item').length > 0) {
      var next_cid = chat.prev().eq(0).data('cid')
      var next_uid = chat.next().eq(0).data('uid')
    }

    $('#ms_chat_' + cid).remove()
    if ($('.chat_dialog').length > 0) chat.remove()

    /*清空会话，或者展示下个会话的聊天列表*/
    $('#chat_' + cid).addClass('current_room')
    easemob.listMes(next_cid)

    window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, function () {
      window.TS.dataBase.room.where('id').equals('cid').modify({
        del: 1
      })
    })
  },

  /*列出消息*/
  listMes: function (cid) {
    var _this = this
    /*设置房间名*/
    var title = '<span>' + $('#chat_' + cid).data('name') + '</span>'
    /*若为群组，显示群信息修改按钮*/
    if (cid.toString().length == 14) {
      title += '<svg class="icon" aria-hidden="true" id="chat_ad_info">'
        + '<use xlink:href="#icon-more1"></use>'
        + '</svg>'
    }
    $('#chat_wrap .body_title').html(title).show()
    $('#chat_cont').html('')

    /*查询消息*/
    window.TS.dataBase.transaction('rw?', window.TS.dataBase.message,
      function () {
        window.TS.dataBase.message
          .orderBy('time')
          .filter(function (item) {
            return (item.cid === cid)
          })
          .limit(15)
          .reverse()
          .toArray(function (array) {
            var messageList = []
            var messageBody = {}
            if (array.length) {
              array = array.reverse()
              array.forEach(function (value) {
                easemob.setMes(value.txt, value.uid)
              })
            }
          })
      })
  },

  /*发送消息*/
  sendMes: function (cid, touid) {
    var txt = $.trim($('#chat_text').val())

    if (txt == '') {
      $('#chat_text').focus()
      return false
    }

    /*生成本地消息id*/
    var id = easemob.conn.getUniqueId()
    /*创建文本消息*/
    var msg = new WebIM.message('txt', id)
    var options = {
      msg: txt,
      to: touid,
      roomType: false,
      success: function (id, serverMsgId) {
        /*消息入库*/
        var dbMsg = {}
        dbMsg.id = id
        dbMsg.time = (new Date()).valueOf()
        dbMsg.cid = cid
        dbMsg.type = 'chat'
        dbMsg.mid = dbMsg.uid = TS.MID
        dbMsg.touid = touid
        dbMsg.read = 1
        dbMsg.txt = txt
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.message,
          function () {
            window.TS.dataBase.message.add(dbMsg)
          })
        easemob.setMes(txt, window.TS.MID)
      },
      fail: function (e) {
        console.log(e)
      }
    }
    if (touid.toString().length == 14) {
      options.chatType = 'chatRoom'
      msg.set(options)
      msg.setGroup('groupchat')
    } else {
      msg.set(options)
      options.chatType = 'singleChat'
    }

    easemob.conn.send(msg.body)
  },

  /*设置消息*/
  setMes: function (txt, user_id) {
    $('#chat_text').val('')
    txt = txt.replace(/\r\n/g, '<br>')
    txt = txt.replace(/\n/g, '<br>')

    if (user_id == window.TS.MID) {
      html = '<div class="chatC_right">'
        + '<img src="' + getAvatar(this.users[user_id]) +
        '" class="chat_avatar fr">'
        + '<span class="chat_right_body">' + txt + '</span>'
        + '</div>'
    } else if (user_id == 'admin') {
      html = '<div class="chat_notice">'
        + '<span class="chat_notice_span">' + txt + '</span>'
        + '</div>'
    } else {
      html = '<div class="chatC_left">'
        + '<img src="' + getAvatar(this.users[user_id]) +
        '" class="chat_avatar">'
        + '<span class="chat_left_body">' + txt + '</span>'
        + '</div>'
    }
    $('#chat_cont').append(html)
    var div = document.getElementById('chat_scroll')
    div.scrollTop = div.scrollHeight
  },

  /*更新最后一条消息*/
  updateLastMes: function (cid, txt) {
    $('#chat_' + cid + ' .chat_item').find('div').html(txt)
  },

  /*获取未读消息数量html*/
  formatUnreadHtml: function (type, value) {
    if (type == 0) {
      var html = '<div class="unread_div"><span>' + (value > 99 ? 99 : value) +
        '</span></div>'
    } else {
      var html = '<div class="chat_unread_div"><span>' +
        (value > 99 ? 99 : value) + '</span></div>'
    }
    return html
  },

  /*设置未读消息数量*/
  setUnreadMes: function () {
    for (var i in TS.UNREAD) {
      if (TS.UNREAD[i] > 0) {
        $('#ms_' + i + ' .unread_div').remove()
        $('#ms_' + i).prepend(easemob.formatUnreadHtml(0, TS.UNREAD[i]))
        if ($('.chat_dialog').length > 0) {
          $('#chat_' + i + ' .chat_unread_div').remove()
          $('#chat_' + i).prepend(easemob.formatUnreadHtml(1, TS.UNREAD[i]))
        }
      } else {
        $('#ms_' + i + ' .unread_div').remove()
        $('#chat_' + i + ' .chat_unread_div').remove()
      }

      // if ((i == 'comments' || i == 'likes') && TS.UNREAD['last_' + i]) {
      //     var txt = i == 'comments' ? '评论了你' : '点赞了你';
      //     $('#chat_' + i).find('.last_content').html(TS.UNREAD['last_' + i] + txt);
      // }
    }
  },

  /*设置未读聊天消息数量*/
  setUnreadChat: function (cid, value) {
    $('#ms_chat_' + cid + ' .unread_div').remove()
    $('#ms_chat_' + cid).prepend(easemob.formatUnreadHtml(0, value))
    if ($('.chat_dialog').length > 0) {
      $('#chat_' + cid + ' .chat_unread_div').remove()
      $('#chat_' + cid).prepend(easemob.formatUnreadHtml(1, value))
    }
  },

  /*设置消息已读*/
  setRead: function(type, cid) {
    /*消息*/
    if (type === 0) {
      axios.patch(
        '/api/v2/user/notifications',
        { type: cid },
        {
          validateStatus: function(s) {
            return s === 204
          }
        }
      ).then(function() {
        switch (cid) {
          case 'comment':
            TS.UNREAD.comments = 0
            break
          case 'like':
            TS.UNREAD.liked = 0
            break
          case 'at':
            TS.UNREAD.mention = 0
            break
          case 'system':
            TS.UNREAD.notifications = 0
            break
        }
        easemob.setUnreadMes()
      }).catch(function(error) {
        showError(error.response.data)
      })

      TS.UNREAD[cid] = 0
      $('#ms_' + cid).find('.unread_div').remove()
      $('#chat_' + cid).find('.chat_unread_div').remove()
      /* 聊天*/
    } else {
      $('#ms_chat_' + cid).find('.unread_div').remove()
      $('#chat_' + cid).find('.chat_unread_div').remove()
      window.TS.dataBase.transaction('rw?', window.TS.dataBase.message,
        function() {
          window.TS.dataBase.message.where({ cid: cid }).modify({
            read: 1
          })
        })
    }
  },

  /*打开消息对话框*/
  openChatDialog: function (type, cid, uid) {
    /* 聊天消息*/
    if (type === 0) {
      easemob.setRead(1, cid)
      ly.load('/message?type=' + type + '&cid=' + cid + '&uid=' + uid, '',
        '810px', '572px')
    } else {
      ly.load('/message?type=' + type, '', '810px', '572px')
    }
  },

  /*获取未读消息数量*/
  getUnreadMessage: function () {
    /*获取未读通知数量*/
    // axios.get('/api/v2/user/notifications')
    //   .then(function (response) {
    //         TS.UNREAD.notifications = response.headers['unread-notification-limit'];

    //         easemob.setUnreadMes();
    //   })
    //   .catch(function (error) {
    //     showError(error.response.data);
    //   });

    /*获取未读点赞，评论，审核通知数量*/
    // axios.get('/api/v2/user/unread-count')
    //   .then(function (response) {
    //         var res = response.data;
    //         res.counts = res.counts ? res.counts : {};
    //         TS.UNREAD.comments = res.counts.unread_comments_count ? res.counts.unread_comments_count : 0;
    //         TS.UNREAD.last_comments = res.comments !== undefined && res.comments.length > 0 ? res.comments[0]['user']['name'] : '';
    //         TS.UNREAD.likes = res.counts.unread_likes_count ? res.counts.unread_likes_count : 0;
    //         TS.UNREAD.last_likes = res.likes !== undefined &&  res.likes.length > 0 ? res.likes[0]['user']['name'] : '';

    //         /*审核通知数量*/
    //         var pinneds_count = 0;
    //         for(var i in res.pinneds){
    //             pinneds_count += res.pinneds[i]['count'];
    //         }
    //         TS.UNREAD.pinneds = pinneds_count;

    //         easemob.setUnreadMes();
    //   })
    //   .catch(function (error) {
    //     showError(error.response.data);
    //   });

    axios.get('/api/v2/user/counts')
      .then(function (response) {
        var res = response.data.user
        TS.UNREAD.commented = res.commented
        TS.UNREAD.liked = res.liked
        TS.UNREAD.at = res.at
        TS.UNREAD.system = res.system
        TS.UNREAD.pinneds = parseInt(res['news-comment-pinned']) +
          parseInt(res['feed-comment-pinned']) +
          parseInt(res['post-comment-pinned']) + parseInt(res['post-pinned'])

        easemob.setUnreadMes()
      })
      .catch(function (error) {
        console.log(error)
        showError(error.response.data)
      })

  },

  /*获取未读聊天消息数量*/
  getUnreadChats: function () {
    /*获取未读消息数量*/
    window.TS.dataBase.transaction('rw?', window.TS.dataBase.room,
      window.TS.dataBase.message, function () {
        window.TS.dataBase.room.where({ mid: TS.MID }).each(function (value) {
          window.TS.dataBase.message.where('[cid+read]')
            .equals([value.id, 0])
            .count(function (number) {
              if (number > 0) {
                easemob.setUnreadChat(value.id, number)
              }
            })
        })
      })
      .catch(function (e) {
        console.log(e)
      })
  },

  /*创建群组*/
  addGroup: function () {
    var groupname = $('#chat_selected_users').attr('name')
    var members = $('#chat_selected_users').val()
    if (members == '') return false

    // 单聊
    if (members.split(',').length == 1) {
      easemob.updateUsers(members)
      uid = members.toString()

      window.TS.dataBase.transaction('rw?', window.TS.dataBase.room,
        function () {
          window.TS.dataBase.room.where('[mid+uid]')
            .equals([TS.MID, uid])
            .first(function (item) {
              /*不存在创建会话*/
              if (item === undefined) {
                var room = {
                  type: 'chat',
                  title: easemob.users[uid]['name'],
                  mid: TS.MID,
                  uid: uid,
                  last_message_time: (new Date()).valueOf(),
                  last_message_txt: '',
                  del: 0
                }

                window.TS.dataBase.room.add(room).then(function (i) {
                  easemob.cid = i
                  easemob.setNewCon(room)
                  $('#chat_more').html('').hide()
                  $('#chat_normal').show()
                  $('#chat_' + i).click()
                })
                /* 存在修改会话内容*/
              } else {
                if (item.del == 1) {
                  window.TS.dataBase.room.where('[mid+uid]')
                    .equals([TS.MID, uid])
                    .modify({
                      del: 0
                    })
                }
                $('#chat_more').html('').hide()
                $('#chat_normal').show()
                $('#chat_' + i).click()
              }
            })
        })
    } else { // 群聊
      var params = {
        groupname: groupname,
        desc: '暂无描述',
        members: members
      }
      axios.post('/api/v2/easemob/group', params)
        .then(function (response) {
          var room = {
            group: response.data.im_group_id,
            title: groupname,
            type: 'groupchat',
            mid: TS.MID,
            uid: members,
            last_message_time: (new Date()).valueOf(),
            last_message_txt: '',
            del: 0
          }

          window.TS.dataBase.transaction('rw?', window.TS.dataBase.room,
            function () {
              window.TS.dataBase.room.add(room).then(function (i) {
                easemob.updateUsers(members.split(','))
                easemob.setNewCon(room)
                $('#chat_more').html('').hide()
                $('#chat_normal').show()
                $('#chat_' + i).click()
              })
            })
        })
        .catch(function (error) {
          showError(error.response.data)
        })
    }
  },

  /*取消创建群组*/
  cancelGroup: function () {
    $('li[type="message"]').click()
  }
}

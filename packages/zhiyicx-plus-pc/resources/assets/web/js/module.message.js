var connect;
var socket = {};
socket = {
    onOpen : function(event) {
    },
    onSend : function(datas) {
        console.log('发送数据包：' + datas);
    },
    onMessage : function(datas) {
        var msg = datas;
        var messagetype = msg.data.substr(0, 1); // 获取消息第一位判断消息类型
        var data = JSON.parse(msg.data.substr(1)); // 数据转换

        // 接收消息
        if (messagetype == 2) {
            var dbMsg = data[1];
            delete dbMsg.type;
            dbMsg.time = dbMsg.mid / 8388608 + 1451577600000;
            dbMsg.hash = '';
            dbMsg.owner = window.TS.MID;
            dbMsg.read = message.datas.cid == dbMsg.cid ? 1 : 0;
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                // 消息放入本地
                window.TS.dataBase.message.put(dbMsg);

                // 修改房间最后消息时间
                window.TS.dataBase.room.where({cid: dbMsg.cid,owner: dbMsg.owner}).modify({
                    last_message_time: dbMsg.time,
                    last_message: dbMsg.txt
                })

                // 若房间为删除状态
                window.TS.dataBase.room.where({cid: dbMsg.cid,owner: dbMsg.owner}).first().then(results => {
                    if (results.del == 1) {
                        window.TS.dataBase.room.where({cid: dbMsg.cid,owner: dbMsg.owner}).modify({
                            del: 0
                        });
                        message.setNewCon(message.datas.list[dbMsg.cid]);
                    }
                });
            });

            // 若聊天窗口为打开状态
            if ($('.chat_dialog').length > 0) {
                // 当前会话，添加消息
                if (message.datas.cid == dbMsg.cid && window.TS.MID != dbMsg.uid) {
                    message.setMessage(dbMsg.txt, dbMsg.uid);
                }
                message.updateLastMessage(dbMsg.cid, dbMsg.txt);
            }

            // 若房间为新创建，新建房间
            if (!message.datas.list[dbMsg['cid']]) {
                // 获取对话信息
                axios.get('/api/v2/im/conversations/' + dbMsg['cid'])
                  .then(function (response) {
                    var _res = _.keyBy([response.data], 'cid');
                    message.datas.list = Object.assign({}, message.datas.list, _res);

                    // 获取用户信息
                    var _uids = _.split(response.data['uids'], ',');
                    _.forEach(_uids, function(v, k) {
                        if (v != window.TS.MID) {
                            var user = getUserInfo(v);
                            var _user = _.keyBy([user], 'id');
                            message.datas.users = Object.assign({}, message.datas.users, _user);

                            response.data['other_uid'] = parseInt(v);
                        }
                    });

                    response.data.last_message = dbMsg.txt;
                    message.storeConversation(response.data);
                    message.setNewCon(response.data);
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            }
        }

        // 应答消息
        if (messagetype == 3) {
            // 消息同步
            if (data[0] === 'convr.msg.sync' && data[1].length) {
                data[1].forEach((value, index) => {
                    value.time = value.mid / 8388608 + 1451577600000;
                    value.owner = window.TS.MID;
                    value.read = 1;
                    // 对比本地存储的会话，写入新会话
                    window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                        // 查找我的最后一条消息
                        window.TS.dataBase.message.where({cid: value.cid, owner: window.TS.MID}).last(item => {
                            if ((item !== undefined && value.seq > item.seq) || item === undefined) {
                                // 写入数据库
                                window.TS.dataBase.message.put(value);
                                // 修改房间最后通话时间
                                window.TS.dataBase.room.where({cid: value.cid,owner: window.TS.MID}).modify({
                                    last_message_time: value.time,
                                    last_message: value.txt
                                });
                            }
                        });
                    })
                    .catch(e => {
                        console.log(e);
                    })
                });

                // 设置房间
                if (message.datas.list[data[1][0]['cid']]) {
                   message.setOuterCon(message.datas.list[data[1][0]['cid']]);
                   // 存储顺序
                   message.datas.seqs.push(data[1][0]['cid'])
                }
            }

            // 登录后同步消息
            if (data[0] === 'auth' && data[1].seqs) {
                var _message = message;

                data[1].seqs.forEach(seq => {
                    var msg = '2';
                    var message = [
                        'convr.msg.sync', {
                            "cid": parseInt(seq.cid),
                            "limit": 10,
                            "order": 1 // 倒序获取最新10条消息
                        }
                    ];
                    msg += JSON.stringify(message);
                    window.TS.webSocket.send(msg);
                });
            }

            // 接受消息
            if (data[0] === 'convr.msg') {
                // 添加到本地数据库
                var dbData = {
                    seq: data[1].seq,
                    mid: data[1].mid,
                    time: data[1].mid / 8388608 + 1451577600000,
                    owner: window.TS.MID
                };
                window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                    // 修改本地消息
                    window.TS.dataBase.message.where('hash').equals(data[2]).modify(dbData);
                    window.TS.dataBase.message.where('hash').equals(data[2]).first().then(results => {
                        // 更改房间的最后消息时间
                        window.TS.dataBase.room.where({cid: results.cid,owner: window.TS.MID}).modify({
                            last_message_time: results.time,
                            last_message: results.txt
                        });
                    });
                })
                .catch(window.TS.dataBase.ModifyError, function(e) {
                    console.error(e);
                }).catch(function(e) {
                    console.error(e);
                });
            }
        }
    },
    onError : function(event) {
        console.log('WebSocket错误');
    },
    onClose : function(event) {
        if(!window.TS.webSocket) return;
        window.TS.webSocket = null;
        console.log('WebSocket关闭：' + TS.BOOT['im:serve']);
    }
};

var message = {};
message = {
    datas: {seqs: []},

    init: function(datas) {
        // 获取对话列表
        message.getConversations();

        // 链接socket
        message.connect();

        // 设置未读数
        message.getUnreadMessage();
        var unread_message_timeout = window.setInterval(message.getUnreadMessage, 5000);
        message.getUnreadChats();
        var unread_chat_timeout = window.setInterval(message.getUnreadChats, 1000);
    },

    connect: function() {
        // 创建本地存储
        var db = new Dexie('TS');
        db.version(1).stores({
            // message
            message: "++, owner, cid, txt, uid, hash, mid, seq, time, read",

            // room
            room: "++, owner, cid, user_id, name, pwd, type, uids, last_message, last_message_time, del",
        });

        window.TS.dataBase = db;

        // 存储会话列表
        _.forEach(this.datas.list, function(value, key){
            value = message.storeConversation(value);
        });

        // 非连接状态及未连接状态 连接SOCKET
        if (window.TS.webSocket == null) {
            var url = '/api/v1/im/users';
            axios.get(url)
              .then(function (response) {
                if (response.data.status) {
                    try {
                        window.TS.webSocket = new window.WebSocket(TS.BOOT['im:serve'] + '?token=' + response.data.data.im_password);
                        window.TS.webSocket.onopen = function(evt) {
                            socket.onOpen(evt);
                        }
                        window.TS.webSocket.onmessage = function(evt) {
                            socket.onMessage(evt);
                        }
                        window.TS.webSocket.onclose = function(evt) {
                            socket.onClose(evt);
                        }
                    } catch (e) {
                        window.console.log(e);
                    }
                } else {
                    console.log('获取聊天授权失败');
                }
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        } else if (window.TS.webSocket && window.TS.webSocket.readyState != 1) {
            try {
                window.TS.webSocket = new window.WebSocket(TS.BOOT['im:serve']);
                window.TS.webSocket.onopen = function(evt) {
                    socket.onOpen(evt);
                }
                window.TS.webSocket.onmessage = function(evt) {
                    socket.onMessage(evt);
                }
                window.TS.webSocket.onclose = function(evt) {
                    socket.onClose(evt);
                }
            } catch (e) {
                window.console.log(e);
            }
        }
    },

    // 获取聊天对话列表
    getConversations: function() {

        var _this = this;
        axios.get('/api/v2/im/conversations/list/all')
          .then(function (response) {
            var uids = [];
            var _res = _.keyBy(response.data, 'cid');

            _.forEach(_res, function(value, key){
                var _uids = _.split(value['uids'], ',');
                _.forEach(_uids, function(v, k) {
                    if (v != window.TS.MID) {
                        uids.push(v);
                        value['other_uid'] = parseInt(v);
                    }
                });
            })

            // 获取对话中其他用户用户信息
            var users = getUserInfo(uids);
            var _users = _.keyBy(users, 'id');

            _this.datas.list = _res;
            _this.datas.users = _users;
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    },

    // 设置弹窗会话
    setInnerCon: function(room) {
        var _this = this;
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where({cid: room.cid,owner: window.TS.MID}).first(function(item){
                if (item.del == 0 || item.cid == _this.datas.cid) {
                    var css = room.cid == _this.datas.cid ? 'class="room_item current_room"' : 'class="room_item"';

                    var last_message = item.last_message == undefined ? '' : item.last_message;

                    var html = '<li ' + css + ' class="room_item" data-type="5" data-cid="' + room['cid'] + '" id="chat_' + room['cid'] + '">'
                                +      '<div class="chat_delete"><a href="javascript:;" onclick="message.delConversation(' + room['cid'] + ')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-delbtn1"></use></svg></a></div>'
                                +      '<div class="chat_left_icon">'
                                +          '<img src="' + getAvatar(_this.datas.users[room.other_uid]) + '" class="chat_svg">'
                                +       '</div>'
                                +      '<div class="chat_item">'
                                +          '<span class="chat_span">' + _this.datas.users[room.other_uid]['name'] + '</span>'
                                +          '<div>' + last_message + '</div>'
                                +      '</div>'
                                +  '</li>';

                    if (room.cid == _this.datas.cid) {
                        $('#chat_pinneds').after(html);
                        message.listMessage(_this.datas.cid);
                    } else {
                        $('#root_list').append(html);
                    }
                }

                // 设置为未删除
                if (item.del == 1 && item.cid == _this.datas.cid) {
                    window.TS.dataBase.room.where({cid: room.cid,owner: window.TS.MID}).modify({
                        del: 0
                    })
                }
            });
        });
    },

    // 设置侧边会话
    setOuterCon: function(room) {
        var _this = this;
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where({cid: room.cid,owner: window.TS.MID}).first(function(item){
                if (item.del == 0) {
                    var sidehtml = '<dd class="ms_chat" id="ms_chat_' + room.cid + '" data-cid="' + room.cid + '" data-name="' + _this.datas.users[room.other_uid]['name'] + '"><a href="javascript:;" onclick="message.openChatDialog(5, '+ room.cid +')"><img src="' + getAvatar(_this.datas.users[room.other_uid], 50) + '"/></a></dd>';

                    $('#ms_fixed').append(sidehtml);
                }
            });
        });
    },

    // 设置新会话
    setNewCon: function(room) {
        var _this = this;
        var sidehtml = '<dd class="ms_chat" id="ms_chat_' + room.cid + '" data-cid="' + room.cid + '" data-name="' + _this.datas.users[room.other_uid]['name'] + '"><a href="javascript:;" onclick="message.openChatDialog(5, '+ room.cid +')"><img src="' + getAvatar(_this.datas.users[room.other_uid], 50) + '"/></a></dd>';

        $('#ms_pinneds').after(sidehtml);

        if ($('.chat_dialog').length > 0) {
            var last_message = room.last_message == undefined ? '' : room.last_message;

            var html = '<li class="room_item" data-type="5" data-cid="' + room['cid'] + '" id="chat_' + room['cid'] + '">'
                        +      '<div class="chat_delete"><a href="javascript:;" onclick="message.delConversation(' + room['cid'] + ')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-delbtn1"></use></svg></a></div>'
                        +      '<div class="chat_left_icon">'
                        +          '<img src="' + getAvatar(this.datas.users[room.other_uid]) + '" class="chat_svg">'
                        +       '</div>'
                        +      '<div class="chat_item">'
                        +          '<span class="chat_span">' + this.datas.users[room.other_uid]['name'] + '</span>'
                        +          '<div>' + last_message + '</div>'
                        +      '</div>'
                        +  '</li>';
            $('#chat_pinneds').after(html);
        }
    },

    // 存储会话
    storeConversation: function(value) {
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where({cid: value.cid,owner: window.TS.MID}).first(function(item){
                if (item === undefined) {
                    value.last_message_time = 0;
                    value.last_message = '';
                    value.owner = window.TS.MID;
                    value.del = 0;
                    window.TS.dataBase.room.put(value);
                } else {
                    value.last_message = item.last_message;
                }
            });
        });
        return value;
    },

    // 删除会话
    delConversation: function(cid) {
        cancelBubble();
        var chat = $('#chat_' + cid);

        // 查找下个会话
        if (chat.next().length > 0) {
            var next_cid = chat.next().eq(0).data('cid');
        } else if (chat.prev('.room_item').length > 0) {
            var next_cid = chat.prev().eq(0).data('cid');
        } else {
            var next_cid = 0;
        }

        $('#ms_chat_' + cid).remove();
        if ($('.chat_dialog').length > 0) chat.remove();

        // 清空会话，或者展示下个会话的聊天列表
        if (next_cid == 0) {
            messageData(3);
        } else {
            $('#chat_' + cid).addClass('current_room');
            message.listMessage(next_cid);
        }

        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where({cid: cid,owner: window.TS.MID}).modify({
                del: 1
            });
        });
    },

    // 创建会话
    createConversation: function(user_id) {
        checkLogin();
        axios.post('/api/v2/im/conversations', { type: 0, uids: TS.USER.id + ',' + user_id })
          .then(function (response) {
                var _res = _.keyBy([response.data], 'cid');
                message.datas.list = Object.assign({}, message.datas.list, _res);

                // 获取用户信息
                var user = getUserInfo(user_id);
                var _user = _.keyBy([user], 'id');
                message.datas.users = Object.assign({}, message.datas.users, _user);

                response.data['other_uid'] = user_id;
                message.storeConversation(response.data);
                message.datas.seqs = _.union(message.datas.seqs, [response.data.cid]);
                // 如果侧边栏没有该会话，则创建
                if ($('#ms_chat_' + response.data.cid).length == 0) {
                    message.setOuterCon(response.data);
                }
                message.openChatDialog(5, response.data.cid);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    },

    // 查询消息列表
    listMessage: function(cid) {
        var _this = this;
        // 设置房间名
        $('#chat_wrap .body_title').html(message.datas.users[message.datas.list[cid]['other_uid']].name);
        $('#chat_wrap .clickMore').remove();
        $('#chat_cont').html('');

        // 查询消息
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
            window.TS.dataBase.message
                .orderBy('seq')
                .filter( (item) => {
                    return (item.seq != -1 && item.cid === cid);
                })
                .limit(15)
                .reverse()
                .toArray( array => {
                    var messageList = [];
                    var messageBody = {};
                    if(array.length) {
                        array = array.reverse();
                        array.forEach((value) => {
                            message.setMessage(value.txt, value.uid);
                        });
                    }
                });
        });
    },

    // 发送消息
    sendMessage: function(cid) {
        var txt = $.trim($('#chat_text').val());
        if (txt == '') {
            $('#chat_text').focus();
            return false;
        }
        var msg = '2';
        var time = (new Date()).getTime();
        var hash = time + '_'  + TS.MID;
        var message_one = [
            'convr.msg',
            {
            "cid": cid, // 对话id
            "type": 0, // 消息的类型，私密消息
            "txt": txt, // 消息的文本内容，字符串，可选，默认空字符串
            "rt": false, // 非实时消息
            },
            hash
        ];
        msg += JSON.stringify(message_one);

        if(!window.TS.webSocket || window.TS.webSocket.readyState != 1) {
            message.connect();

            connect = window.setTimeout(function(){
                if(window.TS.webSocket.readyState == 1) {
                    message.sendMessage(cid);
                }
            },1000);
        } else {
            clearTimeout(connect);
            window.TS.webSocket.send(msg);
            var dbMsg = {
                    cid: cid,
                    uid: window.TS.MID,
                    txt: txt,
                    hash: hash,
                    mid: 0,
                    seq: -1,
                    time: 0,
                    read: 1,
                    owner: window.TS.MID
                };
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
                window.TS.dataBase.message.put(dbMsg);
            })
            .catch (function (e) {
                console.error(e);
            });

            message.setMessage(txt, window.TS.MID);
        }

    },

    // 设置消息
    setMessage: function(txt, user_id){
        $('#chat_text').val('');
        txt = txt.replace(/\r\n/g, "<br>");
        txt = txt.replace(/\n/g, "<br>");

        if (user_id != window.TS.MID) {
            html = '<div class="chatC_left">'
                 +      '<img src="' + getAvatar(this.datas.users[user_id]) + '" class="chat_avatar">'
                 +      '<span class="chat_left_body">' + txt + '</span>'
                 + '</div>';
        } else {
            html = '<div class="chatC_right">'
                 +      '<img src="' + getAvatar(TS.USER) + '" class="chat_avatar fr">'
                 +      '<span class="chat_right_body">' + txt + '</span>'
                 + '</div>';
        }
        $('#chat_cont').append(html);
        var div = document.getElementById('chat_scroll');
        div.scrollTop = div.scrollHeight;
    },

    // 获取未读消息数量
    getUnreadMessage: function() {
        // 获取未读通知数量
        axios.post('/api/v2/user/notifications')
          .then(function (response) {
            console.log(response)
                TS.UNREAD.notifications = response.headers('unread-notification-limit');

                message.setUnreadMessage();
          })
          .catch(function (error) {
            showError(error.response.data);
          });

        // 获取未读点赞，评论，审核通知数量
        axios.get('/api/v2/user/unread-count')
          .then(function (response) {
                var res = response.data;
                res.counts = res.counts ? res.counts : {};
                TS.UNREAD.comments = res.counts.unread_comments_count ? res.counts.unread_comments_count : 0;
                TS.UNREAD.last_comments = res.comments.length > 0 ? res.comments[0]['user']['name'] : '';
                TS.UNREAD.likes = res.counts.unread_likes_count ? res.counts.unread_likes_count : 0;
                TS.UNREAD.last_likes = res.likes.length > 0 ? res.likes[0]['user']['name'] : '';

                // 审核通知数量
                var pinneds_count = 0;
                for(var i in res.pinneds){
                    pinneds_count += res.pinneds[i]['count'];
                }
                TS.UNREAD.pinneds = pinneds_count;

                message.setUnreadMessage();
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    },

    // 获取未读聊天消息数量
    getUnreadChats: function() {
        // 获取未读消息数量
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, window.TS.dataBase.message, () => {
            window.TS.dataBase.room.where({owner: window.TS.MID}).each( value => {
                window.TS.dataBase.message.where({read: 0, cid: value.cid}).count( number => {
                    if (number > 0) {
                        message.setUnreadChat(value.cid, number);
                    }
                });
            });
        })
        .catch(e => {
            console.log(e);
        });
    },

    // 设置未读消息数量
    setUnreadMessage: function() {
        for (var i in TS.UNREAD) {
            if (TS.UNREAD[i] > 0) {
                $('#ms_' + i + ' .unread_div').remove();
                $('#ms_' + i).prepend(message.formatUnreadHtml(0, TS.UNREAD[i]));
                if ($('.chat_dialog').length > 0) {
                    $('#chat_' + i + ' .chat_unread_div').remove();
                    $('#chat_' + i).prepend(message.formatUnreadHtml(1, TS.UNREAD[i]));
                }
            } else {
                $('#ms_' + i + ' .unread_div').remove();
                $('#chat_' + i + ' .chat_unread_div').remove();
            }

            if ((i == 'comments' || i == 'likes') && TS.UNREAD['last_' + i]) {
                var txt = i == 'comments' ? '评论了你' : '点赞了你';
                $('#chat_' + i).find('.last_content').html(TS.UNREAD['last_' + i] + txt);
            }
        }
    },

    // 设置未读聊天消息数量
    setUnreadChat: function(cid, value) {
        $('#ms_chat_' + cid + ' .unread_div').remove();
        $('#ms_chat_' + cid).prepend(message.formatUnreadHtml(0, value));
        if ($('.chat_dialog').length > 0) {
            $('#chat_' + cid + ' .chat_unread_div').remove();
            $('#chat_' + cid).prepend(message.formatUnreadHtml(1, value));
        }
    },

    // 设置消息已读
    setRead: function(type, cid) {
        if (type == 0) { // 消息
            TS.UNREAD[cid] = 0;
            $('#ms_' + cid).find('.unread_div').remove();
            $('#chat_' + cid).find('.chat_unread_div').remove();
        } else { // 聊天
            $('#ms_chat_' + cid).find('.unread_div').remove();
            $('#chat_' + cid).find('.chat_unread_div').remove();
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
                window.TS.dataBase.message.where({owner: window.TS.MID, cid: cid}).modify({
                    read: 1
                });
            });
        }
    },

    // 获取未读消息数量html
    formatUnreadHtml: function(type, value) {
        if (type == 0) {
            var html = '<div class="unread_div"><span>' + (value > 99 ? 99 : value) + '</span></div>';
        } else {
            var html = '<div class="chat_unread_div"><span>' + (value > 99 ? 99 : value) + '</span></div>';
        }
        return html;
    },

    // 更新最后一条消息
    updateLastMessage: function(cid, txt) {
        $('#chat_' + cid + ' .chat_item').find('div').html(txt);
    },

    // 打开消息对话框
    openChatDialog: function(type, cid) {
        if (type == 5) { // 聊天消息
            message.setRead(1, cid);
            ly.load('/message/' + type + '/' + cid, '', '720px', '572px');
        } else {
            ly.load('/message/' + type, '', '720px', '572px');
        }
    }
}

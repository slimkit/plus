var mention = {
  $textarea: null, // 输入框 jQuery 对象
  $hiddenObj: null, // 镜像输入框 jQuery 对象
  $atList: null, // at 列表 jQuery 对象
  savedCursor: 0, // 保存的光标位置
  objString: '', // 输入框文本
  cursorPosition: 0,   // 记录当前光标位置
  beforeCursorString: '', // 光标之前的字符串
  atLocation: -1, // @字符在光标前出现的最近位置
  indexString: '', // 光标和光标前首个@字符之间的字符串
  positionString: '', // 从开始到@字符之间的字符串

  /**
   * 获取光标当前位置
   */
  getCursorPosition: function () {
    return this.$textarea[0].selectionEnd
  },

  /**
   * 设置光标位置
   */
  setCursorPosition: function (pos) {
    this.$textarea[0].setSelectionRange(pos, pos)
    this.$textarea[0].focus()
  },

  /**
   * 获取字符串长度 中文字符记2 英文字符记1
   * @param {String} string
   */
  getLength: function (string) {
    var realLength = 0
    var charCode = -1

    for (var i = 0; i < string.length; i++) {
      charCode = string.charCodeAt(i)
      realLength += (charCode >= 0 && charCode <= 128) ? 1 : 2
    }
    return realLength
  },

  /**
   * 处理选择某用户
   */
  handleString: function (text) {
    // 将 textarea 分成三块，@之前的text1、@+联系人+' '的text2、光标之后的text3
    var text1 = this.objString.substr(0, this.atLocation)
    var text2 = '@' + text + ' '
    var text3 = this.objString.substr(this.cursorPosition,
      this.getLength(this.objString) - this.cursorPosition)
    this.$textarea.val(text1 + text2 + text3)

    this.$atList.empty().hide()

    var position = text1.length + text2.length
    this.setCursorPosition(position)

    checkNums(this.$textarea, 255, 'nums')
  },

  /**
   * 展示 at 列表
   */
  dynamicDisplayAtList: function () {
    var list = []

    if (this.indexString !== undefined && this.indexString.length > 1) {
      var keyword = this.indexString.substring(1, this.indexString.length)
      var _this = this
      axios.get('/api/v2/users', { params: { name: keyword, limit: 8 } })
        .then(function (res) {
          var result = res.data.slice(0, 8)
          list = []
          // 填充列表
          result.forEach(function (user) {
            list.push(user.name)
          })

          var dom = ''
          if (list.length > 0) {
            dom = keyword.length > 1
              ? '<li class="list-title">选择好友或直接输入</li>'
              : '<li class="list-title">选择用户名或轻敲空格完成输入</li>'
            list.forEach(function (user) {
              dom += '<li class="list-content">' + user + '</li>'
            })
          } else {
            dom = '<li class="list-title">没有找到该用户</li>'
          }

          _this.$atList.html(dom)

          var $listClick = _this.$atList.find('li.list-content')
          if ($listClick.length > 0) {
            $listClick.eq(0).addClass('active')
          }
        })

    } else {
      list = []
      TS.USER_FOLLOW_MUTUAL.forEach(function (user) {
        list.push(user.name)
      })
      var dom = ''
      if (list !== undefined && list.length > 0) {
        dom = this.indexString.length > 1
          ? '<li class="list-title">选择好友或直接输入</li>'
          : '<li class="list-title">选择用户名或轻敲空格完成输入</li>'
        for (var i = 0; i <
        list.length; i++) dom += '<li class="list-content">' + list[i] + '</li>'
      } else {
        dom = '<li class="list-title">请输入要提醒的人</li>'
      }

      this.$atList.html(dom)

      var $listClick = this.$atList.find('li.list-content')
      if ($listClick.length > 0) {
        $listClick.eq(0).addClass('active')
      }
    }
  },

  /**
   * 当按下鼠标或键盘后触发的事件
   */
  objChange: function (event) {
    this.objString = this.$textarea.val() // 取值
    this.cursorPosition = this.getCursorPosition() // 光标当前位置
    this.beforeCursorString = this.objString.substr(0, this.cursorPosition) // 光标之前的字符串
    this.atLocation = this.beforeCursorString.lastIndexOf('@') // 找到 @ 字符在光标签出现的最近位置
    this.indexString = this.objString.substr(this.atLocation,
      this.cursorPosition - this.atLocation) // 记录光标和@间的字符串，判断是否含有空格
    this.positionString = this.objString.substr(0, this.atLocation) // 获取从开始到光标之前@之间的字符串 定位at弹框的位置
    if (this.$atList.css('display') === 'block') {
      // 显示at中的选项列表
      var key = event.keyCode
      var $list = this.$atList.find('.list-content')
      var len = $list.length
      var index = $list.index($(this.$atList.find('.active')))

      if (key === 40) { // 下箭头
        this.setCursorPosition(this.savedCursor)
        var next = index === len - 1 ? -1 : index
        $list.removeClass('active')
        $list.eq(next + 1).addClass('active')
        return
      } else if (key === 38) { // 上箭头
        this.setCursorPosition(this.savedCursor)
        var prev = index === 0 ? len : index
        $list.removeClass('active')
        $list.eq(prev - 1).addClass('active')
        return
      } else if (key === 13) { // 回车
        var text = this.$atList.find('li.active').text()
        this.handleString(text)
        return
      }
    }

    // 发现含有@ 并且@和光标之间没有空格和换行时
    if (this.beforeCursorString.indexOf('@') !== -1
      && this.indexString.indexOf(' ') === -1
      && this.indexString.indexOf('\n') === -1) {
      this.savedCursor = this.getCursorPosition(this.$textarea)

      this.dynamicDisplayAtList()

      this.$atList.show()
      this.$hiddenObj.html(this.positionString.replace(/\n/g, '<br>') +
        '<span class="at">@</span>')

      var $at = this.$hiddenObj.find('.at')

      this.$atList.css({
        left: this.getXY($at[0]).left + 2 + 'px',
        top: this.getXY($at[0]).top - this.$textarea[0].scrollTop + 28 + 'px'
      })
    } else {
      this.$atList.hide().empty()
    }
  },

  // 返回 obj 位置
  getXY: function (obj) {
    // 获取滑动条位置
    let scrollTop = obj.scrollTop || 0
    let scrollLeft = obj.scrollLeft || 0
    let isIE = document.all ? 2 : 0

    let position = {}
    position.left = obj.offsetLeft - isIE + scrollLeft
    position.top = obj.offsetTop - isIE + scrollTop

    return position
  }
}

$(function () {
  // 监听输入区按键， 弹出at弹框
  $('#feed_content').on('keyup mouseup', function (event) {
    event.preventDefault()
    mention.$textarea = $('#feed_content')
    mention.$hiddenObj = $('#mirror')
    mention.$atList = $('#mention_list')
    mention.objChange(event)
  })

  $('.feed_content, .detail_comment, .profile_list, .feed_bottom')
    .on('keyup mouseup', '.ev-comment-editor', function (event) {
      event.preventDefault()
      mention.$textarea = $(this)
      mention.$hiddenObj = $(this).next('.ev-mirror')
      mention.$atList = $(this).parent().children('.ev-mention-list')
      mention.objChange(event)
    })

  // 监听选择框
  $('#mention_list').on('click', '.list-content', function (event) {
    mention.handleString($(event.target).text())
  })
  $('#mention_list').on('mouseover', '.list-content', function () {
    $('.list-content').removeClass('active')
    $(this).addClass('active')
  })
})

(function($) {
  // 获取路径
  jQuery.fn.PicShow = function(option) {
    option.id = option.id || $(this).attr('id')
    option.width = option.width || 80//多图片显示宽度
    option.height = option.height || 80//多图片显示高度
    option.smallWidth = option.smallWidth || 58
    option.smallHeight = option.smallHeight || 58
    option.oneWidth = option.oneWidth || 120//单个图片显示宽度
    option.oneHeight = option.oneHeight || 120//单个图片显示高度
    option.bigWidth = option.bigWidth || 635 //大图片显示宽度
    option.bigHeight = option.bigHeight || 400//大图片显示高度
    option.rowSize = option.rowSize || 4//每行显示多少个
    if (option.id == null || option.id == '') {
      $(this).attr('id', new Date().getTime())
      option.id = $(this).attr('id')
    }
    var me = $(this)
    var m
    option.data = {}//图片数组   id img bigimg img
    var current_i = 0//当前中间显示的图片在数组中的位置 important
    var currentPage = 1//底下小图片当前第几页
    var currentPageSize = 0//总共多少页
    var morePic = false

    function narrowMorePicShow () {//多图片 小图片显示
      var _picshow_narrow_exist = m.find('#' + option.id + '_picshow_narrow')
      if (_picshow_narrow_exist.size() > 0) {
        _picshow_narrow_exist.show()
        return
      }
      var morepicArray = new Array()
      morepicArray.push(
        '<div id="' + option.id + '_picshow_narrow" class="PicShow">')
      morepicArray.push('<ul>')
      for (var i = 0; i < option.data.length; i++) {
        morepicArray.push('<li>')
        morepicArray.push(
          '<img id="' + option.data[i].id + '" height="' + option.height +
          'px" width="' + option.width + 'px" class="bigcursor" curLoc="' + i +
          '" ')
        morepicArray.push('src="' + option.data[i].img + '" alt="">')
        morepicArray.push('</li>')
      }
      morepicArray.push('</ul>')
      morepicArray.push('</div>')
      $('#' + option.id).append(morepicArray.join(''))
      var _picshow_narrow = $('#' + option.id + '_picshow_narrow')
      _picshow_narrow.css('width',
        ((option.width + 6) * option.rowSize + 45) + 'px')
    }

    function expandMorePicShow () {//多图片 大图片显示
      var id = m.attr('id')
      var _picshow_expand_exist = m.find('.PicShowExpand')
      if (_picshow_expand_exist.size() > 0) {
        getCurrentPicChange()
        expandCenterUpdate()
        _picshow_expand_exist.show()
        return
      }
      var b = new Array()
      b.push('<div id="' + id + '_picshow_expand" class="PicShowExpand">')
      b.push(expandTop())
      b.push(expandCenterImgHtml(option.data[current_i].id,
        option.data[current_i].img, option.data[current_i].width,
        option.data[current_i].height))
      b.push(expandFootMoreHtml())
      b.push('</div>')
      $('#' + id).append(b.join(''))
      $('#' + id + '_picshow_expand').css('width', option.bigWidth + 'px')
      getCurrentPicChange()
    }

    function narrowOnePicShow () {//单个图片 小图片显示
      var _picshow_narrow_exist = m.find('#' + option.id + '_picshow_narrow')
      if (_picshow_narrow_exist.size() > 0) {
        _picshow_narrow_exist.show()
        return
      }
      var onepicArray = new Array()
      onepicArray.push(
        '<div id="' + option.id + '_picshow_narrow" class="PicShow">')
      onepicArray.push(
        '<img id="' + option.data[0].id + '" class="bigcursor" curLoc="0" ')
      onepicArray.push('src="' + option.data[0].img + '" alt="">')
      onepicArray.push('</div>')
      m.append(onepicArray.join(''))
    }

    function expandOnePicShow () {//单个图片 大图片 显示
      var id = m.attr('id')
      var _picshow_expand_exist = m.find('.PicShowExpand')
      if (_picshow_expand_exist.size() > 0) {
        _picshow_expand_exist.show()
        return
      }
      var b = new Array()
      b.push('<div id="' + id + '_picshow_expand" class="PicShowExpand">')
      b.push(expandTop())
      b.push(expandCenterImgHtml(option.data[current_i].id,
        option.data[current_i].img, option.data[current_i].width,
        option.data[current_i].height))
      b.push('</div>')
      $('#' + id).append(b.join(''))
      $('#' + id + '_picshow_expand').css('width', option.bigWidth + 'px')
    }

    function expandTop () {
      var a = new Array()
      a.push('<p class="expand_top">')
      a.push(
        '<a  href="javascript:void(0);" class="retract"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-top"></use></svg>收起</a>')
      a.push('<i class="W_vline">|</i>')
      a.push(
        '<a href="javascript:;" class="showbig"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-enlarge"></use></svg>查看原图</a>')
      a.push('</p>')
      return a.join('')
    }

    function expandCenterImgHtml (id, img, width, height) {
      if (width > 555) {
        var w = 555
        var h = height * (555 / width)
      } else {
        var w = width
        var h = height
      }
      var c = new Array()
      c.push('<div id="expandCenterImg">')
      c.push(
        '<img src="' + img + '" id="' + id + '" class="centerImg" height=' + h +
        ' width=' + w + '>')
      c.push('</div>')
      return c.join('')
    }

    function expandFootMoreHtml () {
      var d = new Array()
      d.push('<div id="pic_choose_box" class="pic_choose_box">')
      d.push('<div class="stage_box">')
      d.push('<ul>')
      for (var i = 0; i < option.data.length; i++) {
        d.push('<li>')
        d.push('<a href="javascript:;" curLoc="' + i + '">')
        d.push(
          '<img id="' + option.data[i].id + '" height="' + option.smallWidth +
          'px" width="' + option.smallHeight + 'px" class="cursor" ')
        d.push('src="' + option.data[i].tinyimg + '" alt="">')
        d.push('</a>')
        d.push('</li>')
      }
      d.push('</ul>')
      d.push('</div>')
      d.push('</div>')
      return d.join('')
    }

    /**
     * 多图片中间图片改变时的方法
     */
    function expandCenterUpdate () {
      var width = option.data[current_i]['width']
      var height = option.data[current_i]['height']
      if (width > 555) {
        var w = 555
        var h = height * (555 / width)
      } else {
        var w = width
        var h = height
      }
      var expandCenterImg = m.find('#expandCenterImg')
      var c = new Array()
      c.push('<img src="' + option.data[current_i].img + '" id="' +
        option.data[current_i].id + '" class="centerImg" height=' + h +
        ' width=' + w + '>')
      expandCenterImg.children().remove()
      expandCenterImg.append(c.join(''))
      expandCenterImg.css('height', 'auto')
    }

    /**
     * 进入多图片大图时方法
     */
    function getCurrentPicChange () {
      currentPage = Math.ceil((parseInt(current_i) + 1) / 7)//当前第几页
      changeCurrentPage()
      var stage_boxa = m.find('#pic_choose_box .stage_box')
      stage_boxa.find('a').each(function(i) {
        $(this).removeClass('current')
        if (current_i == i) {
          $(this).addClass('current')
        }
      })
    }

    /**
     * 当前页面改变时  ------底下的图片改变样式
     */
    function changeCurrentPage () {
      var arrow_right_small = m.find('.arrow_right_small')
      var arrow_left_small = m.find('.arrow_left_small')
      var ico_pic_prev = m.find('.ico_pic_prev')
      var ico_pic_next = m.find('.ico_pic_next')
      if (currentPage > 1) {
        arrow_left_small.removeClass('big2').addClass('big1')
        ico_pic_prev.removeClass('text2').addClass('text1')
      } else {
        arrow_left_small.removeClass('big1').addClass('big2')
        ico_pic_prev.removeClass('text1').addClass('text2')
      }
      if (currentPage >= currentPageSize) {
        arrow_right_small.removeClass('big1').addClass('big2')
        ico_pic_next.removeClass('text1').addClass('text2')
      } else {
        arrow_right_small.removeClass('big2').addClass('big1')
        ico_pic_next.removeClass('text2').addClass('text1')
      }
      var stage_box_ul = m.find('#pic_choose_box .stage_box ul')
      var marginleft = (currentPage - 1) * 59 * 7
      stage_box_ul.css('margin-left', '-' + marginleft + 'px')
    }

    //当最大化的时候事件绑定----收起
    me.delegate('.retract', 'click', function() {
      m.find('.feed_images').show()
      m.find('.PicShowExpand').hide()
    })

    //当最大化的时候事件绑定----原图
    me.unbind('click').delegate('.showbig', 'click', function() {
      window.open(option.data[current_i].img)
    })

    //当最大化的时候事件绑定----收起
    me.delegate('.smallcursor', 'click', function() {
      m.find('.PicShowExpand').hide()
      m.find('.feed_images').show()
      // me.find("#"+option.id+"_picshow_narrow").show();
    })

    //当最大化的时候事件绑定----上一个
    me.delegate('.leftcursor', 'click', function() {
      if (current_i == 0) return
      current_i -= 1
      expandMorePicShow()
    })

    //当最大化的时候事件绑定----下一个
    me.delegate('.rightcursor', 'click', function() {
      if (current_i == option.data.length - 1) return
      current_i += 1
      expandMorePicShow()
    })

    //大图时 绑定 移动事件
    me.delegate('#expandCenterImg', 'mousemove', function(e) {
      if (morePic == false) {
        $(this).addClass('smallcursor')
        return
      }
      var x = e.clientX
      var left = $(this).offset().left
      var switchWidth = x - left
      if (switchWidth < 150 && current_i != 0) {
        $(this).
          removeClass('smallcursor').
          removeClass('rightcursor').
          addClass('leftcursor')
      } else if (switchWidth >= option.bigWidth - 230 && current_i !=
        option.data.length - 1) {
        $(this).
          removeClass('leftcursor').
          removeClass('smallcursor').
          addClass('rightcursor')
      } else if (switchWidth <= option.bigWidth - 230) {
        $(this).
          removeClass('leftcursor').
          removeClass('rightcursor').
          addClass('smallcursor')
      } else {
        $(this).
          removeClass('leftcursor').
          removeClass('rightcursor').
          removeClass('smallcursor')
      }
    })

    //大图时底下小图事件绑定
    me.delegate('.stage_box a', 'click', function() {
      var curLoc = parseInt($(this).attr('curLoc'))
      current_i = curLoc
      expandCenterUpdate()
      me.find('.stage_box a').removeClass('current')
      $(this).addClass('current')
    })

    //小图片放大事件
    me.delegate('.bigcursor', 'click', function(e) {
      m = $(this).parents('.feed_photos')
      option.data = []
      m.find('.feed_images img').each(function(i) {
        var size = $(this).data('size').split('x')
        var blur = $(this).data('blur') == ''
        var img = {
          id: 'img' + i,
          img: TS.SITE_URL + '/api/v2/files/' + $(this).data('id') + '?token=' +
            TS.TOKEN,
          tinyimg: TS.SITE_URL + '/api/v2/files/' + $(this).data('id') +
            '?w=58&h=58&token=' + TS.TOKEN,
          // img: 'http://test-plus.zhibocloud.cn/api/v2/files/' + $(this).data('id') + '?token=' + TS.TOKEN,
          // tinyimg: 'http://test-plus.zhibocloud.cn/api/v2/files/' + $(this).data('id')+ '?w=58&h=58&token=' + TS.TOKEN,
          width: size[0],
          height: size[1]
        }
        option.data.push(img)
      })

      if (option.data.length > 1) {
        morePic = true
      } else if (option.data.length == 0) {
        return
      }

      m.find('.feed_images').hide()
      var curLoc = parseInt($(this).attr('curLoc'))
      current_i = curLoc
      if (morePic) {
        expandMorePicShow()
      } else {
        expandOnePicShow()
      }
    })
  }

})($)

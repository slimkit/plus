var weibo = {};

/**
 * 上传后操作
 * @return void
 */
weibo.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="weibo.showImg();" src="' + TS.SITE_URL + '/api/v2/files/' + task_id + '"/ tid="' + task_id + '" amount="">';
    var del = '<span class="imgdel"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
};
weibo.showImg = function(){
    layer.photos({
      photos: '#file_upload_1-queue'
      ,anim: 0
      ,move: false
    });
};
/**
 * 发布动态
 * @return void
 */
weibo.postFeed = function() {
    // 登录判断
    checkLogin();

    // 付费免费
    var select = $('#feed_select').data('value');

    var reward_amounts = TS.BOOT.feed.items;

    if (select == 'pay') {
        if ($('.feed_picture').find('img').length > 0) { // 图片付费弹窗
            // 分享文字内容不超过255字
            if ($('#feed_content').text().length > initNums) {
                noticebox('分享内容长度不能超过' + initNums + '字', 0);
                return false;
            }
            var pay_box = '<div class="feed_pay_box"><p class="confirm_title">付费设置</p>';
            var images_box = '<div class="pay_images">';
            var info_box = '';
            $('.feed_picture').find('img').each(function(index) {
                var amount = $(this).attr('amount') != '' ? $(this).attr('amount') : '';

                var svg = amount == '' ? '' : '<svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-lock"></use></svg>';
                images_box += '<div class="pay_image"><img ' + (index == 0 ? 'class="current"' : '') + 'src="' + $(this).attr('src') + '" tid="' + $(this).attr('tid') + '" amount="' + amount + '"/>' + svg + '</div>';

                // 如果为第一张图，添加付费信息
                if (index == 0){
                    // 付费信息
                    info_box = '<div class="pay_info"><div class="pay_head clearfix">'
                                    + '<span class="pay_text">设置图片收费金额</span>'
                                    + '<span class="pay_btn pay_btn_yes">确定</span>'
                                    + '<span class="pay_btn pay_btn_reset">重置</span>'
                                + '</div>'
                                + '<div class="pay_body">';
                    $.each(reward_amounts, function (index, value) {
                        if (value > 0) {
                            info_box += '<span' + (amount == value ? ' class="current"' : '') + ' amount="' + value + '">' + value + '</span>';
                        }
                    });
                    info_box += '<input min="1" oninput="value=moneyLimit(value)"  type="number" placeholder="自定义金额，必须为整数" value="' + ($.inArray(amount, reward_amounts) ? '' : amount) + '">'
                             +'</div>';
                }
            });
            images_box += '<div class="triangle"></div></div>';
            info_box += '</div>'

            var html = pay_box + images_box + info_box + '</div>';
        } else { // 文字付费弹窗
            // 分享字数限制
            var strlen = $('#feed_content').text().length;
            var leftnums = initNums - strlen;
            if (leftnums < 0 || leftnums == initNums || strlen < TS.BOOT.feed.limit) {
                noticebox('付费动态内容长度为' + TS.BOOT.feed.limit + '-' + initNums + '字', 0);
                return false;
            }

            var amount = $('#feed_content').attr('amount') != '' ? $('#feed_content').attr('amount') : '';
            var pay_box = '<div class="feed_pay_box"><p class="pay_title">付费设置</p>';
            var info_box = '';
            info_box = '<div class="pay_info"><div class="pay_head clearfix">'
                            + '<span class="pay_text">设置文字收费金额</span>'
                        + '</div>';

            info_box +=  '<div class="pay_body">';
            $.each(reward_amounts, function (index, value) {
                if (value > 0) {
                    info_box += '<span' + (amount == value ? ' class="current"' : '') + ' amount="' + value + '">' + value + '</span>';
                }
            });
            info_box += '<input min="1" oninput="value=moneyLimit(value)"  type="number" placeholder="自定义金额，必须为整数" value="' + ($.inArray(amount, reward_amounts) ? '' : amount) + '">'
                +'</div>';
            info_box += '</div>';

            var html = pay_box + info_box + '</div>';
        }
        ly.confirm(html, '', '', function(){
            return weibo.doPostFeed('pay');
        });
    } else {
        // 分享字数限制
        var strlen = $('#feed_content').text().length;
        var leftnums = initNums - strlen;

        // 免费并仅有文字时验证1-255个字，其余不超过255字即可
        if ($('.feed_picture').find('img').length == 0  ? (leftnums < 0 || leftnums == initNums) : (leftnums < 0)) {
            noticebox('分享内容长度为1-' + initNums + '字', 0);
            return false;
        }
        weibo.doPostFeed('free');
    }
};

weibo.doPostFeed = function(type) {
    var _this = this;
    if (_this.lockStatus == 1) {
        return;
    }
    // 组装数据
    var data = {
        feed_content: $('#feed_content').text(),
        feed_from: 1,
        feed_mark: TS.MID + new Date().getTime(),
        topics: weibo.selectedTopics,
    };
    var images = [];
    if (type == 'free') { // 免费
        $('.feed_picture').find('img').each(function() {
            images.push({'id':$(this).attr('tid')});
        });
        if (images.length != 0) data.images = images;
    } else {  // 付费
        // 图片付费
        if ($('.pay_images').length > 0) {
            var has_amount = 0;
            $('.pay_images').find('img').each(function() {
                var amount = $(this).attr('amount');
                if (amount == '') {
                    images.push({'id':$(this).attr('tid')});
                } else {
                    has_amount = 1;
                    images.push({'id':$(this).attr('tid'), 'type': 'read', 'amount': amount});
                }
            });
            // 判断是否有图片添加付费信息
            if (!has_amount) {
                lyNotice('应配置至少一张图片费用');

                return false;
            }

            if (images.length != 0) data.images = images;
        } else {
            // 文字付费
            var amount = $('#feed_content').attr('amount');
            if (amount == '') {
                lyNotice('请配置付费金额');

                return false;
            }
            data.amount = amount;
        }
    }

    _this.lockStatus = 1;

    axios.post('/api/v2/feeds', data)
      .then(function (response) {
            $('.feed_picture').html('').hide();
            $('#feed_content').html('');
            weibo.afterPostFeed(response.data.id);
            noticebox('发布成功', 1);
            layer.closeAll();
            _this.lockStatus = 0;
      })
      .catch(function (error) {
        _this.lockStatus = 0;
        type == 'pay' ? lyShowError(error.response.data) : showError(error.response.data);
      });
};

weibo.afterPostFeed = function(feed_id) {
    axios.get('/feeds', {params: {feed_id:feed_id, isAjax:true} })
      .then(function (response) {
            if ($('#feeds_list').find('.no_data_div').length > 0) {
                $('#feeds_list').find('.no_data_div').remove();
            }
            $(response.data.data).hide().prependTo('#feeds_list').fadeIn('slow');
            $("img.lazy").lazyload({effect: "fadeIn"});
      })
      .catch(function (error) {
        showError(error.response.data)
      });
};
weibo.delFeed = function(feed_id, type) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var url = '/api/v2/feeds/' + feed_id+'/currency';
        axios.delete(url)
          .then(function (response) {
                layer.closeAll();
                if (type) {
                    noticebox('删除成功', 1, '/feeds');
                }
                $('#feed_' + feed_id).fadeOut();
          })
          .catch(function (error) {
            layer.closeAll();
            showError(error.response.data)
          });
    });
};

weibo.denounce = function(obj) {
    var feed_id = $(obj).attr('feed_id');
    var to_uid = $(obj).attr('to_uid');
    layer.prompt(function(val, index) {
        if (!val) {
            layer.msg(' 请填写举报理由', { icon: 0 });
        }
        var url = '';
        axios.post(url, { aid: feed_id, to_uid: to_uid, reason: val, from: 'weibo' })
          .then(function (response) {
            layer.msg(' 举报成功', { icon: 1 });
          })
          .catch(function (error) {
            showError(error.response.data)
          });
        layer.close(index);
    });
};
//微博申请置顶
weibo.pinneds = function (feed_id) {
    var url = '/api/v2/feeds/'+feed_id+'/currency-pinneds';
    pinneds(url, 'pinned');
};
weibo.addComment = function (row_id, type) {
    var url = '/api/v2/feeds/' + row_id + '/comments';
    comment.support.row_id = row_id;
    comment.support.position = type;
    comment.support.editor = $('#J-editor'+row_id);
    comment.support.button = $('#J-button'+row_id);
    comment.publish(url, function(res){
        $('.nums').text(comment.support.wordcount);
        $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
    });
};
weibo.payText = function(obj, tourl){
    checkLogin();

    var _this = $(obj);
    tourl = tourl || '';
    var feed_item = _this.parents('.feed_item');
    var id = feed_item.attr('id');
    var amount = feed_item.data('amount');
    var node = feed_item.data('node');

    var html = formatConfirm('购买支付', '<div class="confirm_money">' + amount + '</div>您只需要支付' + amount + '积分即可查看完整内容，是否确认支付？');
    ly.confirm(html, '', '', function(){
        var url = '/api/v2/currency/purchases/' + node;
        // 确认支付
        axios.post(url)
          .then(function (response) {
            layer.closeAll();
            if (tourl == '') {
                noticebox('支付成功', 1);
                /*获取动态完整内容*/
                axios.get('/api/v2/feeds/'+id.replace('feed_', ''))
                  .then(function (response) {
                    _this.text(response.data.feed_content);
                    _this.removeClass('fuzzy');
                    _this.removeAttr('onclick');
                    _this.parents('.feed_body').siblings('.feed_title').children('.date').removeAttr('onclick').attr('href', '/feeds/'+id.replace('feed_', ''));
                  })
                  .catch(function (error) {
                    showError(error.response.data)
                  });
            } else {
                noticebox('支付成功', 1, tourl);
            }
          })
          .catch(function (error) {
            layer.closeAll();
            showError(error.response.data)
          });
    })
};
weibo.payImage = function(obj){
        // 阻止冒泡
        cancelBubble();
        checkLogin();

        var _this = $(obj);
        var amount = parseFloat(_this.data('amount'));
        var node = _this.data('node');
        var file = _this.data('file');
        var image = _this.data('original');

        var html = formatConfirm('购买支付', '<div class="confirm_money">' + amount + '</div>您只需要支付' + amount + '积分即可查看高清大图，是否确认支付？');
        ly.confirm(html, '', '', function(){
            var url = '/api/v2/currency/purchases/' + node;
            /*确认支付*/
            axios.post(url)
              .then(function (response) {
                _this.attr('src', image + '&t=paid');
                _this.removeAttr('onclick');
                _this.addClass('bigcursor');
                layer.closeAll();
                noticebox('支付成功', 1);
              })
              .catch(function (error) {
                layer.closeAll();
                showError(error.response.data)
              });
        })
};

/**
 * 显示话题选择框
 *
 * @param {boolean} [show] 是否为显示, 如果不填则表示切换
 */
weibo.showTopics = function(show) {
  var $el = $('.ev-view-topic-select')
  if (show === false) $el.slideUp('fast');
  else if (show === true) $el.slideDown('fast');
  else $el.slideToggle('fast');
}

/**
 * 搜索话题
 * using lodash.debounce with 450ms
 */
weibo.searchTopics = _.debounce(function(el) {
  var query = $(el).val();
  if (query) {
    $('.ev-view-topic-hot').text('搜索中...');
  }
  $('.ev-view-topic-list').empty();
  axios.get('/api/v2/feed/topics', { params: {q: query, limit: 8} })
    .then(function(res) {
      var result = res.data.slice(0, 8);
      if (result.length) {
        $('.ev-view-topic-hot').empty();
        // 填充列表
        result.forEach(function(topic) {
          // 高亮关键字
          var regex = new RegExp(query, 'g');
          var nameMarked = topic.name.replace(regex, '<span style="color: #59b6d7;">$&<span>');
          $('.ev-view-topic-list').append('<li data-topic-id="'+topic.id+'" data-topic-name="'+topic.name+'">'+nameMarked+'</li>');
        });
      } else {
        $('.ev-view-topic-hot').text('没有找到结果');
      }
    })
}, 450)

/**
 * 显示话题选择框
 *
 * @param {boolean} [show] 是否为显示, 如果不填则表示切换
 */
weibo.showMention = function(show) {
    var $el = $('.ev-view-mention-select')
    if (show === false) $el.slideUp('fast');
    else if (show === true) $el.slideDown('fast');
    else $el.slideToggle('fast');
}

$(function() {

    weibo.selectedTopics = [];

    var defaultTopic = $('.ev-selected-topics').find('.ev-delete-topic').data('topic-id');
    if (defaultTopic) weibo.selectedTopics.push(defaultTopic);

    // 图片删除事件
    $(".feed_post").on("click", ".imgdel", function() {
        $(this).parent().remove();
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length == 0) {
            $('.uploadify-queue-add').remove();
            $('#file_upload_1-queue').hide();
        }
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length != 0  && $('.uploadify-queue-add').length == 0 ){
            var add = '<a class="feed_picture_span uploadify-queue-add"></a>'
            $('.uploadify-queue').append(add);
        }
    });

    // 捕获添加话题
    $(document).on('click', '.ev-view-topic-list > li', function() {
      var id = $(this).data('topic-id')
      if (weibo.selectedTopics.indexOf(id) > -1) {
        weibo.showTopics(false);
        return layer.msg('你已经添加过这个话题了')
      };
      var name = $(this).data('topic-name')
      var html = '<li class="selected-topic-item ev-selected-topic-item">' + name +
        '<span data-topic-id="'+id+'" class="close ev-delete-topic">'+
        '<svg class="icon" aria-hidden="true" style="fill: #59d6b7;"><use xlink:href="#icon-close"></use></svg>'+
        '</span></li>';
      $('.ev-selected-topics').append(html);
      weibo.selectedTopics.push(id);
      weibo.showTopics(false);
    })

    // 捕获移除话题
    $(document).on('click', '.ev-delete-topic', function() {
      var id = $(this).data('topic-id');
      $(this).parents('.ev-selected-topic-item').remove();
      var index = weibo.selectedTopics.indexOf(id)
      if (index > -1) weibo.selectedTopics.splice(index, 1);
    })

    // 捕获添加话题按钮以显示话题
    $(document).on('click','.ev-btn-topic', function(event) {
      event.stopPropagation();  // 禁止冒泡
      weibo.showTopics(true);
    })

    // 捕获话题搜索框以外的点击事件以关闭话题搜索框
    $(document).on('click', function(event) {
      var $parent = $(event.target).closest('.ev-view-topic-select')
      if (!$parent.length) weibo.showTopics(false)
    })

    // 捕获 at 按钮
    $(document).on('click', '.ev-btn-mention', function(event) {
      event.stopPropagation();
      weibo.showMention(true);
    })

    // 捕获话题搜索框以外的点击事件以关闭话题搜索框
    $(document).on('click', function(event) {
      var $parent = $(event.target).closest('.ev-view-mention-select')
      if (!$parent.length) weibo.showMention(false)
    })

    // 捕获添加话题
    $(document).on('click', '.ev-view-follow-users > li', function() {
      var id = $(this).data('user-id')
      var name = $(this).data('user-name')

      $('#feed_content').html($('#feed_content').html() + " <span contenteditable=\"false\" style=\"color: #59b6d7;\">\u00ad@" + name + "\u00ad</span> ")

      weibo.showMention(false);
    })

    // 监听输入框按键 用于检测@符号键入和删除整段@内容
    var isShiftKey = false;
    var is2Key = false;

    // 监听输入框按键, 检测到退格键删除@区域
    $('#feed_content').on('keydown', function (event) {
      if (window.getSelection && event.which == 8) { // backspace
          // fix backspace bug in FF
          // https://bugzilla.mozilla.org/show_bug.cgi?id=685445
          var selection = window.getSelection();
          if (!selection.isCollapsed || !selection.rangeCount) {
              return;
          }

          var curRange = selection.getRangeAt(selection.rangeCount - 1);
          if (curRange.commonAncestorContainer.nodeType == 3 && curRange.startOffset > 0) {
              // we are in child selection. The characters of the text node is being deleted
              return;
          }

          var range = document.createRange();
          if (selection.anchorNode != this) {
              // selection is in character mode. expand it to the whole editable field
              range.selectNodeContents(this);
              range.setEndBefore(selection.anchorNode);
          } else if (selection.anchorOffset > 0) {
              range.setEnd(this, selection.anchorOffset);
          } else {
              // reached the beginning of editable field
              return;
          }
          range.setStart(this, range.endOffset - 1);

          var previousNode = range.cloneContents().lastChild;
          if (previousNode && previousNode.contentEditable == 'false') {
              // this is some rich content, e.g. smile. We should help the user to delete it
              range.deleteContents();
              event.preventDefault();
          }
      }
      if (event.which === 16 ) {
        isShiftKey = true;
      }
      if (event.which === 50) {
        is2Key = true
      }
      if (isShiftKey && is2Key) {
        weibo.showMention(true);
      }
    });

    $('#feed_content').on('keyup', function(event) {
      if (event.which === 16) {
        isShiftKey = false;
      }
      if (event.which === 50) {
        is2Key = false;
      }
    })

    // 微博分类tab
    $('.show_tab a').on('click', function() {
        var type = $(this).data('type');
        $('#feeds_list').html('');
        weibo.init({ container: '#feeds_list', type: type });
        $('.show_tab a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });

    // 付费图片弹窗
    $(document).on('click', '.locked_image', function() {
        checkLogin();

        var _this = $(this);
        var amount = parseFloat(_this.data('amount'));
        var node = _this.data('node');
        var file = _this.data('file');
        var image = _this.data('original');

        var html = formatConfirm('购买支付', '<div class="confirm_money">' + amount + '</div>您只需要支付' + amount + '积分即可查看高清大图，是否确认支付？');
        ly.confirm(html, '', '', function(){
            var url = '/api/v2/currency/purchases/' + node;
            /*确认支付*/
            axios.post(url)
              .then(function (response) {
                layer.closeAll();
                var img = '<img class="lazy per_image" data-original="' + image + '"/>';
                _this.replaceWith(img);
                $("img.lazy").lazyload({ effect: "fadeIn" });
                noticebox('支付成功', 1);
              })
              .catch(function (error) {
                layer.closeAll();
                showError(error.response.data)
              });
        })
    });

    // 付费设置确认
    $(document).on('click', '.pay_btn_yes', function() {
        // 输入框输入值
        var amount = $('.pay_body input').val();
        // 选择值
        var span_amount = $('.pay_body .current').attr('amount');
        if (amount == '' && typeof(span_amount) == 'undefined') {
            return false;
        }
        var real = amount == '' ? span_amount : amount;

        // 选择图片索引
        var index = $('.pay_image .current').parent().index();

        // 设置金额
        $('.pay_images .pay_image').eq(index).find('img').attr('amount', real);
        $('.feed_picture img').eq(index).attr('amount', real);

        // 添加标示
        if ($('.pay_images .pay_image').eq(index).find('svg').length == 0){
            $('.pay_images .pay_image').eq(index).append('<svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-lock"></use></svg>');
        }
    });

    $(document).on('click', '.pay_btn_reset', function() {
        // 选择图片索引
        var index = $('.pay_image .current').parent().index();

        // 设置金额
        $('.pay_images .pay_image').eq(index).find('img').attr('amount', '');
        $('.feed_picture img').eq(index).attr('amount', '');

        // 添加标示
        $('.pay_images .pay_image').eq(index).find('svg').remove();

        $('.pay_body span').removeClass('current');
        $('.pay_body input').val('');
    });

    // 付费图片点击
    $(document).on('click', '.pay_images img', function() {
        $(this).parents('.pay_images').find('img').removeClass('current');
        $(this).addClass('current');

        var amount = $(this).attr('amount');

        $('.pay_body').find('span').removeClass('current');
        $('.pay_body').find('input').val('');
        if (amount != '') {
            if (amount == '1') {
                $('.pay_body span[amount="1"]').addClass('current');
            } else if (amount == '5') {
                $('.pay_body span[amount="5"]').addClass('current');
            } else if (amount == '10') {
                $('.pay_body span[amount="10"]').addClass('current');
            } else {
                $('.pay_body input').val(amount);
            }
        }

        // 三角位置
        var left = $(this).parent().position().left + 3;
        $(this).parents('.pay_images').find('.triangle').css('margin-left', left);
    });

    // 收费金额选择
    $(document).on('click', '.pay_body span', function() {
        $(this).siblings().removeClass('current');
        $(this).addClass('current');
        $(this).parent().find('input').val('');

        // 若为文字付费
        if ($('.pay_images').length == 0) {
            $('#feed_content').attr('amount', $(this).attr('amount'));
        }
    });

    // 收费金额输入
    $(document).on('focus change', '.pay_body input', function() {
        $(this).parent().find('span').removeClass('current');

        // 若为文字付费
        if ($('.pay_images').length == 0) {
            $('#feed_content').attr('amount', $(this).val());
        }
    });
});

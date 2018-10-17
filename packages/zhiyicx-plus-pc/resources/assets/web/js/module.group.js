var grouped = {
    url: '',

    init: function(obj){
        checkLogin();
        this._this = obj;
        this.state = $(obj).attr('state');
        this.money = $(obj).attr('money');
        this.mode = $(obj).attr('mode');
        this.gid = $(obj).attr('gid');
        this.count = parseInt($('#join-count-'+this.gid).text());
        if (parseInt(this.state)) {
            this.unjoined();
        } else {
            this.joined();
        }
    },
    joined:function(){
        var html = '';
        var _self = this;
        var _this = this._this;
        grouped.url = '/api/v2/plus-group/currency-groups/'+this.gid;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;

        if (this.mode == 'paid') {
            var money = this.money;
            grouped.money = money
            html = '<div class="f-tac" style="padding:20px;">'+
                        '<h3 class="f-mb30">加入付费圈子</h3>'+
                        '<div><font color="red" size="4">'+money+'</font></div>'+
                        '<p>加入此圈子需要支付'+ money + TS.CURRENCY_UNIT + '，是否<br/>继续加入?</p>'+
                   '</div>';
        }
        if (this.mode == 'private') {
            html = '<div class="f-tac" style="padding:20px;">'+
                        '<h3 class="f-mb30">圈子审核</h3>'+
                        '<p>加入此圈子需要圈主或管理员审核，<br/>是否继续加入?</p>'+
                   '</div>';
        }
        if (html && html != '') {
            ly.confirm(html,'','',function(){
                if (TS.BOOT['pay-validate-user-password']) {
                    var html = '<div class="reward_box">'
                        +   '<p class="confirm_title">输入密码</p>'
                        +   '<div class="reward_amount">金额：' + grouped.money + TS.CURRENCY_UNIT + '</div>'
                        +   '<div class="reward_input_wrap">'
                        +       '<input id="J-password-confirm" placeholder="请输入登录密码" pattern="^.{6-16}$" type="password" maxlength="16" readonly onclick="this.removeAttribute(\'readonly\')" />'
                        +       '<button onclick="grouped.postJoined()">确认</button>'
                        +   '</div>'
                        +   '<div class="reward_forgot"><a href="'+ TS.SITE_URL +'/forget-password">忘记密码?</a></div>'
                        + '</div>';
                    layer.open({
                        type: 0,
                        title: '',
                        content: html,
                        btn: '',
                    })
                } else {
                    grouped.postJoined()
                }
            })
            _this.lockStatus = 0;
        } else {
            axios.put(grouped.url)
                .then(function (response) {
                    $(_this).text('已加入');
                    $(_this).attr('state', 1);
                    $(_this).addClass('joined');
                    noticebox('加入成功', 1)
                    $('#join-count-'+_self.gid).text(_self.count + 1);
                    _this.lockStatus = 0;
                })
                .catch(function (error) {
                    _this.lockStatus = 0;
                    showError(error.response.data);
                });
        }
    },
    postJoined(){
        if (TS.BOOT['pay-validate-user-password']) {
            var password = $('#J-password-confirm').val();
        }
        axios.put(grouped.url, {password: password})
            .then(function (response) {
                noticebox(response.data.message, 1);
            })
            .catch(function (error) {
                showError(error.response.data);
            });
        ly.close();
    },
    unjoined:function(){
        var _self = this;
        var _this = this._this;
        var url = '/api/v2/plus-group/groups/'+this.gid+'/exit';
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        axios.delete(url)
          .then(function (response) {
            $(_this).text('+加入');
            $(_this).attr('state', 0);
            $(_this).removeClass('joined');
            $('#join-count-'+_self.gid).text(_self.count - 1);
            _this.lockStatus = 0;
          })
          .catch(function (error) {
            _this.lockStatus = 0;
            showError(error.response.data);
          });
    },
    intro:function(status){
        if (status == 0) {
            $('.ct-intro-all').hide();
            $('.ct-intro').show();
        } else {
            $('.ct-intro-all').show();
            $('.ct-intro').hide();
        }
    }
}

var post = {};

/**
 * 上传后操作
 * @return void
 */
post.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="post.showImg();" src="' + TS.SITE_URL + '/api/v2/files/' + task_id + '"/ tid="' + task_id + '" amount="">';
    var del = '<span class="imgdel"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
};
post.showImg = function(){
    layer.photos({
        photos: '#file_upload_1-queue'
        ,anim: 0
        ,move: false
    });
};

post.createPost = function (group_id) {
    checkLogin();
    var _this = this;
    var images = [];
    var url = '/api/v2/groups/' + group_id + '/posts';
    if (_this.lockStatus == 1) {
        noticebox('请勿重复提交', 0);
        return;
    }
    $('.feed_picture').find('img').each(function() {
        images.push({'id':$(this).attr('tid')});
    });
    var data = {
        //title: $('#post_title').val(),
        content: $('#feed_content').text(),
        images: images,
        group_post_mark: TS.MID + new Date().getTime(),
    };

    var strlen = data.content.length;
    var leftnums = initNums - strlen;
    if ((leftnums < 0 || leftnums == initNums) && data.images.length < 1) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }
    _this.lockStatus = 1;

    axios.post(url, data)
      .then(function (response) {
        noticebox('发布成功', 1);
        $('.feed_picture').html('').hide();
        $('#post_title').val('');
        $('#feed_content').html('');
        post.afterCreatePost(group_id, response.data.id);
        _this.lockStatus = 0;
      })
      .catch(function (error) {
        _this.lockStatus = 0;
        showError(error.response.data);
      });
};

post.afterCreatePost = function (group_id, post_id) {
    var url = '/groups/getPost';
    axios.get(url, { params:{group_id: group_id, post_id: post_id} })
      .then(function (response) {
        if ($('#feeds_list').find('.no_data_div').length > 0) {
            $('#feeds_list').find('.no_data_div').remove();
        }
        $(response.data.data).hide().prependTo('#feeds_list').fadeIn('slow');
        $("img.lazy").lazyload({effect: "fadeIn"});
      })
      .catch(function (error) {
        showError(error.response.data);
      });
};

post.delPost = function(group_id, post_id, poi) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var tourl = '/groups/'+group_id;
        var url ='/api/v2/plus-group/groups/' + group_id + '/posts/' + post_id;
        axios.delete(url)
          .then(function (response) {
            $('#feed' + post_id).fadeOut();
            layer.closeAll();
            if (poi == 'read') {
                noticebox('删除成功', 1, tourl);
            }
          })
          .catch(function (error) {
            layer.closeAll();
            showError(error.response.data);
          });
    });
};
/* 置顶帖子 */
post.pinnedPost = function(post_id, type){
    var url = '/api/v2/plus-group/pinned/posts/'+post_id + '/create';
    if (type == 'pinned') {
        url = '/api/v2/plus-group/pinned/posts/'+post_id;
    }
    pinneds.show(url, type);
};
/* 撤销置顶 */
post.cancelPinned = function(post_id){
    ly.confirm(confirmTxt + '确定取消置顶帖子？', '', '', function(){
        axios.patch('/api/v2/plus-group/pinned/posts/'+post_id+'/cancel')
          .then(function (response) {
            noticebox(response.data.message, 1, 'refresh');
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    });
};

/**
 * 将帖子设置为精华
 */
post.setExcellent = function(post_id, set) {
  var text = set ? '确定将帖子设为精华？' : '确定要取消设置精华帖？';
  ly.confirm(confirmTxt + text, '', '', function() {
    ly.close();
    axios.put('/api/v2/group/posts/'+post_id+'/toggle-excellent')
      .then(function (res) {
        noticebox('操作成功！', 1, 'refresh');
      })
      .catch(function(error) {
        showError(error.response.data)
      })
  })
}

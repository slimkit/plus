/**
 * 文章投稿。
 */
$('.subject-submit').on('click', function() {
    var _this = this;
    if (_this.lockStatus == 1) {
        noticebox('请勿重复提交', 0);
        return;
    }
    var args = {
        'content': editor.value(),
        'from'   : $('#subject-from').val(),
        'image'  : $('#subject-image').val(),
        'title'  : $('#subject-title').val(),
        'author' : $('#subject-author').val(),
        'subject': $('#subject-abstract').val(),
        'cate_id': $('.zy_select').data('value'),
        'news_id': $('#news_id').val(),
        'tags'   : [],
    };
    $('.tags-box span').each(function(){
        args.tags.push($(this).data('id'));
    });
    if (!args.title || args.title.length > 20) {
        noticebox('标题为1 ~ 20个字', 0);
        return;
    }
    if (!args.cate_id) {
        noticebox('请选择文章分类', 0);
        return;
    }
    if (args.subject.length > 200) {
        noticebox('摘要内容不能超过200字', 0);
        return;
    }
    if (!args.content || args.content.length > 20000) {
        noticebox('内容为1 ~ 2W字', 0);
        return;
    }
    if (args.tags.length < 1) {
        noticebox('请选择标签', 0);
        return;
    }
    if (!args.image) {
        noticebox('请上传封面图片', 0);
        return;
    }
    if (!args.image || args.image == 0) {
        noticebox('请上传封面图片', 0);
        return;
    }
    if (args.news_id > 0) {
        return news.update(args);
    }

    var isVerified = TS.BOOT['news:contribute'].verified;
    var isPay = TS.BOOT['news:contribute'].pay;
    var pay_contribute = TS.BOOT['news:pay_conyribute'];

    if (isVerified && TS.USER.verified == null) {
        ly.confirm(formatConfirm('投稿提示', '成功通过平台认证的用户才能投稿，是否去认证？'), '去认证' , '', function(){
            window.location.href = '/settings/authenticate';
        });
        return false;
    } else if (isPay && pay_contribute > 0) {
        var html = formatConfirm('投稿提示', '<div class="confirm_money">' + pay_contribute + '</div>本次投稿您需要支付' + pay_contribute + TS.CURRENCY_UNIT + '，是否继续投稿？');
        ly.confirm(html, '投稿' , '', function(){
            news.args = args
            if (TS.BOOT['pay-validate-user-password']) return showPassword(TS.BOOT['news:pay_conyribute'], 'news.create(news.args)')
            else return news.create(args);
        });

        return false;
    }

    return news.create(args);
});

var news = {
    args: {},
    create: function (args) {
        var _this = this;
        if ( _this.lockStatus == 1) {
            return;
        }
         _this.lockStatus = 1;
        if (TS.BOOT['pay-validate-user-password']) args.password = $('#J-password-confirm').val();

        var url = '/api/v2/news/categories/'+args.cate_id+'/currency-news';
        axios.post(url, args)
          .then(function (response) {
            layer.closeAll();
            noticebox('投稿成功，请等待审核', 1, '/news');
          })
          .catch(function (error) {
            _this.lockStatus = 0;
            layer.closeAll();
            showError(error.response.data);
          });
    },
    update: function (args) {
        var _this = this;
        if ( _this.lockStatus == 1) {
            return;
        }
         _this.lockStatus = 1;
        var url = '/api/v2/news/categories/'+args.cate_id+'/news/'+args.news_id;
        axios.patch(url, args)
          .then(function (response) {
            layer.closeAll();
            noticebox('修改成功，请等待审核', 1, '/news');
          })
          .catch(function (error) {
            _this.lockStatus = 0;
            showError(error.response.data);
          });
    },
    delete: function (news_id, cate_id) {
        var url = '/api/v2/news/categories/'+cate_id+'/news/'+news_id;
        layer.confirm(confirmTxt + '确定删除这篇文章？', function() {
            axios.delete(url)
              .then(function (response) {
                layer.closeAll();
                noticebox(response.data.message, 1);
              })
              .catch(function (error) {
                _this.lockStatus = 0;
                showError(error.response.data);
              });
        });
    },
    pinneds: function (news_id) {
        var url = '/api/v2/news/'+news_id+'/currency-pinneds';
        pinneds.show(url, 'pinned');
    },
    addComment: function (row_id, type) {
        var url = '/api/v2/news/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    }
};

function subString(str, len, hasDot) {
    hasDot = hasDot ? hasDot : false;
    var newLength = 0;
    var newStr = "";
    var chineseRegex = /[^\x00-\xff]/g;
    var singleChar = "";
    var strLength = str.replace(chineseRegex, "**").length;
    for (var i = 0; i < strLength; i++) {
        singleChar = str.charAt(i).toString();
        newLength++;
        if (newLength > len) {
            break;
        }
        newStr += singleChar;
    }

    if (hasDot && strLength > len) {
        newStr += "...";
    }
    return newStr;
}

/**
 * 文本域自适应高度.
 *
 * @param {HTMLElement} 输入框元素
 * @param {Number} 设置光标与输入框保持的距离(默认0)
 * @param {Number} 设置最大高度(可选)
 */
var autoTextarea = function(c, d, h) {
    d = d || 0;
    var a = !!document.getBoxObjectFor || "mozInnerScreenX" in window, b = !!window.opera && !!window.opera.toString().indexOf("Opera"), e = function(j, k) {
        c.addEventListener ? c.addEventListener(j, k, false) :c.attachEvent("on" + j, k);
    }, f = c.currentStyle ? function(j) {
        var l = c.currentStyle[j];
        if (j === "height" && l.search(/px/i) !== 1) {
            var k = c.getBoundingClientRect();
            return k.bottom - k.top - parseFloat(f("paddingTop")) - parseFloat(f("paddingBottom")) + "px";
        }
        return l;
    } :function(j) {
        return getComputedStyle(c, null)[j];
    }, i = parseFloat(f("height"));
    c.style.resize = "none";
    var g = function() {
        var m, j, l = 0, k = c.style;
        if (c._length === c.value.length) {
            return;
        }
        c._length = c.value.length;
        if (!a && !b) {
            l = parseInt(f("paddingTop")) + parseInt(f("paddingBottom"));
        }
        m = document.body.scrollTop || document.documentElement.scrollTop;
        c.style.height = i + "px";
        if (c.scrollHeight > i) {
            if (h && c.scrollHeight > h) {
                j = h - l;
                k.overflowY = "auto";
            } else {
                j = c.scrollHeight - l;
                k.overflowY = "hidden";
            }
            k.height = j + d + "px";
            m += parseInt(k.height) - c.currHeight;
            document.body.scrollTop = m;
            document.documentElement.scrollTop = m;
            c.currHeight = parseInt(k.height);
        }
    };
    e("propertychange", g);
    e("input", g);
    e("focus", g);
};

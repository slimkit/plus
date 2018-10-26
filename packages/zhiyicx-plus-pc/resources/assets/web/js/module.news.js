
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
        layer.alert(buyTSInfo)
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

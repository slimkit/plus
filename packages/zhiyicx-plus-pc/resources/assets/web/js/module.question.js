
var QA = {
    addComment: function (row_id, type) {
        var url = '/api/v2/question-answers/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.support.top = false;
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    },
    adoptions: function (question_id, answer_id, back_url) {
        var url = '/api/v2/questions/' + question_id + '/currency-adoptions/' + answer_id;
        axios.put(url)
          .then(function (response) {
            noticebox('采纳成功', 1, back_url);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    },
    delAnswer: function (question_id, answer_id, callUrl) {
        callUrl = callUrl ? callUrl : '';
        url = '/api/v2/question-answers/' + answer_id;
        axios.delete(url)
          .then(function (response) {
             if (callUrl == '') {
                 $('#answer' + answer_id).fadeOut();
                 $('.qs' + question_id).text(parseInt($('.qs' + question_id).text())-1);
             } else {
                 noticebox('删除成功', 1, callUrl);
             }
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    },
    share: function (answer_id) {
        var bdDesc = $('#answer' + answer_id).find('.answer-body').text();
        var reg = /<img src="(.*?)".*?/;
        var imgs = reg.exec($('.show-answer-body').html());
        var img = imgs != null ? imgs[1] : '';
        bdshare.addConfig('share', {
            "tag" : "share_answerlist",
            'bdText' : bdDesc,
            'bdDesc' : '',
            'bdUrl' : TS.SITE_URL + '/questions/answer/' + answer_id,
            'bdPic': img
        });

        console.log(bdDesc);
    },
    look: function (answer_id, money, question_id, obj) {
        checkLogin();
        obj = obj ? obj : false;
        ly.confirm(formatConfirm('围观支付', '本次围观您需要支付' + money + TS.CURRENCY_UNIT + '，是否继续围观？'), '' , '', function(){
            var _this = this;
            if (_this.lockStatus == 1) {
                return;
            }
            _this.lockStatus = 1;
            var url ='/api/v2/question-answers/' + answer_id + '/currency-onlookers';
            axios.post(url)
              .then(function (response) {
                if (!obj) {
                    noticebox('围观成功', 1, 'refresh');
                } else {
                    noticebox('围观成功', 1);
                    var txt = response.data.answer.body.replace(/\@*\!\[\w*\]\(([https]+\:\/\/[\w\/\.]+|[0-9]+)\)/g, "[图片]");
                    var body = txt.length > 130 ? txt.substr(0, 130) + '...' : txt;
                    $(obj).removeClass('fuzzy');
                    $(obj).removeAttr('onclick');
                    $(obj).text(body);
                    $(obj).after('<a href="/questions/' + question_id + '/answers/' + answer_id + '" class="button button-plain button-more">查看详情</a>');
                    layer.closeAll();
                    _this.lockStatus = 0;
                }
              })
              .catch(function (error) {
                    _this.lockStatus = 0;
                    layer.closeAll();
                    showError(error.response.data);
              });
        });
    }
};

var question = {
    args: {},
    lockStatus: 0,
    amount: 0,
    create:function (topic_id) {
        checkLogin();
        var url = '/questions/create';
        if (topic_id) {
            url = '/questions/create?topic_id=' + topic_id;
        }
        window.location.href = url;
    },
    addComment: function (row_id, type) {
        var url = '/api/v2/questions/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.support.top = false;
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    },
    delQuestion: function(question_id) {
        ly.confirm(formatConfirm('提示', '确定删除这条信息？'), '' , '', function(){
            var url ='/api/v2/currency-questions/' + question_id;
            axios.delete(url)
              .then(function (response) {
                layer.closeAll();
                noticebox('删除成功', 1, '/questions');
              })
              .catch(function (error) {
                layer.closeAll();
                showError(error.response.data);
              });
        });
    },
    share: function (question_id) {
        var bdDesc = $('.richtext').text();
        var reg = /<img src="(.*?)".*?/;
        var imgs = reg.exec($('.show-body').html());
        var img = imgs != null ? imgs[1] : '';
        bdshare.addConfig('share', {
            "tag" : "share_questionlist",
            'bdText' : bdDesc,
            'bdDesc' : "",
            'bdUrl' : TS.SITE_URL + '/questions/' + question_id,
            'bdPic': img
        });
    },
    selected: function (question_id, money) {
        var html = formatConfirm('精选问答支付', '<div class="confirm_money">' + money + '</div>本次申请精选您需要支付' + money + TS.CURRENCY_UNIT + '，是否继续申请？');

        ly.confirm(html, '' , '', function(){
            if (TS.USER.currency.sum < money) {
                window.location.href = TS.SITE_URL + "/settings/currency/pay";
                return;
            }
            if (question.lockStatus == 1) {
                return;
            }
            question.lockStatus = 1;
            question.url ='/api/v2/user/currency-question-application/' + question_id;
            if (TS.BOOT['pay-validate-user-password']) question.showPassword(money, 'postApplyTop')
            else question.postApplyTop();
        });
    },
    showPassword: function(money, funName) {
        var html = '<div class="reward_box">'
            +   '<p class="confirm_title">输入密码</p>'
            +   '<div class="reward_amount">金额：' + money + TS.CURRENCY_UNIT + '</div>'
            +   '<div class="reward_input_wrap">'
            +       '<input id="J-password-confirm" placeholder="请输入登陆密码" pattern="^.{6-16}$" type="password" maxlength="16" readonly onclick="this.removeAttribute(\'readonly\')" />'
            +       '<button onclick="question.'+ funName +'()">确认</button>'
            +   '</div>'
            +   '<div class="reward_forgot"><a href="'+ TS.SITE_URL +'/forget-password">忘记密码?</a></div>'
            + '</div>';
        layer.open({
            type: 0,
            title: '',
            content: html,
            btn: '',
        })
    },
    postApplyTop: function() {
        if (TS.BOOT['pay-validate-user-password']) var password = $("#J-password-confirm").val()
        axios.post(question.url, {password: password})
            .then(function (response) {
                layer.closeAll();
                question.lockStatus = 0;
                noticebox('申请成功', 1);
            })
            .catch(function (error) {
                layer.closeAll();
                question.lockStatus = 0;
                showError(error.response.data);
            });
    },
    postAmount: function() {
        if (TS.BOOT['pay-validate-user-password']) {
            var password = $('#J-password-confirm').val()
        }
        axios.patch(question.url, {amount: args.amount, password: password})
            .then(function () {
                layer.closeAll();
                question.lockStatus = 0;
                noticebox('操作成功', 1, 'refresh');
            })
            .catch(function (error) {
                question.lockStatus = 0;
                lyShowError(error.response.data);
            });
    },
    amount: function (question_id) {
        checkLogin();
        var html = '<div class="reward_box">'
            + '<div class="reward_title">公开悬赏</div>'
            + '<div class="reward_text">选择公开悬赏金额</div>'
            + '<div class="reward_spans">';

        $.each(TS.BOOT.site.reward.amounts.split(','), function (index, value) {
            if (value > 0) {
                html += '<span num="' + value + '">' + value + '</span>';
            }
        });
        html += '</div>'
            + '<div class="reward_input">'
            + '<input min="1" oninput="value=moneyLimit(value)" type="number" placeholder="自定义金额，必须为整数">'
            + '</div>'
            + '</div>';

        ly.confirm(html, '公开悬赏', '', function(){
            var _this = this;
            if (_this.lockStatus == 1) {
                return;
            }
            _this.lockStatus = 1;
            var num = $('.reward_spans .current').length > 0 ? $('.reward_spans .current').attr('num') : '';
            var amount = $('.reward_input input').val();

            if (!num && !amount) {
                return false;
            }
            amount = num ? num : amount;
            if (TS.USER.currency.sum < amount) {
                window.location.href = TS.SITE_URL + "/settings/currency/pay";
                return false;
            }
            question.url = '/api/v2/currency-questions/' + question_id + '/amount';
            if (TS.BOOT['pay-validate-user-password']) question.showPassword(amount, 'postAmount')
            else question.postAmount()
        });
    }
};

var QT = {
    show : function () {
        checkLogin();
        var html = '<form class="topic-show">'
            + '<p class="topic-title">建议创建专题</p>'
            + '<div class="topic-from-row">'
            + '<input id="topic_name" type="text" name="name" placeholder="请输入专题名称">'
            + '</div>'
            + '<div class="topic-from-row">'
            + '<textarea id="topic_desc" name="description" placeholder="请输入专题相关描述信息"></textarea>'
            + '</div>'
            + '</form>';
        ly.alert(html, '提交', function(){
            var data = {
                name: $('#topic_name').val(),
                description: $('#topic_desc').val(),
            };
            axios.post('/api/v2/user/question-topics/application', data)
              .then(function (response) {
                noticebox('申请成功', 1);
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        });
    },
    follow: function (obj) {
        var _this = obj;
        var status = $(_this).attr('status');
        var topic_id = $(_this).attr('tid');
        var followCount = parseInt($('#tf-count-'+topic_id).text());
        topic(status, topic_id, function(){
            if (status == 1) {
                $(_this).text('+关注');
                $(_this).attr('status', 0);
                $(_this).removeClass('followed');
                $('#tf-count-'+topic_id).text(followCount - 1);
            } else {
                $(_this).text('已关注');
                $(_this).attr('status', 1);
                $(_this).addClass('followed');
                $('#tf-count-'+topic_id).text(followCount + 1);
            }
        });
    }
};

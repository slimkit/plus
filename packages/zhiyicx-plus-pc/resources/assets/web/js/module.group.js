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
                if (TS.BOOT['pay-validate-user-password'] && this.mode == 'paid') {
                    var html = '<div class="reward_box">'
                        +   '<p class="confirm_title">输入密码</p>'
                        +   '<div class="reward_amount">金额：' + grouped.money + TS.CURRENCY_UNIT + '</div>'
                        +   '<div class="reward_input_wrap">'
                        +       '<input id="J-password-confirm" placeholder="请输入登录密码" pattern="^.{6,16}$" type="password" maxlength="16" readonly onclick="this.removeAttribute(\'readonly\')" />'
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

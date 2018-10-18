// 三方注册/绑定提交
$('#oauth_btn').click(function() {
    var _this = $(this);
    var login = $('input[name="login"]').val();
    var name = $('input[name="name"]').val();
    var password = $('input[name="password"]').val();
    // 三方类型
    var othertype = $('input[name="other_type"]').val();
    var access_token = $('input[name="access_token"]').val();

    // 类型[bind:绑定, register:注册]
    var oauthtype = $('input[name="verifiable_type"]').val();
    var type = 'PUT';
    var title = '';
    if (oauthtype == 'bind') {
        if (login == '') {
            $('input[name="login"]').focus();
            return false;
        }
        if (password == '') {
            $('input[name="password"]').focus();
            return false;
        }
        title = '绑定';
    } else {
        if (strLen(name) < 2) {
            noticebox('用户名不能低于2个中文或4个英文', 0);
            $('input[name="name"]').focus();
            return false;
        }
        type = 'PATCH';
        title = '注册';
    }
    $('#auth_form').ajaxSubmit({
        type: type,
        url: '/api/v2/socialite/' + othertype,
        beforeSend: function() {
            _this.text(title + '中');
            _this.css('cursor', 'no-drop');
        },
        success: function(res) {
            noticebox(title + '成功，跳转中...', 1, '/socialite/token/' + res.token);
        },
        error: function(xhr) {

            showError(xhr.responseJSON);
        },
        complete: function() {
            _this.text('确定');
            _this.css('cursor', 'pointer');
        }
    });

    return false;
});
/* 基本资料更新 */
$('#J-user-info').on('click', function(e) {
    var getArgs = function() {
        var inp = $('#J-input input,#J-input select').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());

            if ($(inp[i]).attr('name') == 'sex') {
                args.set('sex', $('[name="sex"]:checked').val());
            }
        };
        return args.get();
    };
    var arg = getArgs();
    if (!args.data.name || args.data.name.length > 8) {
        noticebox('用户名长度为2-8位', 0);
        return;
    }
    if (!args.data.bio) {
        noticebox('个人简介不能为空', 0);
        return;
    }
    if (args.data.bio.length > 50) {
        noticebox('个人简介不能超过50个字符', 0);
        return;
    }
    axios.patch('/api/v2/user', arg)
      .then(function (response) {
        noticebox('资料修改成功', 1, 'refresh');
      })
      .catch(function (error) {
        noticebox('资料修改失败', 0, 'refresh');
      });
});

/**
 * 微博操作
 * @type {Object}
 */
var weibo = {
    delFeed : function(id) {
        var url = '/api/v2/feeds/' + id;
        layer.confirm('确定删除这条信息？', {icon: 3}, function(index) {
            axios.delete(url)
              .then(function (response) {
                $('#feed' + id).fadeOut();
                layer.close(index);
              })
              .catch(function (error) {
                layer.closeAll();
                showError(error.response.data);
              });
        });
    },
    pinneds: function (id) {
        var url = '/api/v2/feeds/'+id+'/pinneds';
        pinneds.show(url);
    },
    addComment: function (row_id, type) {
        var url = '/api/v2/feeds/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    }
}

var news = {
    pinneds: function(id) {
        var url = '/api/v2/news/'+id+'/pinneds';
        pinneds.show(url);
    },
    addComment: function(row_id, type) {
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

}

$(function() {
    $('#cover').on('change', function(e) {
        var file = e.target.files[0];

        var reader = new FileReader();
        reader.onload = function(e) {
            var base64 = e.target.result;
            var hash = md5(base64);

            var params = {
                filename: md5(file.name) + '.' + file.name.split('.').splice(-1),
                hash: hash,
                size: file.size,
                mime_type: file.type,
                storage: { channel: 'public' },
            }
            axios.post('/api/v2/storage', params).then(function(res) {
                var result = res.data;
                var node = result.node;
                var instance = axios.create();
                instance.request({
                    method: result.method,
                    url: result.uri,
                    headers: result.headers,
                    data: file,
                }).then(function(res) {
                    axios.patch('/api/v2/user', { bg: node }).then(function() {
                        noticebox('更换背景图成功', 1);
                        $('.profile_top_cover').css("background-image","url("+window.URL.createObjectURL(file)+")");
                    }).catch(function(error) {
                        showError(error.response.data);
                    })
                }).catch(function (error) {
                  showError(error.response.data);
                })
            }).catch(function (error) {
              showError(error.response.data);
            })
        }

        reader.readAsArrayBuffer(file);
    });

    // 显示跳转详情文字
    $('#content_list').on("mouseover mouseout", '.date', function(event){
        if(event.type == "mouseover"){
          var width = $(this).find('span').first().width();
            width = width < 60 ? 60 : width;
          $(this).find('span').first().hide();
          $(this).find('span').last().css({display:'inline-block', width: width});
        }else if(event.type == "mouseout"){
          $(this).find('span').first().show();
          $(this).find('span').last().hide();
        }
    });

    // 关注
    $('#follow').click(function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = $(this).attr('uid');
        follow(status, user_id, _this, function(target){
            if (target.attr('status') == 1) {
                target.find('span').text('关注');
                target.find('.icon').show();
                target.attr('status', 0);
                target.removeClass('followed');
            } else {
                target.find('.icon').hide();
                target.find('span').text('已关注');
                target.attr('status', 1);
                target.addClass('followed');
            }
        });
    });

});

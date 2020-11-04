@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<link rel="stylesheet" href="{{ URL::asset('assets/pc/css/message.css')}}"/>

<div class="chat_dialog">
    {{-- 类型 --}}
    <div class="chat_left_type">
        <ul>
            <li type="notice" @if($type != 0) class="current" @endif>
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-liaotiantubiao_8"></use>
                </svg>
            </li>
            <li type="message" @if($type == 0) class="current" @endif>
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-liaotiantubiao_3"></use>
                </svg>
            </li>
        </ul>
    </div>

    <div class="chat_container" id="chat_normal">
        <div class="chat_left_wrap">
            {{-- 通知 --}}
            <div class="chat_left @if($type == 0) hide" @endif" id="chat_left_notice">
                <ul>
                    <li @if($type == 8) class="current_room"@endif data-type="8" id="chat_at">
                        <div class="chat_left_icon">
                            <svg class="icon chat_svg" aria-hidden="true">
                                <use xlink:href="#icon-side-mention"></use>
                            </svg>
                        </div>
                        <div class="chat_item">
                            <span class="chat_span">@我的</span>
                            <div class="last_content"></div>
                        </div>
                    </li>
                    <li @if($type == 1)class="current_room"@endif data-type="1" id="chat_commented">
                        <div class="chat_left_icon">
                            <svg class="icon chat_svg" aria-hidden="true">
                                <use xlink:href="#icon-side-msg"></use>
                            </svg>
                        </div>
                        <div class="chat_item">
                            <span class="chat_span">评论的</span>
                            <div class="last_content"></div>
                        </div>
                    </li>
                    <li @if($type == 2)class="current_room"@endif data-type="2" id="chat_liked">
                        <div class="chat_left_icon">
                            <svg class="icon chat_svg" aria-hidden="true">
                                <use xlink:href="#icon-side-like"></use>
                            </svg>
                        </div>
                        <div class="chat_item">
                            <span class="chat_span">赞过的</span>
                            <div class="last_content"></div>
                        </div>
                    </li>
                    <li @if($type == 3)class="current_room"@endif data-type="3" id="chat_system">
                        <div class="chat_left_icon">
                            <svg class="icon chat_svg" aria-hidden="true">
                                <use xlink:href="#icon-side-notice"></use>
                            </svg>
                        </div>
                        <div class="chat_item">
                            <span class="chat_span">通知</span>
                        </div>
                    </li>
                    <li @if($type == 4)class="current_room"@endif data-type="4" id="chat_pinneds">
                        <div class="chat_left_icon">
                            <svg class="icon chat_svg" aria-hidden="true">
                                <use xlink:href="#icon-side-auth"></use>
                            </svg>
                        </div>
                        <div class="chat_item">
                            @if((isset($pinneds['news']) && count($pinneds['news']) > 0) || (isset($pinneds['feeds']) && count($pinneds['feeds']) > 0))
                                <span class="chat_span">审核通知</span>
                                <div>未审核的信息请及时处理</div>
                            @else
                                <span class="chat_span">审核通知</span>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>

            {{-- 聊天 --}}
            <div class="chat_left @if($type != 0) hide @endif" id="chat_left_message">
                <div class="chat_search">
                    <svg class="icon chat_search_icon" aria-hidden="true">
                        <use xlink:href="#icon-search"></use>
                    </svg>
                    <input class="chat_search_input" type="text" placeholder="搜索" value="" id="chat_search_input">
                    <a href="javascript:;" style="margin-left:5px;" id="chat_add_group">
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-liaotiantubiao_1"></use>
                        </svg>
                    </a>
                </div>
                <ul></ul>
            </div>
        </div>

        {{-- 消息内容 --}}
        <div class="chat_right @if($type == 0) hide @endif" id="message_wrap">
            <div class="body_title">评论的</div>

            {{-- 审核通知 --}}
            <div class="audit_top hide">
                <div data-value="" class="zy_select t_c gap12 message_select">
                    <span>动态评论置顶</span>
                    <ul>
                        <li data-value="4" class="active">动态评论置顶</li>
                        <li data-value="5">文章评论置顶</li>
                        <li data-value="6">帖子评论置顶</li>
                        <li data-value="7">帖子置顶</li>
                    </ul>
                    <i></i>
                </div>
            </div>

            <div class="chat_content_wrap" id="popupcover">
                <div class="chat_content">
                    <div class="message_cont" id="message_cont">
                    </div>
                </div>
            </div>
        </div>

        {{-- 聊天 --}}
        <div class="chat_right @if($type != 0) hide @endif" id="chat_wrap">
            <div class="body_title"></div>

            <div class="chat_content_wrap chat_height">
                <div class="chat_content" id="chat_scroll">
                    <div class="chat_cont" id="chat_cont">

                    </div>
                </div>
            </div>

            <div class="chat_bottom">
                <textarea placeholder="输入文字, ctrl+enter发送" class="chat_textarea" id="chat_text"></textarea>
                <span class="chat_send" onclick="easemob.sendMes({{ $cid }}, {{ $uid }})" id="chat_send">发送</span>
            </div>
        </div>
    </div>

    {{-- 群管理，群添加等 --}}
    <div class="chat_container" id="chat_more">
    </div>
</div>

<script type="text/javascript">
    var type = {{ $type }};
    var body_title = $('.body_title');
    var audit_top = $('.audit_top');
    var select = $(".message_select");
    var _loader = _.cloneDeep(loader);

    $(function () {
        axios.defaults.baseURL = TS.SITE_URL;
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

        select.on("click", function(e){
            e.stopPropagation();
            return !($(this).hasClass("open")) ? $(this).addClass("open") : $(this).removeClass("open");
        });

        select.on("click", "li", function(e){
            e.stopPropagation();
            layer.alert(buyTSInfo)
        });

        $(document).click(function() {
            select.removeClass("open")
        });

        // 切换类型
        $('.chat_left_type li').click(function(){
            $('#chat_more').hide();
            $('#chat_normal').show();
            var type = $(this).attr('type');
            var item = $('#chat_left_' + type);

            $('.chat_left').hide();
            item.show();

            if(item.find('.current_room').length > 0) {
                item.find('.current_room').click();
            } else {
                item.find('li').eq(0).click();
            }

            $(this).siblings().removeClass('current');
            $(this).addClass('current');
        })

        // 切换会话
        $('.chat_left').on('click', 'li', function () {
            $(this).hasClass("current_room") || ($(this).addClass("current_room").siblings('.current_room').removeClass('current_room'));
            var type = $(this).data('type');
            if (type === 0) {
                body_title.removeClass('hide');
                $('#message_wrap').hide();
                $('#chat_wrap').show();
                var cid = $(this).data('cid');
                var uid = $(this).data('uid');
                var title = $(this).data('name');
                $('#chat_send').attr('onclick', 'easemob.sendMes(' + cid + ', ' + uid + ')');

                // 设置为已读
                easemob.cid = cid;
                easemob.setRead(1, cid);
                easemob.listMes(cid);
                $('#chat_text').click();
            } else {
                easemob.cid = 0;
                $('#message_wrap').show();
                $('#chat_wrap').hide();
                messageData(type);
            }
        });

        // 删除会话样式
        $('.room_item').hover(function(){
            $(this).find('.chat_unread_div').hide();
            $(this).find('.chat_delete').show();
        }, function(){
            $(this).find('.chat_delete').hide();
            $(this).find('.chat_unread_div').show();
        })

        // 添加联系人
        $('#chat_add_group').click(function(){
            $('#chat_normal').hide();
            $('#chat_more').show();
        })

        // 会话搜索输入
        $("#chat_search_input").keyup(function(event){
            //利用event的timeStamp来标记时间，这样每次的keyup事件都会修改last的值
            message_search_last = event.timeStamp;
            setTimeout(function(){
                if(message_search_last - event.timeStamp == 0){
                    messageSearch();
                }
            }, 500);
        });

        $('#popupcover').on('click', function(event) {
            event.stopPropagation()
            layer.alert(buyTSInfo)
        })

        // 创建群组
        $('#chat_add_group').click(function(){
            $('#chat_normal').hide();
            var html =  '<div class="chat_ad">'
                        +'<input type="hidden" id="chat_selected_users" name=""/>'
                        +'<div class="chat_ad_title">选择联系人</div>'
                        +'<div class="chat_ad_container">'
                        +    '<div class="chat_ad_select">'
                        +        '<div class="chat_ad_search">'
                        +            '<svg class="icon chat_ad_search_icon" aria-hidden="true">'
                        +                '<use xlink:href="#icon-search"></use>'
                        +            '</svg>'
                        +            '<input type="text" placeholder="搜索" value="" id="chat_ad_search_input">'
                        +        '</div>'
                        +        '<div class="chat_ad_users_wrap">'
                        +            '<div class="chat_ad_users">'
                        +                '<ul id="chat_ad_users">'
                        +                '</ul>'
                        +            '</div>'
                        +        '</div>'
                        +    '</div>'
                        +    '<div class="chat_ad_selected">'
                        +        '<div class="chat_ad_selected_title">已选择联系人</div>'
                        +        '<div class="chat_ad_selected_users_wrap">'
                        +            '<div class="chat_ad_selected_users">'
                        +                '<ul>'
                        +                '</ul>'
                        +                '<div class="chat_ad_selected_op layui-layer-btn layui-layer-btn-">'
                        +                '<a href="javascript:;" onclick="easemob.addGroup()" class="layui-layer-btn0">确认</a><a href="javascript:;" onclick="easemob.cancelGroup()" class="layui-layer-btn1">取消</a>'
                        +                '<div/>'
                        +            '</div>'
                        +        '</div>'
                        +    '</div>'
                        '</div>'
                    +'</div>';
            $('#chat_more').html(html).show();
            _loader.init({
                container: '#chat_ad_users',
                loading: '#chat_ad_users',
                url: '/message/followMutual',
                paramtype: 1,
                params: {limit: 10},
                loadtype: 2
            });
        });

        // 选择联系人
        $('#chat_more').on('click', '.chat_add_user_btn', function () {
            $(this).hide();
            $(this).parent().find('.chat_del_user_btn').show();
            var id = $(this).parent().data('id');
            var avatar = $(this).siblings('img').attr('src');
            var name = $(this).siblings('span').text();
            var html = '';
            html += '<li data-id="' + id + '">'
                    +    '<img src="' + avatar + '"/>'
                    +    '<span>' + name + '</span>'
                    +    '<svg class="icon chat_del_selected_user_btn" aria-hidden="true">'
                    +        '<use xlink:href="#icon-close"></use>'
                    +    '</svg>'
                    +'</li>';
            $('.chat_ad_selected_users ul').append(html);
            updateSelected();
        });

        // 左侧去掉联系人
        $('#chat_more').on('click', '.chat_del_user_btn', function () {
            $(this).hide();
            $(this).parent().find('.chat_add_user_btn').show();
            var id = $(this).parent().data('id');
            $('.chat_ad_selected_users').find('li[data-id="' + id + '"]').remove();
            updateSelected();
        });

        // 右侧去掉联系人
        $('#chat_more').on('click', '.chat_del_selected_user_btn', function () {
            var id = $(this).parent().data('id');
            $(this).parent().remove();
            $('.chat_ad_users').find('li[data-id="' + id + '"] .chat_del_user_btn').hide();
            $('.chat_ad_users').find('li[data-id="' + id + '"] .chat_add_user_btn').show();
            updateSelected();
        });

        // 创建群组搜索输入
        $('#chat_more').on('keyup', "#chat_ad_search_input", function(event) {
            //利用event的timeStamp来标记时间，这样每次的keyup事件都会修改last的值
            users_search_last = event.timeStamp;
            setTimeout(function(){
                if(users_search_last - event.timeStamp == 0){
                    usersSearch();
                }
            }, 500);
        });

        // 群组信息
        $('#chat_wrap').on('click', '#chat_ad_info', function() {
            $('#chat_normal').hide();
        });


        // 设置未读数量
        for (var i in TS.UNREAD) {
            if (TS.UNREAD[i] > 0) {
                $('#chat_' + i + ' .chat_unread_div').remove();
                $('#chat_' + i).prepend(easemob.formatUnreadHtml(1, TS.UNREAD[i]));
            }
        }

        if (TS.EASEMOB_KEY) {
            // 设置聊天会话
            easemob.cid = {{ $cid ?? 0 }};
            easemob.setInnerCon();
        }

        // 加载内容
        if(type != 0) messageData(type);

        document.querySelector('#popupcover').addEventListener('click', function(event) {
            event.stopPropagation()
            layer.alert(buyTSInfo)
        }, true)
    });


    // 获取消息列表
    function messageData(type) {
        $('#message_cont').html('');
        var title = '';
        switch(type) {
            case 1: // 评论
                title = '评论的';
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/comments',
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader',
                    callback: function(){
                        easemob.setRead(0, 'comment');
                    }
                });
                body_title.html(title);
                body_title.removeClass('hide');
                audit_top.addClass('hide');

                break;
            case 2: // 点赞
                title = '点赞的';
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/likes',
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader',
                    callback: function(){
                        easemob.setRead(0, 'like');
                    }
                });
                body_title.html(title);
                body_title.removeClass('hide');
                audit_top.addClass('hide');

                break;
            case 3: // 通知
                title = '通知';
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/notifications',
                    paramtype: 1,
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader',
                    callback: function(){
                        easemob.setRead(0, 'system');
                    }
                });
                body_title.html(title);
                body_title.removeClass('hide');
                audit_top.addClass('hide');

                break;
            case 4: // 动态评论置顶审核
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedFeedComment',
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader'
                });
                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 5: // 资讯评论置顶审核
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedNewsComment',
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader'
                });

                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 6: // 帖子评论置顶审核
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedPostComment',
                    paramtype: 1,
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader'
                });

                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 7: // 帖子置顶审核
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedPost',
                    paramtype: 1,
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader'
                });

                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 8:
                title = '@我的';
                _loader.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/mention',
                    paramtype: 0,
                    params: {limit: 20},
                    loadtype: 2,
                    selfname: '_loader',
                    callback: function(){
                        easemob.setRead(0, 'at');
                    }
                })
                body_title.html(title).removeClass('hide')
                audit_top.addClass('hide')
        }
    }

    // 聊天会话搜索
    function messageSearch() {
        var val = $('#chat_search_input').val();
        if (val == '') {
            $('#chat_left_message li').show();
        } else {
            $('#chat_left_message li').each(function(){
                if ($(this).data('name').indexOf(val) == -1){
                    $(this).hide();
                } else {
                    $(this).show();
                }
            })
        }
    }

    // 用户搜索
    function usersSearch() {
        var val = $('#chat_ad_search_input').val();
        $('#chat_ad_users').html('');
        if (val == '') {
            _loader.init({
                container: '#chat_ad_users',
                loading: '#chat_ad_users',
                url: '/message/followMutual',
                paramtype: 1,
                params: {limit: 10},
                loadtype: 2
            });
        } else {
            _loader.init({
                container: '#chat_ad_users',
                loading: '#chat_ad_users',
                url: '/message/followMutual',
                paramtype: 1,
                params: {limit: 10, keyword: val},
                loadtype: 2
            });
        }
    }

    // 更新已选择用户
    function updateSelected() {
        var ids = [];
        var names = [];
        $('.chat_ad_selected_users li').each(function(){
            ids.push(parseInt($(this).data('id')));
            names.push($(this).find('span').text());
        });
        $('#chat_selected_users').val(ids.join(','));
        $('#chat_selected_users').attr('name', names.join('、'));
    }
</script>

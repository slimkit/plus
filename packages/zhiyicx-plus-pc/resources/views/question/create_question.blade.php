@section('title') 提问 @endsection @extends('pcview::layouts.default') @section('styles')
<link rel="stylesheet" href="{{ URL::asset('assets/pc/css/question.css') }}" />
@endsection @section('content')
<div class="create-question">
    <div class="step1">
        <div class="question-tw">提问</div>
        <div class="question-form-row" style="position:relative">
            <input type="hidden" id="question_id" name="id" value="{{ $id or 0 }}" />
            <input id="subject" name="subject" type="text" value="{{ $question['subject'] or '' }}" placeholder="请输入问题并以问号结束" autocomplete="off" maxlength="50"/>
            <div class="subject-error"></div>
            <div class="question-searching">
                <div class="searching-existing"></div>
            </div>
        </div>
        <div class="question-form-row question-topics">
            <img src="{{ asset('assets/pc/css/img/tags_icon.png') }}" class="J-select-topics-img" @if(isset($question) || isset($topic)) style="display: none;" @endif>
            <label for="J-select-topics" @if(isset($question) || isset($topic)) style="display: none;" @endif>请选择专题</label>
            <ul class="question-topics-selected" id="J-select-topics">
                @if(isset($question))
                    @foreach($question['topics'] as $tc)
                        <li class="topic_{{ $tc['id'] }}" data-id="{{ $tc['id'] }}">{{ $tc['name'] }}</li>
                    @endforeach
                @elseif(isset($topic))
                    <li class="topic_{{ $topic['id'] }}" data-id="{{ $topic['id'] }}">{{ $topic['name'] }}</li>
                @endif
            </ul>
            <div class="question-topics-list" id="J-topic-box" style="display: none;">
                <dl>
                    @foreach ($topics as $topic)
                        <dd data-id="{{$topic->id}}">{{ $topic->name }}</dd>
                    @endforeach
                </dl>
            </div>
            <input type="hidden" name="topics" id="topics" />
        </div>
        <div class="question-form-row">
            @include('pcview::widgets.markdown', ['height'=>'400px', 'width' => '100%', 'content' => $question['body'] ?? ''])
        </div>
        <div class="question-form-row">

            <input id="anonymity" name="anonymity" type="checkbox" @if(isset($question) && $question['anonymity'] == 1) checked="checked" @endif class="input-checkbox"/>
            <label for="anonymity">启动匿名</label>
        </div>
        @if(isset($question) && ($question['amount'] > 0 || empty($question['invitations']) || $question['status'] == 1))
            <div class="question-next"><button id="question-submit">完成</button></div>
        @else
            <div class="question-next"><button id="question-next">下一步</button></div>
        @endif
    </div>
    @if(!isset($question) || ($question['amount'] == 0 || empty($question['invitations'])))
        <div class="step2">
            <div class="question-tw">设置悬赏
                <span class="tw-notice">(可跳过)</span>
                <span class="reward-rule">悬赏规则</span>
            </div>
            <div class="reward-row">
                <div class="reward-notice">设置悬赏金额</div>
                <ul class="reward-example">
                    @foreach(explode(',', $config['bootstrappers']['site']['reward']['amounts']) as $amount)
                        <li>{{ $amount }}</li>
                    @endforeach
                </ul>
                <input type="text" min="1" oninput="value=moneyLimit(value)" class="custom-money" id="amount" placeholder="自定义悬赏金额">
                <input type="hidden" id="amount-hide" name="amount">
            </div>
            @if(!isset($question))
                <div class="reward-row">
                    <div class="invitation-notice">
                        {{--<div class="reward-notice">悬赏邀请</div>--}}
                        {{--<span>--}}
                        {{--<input id="reward" name="reward" type="checkbox" class="input-checkbox"/>--}}
                        {{--<label for="reward">邀请答题人，设置围观等</label>--}}
                        {{--</span>--}}
                        <div class="reward-notice">悬赏邀请</div>
                        <span>
                            <input id="rewardyes" name="reward" type="radio" value="1" class="input-radio"/>
                            <label for="rewardyes">是</label>
                        </span>
                        <span>
                            <input id="rewardno" name="reward" type="radio" value="0" checked="checked" class="input-radio"/>
                            <label for="rewardno">否</label>
                        </span>
                    </div>
                    <div class="invitation-con">
                        <dl>
                            <dt>邀请回答</dt>
                            <dd id="invitation_user">
                                <a href="javascript:" id="invitation-add">添加</a>
                            </dd>
                        </dl>
                        <dl>
                            <dt>是否开启围观</dt>
                            <dd>
                        <span>
                            <input id="lookyes" name="look" type="radio" value="1" class="input-radio"/>
                             <label for="lookyes">是</label>
                        </span>
                                <span>
                            <input id="lookno" name="look" type="radio" value="0" checked="checked" class="input-radio"/>
                             <label for="lookno">否</label>
                        </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            @endif
            <div class="question-next">
                <button id="question-last">上一步</button>
                <button id="question-submit">@if(isset($question))完成@else发布问题@endif</button>
            </div>

        </div>
    @endif

</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
    <script>
        // 问题搜索
        var last;
        var subject = $('#subject');
        var args = {};
        var step1 = $('.step1');
        var step2 = $('.step2');
        var question_id = {{ $question['id'] or 0}};
        var selBox = $('#J-select-topics');
        var lockStatus = false;
        subject.keyup(function (event) {
            //利用event的timeStamp来标记时间，这样每次的keyup事件都会修改last的值
            last = event.timeStamp;
            setTimeout(function(){
                if(last - event.timeStamp == 0){
                    $('.subject-error').text('').hide();
                    question_search();
                }
            }, 500);
        });
        subject.focus(function() {
            var val = $.trim(subject.val());
            if (val.length >= 1) {
                question_search();
            }
        });
        function question_search(event) {
            var val = $.trim(subject.val());
            var question_searching = $('.question-searching');
            var searching_existing = $(".searching-existing");
            searching_existing.html('');
            $('.searching-notice').remove();

            if (!val || val == "") { return; }
            axios.get('/api/v2/questions', { params:{ subject:val, type:"all", limit:8 } })
              .then(function (response) {
                if (response.data.length > 0) {
                    question_searching.prepend('<div class="searching-notice">您的问题可能已有答案</div>');
                    $.each(response.data, function(key, value) {
                        if (key < 8) {
                            var html = '<div class="search-list"><a href="/questions/' + value.id + '">' + value.subject + '</a><span class=>'+value.answers_count+'个回答</span></div>';
                            searching_existing.append(html);
                        }
                    });
                    question_searching.show();
                    $('.searching-notice').on('click', function () {
                        searching_existing.html('');
                        $('.searching-notice').remove();
                    })
                }
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        }

        // 选择专题
        $('.question-topics').on('click', '#J-select-topics, >label', function(e){
            e.stopPropagation();
            $('#J-topic-box').toggle();
        });
        $('#J-topic-box dd').on('click', function(e){
            e.stopPropagation();
            var topic_id = $(this).data('id');
            var topic_name = $(this).text();
            if (selBox.find('li').hasClass('topic_'+topic_id)) {
                noticebox('专题已存在', 0);

                return false;
            }
            if (selBox.find('li').length > 4) {
                noticebox('专题最多五个', 0);

                return false;
            }
            selBox.append('<li class="topic_'+topic_id+'" data-id="'+topic_id+'">'+topic_name+'</li>');
            if (selBox.find('li').length > 0) {
                $('.question-topics label').hide();
                $('.question-topics img').hide();
            }
        });
        selBox.on('click', 'li', function(){
            $(this).remove();
            if (selBox.find('li').length == 0) {
                $('.question-topics label').show();
                $('.question-topics img').show();
            }
        });

        // 下一步
        $('#question-next').on('click', function () {
            checkLogin();

            if (!stepOne()) {

                return false;
            }
            step1.hide();
            step2.show();
        });

        // 悬赏规则
        $('.reward-rule').on('click', function () {
            var html = formatConfirm('悬赏规则', '化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？')
            ly.alert(html);
        });

        // 选择悬赏金额
        $('.reward-example').on('click', 'li', function () {
            if ($(this).hasClass('select-amount')) {
                $("#amount").val('');
                $("#amount-hide").val('');
                $(this).removeClass('select-amount');
            }else{
                $(this).siblings().removeClass('select-amount');
                $(this).addClass('select-amount');
                $("#amount-hide").val($(this).text());
            }

        });
        $('#amount').focus(function () {
            $("#amount-hide").val('');
            $('.select-amount').removeClass('select-amount');
        });

        // 是否开启悬赏邀请
        $('#rewardno').on('click', function () {
            $('.invitation-con').hide('fast');
            $('#invitation-add').text('添加');
            $("#lookyes").removeAttr("checked");
            $("input[type='radio'][name='look']").get(1).checked=true;
            args.invitations_ = [];
            args.automaticity = 0;
        });
        $('#rewardyes').on('click', function () {
            $('.invitation-con').show('fast');
        });
        $('#reward').on('click', function () {
            if ($("input[type='checkbox'][name='reward']:checked").val() == 'on') {
                $('.invitation-con').show('fast');
            } else {
                $('.invitation-con').hide('fast');
            }
        });

        // 添加邀请人
        $('#invitation-add').on('click', function () {
            ly.load('/questions/users?topics=' + args.topics_.join(','), '', '480px', '550px', 'GET');
        });
        $('#question-last').on('click', function () {
            step2.hide();
            step1.show();
        });
        // 发布问题
        $('#question-submit').on('click', function () {
            if (lockStatus) {
                noticebox('请勿重复提交', 0);

                return false;
            }
            args.amount = parseInt($('#amount').val()) || parseInt($("#amount-hide").val()) || 0;
            args.look = $("input[type='radio'][name='look']:checked").val();
            var topic = [];
            for (var key in args.topics_) {
                topic[key] = {};
                topic[key].id = args.topics_[key];
            }
            args.topics = topic;
            var invitations = [];
            for (var key in args.invitations_) {
                invitations[key] = {};
                invitations[key].user = args.invitations_[key];
            }
            args.invitations = invitations;
            args.automaticity = 0;
            if (args.look == 1 || $("input[type='radio'][name='reward']:checked").val() == 1) {
                if (args.invitations.length != 1) {
                    noticebox('邀请的专家呢？', 0);

                    return false;
                }
                if (args.amount <= 0) {
                    noticebox('请设置悬赏金额', 0);

                    return false;
                }
                if ($.inArray({{ $TS['id'] }}, args.invitations_) > -1) {
                    noticebox('不能邀请自己', 0);

                    return false;
                }
                args.automaticity = 1;
            }
            if (question_id > 0) {

                return update();
            }
            lockStatus = true;
            axios.post('/api/v2/currency-questions', args)
              .then(function (response) {
                lockStatus = false;
                noticebox(response.data.message, 1, '/questions/'+response.data.question.id);
              })
              .catch(function (error) {
                lockStatus = false;
                showError(error.response.data);
              });
        });

        function stepOne() {
            args.subject = $('#subject').val().replace(/(\s*$)/g, "");
            args.body = editor.value();
            args.anonymity = $("input[type='checkbox'][name='anonymity']:checked").val() == 'on' ? 1 : 0;
            args.topics_ = [];
            $('#J-select-topics li').each(function(index){
                args.topics_.push($(this).data('id'));
            });
            if (args.subject.length < 1) {
                $('#subject').focus();
                noticebox('请输入标题', 0);
                // $('.subject-error').text('请输入标题').show();
                return false;
            } else if (args.subject.charAt(args.subject.length - 1) != '?' && args.subject.charAt(args.subject.length - 1) != '？') {
                $('#subject').focus();
                noticebox('请以问号结束提问', 0);
                // $('.subject-error').text('请以问号结束提问').show();

                return false;
            } else if (args.subject.match(/([\u4E00-\u9FA5A-Za-z0-9])/g) == null) {
                $('#subject').focus();
                noticebox('请认真填写标题', 0);
                // $('.subject-error').text('请认真填写标题').show();

                return false;
            }

            if (args.topics_.length < 1) {
                noticebox('请选择专题', 0);

                return false;
            }

            if (args.body.length < 1) {
                noticebox('请填写问题描述', 0);

                return false;
            }

            return true;
        }

        function update() {
            if (!args.subject || !args.body || !args.topics_) {
                if (!stepOne()) {

                    return false;
                }
            }
            var topic = [];
            for (var key in args.topics_) {
                topic[key] = {};
                topic[key].id = args.topics_[key];
            }
            args.topics = topic;
            lockStatus = true;
            axios.patch('/api/v2/currency-questions/'+question_id, args)
              .then(function (response) {
                lockStatus = false;
                noticebox('修改成功', 1, '/questions/'+question_id);
              })
              .catch(function (error) {
                lockStatus = false;
                showError(error.response.data);
              });
        }

    </script>
@endsection

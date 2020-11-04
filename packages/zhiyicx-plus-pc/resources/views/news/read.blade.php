@php
    use Michelf\MarkdownExtra;
    use sixlive\ParsedownHighlight;use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatMarkdown;
@endphp

@section('title') 文章 - {{ $news['title'] }} @endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/news.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/default.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/pc/markdown/pluseditor.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="news_left">
            <div class="detail_body">
                <div class="detail_title">
                    {{ $news['title'] }}
                </div>

                <div class="detail_info relative" id="news_toolbar">
                    <a href="{{ route('pc:news', ['cate_id' => $news['category']['id']]) }}"
                       class="cates_span">{{ $news['category']['name'] ?? '默认' }}</a>
                    <span>{{ $news['from'] != '原创' ? $news['from'] : $news['author'] }}  ·  {{ $news['hits'] }}浏览  ·  {{ getTime($news['created_at']) }}</span>
                    @if($news['audit_status'] != 1)
                        <span class="options" onclick="options(this)">
                        <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                    </span>
                    @endif
                    <div class="options_div">
                        <div class="triangle"></div>
                        <ul>
                            <li>
                                <a href="javascript:;" onclick="repostable.show('news' ,{{ $news['id'] }})">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#icon-share"></use>
                                    </svg>
                                    转发
                                </a>
                            </li>
                            @if($news['user_id'] === ($TS['id'] ?? 0))
                                @if($news['audit_status'] == 3)
                                    <li>
                                        <a href="{{ route('pc:newsrelease', $news['id']) }}">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-edit"></use>
                                            </svg>
                                            编辑
                                        </a>
                                    </li>
                                @elseif($news['audit_status'] == 0)
                                    <li>
                                        <a href="javascript:;" onclick="news.pinneds({{$news['id']}});">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-pinned2"></use>
                                            </svg>
                                            申请置顶
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;"
                                           onclick="news.delete({{$news['id']}}, {{$news['category']['id']}});">
                                            <svg class="icon" aria-hidden="true">
                                                <use xlink:href="#icon-delete"></use>
                                            </svg>
                                            删除
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a href="javascript:;" onclick="reported.init('{{$news['id']}}', 'news-detail');">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#icon-report"></use>
                                        </svg>
                                        <span>举报</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                @if($news['subject'])
                    <div class="detail_subject">
                        <img src="{{ asset('assets/pc/images/zixun-left.png') }}"/>
                        <div class="subject_content">{{ $news['subject'] }}</div>
                        <img src="{{ asset('assets/pc/images/zixun-right.png') }}" class="right"/>
                    </div>
                @endif

                <div id="markdown-body" class="detail_content markdown-body editormd-preview-container">
                    {!! formatMarkdown($news['content']) !!}
                </div>
                @if (!$news['audit_status'])
                    <div class="detail_share">
                    <span id="J-collect{{ $news['id'] }}" rel="{{ $news['collect_count'] }}"
                          status="{{(int) $news['has_collect']}}">
                        @if($news['has_collect'])
                            <a class="act" href="javascript:;" onclick="collected.init({{$news['id']}}, 'news', 0);"
                               class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                            <font class="cs">{{ $news['collect_count'] }}</font> 人收藏
                        </a>
                        @else
                            <a href="javascript:;" onclick="collected.init({{$news['id']}}, 'news', 0);">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                            <font class="cs">{{ $news['collect_count'] }}</font> 人收藏
                        </a>
                        @endif
                    </span>
                        <span class="digg" id="J-likes{{$news['id']}}" rel="{{$news['digg_count']}}"
                              status="{{(int) $news['has_like']}}">
                        @if($news['has_like'])
                                <a class="act" href="javascript:void(0)"
                                   onclick="liked.init({{$news['id']}}, 'news', 0)">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like-copy"></use></svg>
                            <font>{{$news['digg_count']}}</font> 人喜欢
                        </a>
                            @else
                                <a href="javascript:;" onclick="liked.init({{$news['id']}}, 'news', 0)">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                            <font>{{$news['digg_count']}}</font> 人喜欢
                        </a>
                            @endif
                    </span>

                    {{-- 第三方分享 --}}
                    <div class="detail_third_share">
                        分享至：
                        @include('pcview::widgets.thirdshare' , ['share_url' => route('redirect', array('target' => '/news/'.$news['id'])), 'share_title' => $news['subject'], 'share_pic' => config('app.url') . '/api/v2/files/' . $news['image']['id'] ])
                    </div>

                    {{-- 打赏 --}}
                    @if($news['user_id'] !== ($TS['id'] ?? 0))
                    @include('pcview::widgets.rewards' , ['rewards_data' => $news['rewards'], 'rewards_type' => 'news', 'rewards_id' => $news['id'], 'rewards_info' => $news['reward']])
                    @endif
                </div>

                    {{-- 相关推荐 --}}
                    @if (!empty($news_rel))
                        <div class="detail_recommend">
                            <p class="rel_title">相关推荐</p>
                            <div class="rel_tags">
                                @foreach ($news['tags'] as $tag)
                                    <span>{{ $tag['name'] }}</span>
                                @endforeach
                            </div>

                            @foreach ($news_rel as $rel)
                                <div class="rel_news_item clearfix">
                                    <div class="rel_news_img">
                                        <a href="{{ route('pc:newsread', ['news_id' => $rel['id']]) }}">
                                            <img class="lazy" width="180" height="130"
                                                 data-original="{{ getImageUrl($rel['image'], 180, 130)}}"/>
                                        </a>
                                    </div>
                                    <div class="rel_news_word">
                                        <a href="{{ route('pc:newsread', ['news_id' => $rel['id']]) }}"
                                           class="news_title"> {{ $rel['title'] }} </a>
                                        <p>{{ $rel['subject'] }}</p>
                                        <div class="news_bm">
                                            <a href="{{ route('pc:news', ['cate_id' => $rel['category']['id']]) }}"
                                               class="cates_span">{{ $rel['category']['name'] ?? '默认'}}</a>
                                            <span>{{ $rel['from'] }}  ·  {{ $rel['hits'] }}浏览  ·  {{ getTime($rel['created_at']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- 评论  --}}
                    @include('pcview::widgets.comments', [
                        'id' => $news['id'],
                        'comments_count' => $news['comment_count'],
                        'comments_type' => 'news',
                        'loading' => '.detail_comment',
                        'position' => 0,
                        'top' => 1,
                    ])

                @endif
            </div>
        </div>
    </div>

    <div class="right_container">
        <div class="news_release_btn">
            <a href="{{ route('pc:newsrelease') }}">
                <span>
                    <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-publish"></use></svg>投稿
                </span>
            </a>
        </div>
        {{-- 近期热点 --}}
        @include('pcview::widgets.hotnews')

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/module.news.js') }}"></script>
    <script src="{{ asset('assets/pc/js/module.mention.js') }}"></script>
    <script src="{{ asset('assets/pc/js/qrcode.js') }}"></script>
    <script>
      $(function() {
        {{--var md = markdownit({--}}
        {{--  breaks: true,--}}
        {{--  html: false,--}}
        {{--  highlight: function (code) {--}}
        {{--    return hljs ? hljs.highlightAuto(code).value : code--}}
        {{--  },--}}
        {{--})--}}
        {{--  .use(markdownitContainer, 'hljs-left') /* align left */--}}
        {{--  .use(markdownitContainer, 'hljs-center')/* align center */--}}
        {{--  .use(markdownitContainer, 'hljs-right')--}}

        {{--$('#markdown-body').html(md.render(`{!!formatMarkdown($news['content'])!!}`))--}}

        $('img.lazy').lazyload({ effect: 'fadeIn' })

        // 近期热点
        if ($('.time_menu li a').length > 0) {
          $('.time_menu li').hover(function() {
            var type = $(this).attr('type')

            $(this).siblings().find('a').removeClass('hover')
            $(this).find('a').addClass('hover')

            $('.hot_news_list div').hide()
            $('#' + type).show()
          })
        }
      })

    </script>
@endsection

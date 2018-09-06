@php
    use Carbon\Carbon;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

{{-- 右侧广告位 --}}
@if ($type == 1)

    @if(!empty($ads))
        @foreach($ads as $ad)
        <div class="news_ad">
            <a href="{{ $ad['data']['link'] }}" target="_blank">
                <img src="{{ $ad['data']['image'] }}" />
            </a>
        </div>
        @endforeach
    @endif

{{-- 资讯顶部广告 --}}
@elseif ($type == 2)

    @if(!empty($ads))
        <div class="unslider">
            <ul>
                @foreach($ads as $ad)
                  <li>
                    <a href="{{ $ad['data']['link'] }}">
                        <img src="{{ $ad['data']['image'] }}" width="100%" height="414">
                    </a>
                    @if ($ad['title'])
                        <a href="{{ $ad['data']['link'] }}"><p class="title">{{ $ad['title'] }}</p></a>
                    @endif
                  </li>
                @endforeach
            </ul>
        </div>
    @endif

@elseif($type == 3 && isset($ads[$page-1]['data']) && $_GET['type'] == 'hot')

    <div class="ads_item">
        <a target="_blank" href="{{ $ads[$page-1]['data']['link'] ?? ''}}">
            <dl class="user-box mt30 clearfix">
                <dt class="fl">
                    <img class="round" src="{{ $ads[$page-1]['data']['avatar'] ?? ''}}" width="50">
                </dt>
                <dd class="fl ml20 body">
                    <span class="tcolor">{{ $ads[$page-1]['data']['name'] ?? ''}}</span>
                    <div class="font12 gcolor fr">{{ getTime(Carbon::createFromFormat('Y-m-d H:i:s', $ads[$page-1]['data']['time'])) }}</div>
                </dd>
            </dl>
            <p class="mt0">{{ $ads[$page-1]['data']['content'] ?? ''}}</p>
            <div> <img src="{{ $ads[$page-1]['data']['image'] ?? ''}}" alt="image"> </div>
        </a>
        <p><span class="tag">广告</span></p>
        <div class="feed_line"></div>
    </div>

@elseif($type == 4 && isset($ads[$page-1]['data']) && isset($_GET['recommend']))

    <div class="news_item">
        <a target="_blank" href="{{ $ads[$page-1]['data']['link'] ?? ''}}">
            <div class="news_img">
                    <img class="lazy" width="230" height="163" data-original="{{ $ads[$page-1]['data']['image'] ?? ''}}"/>
            </div>
            <div class="news_word">
                <div class="news_title"> {{ $ads[$page-1]['title'] ?? ''}} </div>
                <p>{{ $ads[$page-1]['data']['content'] ?? ''}}</p>
                <div class="news_bm">
                   <span>{{ $ads[$page-1]['data']['name'] ?? ''}}  ·  {{ getTime(Carbon::createFromFormat('Y-m-d H:i:s', $ads[$page-1]['data']['time'])) }}</span>
                   <span class="tag ml10">广告</span>
                </div>
            </div>
        </a>
    </div>

@endif
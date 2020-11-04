@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if(!empty($news))
     @foreach($news as $item)
     <div class="news_item">
          <div class="news_img">
               <a href="{{ route('pc:newsread', ['news' => $item['id']]) }}">
                    <img class="lazy" width="230" height="163" data-original="{{ getImageUrl($item['image'], 230, 163)}}"/>
               </a>
          </div>
          <div class="news_word">
               <a class="news_title" href="{{ route('pc:newsread', ['news' => $item['id']]) }}">
                    {{ $item['title'] }}
               </a>
               <p>{{ $item['subject'] }}</p>
               <div class="news_bm">
                    @if(isset($item['pinned']) && $item['pinned'])
                         <a href="javascript:;" class="cates_span span_green">置顶</a>
                    @endif
                    @if (!isset($cate_id) || $cate_id == 0)
                         <a href="{{ route('pc:news', ['cate_id' => $item['category']['id']]) }}" class="cates_span">{{ $item['category']['name'] }}</a>
                    @endif
                    <span>{{ $item['from'] }}  ·  {{ $item['hits'] }}浏览  ·  {{ getTime($item['created_at'], 1) }}</span>
               </div>
          </div>
     </div>
     @endforeach
@endif

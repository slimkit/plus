@php
    // 单张图片默认展示宽高
    $conw = isset($conw) ? $conw : 555;
    $conh = isset($conh) ? $conh : 400;
@endphp
@if($post['images'] && count($post['images']) > 0)
<div id="feed_photos_{{$post['id']}}" class="feed_photos">
    <div class="feed_images">
    @if(count($post['images']) == 1)
        <div class="image_box_one">
            @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => $conw, 'height' => $conh, 'count' => 'one', 'curloc' => 0])
        </div>
    @elseif(count($post['images']) == 2)
        <div style="width: 100%; display: flex;">
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 277, 'height' => 273, 'curloc' => 0])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 277, 'height' => 273, 'curloc' => 1])
            </div>
        </div>
    @elseif(count($post['images']) == 3)
        <div style="width: 100%; display: flex;">
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 184, 'height' => 180, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 184, 'height' => 180, 'curloc' => 1])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 184, 'height' => 180, 'curloc' => 2])
            </div>
        </div>
    @elseif(count($post['images']) == 4)
        <div style="width: 100%; display: flex;">
            <div style="width: 50%">
                <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 277, 'height' => 273, 'curloc' => 0])
                </div>
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 277, 'height' => 273, 'curloc' => 1])
              </div>
            </div>
            <div style="width: 50%">
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 277, 'height' => 273, 'curloc' => 2])
              </div>
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][3], 'width' => 277, 'height' => 273, 'curloc' => 3])
              </div>
            </div>
        </div>
    @elseif(count($post['images']) == 5)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 66.6666%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 370, 'height' => 366, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 185, 'height' => 183, 'curloc' => 1])
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 185, 'height' => 183, 'curloc' => 2])
                </div>
            </div>
            <div style="width: 100%; display: flex;">
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][3], 'width' => 277, 'height' => 273, 'curloc' => 3])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][4], 'width' => 277, 'height' => 273, 'curloc' => 4])
                </div>
            </div>
        </div>
    @elseif(count($post['images']) == 6)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 66.6666%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 370, 'height' => 366, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 185, 'height' => 183, 'curloc' => 1])
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 185, 'height' => 183, 'curloc' => 2])
                </div>
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][3], 'width' => 185, 'height' => 183, 'curloc' => 3])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][4], 'width' => 185, 'height' => 183, 'curloc' => 4])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][5], 'width' => 185, 'height' => 183, 'curloc' => 5])
            </div>
        </div>
    @elseif(count($post['images']) == 7)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%">
                <div style="width: 100%" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 277, 'height' => 273, 'curloc' => 0])
                </div>
                <div style="width: 100%" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 277, 'height' => 273, 'curloc' => 1])
                </div>
            </div>
            <div style="width: 50%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 138, 'height' => 135, 'curloc' => 2])
                </div>
                <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][3], 'width' => 138, 'height' => 135, 'curloc' => 3])
                </div>
                <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][4], 'width' => 277, 'height' => 273, 'curloc' => 4])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][5], 'width' => 138, 'height' => 135, 'curloc' => 5])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post['images'][6], 'width' => 138, 'height' => 135, 'curloc' => 6])
                </div>
            </div>
        </div>
    @elseif(count($post['images']) == 8)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 185, 'height' => 183, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 185, 'height' => 183, 'curloc' => 1])
            </div>
            <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 185, 'height' => 183, 'curloc' => 2])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][3], 'width' => 277, 'height' => 273, 'curloc' => 3])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][4], 'width' => 277, 'height' => 273, 'curloc' => 4])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][5], 'width' => 185, 'height' => 183, 'curloc' => 5])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][6], 'width' => 185, 'height' => 183, 'curloc' => 6])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][7], 'width' => 185, 'height' => 183, 'curloc' => 7])
            </div>
        </div>
    @elseif(count($post['images']) >= 9)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][0], 'width' => 185, 'height' => 181, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][1], 'width' => 185, 'height' => 181, 'curloc' => 1])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][2], 'width' => 185, 'height' => 181, 'curloc' => 2])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][3], 'width' => 185, 'height' => 181, 'curloc' => 3])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][4], 'width' => 185, 'height' => 181, 'curloc' => 4])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][5], 'width' => 185, 'height' => 181, 'curloc' => 5])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][6], 'width' => 185, 'height' => 181, 'curloc' => 6])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][7], 'width' => 185, 'height' => 181, 'curloc' => 7])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post['images'][8], 'width' => 185, 'height' => 181, 'curloc' => 8])
            </div>
        </div>

    @endif
    {{-- 圈子图片操作9张显示 --}}
    @if (count($post['images']) > 9)
    <a href="{{ $target_url ?? 'javascript:;' }}"><div class="more_count_div"></div></a>
    <a href="{{ $target_url ?? 'javascript:;' }}"><span class="more_count">+{{ (count($post['images']) - 9) }}</span></a>
    @endif
    </div>
</div>
@endif

@if($post['video'] ?? false)
    <div>
        <video poster="{{ $routes['storage'] . $post['video']['cover_id'] }}" src="{{ $routes['storage'] . $post['video']['video_id'] }}" width="{{ $conw }}" height="{{ $conh }}" controls="controls">
        </video>
    </div>
@endif

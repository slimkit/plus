@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@php
$style = $lockstr = $onclick = $class = '';

// 付费免费
if (isset($image['paid']) && !$image['paid']) {
    // 付费数据
    $lockstr =  'data-node=' . $image['paid_node'] . ' data-amount=' . $image['amount'] . ' data-file=' . $image['file'];
    $onclick = 'onclick=weibo.payImage(this)';
} else {
    $class = 'bigcursor';
}

// 计算展示宽高
if (isset($count) && $count == 'one') {
    $size = explode('x', $image['size']);
    if ($size[0] < $width && $size[1] < $height) { // 若宽高都小于展示高度，则保留原尺寸
        $w = $size[0];
        $h = $size[1];
    } else if ($size[0] > $size[1]) { // 宽大于高
        $w = $size[0] > $conw ? $conw : $size[0];
        $h = number_format(($width / $size[0] * $size[1]), 2, '.', '');
    } else if ($size[0] < $size[1]) { // 宽小于高
        $h = $size[1] > $conh ? $conh : $size[1];
        $w = number_format($height / $size[1] * $size[0], 2, '.', '');
    } else if ($size[0] == $size[1]) { // 宽高相同，取展示宽高中较小值
        $w = $h = $size[0] > $conh ? $conh : $size[0];
    }
    $style = 'width:' . $w . 'px;height:' . $h . 'px';
}
@endphp

<img style="{{$style}}"
    class="lazy per_image {{ $class }}"
    data-original="{{ getImageUrl($image, $width, $height, true) }}"
    {{-- GIF 的资源 通过调用 $(el).play() 播放 --}}
    @if (($image['mime'] ?? '') == "image/gif" && !(isset($image['paid']) && !$image['paid']))
    data-original-gif="{{ getImageUrl($image, $width, $height, false) }}"
    @endif
    data-id={{ $image['file'] ?? $image['id'] }}
    data-size={{ $image['size'] }}
    curloc="{{$curloc}}"
    {{ $onclick }}
    {{ $lockstr }}
/>

@if (($image['mime'] ?? '') == 'image/gif')
<span class="gif_badge">GIF</span>
@endif

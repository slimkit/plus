<div class="list_fans">
    <div class="rank-tit">
        <span>{{$title}}</span>
        <span class="right">
            <i class="arrow-rank-l-l" id="{{$genre}}last" onclick="gorank({{isset($nowPage) ? $nowPage : 1}},'{{$genre}}',10)"></i>
              {{--<font id="postnum">1</font>/{{ceil($count/10)}}--}}
            <font id="{{$genre}}num" style="display:none">1</font>
            <i class="arrow-rank-r" id="{{$genre}}next" onclick="gorank({{(isset($nowPage) ? $nowPage : 1) + 1}},'{{$genre}}',10)"></i>
          </span>
    </div>
    <div class="list_pm font14">
        <span class="pm_1">排名</span>
        <span class="pm_2">昵称</span>
        @if(isset($tabName))
            <span class="pm_3">{{isset($tabName) ? $tabName : ''}}</span>
        @endif
    </div>
    <ul class="fans_ul" id="{{$genre}}-rank-list">
        @component('pcview::templates.rank_lists', ['genre' => $genre, 'post' => $post, 'tabName' => (isset($tabName) ? $tabName : ''), 'routes' => $routes])
        @endcomponent
    </ul>
</div>
<script>
    if ($('div[rel="{{$genre}}div"][current="1"]').length < 1) {
        $('#{{$genre}}next').removeClass('arrow-rank-r').addClass('arrow-rank-r-l');
    }
</script>
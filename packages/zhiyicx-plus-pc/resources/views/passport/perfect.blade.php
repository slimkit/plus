@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/passport.css')}}"/>
@endsection

@section('content')
<div class="perfect_container">
    <div class="perfect_wrap">
        <div class="perfect_title">
            <p>选择标签</p>
            <span>标签为全局标签，选择合适的标签，系统可推荐你感兴趣的内容，方便找到相同身份或爱好的人，很重要哦！</span>
        </div>

        @foreach ($tags as $tag)
            <div class="perfect_row">
                <label>{{$tag['name']}}</label>
                    <ul class="perfect_label_list" id="J-tags">
                    @foreach ($tag['tags'] as $item)
                        <li class="tag_{{$item['id']}}
                        @foreach ($user_tag as $t)
                            @if ($t['name'] == $item['name']) active @endif
                        @endforeach" data-id="{{$item['id']}}">{{$item['name']}}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <p class="mt20 font14 tcolor">最多可选5个标签，已选 <font class="mcolor num">{{ count($user_tag) }}</font> 个</p>
        <ul class="selected-box mb20">
            @foreach ($user_tag as $item)
                <li class="taged{{$item['name']}}" data-id="{{$item['id']}}">{{$item['name']}}</li>
            @endforeach
        </ul>

        <div class="perfect_btns">
            {{-- <a href="javascript:;" class="perfect_btn save" id="save">保存</a> --}}
            <a href="{{ route('pc:feeds') }}" class="btn btn-primary btn-lg mt20 wcolor skip" id="skip">进 入</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$('#J-tags li').on('click', function(e){
    var _this = $(this);
    var tag_id = $(this).data('id');
    var tag_name = $(this).text();
    var lenth = $('#J-tags li.active').length;
    if (_this.hasClass('active')) {
        axios.delete('/api/v2/user/tags/'+tag_id)
          .then(function (response) {
            $('.num').text(lenth-1);
            _this.removeClass('active');
            $('.taged'+tag_id).remove();
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    } else {
        if (lenth >= 5) {
            noticebox('个人标签最多选择５个', 0);
            return false;
        }
        axios.put('/api/v2/user/tags/'+tag_id)
          .then(function (response) {
            _this.addClass('active');
            $('.num').text(lenth+1);
            $('.selected-box').append('<li class="taged'+tag_id+'" data-id="'+tag_id+'">'+tag_name+'</li>');
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    }
});

$('.selected-box').on('click', 'li', function(){
    var _self = this;
    var tid = $(_self).data('id');
    var lenth = $('#J-tags li.active').length;
    axios.delete('/api/v2/user/tags/'+tid)
      .then(function (response) {
        $(_self).remove();
        $('.num').text(lenth-1);
        $('.tag_'+tid).removeClass('active');
      })
      .catch(function (error) {
        showError(error.response.data);
      });
});
</script>
@endsection
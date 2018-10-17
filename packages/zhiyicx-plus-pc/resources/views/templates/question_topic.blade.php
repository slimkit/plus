@if (!empty($data))
    @foreach ($data as $key=>$post)
    <div class="m-blk ">
        <div class="m-cover">
            <a href="{{ route('pc:topicinfo', $post['id']) }}">
                <img src="{{ $post['avatar'] ? $post['avatar']['url'] : asset('assets/pc/images/default_picture.png') }}" width="120" height="120">
            </a>
        </div>
        <div class="m-entry">
            <a href="{{ route('pc:topicinfo', $post['id']) }}">
                <p class="u-name f-toe">{{ $post['name'] }}</p>
            </a>
            <div class="m-col">
                关注 <span id="tf-count-{{ $post['id'] }}">{{ $post['follows_count'] }}</span>
                问题 <span>{{ $post['questions_count'] }}</span>
            </div>
            <div class="m-col1">
                @if ($post['has_follow'])
                    <a class="followed" href="javascript:;" tid="{{ $post['id'] }}" status="1" onclick="QT.follow(this)">已关注</a>
                @else
                    <a href="javascript:;" tid="{{ $post['id'] }}" status="0" onclick="QT.follow(this)">+关注</a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
@endif

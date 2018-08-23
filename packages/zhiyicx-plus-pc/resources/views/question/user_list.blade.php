@php
	use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if(!$users->isEmpty())
    @foreach($users as $user)
        <div class="user">
        	<div class="user-header">
        		<img src="{{ getAvatar($user, 40) }}" class="fans_img" alt="{{$user['name']}}">
				@if ($user->verified)
					<img class="verified_icon" src="{{ $user->verified['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
				@endif
        	</div>
        	<div class="user-info">
        		<p class="info-name">{{$user['name']}}</p>
        		<p class="info-num">
        			<span>{{$user['extra']['answers_count'] or 0}}</span>回答
        			<span>{{$user['extra']['likes_count'] or 0}}</span>点赞
        		</p>
        		@if (count($user['tags']) > 0)
	                <ul class="user-tags">
	                    @foreach($user['tags'] as $tag)
	                        <li>{{$tag['name']}}</li>
	                    @endforeach
	                </ul>
                @endif
        	</div>
        	<div class="user-action">
        		<button class="btn btn-primary invitation-a" data-id="{{$user['id']}}" data-name="{{$user['name']}}">邀请回答</button>
        	</div>
        </div>
    @endforeach
@endif
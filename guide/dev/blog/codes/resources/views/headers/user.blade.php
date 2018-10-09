<ul class="nav navbar-nav navbar-right">
    @if (Auth::guest())
    <li><a href="{{ route('login') }}">登入</a></li>
    @else
    <li class="dropdown">
        <a
            href="#"
            class="dropdown-toggle"
            data-toggle="dropdown" 
            role="button" 
            aria-haspopup="true" 
            aria-expanded="false"
        >
            @if (Auth::user()->avatar instanceof \Zhiyi\Plus\FileStorage\FileMetaInterface)
                @php
                    $avatarUrl = Auth::user()->avatar->url();
                    switch (Auth::user()->avatar->getVendorName()) {
                        case 'local':
                            $avatarUrl .= '?rule=h_50,w_50';
                            break;
                        case 'aliyun-oss':
                            $avatarUrl .= '?rule=image/resize,h_50,w_50';
                            break;
                    }
                @endphp
                <img
                    src="{{ $avatarUrl }}"
                    alt="{{ Auth::user()->name }}的头像"
                    style="
                        width: 20px;
                        height: 20px;
                    "
                >
            @else
                {{ Auth::user()->name }}
            @endif
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            @if (Auth::user()->ability('admin: login'))
                <li><a href="{{ url('/admin') }}">进入后台</a></li>
            @endif
            <li><a href="{{ route('logout') }}">退出登录</a></li>
        </ul>
    </li>
    @endif
</ul>

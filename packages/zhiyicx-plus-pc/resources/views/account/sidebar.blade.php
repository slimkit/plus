@php $route = Route::currentRouteName(); @endphp
<div class="account_l">
    <ul class="account_menu">
        <a href="{{ Route('pc:account') }}">
            <li class="@if ($account_cur == 'index')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-userinfo"></use></svg>基本资料</li>
        </a>
        <a href="{{ Route('pc:tags') }}">
            <li class="@if ($account_cur == 'tags')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tag"></use></svg>标签管理</li>
        </a>
        <a href="{{ Route('pc:authenticate') }}">
            <li class="@if ($account_cur == 'authenticate')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-auth"></use></svg>认证管理</li>
        </a>
        <a href="{{ Route('pc:security')}}">
            <li class="@if ($account_cur == 'security')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-password"></use></svg>安全设置</li>
        </a>
        <a href="{{ Route('pc:wallet')}}">
            <li class="@if ($account_cur == 'wallet')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-wallet"></use></svg>我的钱包</li>
        </a>
        <a href="{{ Route('pc:currency')}}">
            <li class="@if ($account_cur == 'currency')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-currency"></use></svg>我的{{ $config['bootstrappers']['site']['currency_name']['name'] }}</li>
        </a>
        <a href="{{ Route('pc:binds')}}">
            <li class="@if ($account_cur == 'binds')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-account"></use></svg>账号管理</li>
        </a>
    </ul>
</div>

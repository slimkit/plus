<div class="footer">
    <div class="footer_cont">
        @if (!$config['nav_bottom']->isEmpty())
        <ul>
            @foreach ($config['nav_bottom'] as $nav)
            <li>
                <a target="{{ $nav->target }}" href="{{ $nav->url }}">{{ $nav->name}} </a>
            </li>
            @endforeach
        </ul>
        @endif
        <div class="rights font12">{{ $config['common']['site_copyright'] ?? 'Powered by ThinkSNS ©2017 ZhishiSoft All Rights Reserved.' }}</div>
        @if (isset($config['app']['icp']))
        <div class="rights font12">{{ $config['app']['icp']}}</div>
        @endif
        <div class="developer">本站/APP由 <span>{{ $config['common']['site_technical'] ?? 'ThinkSNS+' }}</span> 提供技术和产品支持</div>
    </div>
</div>

@component('mail::message')

**{{ $user->name }}，你好，你有一条新消息。**

> {{ $message->getContent() }}

------

<small>邮件由 [{{ config('app.name') }}]({{ config('app.url') }}) 自动发出，请勿回复。</small>
@endcomponent

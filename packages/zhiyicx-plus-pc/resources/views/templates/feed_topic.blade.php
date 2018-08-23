@foreach ($topics as $topic)
<li>@include('pcview::topic.widgets.topic_card', ['topic' => $topic])</li>
@endforeach

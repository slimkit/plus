@foreach ($topics as $topic)
@include('pcview::topic.widgets.topic_card', ['topic' => $topic])
@endforeach

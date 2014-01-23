<ul class="list-inline">
	<li><span class="label label-important">{{ $event['event'] }}</span></li>
	<li>{{ HTML::link('https://github.com/'. $event['actor']['login'], $event['actor']['login'], array('target' => '_blank')) }}</li>
	@if ($event['commit_id'] != null)
		<li>closed the issue in {{ HTML::link('http://github.com/riddles8888/'. $repo .'/commit/'. $event['commit_id'], substr($event['commit_id'], 0, 7), array('target' => '_blank')) }}.</li>
	@else
		<li>closed the issue.</li>
	@endif
</ul>
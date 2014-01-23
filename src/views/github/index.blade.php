<ul class="nav nav-tabs" id="myTab">
	@foreach ($issues as $repo => $details)
		@if (Session::has('repoTab') && Session::get('repoTab') == $repo)
			<li class="active">
		@elseif (!Session::has('repoTab') && $primary == $repo)
			<li class="active">
		@else
			<li>
		@endif
			<a href="#{{ $repo }}" data-toggle="tab" data-repo="{{ $repo }}">{{ $details['displayName'] }} ({{ count($details['issues']) }})</a>
		</li>
	@endforeach
</ul>
<div class="tab-content">
	@foreach ($issues as $repo => $details)
		@if (Session::has('repoTab') && Session::get('repoTab') == $repo)
			<div class="tab-pane active" id="{{ $repo }}">
		@elseif (!Session::has('repoTab') && $primary == $repo)
			<div class="tab-pane active" id="{{ $repo }}">
		@else
			<div class="tab-pane" id="{{ $repo }}">
		@endif
		@include('github.components.issues', array('branch' => $repo, 'branchReadable' => $details['displayName'], 'issues' => $details['issues'], 'githubUser' => $details['githubUser']))
		</div>
	@endforeach
</div>
<script>
	@section('onReadyJs')
		$('a[data-toggle="tab"]').on('click', function () {
			$.get('/github/tab/'+ $(this).attr('data-repo'));
		});
	@stop
</script>
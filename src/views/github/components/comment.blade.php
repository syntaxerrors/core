<?php
	$emailPosition = strripos($comment['body'], "\r\nOn");
?>
<div class="well">
	<div class="media">
		<a class="pull-left" href="https://github.com/{{ $comment['user']['login'] }}" target="_blank">
			{{ HTML::image($comment['user']['avatar_url'], null, array('class' => 'media-object')) }}
			<center>{{ $comment['user']['login'] }}</center>
		</a>
		<small class="text-primary">
			{{ date('F jS, Y \a\t h:ia', strtotime($comment['created_at'])) }}
		</small>
		@if ($comment['user']['login'] == $activeUser->githubLogin)
			<span class="pull-right">
				<a href="javascript:void(0);"  onClick="editComment('comment_body_{{ $comment['id'] }}');"><i class="fa fa-pencil text-disabled"></i></a>
				{{ HTML::linkIcon('/github/delete-comment/'. $githubUser .'/'. $repo .'/'. $issue['number'] .'/'. $comment['id'], 'fa fa-times-circle-o', null, array('class' => 'confirm-remove text-disabled')) }}
			</span>
		@endif
		<div class="media-body">
			<!-- <a href="javascript:void(0);" id="comment_body_{{ $comment['id'] }}" data-value="test"> -->
				@if ($emailPosition !== false)
					{{ $github->api('markdown')->render(nl2br(substr($comment['body'], 0, $emailPosition))) }}
				@else
					{{ $github->api('markdown')->render(nl2br($comment['body'])) }}
				@endif
			<!-- </a> -->
		</div>
	</div>
	<br />
	<div class="clearfix"></div>
</div>
@section('js')
	<script>
		$('#comment_body_{{ $comment['id'] }}').editable({
			send: 'never',  
			title: 'Edit Comment',
			type: 'textarea',
			placement: 'right',
			toggle: 'manual',
		});
		function editComment(commentBody) {
			$('#'+ commentBody).editable('toggle');
		}
	</script>
@stop
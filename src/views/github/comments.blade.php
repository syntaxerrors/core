@section('css')
	{{ HTML::style('vendor/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}
@stop
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<div class="well">
			<div class="well-title">
				{{ HTML::link('http://github.com/'. $githubUser .'/'. $repo .'/issues/'. $issue['number'], $issue['title'] .' #'. $issue['number']) }}
				<div class="well-btn well-btn-right">
					{{ HTML::linkIcon('/github', 'fa fa-arrow-left') }}
					&nbsp;|&nbsp;
					{{ HTML::linkIcon('http://github.com/'. $githubUser .'/'. $repo .'/issues/'. $issue['number'], 'fa fa-github', null, array('target' => '_blank')) }}
				</div>
			</div>
			@include('github.components.issue')
		</div>
		@foreach ($comments as $comment)
			@include('github.components.comment')
		@endforeach
		@foreach ($events as $event)
			<?php if ($event['event'] != 'closed') continue; ?>
			@include('github.components.event')
		@endforeach
		{{ bForm::open() }}
			<div class="well">
				{{ bForm::textarea('body', null, array('class' => 'input-block-level', 'placeholder' => 'Comment')) }}
				<div class="form-group">
					<div class="col-md-10">
						{{ Form::submit('Comment', array('class' => 'btn btn-sm btn-primary')) }}
						{{ Form::submit('Comment & Close', array('class' => 'btn btn-sm btn-inverse', 'name' => 'close')) }}
					</div>
				</div>
			</div>
		{{ bForm::close() }}
	</div>
</div>

@section('jsInclude')
	{{ HTML::script('vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js') }}
@stop
<div class="row">
	<div class="col-md-offset-3 col-md-6">
		<div class="well">
			<div class="well-title">Add Issue to {{ $repo }}</div>
			{{ bForm::open() }}
				{{ bForm::text('title', Input::old('title'), array('class' => 'input-block-level', 'placeholder' => 'Comment'), 'Title') }}
				{{ bForm::select('milestone', $milestones, Input::old('milestone'), array(), 'Milestone') }}
				{{ bForm::select('assignee', $contributors, Input::old('assignee'), array(), 'Assignee') }}
				<div class="clearfix"></div>
				<center>
					<div class="btn-group" data-toggle="buttons-checkbox">
						@foreach ($labelOptions as $label)
							<?php
								$classes = 'btn btn-sm btn-primary';
							?>
							<button type="button" class="{{ $classes }}" data-value="{{ $label }}">{{ ucfirst($label) }}</button>
						@endforeach
					</div>
				</center>
				<br />
				{{ bForm::textarea('body', Input::old('body'), array('class' => 'input-block-level', 'placeholder' => 'Comment'), 'Body') }}
				@foreach ($labelOptions as $label)
					{{ bForm::hidden('labels['. $label .']', 0, array('id' => $label)) }}
				@endforeach
				{{ bForm::submit('Submit issue') }}
			{{ bForm::close() }}
		</div>
	</div>
</div>

<script>
	@section('onReadyJs')
		$('button').on('click', function () {
			var button = $(this);
			var value  = button.attr('data-value');

			if (button.hasClass('active')) {
				$('#'+ value).val('0');
			} else {
				$('#'+ value).val('1');
			}
			console.log($(this).attr('data-value'));
		});
	@stop
</script>
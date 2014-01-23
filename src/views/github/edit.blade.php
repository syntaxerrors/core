<div class="row">
	<div class="col-md-offset-3 col-md-6">
		<div class="well">
			<div class="well-title">Edit Issue to {{ $issue['title'] }}</div>
			{{ bForm::open() }}
				{{ bForm::text('title', $issue['title'], array('class' => 'input-block-level', 'placeholder' => 'Comment'), 'Title') }}
				{{ bForm::select('milestone', $milestones, ($issue['milestone'] != null ? $issue['milestone']['number'] : null), array(), 'Milestone') }}
				{{ bForm::select('assignee', $contributors, ($issue['assignee'] != null ? $issue['assignee']['login'] : null), array(), 'Assignee') }}
				<div class="clearfix"></div>
				<center>
					<div class="btn-group" data-toggle="buttons-checkbox">
						@foreach ($labelOptions as $label)
							<?php
								$classes = 'btn btn-sm btn-primary';

								if (in_array($label, $labels)) {
									$classes .= ' active';
								}
							?>
							<button type="button" class="{{ $classes }}" data-value="{{ $label }}">{{ ucfirst($label) }}</button>
						@endforeach
					</div>
				</center>
				<br />
				{{ bForm::textarea('body', $issue['body'], array('class' => 'input-block-level', 'placeholder' => 'Comment'), 'Body') }}
				@foreach ($labelOptions as $label)
					<?php
						$value = 0;

						if (in_array($label, $labels)) {
							$value = 1;
						}
					?>
					{{ bForm::hidden('labels['. $label .']', $value, array('id' => $label)) }}
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
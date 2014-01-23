{{ bForm::open(false, array('id' => 'composeMessage')) }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h3 id="myRemoteModalLabel">Compose</h3>
	</div>
	<div class="modal-body">
		@if (isset($user))
			<div class="form-group">
				<label class="col-sm-2 control-label">To</label>
				<div class="col-sm-10">
					<p class="form-control-static">
						{{ Form::hidden('receiver_id', $user->id) }}
						{{ $user->username }}
					</p>
				</div>
			</div>
		@elseif ($replyFlag == 0)
			{{ bForm::select('receiver_id', $users, null, array('id' => 'receiver_id'), 'To') }}
		@else
			{{ Form::hidden('child_id', $message->id) }}
			<div class="form-group">
				<label class="col-sm-2 control-label">To</label>
				<div class="col-sm-10">
					<p class="form-control-static">
						@if ($message->sender_id == $activeUser->id)
							{{ Form::hidden('receiver_id', $message->receiver_id) }}
							{{ $message->receiver->username }}
						@else
							{{ Form::hidden('receiver_id', $message->sender_id) }}
							{{ $message->sender->username }}
						@endif
					</p>
				</div>
			</div>
		@endif
		<?php
			$title = null;
			if (isset($message) && $message != null) {
				if (strpos($message->title, 'RE:') === false) {
					$title = 'RE: '. $message->title;
				} else {
					$title = $message->title;
				}
			}
		?>
		{{ bForm::text('title', $title, array('id' => 'title', 'placeholder' => 'Title'), 'Title') }}
		{{ bForm::textarea('content', null, array('id' => 'content', 'placeholder' => 'Body', 'style' => 'margin-left: 0; width: auto;', 'cols' => 73, 'rows' => 5), 'Body') }}
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" id="composeSubmit" aria-hidden="true">Submit</button>
		<div id="composeStatusMessage"></div>
	</div>
{{ bForm::close() }}

<script>
	$('#composeSubmit').on('click', function(event) {
		event.preventDefault();
		$('#composeSubmit').attr('disabled', 'disabled');

		$('.error').removeClass('error');
		$('#composeStatusMessage').empty().append('<i class="fa fa-spinner fa-spin"></i>');

		var data = $('#composeMessage').serialize();

		$.post('/messages/compose', data, function(response) {

			if (response.status == 'success') {
				$('#composeStatusMessage').empty().append('Message sent.');

				// Make the modal go away
				window.setTimeout(function () {
					$('#remoteModal').modal('hide');
					$('#remoteModal').removeData('modal');
					$('#composeSubmit').removeAttr('disabled');
					$('#composeStatusMessage').empty();
				}, 2000);
			}
			if (response.status == 'error') {
				$('#composeStatusMessage').empty();
				$.each(response.errors, function (key, value) {
					$('#' + key).addClass('error');
					$('#composeStatusMessage').append('<span class="text-error">'+ value +'</span><br />');
				});
			}
		});
	});
</script>
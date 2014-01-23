{{ Form::open(array('class' => 'form-horizontal text-center', 'files' => true)) }}
	<a name="reply"></a>
	<div class="well">
		<div class="well-title">
			<a style="color: #000;" onClick="$(this).children().toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');$('#collapseReply').toggle('200');">
				Quick Reply <i class="fa fa-chevron-down"></i>
			</a>
		</div>
		<div id="collapseReply" style="display: none;">
			@if (!$activeUser->checkPermission('FORUM_POST'))
				You do not have permission to post replies.
			@else
				{{ Form::bHidden('quote_id', null, array('id' => 'quote_id')) }}
				{{ Form::bHidden('quote_type', null, array('id' => 'quote_type')) }}
				{{ Form::bText('quote', null, array('id' => 'quote', 'readonly' => 'readonly', 'placeholder' => 'Quoted Post'), 'Quote') }}
				@section('replyForms')
					{{ Form::bSelect('forum_reply_type_id', $details->replyTypes, array(1), array(), 'Type') }}
				@show
				{{ Form::bText('name', null, array('placeholder' => 'Title', 'tabindex' => 1), 'Title') }}
				@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $activeUser->checkPermission('DEVELOPER'))
					{{ Form::bSelect('forum_support_status_id', $details->statuses, null, array(), 'Status') }}
				@elseif ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $activeUser->id == $post->user_id)
					@if ($post->status->status->id != Forum_Support_Status::TYPE_RESOLVED)
						{{ Form::bSelect('forum_support_status_id', array(0 => 'Select a status', Forum_Support_Status::TYPE_RESOLVED => 'Resolved'), null, array(), 'Status') }}
					@endif
				@endif
				<?php $content = null; ?>
				@include('forum.post.components.quickreplybuttons')
				@if ($post->type->keyName == 'IMAGE')
					<div class="row">
						<div class="col-md-offset-2 col-md-10">
							<div class="text-left">
								@include('forum.post.components.imageinput')
							</div>
						</div>
					</div>
				@endif
				{{ Form::bSubmit('Post', array('class' => 'btn btn-sm btn-primary col-md-3', 'tabindex' => 3)) }}
			@endif
		</div>
	</div>
{{ Form::close() }}
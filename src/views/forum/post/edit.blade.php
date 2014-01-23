<div class="row">
	<div class="col-md-10">
		<div class="well">
			<div class="well-title">Edit post</div>
			<div class="rowspan">
				{{ bForm::open() }}
					@if ($post instanceof Forum_Reply)
						@section('replyForms')
						@show
					@else
						@section('postForms')
							{{ bForm::select('forum_post_type_id', $types, array($post->forum_post_type_id), array(), 'Type') }}
						@show
					@endif
					{{ bForm::text('name', $post->name, array('placeholder' => 'Title'), 'Title') }}
					@include('forum.post.components.quickreplybuttons', array('content' => $post->content))
					{{ bForm::text('reason', null, array('placeholder' => 'Reason for edit'), 'Reason') }}
					{{ bForm::submit('Post') }}
				{{ bForm::close() }}
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
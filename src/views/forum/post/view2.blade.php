<div class="row">
	<div class="col-md-11">
		<!-- Start Breadcrumbs -->
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('forum/category/view/'. $post->board->category->id, $post->board->category->name) }}</li>
				@if ($post->board->parent != null)
					<li>{{ HTML::link('forum/board/view/'. $post->board->parent->id, $post->board->parent->name) }}</li>
				@endif
				<li>{{ HTML::link('forum/board/view/'. $post->board->id, $post->board->name) }}</li>
				<li class="active">
					{{ $post->name }}					
					@if (count($replies) == 30 || isset($_GET['page']))
						<?php
							if (isset($_GET['page'])) {
								$page = $_GET['page'];
							} else {
								$page = 1;
							}
						?>
						: Page {{ $page }}
					@endif
				</li>
			</ul>
		</small>
		<!-- End Breadcrumbs -->
		<!-- Start Header Details -->
		@if ($post->replies->count() > 30)
			<div>
				<div style="vertical-align: top;display: inline-block;">
					<span class="muted">Page:</span>&nbsp;
				</div>
				<div style="display: inline-block;"><small>{{ $replies->links() }}</small></div>
			</div>
		@endif
		<div class="pull-left">
			<div class="btn-group">
				@if ($post->previousPost != null)
					{{ HTML::link('forum/post/view/'. $post->previousPost->id, $post->previousPost->name, array('class' => 'btn btn-xs btn-primary')) }}
				@endif
			</div>
		</div>
		<div class="pull-right">
			<div class="btn-group">
				@if ($post->nextPost != null)
					{{ HTML::link('forum/post/view/'. $post->nextPost->id, $post->nextPost->name, array('class' => 'btn btn-xs btn-primary')) }}
				@endif
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- End Header Details -->
		<!--Start Post -->
		@if (!isset($_GET['page']) || $_GET['page'] == 1)
			@include('forum.post.components.postdisplay')
		@endif
		<!--End Post -->
		<!-- Start Replies -->
		@if (count($replies) > 0)
			@foreach ($replies as $reply)
				@include('forum.post.components.postdisplay', array('post' => $reply))
			@endforeach
			@if ($replies->count() > 30)
				<div>
					<div style="vertical-align: top;display: inline-block;">
						<span class="muted">Page:</span>&nbsp;
					</div>
					<div style="display: inline-block;">{{ $replies->links() }}</div>
				</div>
			@endif
			<br />
		@endif
		<!-- End Replies -->
		<!-- Start Quick Reply -->
		@if ($post->forum_post_type_id == Forum_Post::TYPE_LOCKED && $activeUser->getHighestRole('Forum') != 'Forum - Administrator')
			This board is locked for replies.
		@else
			{{ Form::open(array('class' => 'form-horizontal text-center')) }}
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
								{{ Form::bSelect('forum_reply_type_id', $types, array(1), array(), 'Type') }}
							@show
							{{ Form::bText('name', null, array('placeholder' => 'Title', 'tabindex' => 1), 'Title') }}
							@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $activeUser->checkPermission('DEVELOPER'))
								{{ Form::bSelect('forum_support_status_id', $statuses, null, array(), 'Status') }}
							@elseif ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $activeUser->id == $post->user_id)
								@if ($post->status->status->id != Forum_Support_Status::TYPE_RESOLVED)
									{{ Form::bSelect('forum_support_status_id', array(0 => 'Select a status', Forum_Support_Status::TYPE_RESOLVED => 'Resolved'), null, array(), 'Status') }}
								@endif
							@endif
							<?php $content = null; ?>
							@include('forum.post.components.quickreplybuttons')
							{{ Form::bSubmit('Post', array('class' => 'btn btn-sm btn-primary col-md-3', 'tabindex' => 3)) }}
						@endif
					</div>
				</div>
			{{ Form::close() }}
		@endif
		<!-- End Quick Reply -->
		<!-- Start Bottom Breadcrumbs -->
		@if ($post->replies->count() > 30)
			<div>
				<div style="vertical-align: top;display: inline-block;">
					<span class="muted">Page:</span>&nbsp;
				</div>
				<div style="display: inline-block;"><small>{{ $replies->links() }}</small></div>
			</div>
		@endif
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('forum/category/view/'. $post->board->category->id, $post->board->category->name) }}</li>
				@if ($post->board->parent != null)
					<li>{{ HTML::link('forum/board/view/'. $post->board->parent->id, $post->board->parent->name) }}</li>
				@endif
				<li>{{ HTML::link('forum/board/view/'. $post->board->id, $post->board->name) }}</li>
				<li class="active">
					{{ $post->name }}
					@if (count($replies) == 30 || isset($_GET['page']))
						<?php
							if (isset($_GET['page'])) {
								$page = $_GET['page'];
							} else {
								$page = 1;
							}
						?>
						: Page {{ $page }}
					@endif
				</li>
			</ul>
		</small>
		<!-- End Bottom Breadcrumbs -->
	</div>
</div>
<a name="replyField"></a>
<!-- Start Report to moderator modal -->
{{ Form::open(array('class' => 'form-horizontal')) }}
	{{ Form::bHidden('report_resource_id', null, array('id' => 'report_resource_id')) }}
	{{ Form::bHidden('report_resource_name', null, array('id' => 'report_resource_name')) }}
	@include('helpers.modalHeader', array('modalId' => 'reportToModerator', 'modalHeader' => 'Report this post to a moderator'))
		{{ Form::bTextarea('reason', null, array('placeholder' => 'Reason'), 'Reason', 5) }}
		</div>
		<div class="modal-footer">
			{{ Form::bSubmit('Submit Report', array('class' => 'btn btn-xs btn-primary')) }}
			<button class="btn btn-xs btn-primary" data-dismiss="modal" aria-hidden="true" onClick="removeResources('report')">Close</button>
		</div>
	</div>
{{ Form::close() }}
<!-- End Report to moderator modal -->
<script type="text/javascript">
	function addResourcetoReport(object,type) {
		var resourceId   = $(object).attr('data-resource-id');
		var resourceName = $(object).attr('data-resource-name');
		$('#'+ type +'_resource_id').val(resourceId);
		$('#'+ type +'_resource_name').val(resourceName);
	}
	function removeResources(type) {
		$('#'+ type +'_resource_id').val('');
		$('#'+ type +'_resource_name').val('');
	}
	function addQuote(object) {
		$('#collapseReply').addClass('in');
		var quoteId   = $(object).attr('data-quote-id');
		var quoteType = $(object).attr('data-quote-type');
		var quoteName = $(object).attr('data-quote-name');
		$('#quote_id').val(quoteId);
		$('#quote_type').val(quoteType);
		$('#quote').val('Quoting: '+quoteName);
	}
</script>
@if ($post->type->keyName == 'IMAGE')
	@section('css')
		{{ HTML::style('vendor/jansyBootstrap/dist/extend/css/jasny-bootstrap.min.css') }}
		{{ HTML::style('vendor/Gallery/css/blueimp-gallery.min.css') }}
		{{ HTML::style('vendor/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css') }}
	@stop
	@section('jsInclude')
		{{ HTML::script('vendor/jansyBootstrap/dist/extend/js/jasny-bootstrap.min.js') }}
		{{ HTML::script('vendor/Gallery/js/jquery.blueimp-gallery.min.js') }}
		{{ HTML::script('vendor/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js') }}
	@stop
@endif
<div class="row">
	<div class="col-md-11">
		<!-- Start Breadcrumbs -->
		@include('forum.post.components.breadcrumbs')
		<!-- End Breadcrumbs -->
		<!-- Start Information Details -->
		@if ($details->pageCount > 1)
			<div>
				<div style="vertical-align: top;display: inline-block;">
					<span class="muted">Page:</span>&nbsp;
				</div>
				<div style="display: inline-block;">
					<small>
						<ul class="pagination pagination-sm">
							@foreach (range(1, $details->pageCount) as $page)
								<li {{ $page == $details->currentPage ? 'class="active"' : null }}>
									{{ HTML::link('/forum/post/view/'. $post->id .'/'. $page, $page) }}
								</li>
							@endforeach
						</ul>
					</small>
				</div>
			</div>
		@endif
		@include('forum.post.components.quicknav')
		<!-- End information Details -->
		<!--Start Post -->
		@if ($details->parts->post != null)
			@include($details->parts->sidebar)
					@include('forum.post.components.postdisplay')
				</div>
			</div>
		@endif
		<!--End Post -->
		<!-- Start Replies -->
		@if ($details->parts->replies != null)
			@foreach ($details->parts->replies as $reply)
				@include($details->parts->sidebar, array('post' => $reply))
						@include('forum.post.components.postdisplay', array('post' => $reply))
					</div>
				</div>
			@endforeach
			<br />
		@endif
		<!-- End Replies -->
		<!-- Start Quick Reply -->
		@if ($post->forum_post_type_id == Forum_Post::TYPE_LOCKED && $activeUser->getHighestRole('Forum') != 'Forum - Administrator')
			This board is locked for replies.
		@else
			@include('forum.post.components.quickreply')
		@endif
		<!-- End Quick Reply -->
		<!-- Start Bottom Breadcrumbs -->
		@if ($details->pageCount > 1)
			<div>
				<div style="vertical-align: top;display: inline-block;">
					<span class="muted">Page:</span>&nbsp;
				</div>
				<div style="display: inline-block;">
					<small>
						<ul class="pagination pagination-sm">
							@foreach (range(1, $details->pageCount) as $page)
								<li {{ $page == $details->currentPage ? 'class="active"' : null }}>
									{{ HTML::link('/forum/post/view/'. $post->id .'/'. $page, $page) }}
								</li>
							@endforeach
						</ul>
					</small>
				</div>
			</div>
		@endif
		@include('forum.post.components.quicknav')
		@include('forum.post.components.breadcrumbs')
		<!-- End Bottom Breadcrumbs -->
	</div>
</div>
<a name="replyField"></a>
<!-- Start Report to moderator modal -->
{{ bForm::open() }}
	{{ bForm::hidden('report_resource_id', null, array('id' => 'report_resource_id')) }}
	{{ bForm::hidden('report_resource_name', null, array('id' => 'report_resource_name')) }}
	@include('helpers.modalHeader', array('modalId' => 'reportToModerator', 'modalHeader' => 'Report this post to a moderator'))
		{{ bForm::textarea('reason', null, array('placeholder' => 'Reason'), 'Reason', 5) }}
		</div>
		<div class="modal-footer">
			{{ Form::submit('Submit Report', array('class' => 'btn btn-xs btn-primary')) }}
			<button class="btn btn-xs btn-primary" data-dismiss="modal" aria-hidden="true" onClick="removeResources('report')">Close</button>
		</div>
	</div>
{{ Form::close() }}
<!-- End Report to moderator modal -->

@section('js')
	<script>
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
@stop
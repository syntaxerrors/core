<div class="row">
	<div class="col-md-2">
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Moderation Panel</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="reported-posts">
					Reported Posts
					<span class="badge badge-important pull-right">{{ ($reportedPostsCount > 0 ? $reportedPostsCount : null) }}</span>
				</a>
			</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="report-logs">
					Report Logs
					<span class="badge pull-right">{{ $reportLogsCount }}</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-10">
		<div id="ajaxContent"></div>
	</div>
</div>
<script>
	@section('onReadyJs')
		$.AjaxLeftTabs('/forum/moderation/', 'reported-posts');
	@endsection
</script>
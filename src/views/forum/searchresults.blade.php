<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Search Results: Found {{ $posts->getTotal() }}
				<div class="pull-right">
					<small>
						Showing results {{ $posts->getFrom() }} - {{ $posts->getTo() }}
					</small>
				</div>
			</div>
			<table class="table table-hover table-condensed">
				@if ( (isset($posts) && count($posts) > 0) || (isset($replies) && count($replies) > 0) )
					<thead>
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Type</th>
							<th>Replies</th>
							<th>Posted</th>
							<th>Last Updated</th>
						</tr>
					</thead>
					<tbody>
						@if (isset($posts) && count($posts) > 0)
							@foreach ($posts as $post)
								<tr>
									<td>{{ $post->link }}</td>
									<td>{{ $post->displayName }}</td>
									<td>{{ str_replace('Forum_', '', $post->type) }}</td>
									<td>{{ $post->replyCount }}</td>
									<td>{{ $post->createdAtReadable }}</td>
									<td>{{ $post->modifiedAtReadable }}</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				@else
					<tbody>
						<tr>
							<td>No results.</td>
						</tr>
					</tbody>
				@endif
			</table>
			<div class="panel-footer text-center">
				{{ $posts->appends(Input::except('page'))->links() }}
			</div>
		</div>
	</div>
</div>

<script>
	@section('onReadyJs')
		// Make twitter paginator ajax
		$('.pagination a').on('click', function (event) {
			event.preventDefault();
			if ( $(this).attr('href') != '#') {
				$('#ajaxContent').load($(this).attr('href'));
			}
		});
	@stop
</script>
<div class="row">
	<div class="col-md-8">
		<small>
			<ul class="breadcrumb">
				<li class="active">Forums</li>
				<li class="pull-right">{{ HTML::link('/forum/mark-all-read', 'Mark All Read') }}
			</ul>
		</small>
		<?php $main = true; ?>
		@if (count($categories) > 0)
			@foreach ($categories as $category)
				@include('forum.category.view')
			@endforeach
		@endif
	</div>
	<div class="col-md-4">
		{{ bForm::open(false, array('url' => '/forum/search', 'type' => 'GET')) }}
			{{ bForm::text('keyword', null, array('placeholder' => 'Search term'), 'Search') }}
		{{ bForm::close() }}
		<div class="well">
			<div class="well-title">Recent Activity</div>
			<table style="width: 100%;" class="table-hover">
				<tbody>
					@if (count($recentPosts) > 0)
						@foreach ($recentPosts as $post)
							<tr>
								<td class="text-center" style="width: 30px;">
									{{ $post->icon }}
								</td>
								<td>{{ HTML::link('forum/post/view/'. $post->id, $post->name) }}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<div class="well">
			<div class="well-title">Technical Support</div>
			<table style="width: 100%;" class="table-hover">
				<caption>Issues</caption>
				<tbody>
					<tr class="text-info">
						<td class="text-center"><i class="fa fa-bolt"></i></td>
						<td><b>Open Issues</b></td>
						<td>{{ $openIssues }}</td>
					</tr>
					<tr class="text-warning">
						<td class="text-center"><i class="fa fa-clock-o"></i></td>
						<td><b>In Progress Issues</b></td>
						<td>{{ $inProgressIssues }}</td>
					</tr>
					<tr class="text-success">
						<td class="text-center"><i class="fa fa-check-square-o"></i></td>
						<td><b>Resolved Issues</b></td>
						<td>{{ $resolvedIssues }}</td>
					</tr>
				</tbody>
			</table>
			<table style="width: 100%;" class="table-hover">
				<caption>Recent Posts</caption>
				<tbody>
					@if (count($recentSupportPosts) > 0)
						@foreach ($recentSupportPosts as $post)
							<tr>
								<td class="text-center" style="width: 30px;">{{ $post->status->icon }}</td>
								<td>{{ HTML::link('forum/post/view/'. $post->id, $post->name) }}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
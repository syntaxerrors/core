<div class="row">
	<div class="col-md-10">
		@include('forum.board.components.breadcrumbs')
		@if (count($board->children) > 0)
			<div class="panel panel-default">
				<div class="panel-heading">Child Boards</div>
				<ul class="forum">
					@foreach ($board->children as $child)
						@include('forum.category.components.board', array('board' => $child))
					@endforeach
				</ul>
			</div>
		@endif
		<div class="panel panel-default">
			<div class="panel-heading text-center">
				@if ($posts->getTotal() > 30)
					{{ $posts->links() }}
				@endif
			</div>
			<div class="labels">
				<div class="subject">Subject/Author</div>
				<div class="replies">Replies/Views</div>
				<div class="lastPost">LastPost</div>
				<div class="clearfix"></div>
			</div>
			<ul class="forum">
				@if (count($announcements) > 0)
					@foreach ($announcements as $announcement)
						@include('forum.board.components.post', array('post' => $announcement))
					@endforeach
				@endif
				@if (count($posts) > 0)
					@foreach ($posts as $post)
						@include('forum.board.components.post', array('post' => $post))
					@endforeach
				@endif
			</ul>
			<div class="panel-footer text-center">
				@if ($posts->getTotal() > 30)
					{{ $posts->links() }}
				@endif
			</div>
		</div>
		@include('forum.board.components.breadcrumbs')
	</div>
	<div class="col-md-2">
		<div class="well">
			<div class="well-title">Legend</div>
			<table style="width: 100%;" class="table-hover">
				<caption>Posts and Replies</caption>
				<tbody>
					<tr>
						<td class="text-center"><i class="fa fa-eye"></i></td>
						<td>Unread Post</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-exclamation-triangle"></i></td>
						<td>Announcement</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-thumb-tack"></i></td>
						<td>Sticky</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-lock"></i></td>
						<td>Locked</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-inbox"></i></td>
						<td>Application</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-exchange"></i></td>
						<td>Action</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-comments"></i></td>
						<td>Conversation</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-cloud"></i></td>
						<td>Inner Thought</td>
					</tr>
				</tbody>
			</table>
			<br />
			<table style="width: 100%;" class="table-hover">
				<caption>Technical Support Issues</caption>
				<tbody>
					<tr>
						<td class="text-center"><i class="fa fa-bolt"></i></td>
						<td>Open</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-clock-o"></i></td>
						<td>In Progress</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-check-square-o"></i></td>
						<td>Resolved</td>
					</tr>
					<tr>
						<td class="text-center"><i class="fa fa-ban"></i></td>
						<td>Wont Fix<br />
					</tr>
				</tbody>
			</table>
		</div>
		@if ($board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT)
			<div class="well">
				<div class="well-title">Technical Support</div>
				<table style="width: 100%;" class="table-hover">
					<caption>Issues</caption>
					<tbody>
						<tr class="text-info">
							<td class="text-center"><i class="fa fa-bolt"></i></td>
							<td><b>Open</b></td>
							<td>{{ $openIssues }}</td>
						</tr>
						<tr class="text-warning">
							<td class="text-center"><i class="fa fa-clock-o"></i></td>
							<td><b>In Progress</b></td>
							<td>{{ $inProgressIssues }}</td>
						</tr>
						<tr class="text-success">
							<td class="text-center"><i class="fa fa-check-square-o"></i></td>
							<td><b>Resolved</b></td>
							<td>{{ $resolvedIssues }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		@endif
	</div>
</div>
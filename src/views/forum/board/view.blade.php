<div class="row">
	<div class="col-md-10">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('forum/category/view/'. $board->category->id, $board->category->name) }}</li>
				@if ($board->parent != null)
					<li>{{ HTML::link('forum/board/view/'. $board->parent->id, $board->parent->name) }}</li>
				@endif
				<li class="active">
					{{ $board->name }}
					@if (count($posts) == 30 || isset($_GET['page']))
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
				<li class="pull-right">
					{{ HTML::link('/forum/post/add/'. $board->id, 'Add Post') }}
				</li>
			</ul>
		</small>
		@if (count($board->children) > 0)
			<div class="well">
				<div class="well-title">Child Boards</div>
				@foreach ($board->children as $child)
					<table style="width: 100%;">
						<tbody>
							<tr>
								<td class="middle" style="width: 65px;" rowpsan="3">
									@if (Auth::user()->checkUnreadBoard($child->id))
										{{ HTML::image('img/forum/on.png', null, array('style' => 'width: 30px')) }}
									@else
										{{ HTML::image('img/forum/off.png', null, array('style' => 'width: 30px')) }}
									@endif
								</td>
								<td class="boardLink" rowpsan="3">
									<table>
										<tbody>
											<tr>
												<td>{{ HTML::link('forum/board/view/'. $child->id, $child->name) }}</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td class="middle" style="width: 100px;">
									<table class="main no_border">
										<tbody>
											<tr>
												<td>{{ $child->postsCount .' '. Str::plural('Post', $child->postsCount) }}</td>
											</tr>
											<tr>
												<td>{{ $child->repliesCount .' '. Str::plural('Reply', $child->repliesCount) }}</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width: 200px;">
									@if ($child->lastUpdate !== false)
										<?php
											$lastUpdateType = $child->lastUpdate->type->keyName;
											$lastUpdateUser = ($child->lastUpdate->morph_id == null || $lastUpdateType == 'application'
												? $child->lastUpdate->author : $child->lastUpdate->morph);
											$lastUpdateName = ($lastUpdateUser instanceof User ? $lastUpdateUser->username : $lastUpdateUser->name);
										?>
										<small>
											<table>
												<tbody>
													<tr>
														<td>Last Post by {{ HTML::link('/user/view/'. $child->lastUpdate->author->id, $lastUpdateName) }}</td>
													</tr>
													<tr>
														<td>in {{ HTML::link('forum/post/view/'. $child->lastPost->uniqueId .'#reply:'. $child->lastUpdate->id, $child->lastUpdate->name) }}</td>
													</tr>
													<tr>
														<td>on {{ $child->lastUpdate->created_at }}</td>
													</tr>
												</tbody>
											</table>
										</small>
									@else
										<small>
											No posts.
										</small>
									@endif
								</td>
							</tr>
						</tbody>
					</table>
					<hr />
				@endforeach
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
						<li class="{{ $announcement->classes }}">
							<div class="post">
								<div class="subject">
									{{ $announcement->link }}
									<br />
									{{ $announcement->startedBy }}
								</div>
								<div class="replies">
									{{ $announcement->repliesBlock }}
								</div>
								<div class="lastPost">
									{{ $announcement->lastPostBlock }}
								</div>
								<div class="clearfix"></div>
							</div>
						</li>
					@endforeach
				@endif
				@if (count($posts) > 0)
					@foreach ($posts as $post)
						<li class="{{ $post->classes }}">
							<div class="post">
								<div class="subject">
									{{ $post->link }}
									<br />
									{{ $post->startedBy }}
								</div>
								<div class="replies">
									{{ $post->repliesBlock }}
								</div>
								<div class="lastPost">
									{{ $post->lastPostBlock }}
								</div>
								<div class="clearfix"></div>
							</div>
						</li>
					@endforeach
				@endif
			</ul>
			<div class="panel-footer text-center">
				@if ($posts->getTotal() > 30)
					{{ $posts->links() }}
				@endif
			</div>
		</div>
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('forum/category/view/'. $board->category->id, $board->category->name) }}</li>
				<li class="active">
					{{ $board->name }}
					@if (count($posts) == 30 || isset($_GET['page']))
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
				<li class="pull-right">
					{{ HTML::link('/forum/post/add/'. $board->id, 'Add Post') }}
				</li>
			</ul>
		</small>
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
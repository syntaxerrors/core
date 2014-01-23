 @if (!isset($main))
	<small>
		<ul class="breadcrumb">
			<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
			<li class="active">{{ $category->name }}</li>
		</ul>
	</small>
@endif
	<div class="well">
	<div class="well-title">
		{{ $category->name }}
		<div class="well-btn well-btn-left">
			{{ HTML::linkIcon('forum/category/view/'. $category->uniqueId, 'fa fa-share-square-o', null, array('style' => 'color: #000;')) }}
		</div>
		@if ($category->type->keyName == 'technical-support')
			<div class="well-btn well-btn-right">
				<i class="fa fa-cogs"></i>
			</div>
		@endif
	</div>
	@if (count($category->boards) > 0)
		@foreach ($category->boards as $board)
			@if ($board->parent_id == null)
				<table style="width: 100%;">
					<tbody>
						<tr>
							<td style="width: 65px; vertical-align: middle;" rowpsan="3">
								@if ($activeUser->checkUnreadBoard($board->id))
									<i class="fa fa-square fa-3x text-primary"></i>
								@else
									<i class="fa fa-square-o fa-3x text-disabled"></i>
								@endif
							</td>
							<td class="boardLink" rowpsan="3" style="vertical-align: middle;">
								<table>
									<tbody>
										<tr>
											<td>{{ HTML::link('forum/board/view/'. $board->uniqueId, $board->name) }}</td>
										</tr>
										@if ($board->childLinks != null)
											<tr>
												<td><small><small>Child Boards:&nbsp;{{ $board->childLinks }}</small></small></td>
											</tr>
										@endif
									</tbody>
								</table>
							</td>
							<td style="width: 100px; vertical-align: middle;">
								<table class="main no_border">
									<tbody>
										<tr>
											<td>{{ $board->postsCount .' '. Str::plural('Post', $board->postsCount) }}</td>
										</tr>
										<tr>
											<td>{{ $board->repliesCount .' '. Str::plural('Reply', $board->repliesCount) }}</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td style="width: 200px; vertical-align: middle;">
								@if ($board->lastUpdate !== false)
									<?php
										$lastUpdatePage = $board->lastUpdatePage;
										if ($lastUpdatePage != null) {
											$lastUpdateType = $board->lastUpdate->type->keyName;
											$lastUpdateUser = ($board->lastUpdate->morph_id == null || $lastUpdateType == 'application'
												? $board->lastUpdate->author : $board->lastUpdate->morph);
											$lastUpdateName = ($lastUpdateUser instanceof User ? $lastUpdateUser->username : $lastUpdateUser->name);
											$lastUpdateLink = 'forum/post/view/'. $board->lastPost->uniqueId;
											if ($lastUpdatePage > 1) {
												$lastUpdateLink .= '?page='. $lastUpdatePage;
											}
										}
										$lastUpdateLink .=  '#reply:'. $board->lastUpdate->id;
									?>
									<small>
										<table>
											<tbody>
												<tr>
													<td>Last Post by {{ HTML::link('/user/view/'. $board->lastUpdate->author->id, $lastUpdateName) }}</td>
												</tr>
												<tr>
													<td>in {{ HTML::link($lastUpdateLink, $board->lastUpdate->name) }}</td>
												</tr>
												<tr>
													<td>on {{ $board->lastUpdate->created_at }}</td>
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
			@endif
		@endforeach
	@endif
</div>
@if (!isset($main))
	<small>
		<ul class="breadcrumb">
			<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
			<li class="active">{{ $category->name }}</li>
		</ul>
	</small>
@endif
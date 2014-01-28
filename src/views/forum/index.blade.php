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
		<div class="panel panel-default">
			<div class="panel-heading">Recent Activity</div>
			<ul class="forum">
				@if (count($recentPosts) > 0)
					@foreach ($recentPosts as $post)
						<li class="{{ $post->classes }}">
							<div class="post">
								<div class="subject">
									{{ $post->link }}
								</div>
								<div class="clearfix"></div>
							</div>
						</li>
					@endforeach
				@endif
			</ul>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Technical Support Issues</div>
			<ul class="forum">
				<li class="open">
					<div class="post text-info">
						<div class="subject">
							<strong>Open Issues</strong>
						</div>
						<div class="replies"></div>
						<div class="lastPost">
							<strong>{{ HTML::link('/forum/search?status=1', $openIssues, array('class' => 'text-info')) }}</strong>
						</div>
						<div class="clearfix"></div>
					</div>
				</li>
				<li class="inProgress">
					<div class="post text-warning">
						<div class="subject">
							<strong>In Progress Issues</strong>
						</div>
						<div class="replies"></div>
						<div class="lastPost">
							<strong>{{ HTML::link('/forum/search?status=2', $inProgressIssues, array('class' => 'text-warning')) }}</strong>
						</div>
						<div class="clearfix"></div>
					</div>
				</li>
				<li class="resolved">
					<div class="post text-success">
						<div class="subject">
							<strong>Resolved Issues</strong>
						</div>
						<div class="replies"></div>
						<div class="lastPost">
							<strong>{{ HTML::link('/forum/search?status=3', $resolvedIssues, array('class' => 'text-success')) }}</strong>
						</div>
						<div class="clearfix"></div>
					</div>
				</li>
			</ul>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Recent Technical Support Posts</div>
			<ul class="forum">
				@if (count($recentSupportPosts) > 0)
					@foreach ($recentSupportPosts as $post)
						<li class="{{ $post->classes }}">
							<div class="post">
								<div class="subject">
									{{ $post->link }}
								</div>
								<div class="clearfix"></div>
							</div>
						</li>
					@endforeach
				@endif
			</ul>
		</div>
	</div>
</div>
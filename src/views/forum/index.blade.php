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
			<div class="list-glow">
				<ul class="list-glow-group no-header">
					@if (count($recentPosts) > 0)
						@foreach ($recentPosts as $post)
							<li class="{{ $post->classes }}">
								<div class="list-glow-group-item list-glow-group-item-sm">
									<div class="col-md-12">
										{{ $post->link }}
									</div>
								</div>
							</li>
						@endforeach
					@endif
				</ul>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Technical Support Issues</div>
			<div class="list-glow">
				<ul class="list-glow-group no-header">
					<li class="open">
						<div class="list-glow-group-item list-glow-group-item-sm text-info">
							<div class="col-md-6">
								<strong>Open Issues</strong>
							</div>
							<div class="col-md-6">
								<strong>{{ HTML::link('/forum/search?status=1', $openIssues, array('class' => 'text-info')) }}</strong>
							</div>
						</div>
					</li>
					<li class="inProgress">
						<div class="list-glow-group-item list-glow-group-item-sm text-warning">
							<div class="col-md-6">
								<strong>In Progress Issues</strong>
							</div>
							<div class="col-md-6">
								<strong>{{ HTML::link('/forum/search?status=2', $inProgressIssues, array('class' => 'text-warning')) }}</strong>
							</div>
						</div>
					</li>
					<li class="resolved">
						<div class="list-glow-group-item list-glow-group-item-sm text-success">
							<div class="col-md-6">
								<strong>Resolved Issues</strong>
							</div>
							<div class="col-md-6">
								<strong>{{ HTML::link('/forum/search?status=3', $resolvedIssues, array('class' => 'text-success')) }}</strong>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Recent Technical Support Posts</div>
			<div class="list-glow">
				<ul class="list-glow-group no-header">
					@if (count($recentSupportPosts) > 0)
						@foreach ($recentSupportPosts as $post)
							<li class="{{ $post->classes }}">
								<div class="list-glow-group-item list-glow-group-item-sm">
									<div class="col-md-12">
										{{ $post->link }}
									</div>
								</div>
							</li>
						@endforeach
					@endif
				</ul>
			</div>
		</div>
	</div>
</div>
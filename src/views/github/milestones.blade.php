<div class="row">
	<div class="col-md-offset-2 col-md-8">
		@foreach ($milestones as $milestone)
			<div class="panel panel-default">
				<div class="panel-heading">
					{{ $milestone['title'] }}
					<div class="panel-btn">
						{{ HTML::linkIcon('http://github.com/'. $githubUser .'/'. $repo .'/issues?milestone='. $milestone['number'], 'fa fa-github', null, array('target' => '_blank')) }}
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<span>{{ $milestone['percent'] }}%</span>
							<div class="progress" style="margin-top: -18px;">
								<div class="progress-bar progress-bar-info" style="width: {{ $milestone['percent'] }}%;">
								</div>
							</div>
						</div>
					</div>
					@if ($milestone['open_issues'] > 0 || $milestone['closed_issues'] > 0)
						<div class="row">
							<div class="col-md-12">
								<ul class="nav nav-tabs">
									<li class="active">
										<a href="#openIssues_{{ $milestone['id'] }}" data-toggle="tab">
											Open Issues ({{ $milestone['open_issues'] }})
										</a>
									</li>
									<li>
										<a href="#closedIssues_{{ $milestone['id'] }}" data-toggle="tab">
											Closed Issues ({{ $milestone['closed_issues'] }})
										</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="openIssues_{{ $milestone['id'] }}">
										@if ($milestone['open_issues'] > 0)
											@include('github.components.milestoneissues', array('issues' => $milestone['open_issue_list'], 'closed' => false))
										@else
											No open issues for this milestone.
										@endif
									</div>
									<div class="tab-pane" id="closedIssues_{{ $milestone['id'] }}">
										@if ($milestone['closed_issues'] > 0)
											@include('github.components.milestoneissues', array('issues' => $milestone['closed_issue_list'], 'closed' => true))
										@else
											No closed issues for this milestone.
										@endif
									</div>
								</div>
							</div>
						</div>
					@else
						No issues for this milestone.
					@endif
				</div>
			</div>
		@endforeach
	</div>
</div>
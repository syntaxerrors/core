<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{ ucwords($branchReadable) }} Issues
				@if (isset($branch))
					<div class="panel-btn">
						<div class="panel-btn-divider"></div>
						{{ HTML::linkIcon('/github/add/'. $githubUser .'/'. $branch, 'fa fa-plus') }}
						<div class="panel-btn-divider"></div>
						{{ HTML::linkIcon('http://github.com/'. $githubUser .'/'. $branch, 'fa fa-github', null, array('target' => '_blank')) }}
						<div class="panel-btn-divider"></div>
						{{ HTML::linkIcon('/github/refresh/'. $branch, 'fa fa-refresh') }}
					</div>
				@endif
			</div>
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 30%;">Title</th>
						@if (!isset($repo))
							<th>Repo</th>
						@endif
						<th>Labels</th>
						<th>Milestone</th>
						<th>Submitted By</th>
						<th>Assigned To</th>
						<th class="text-center">Comments</th>
						<th>Updated At</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $repoFlag = false; ?>
					@foreach ($issues as $issue)
						<?php
							if (isset($repo) && $repoFlag == false) {
								$repo     = $repo;
								$repoFlag = false;
							} else {
								$repo     = $issue['repository']['name'];
								$repoFlag = true;
							}
							$branch = (isset($branch)  && $repoFlag == false ? $branch : $repo);
						?>
						<tr>
							<td>
								<span class="text-disabled"><small>#{{ $issue['number'] }}</small></span>
								&nbsp;
								{{ $issue['title'] }}
							</td>
							@if ($repoFlag == true)
								<td>
									{{ HTML::link('http://github.com/'. $githubUser .'/'. $repo, $repo, array('target' => '_blank')) }}
								</td>
							@endif
							<td>
								@foreach ($issue['labels'] as $label)
									<span class="label label-{{ $label['name'] }}">
										{{ HTML::link('https://github.com/'. $githubUser .'/'. $repo .'/issues?labels='. $label['name'], $label['name'], array('style' => 'color: #fff;', 'target' => '_blank')) }}
									</span>
								@endforeach
							</td>
							<td>
								@if ($issue['milestone'] != null)
									{{ HTML::link('https://github.com/'. $githubUser .'/'. $repo .'/issues?milestone='. $issue['milestone']['number'], $issue['milestone']['title'], array('target' => '_blank')) }}
								@else
									&nbsp;
								@endif
							</td>
							<td>{{ HTML::link('https://github.com/'. $issue['user']['login'], $issue['user']['login'], array('target' => '_blank')) }}</td>
							<td>
								@if ($issue['assignee'] != null)
									{{ HTML::link('https://github.com/'. $issue['assignee']['login'], $issue['assignee']['login'], array('target' => '_blank')) }}
								@else
									&nbsp;
								@endif
							</td>
							<td class="text-center">{{ $issue['comments'] }}</td>
							<td>{{ date('F jS, Y \a\t h:ia', strtotime($issue['updated_at'])) }}</td>
							<td>
								<div class="btn-group">
									{{ HTML::linkIcon('http://github.com/'. $githubUser .'/'. $branch .'/issues/'. $issue['number'], 'fa fa-github', null, array('class' => 'btn btn-xs btn-inverse', 'target' => '_blank')) }}
									{{ HTML::linkIcon('/github/edit/'. $githubUser .'/'. $repo .'/'. $issue['number'], 'fa fa-pencil-square-o', null, array('class' => 'btn btn-xs btn-primary')) }}
									{{ HTML::linkIcon('/github/comments/'. $githubUser .'/'. $repo .'/'. $issue['number'], 'fa fa-comments', null, array('class' => 'btn btn-xs btn-primary')) }}
									{{ HTML::linkIcon('/github/delete/'. $repo .'/'. $issue['number'], 'fa fa-times', null, array('class' => 'confirm-remove btn btn-xs btn-danger')) }}
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
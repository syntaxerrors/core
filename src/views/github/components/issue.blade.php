<div class="row">
	<small>
		<div class="col-md-4">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td>Submitted By</td>
						<td>{{ HTML::link('https://github.com/'. $issue['user']['login'], $issue['user']['login'], array('target' => '_blank')) }}</td>
					</tr>
					<tr>
						<td>Assigned To</td>
						<td>
							@if ($issue['assignee'] != null)
								{{ HTML::link('https://github.com/'. $issue['assignee']['login'], $issue['assignee']['login'], array('target' => '_blank')) }}
							@else
								&nbsp;
							@endif
						</td>
					</tr>
					<tr>
						<td>Closed By</td>
						<td>
							@if ($issue['closed_at'] != null && $issue['closed_by'] != null)
								{{ HTML::link('https://github.com/'. $issue['closed_by']['login'], $issue['closed_by']['login'], array('target' => '_blank')) }}
							@else
								&nbsp;
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td>Submitted On</td>
						<td>{{ date('F jS, Y \a\t h:ia', strtotime($issue['created_at'])) }}</td>
					</tr>
					<tr>
						<td>Updated On</td>
						<td>{{ date('F jS, Y \a\t h:ia', strtotime($issue['updated_at'])) }}</td>
					</tr>
					<tr>
						<td>Closed On</td>
						<td>
							@if ($issue['closed_at'] != null)
								{{ date('F jS, Y \a\t h:ia', strtotime($issue['closed_at'])) }}
							@else
								&nbsp;
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td>State</td>
						<td>{{ $issue['state'] }}</td>
					</tr>
					<tr>
						<td>Labels</td>
						<td>
							@foreach ($issue['labels'] as $label)
								<span class="label label-{{ $label['name'] }}">
									{{ HTML::link('https://github.com/'. $githubUser .'/'. $repo .'/issues?labels='. $label['name'], $label['name'], array('style' => 'color: #fff;', 'target' => '_blank')) }}
								</span>
							@endforeach
						</td>
					</tr>
					<tr>
						<td>Milestone</td>
						<td>
							@if ($issue['milestone'] != null)
								{{ HTML::link('https://github.com/'. $githubUser .'/'. $repo .'/issues?milestone='. $issue['milestone']['number'], $issue['milestone']['title'], array('target' => '_blank')) }}
								&nbsp;&nbsp;
								<?php
									$openIssues   = $issue['milestone']['open_issues'];
									$closedIssues = $issue['milestone']['closed_issues'];
									$totalIssues  = (int)$openIssues + (int)$closedIssues;
								?>
								{{ percent($closedIssues, $totalIssues) .'%' }}
							@else
								&nbsp;
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</small>
</div>
<hr />
<div class="row">
	<div class="col-md-12">
		{{ $github->api('markdown')->render(nl2br($issue['body'])) }}
	</div>
</div>
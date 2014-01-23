<table class="table table-condensed table-hover">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Name</th>
			<th>Labels</th>
			<th>Comments</th>
			<th>Created</th>
			<th>Updated</th>
			@if ($closed == true)
				<th>Closed</th>
			@endif
		</tr>
	</thead>
	<tbody>
		@foreach ($issues as $issue)
			<tr>
				<td>
					<span class="text-disabled"><small>#{{ $issue['number'] }}</small></span>
				</td>
				<td>
					{{ HTML::link('http://github.com/'. $githubUser .'/'. $repo .'/issues/'. $issue['number'], $issue['title'], array('target' => '_blank')) }}
				</td>
				<td>
					@foreach ($issue['labels'] as $label)
						<span class="label label-{{ $label['name'] }}">
							{{ HTML::link('https://github.com/'. $githubUser .'/'. $repo .'/issues?labels='. $label['name'], $label['name'], array('style' => 'color: #fff;', 'target' => '_blank')) }}
						</span>
					@endforeach
				</td>
				<td>
					<small>{{ $issue['comments'] }}</small>
				</td>
				<td>{{ date('Y-m-d h:ia', strtotime($issue['created_at'])) }}</td>
				<td>{{ date('Y-m-d h:ia', strtotime($issue['updated_at'])) }}</td>
				@if ($closed == true)
					<td>{{ date('Y-m-d h:ia', strtotime($issue['closed_at'])) }}</td>
				@endif
			</tr>
		@endforeach
	</tbody>
</table>
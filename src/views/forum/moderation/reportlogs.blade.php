<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Report Logs</div>
			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>Post Title</th>
						<th>Moderator</th>
						<th>Action</th>
						<th>Submitted On</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($reportLogs as $reportLog)
						<tr>
							<td>{{ $reportLog->moderation->resource->name }}</td>
							<td>{{ $reportLog->user->username }}</td>
							<td>{{ $reportLog->action }}</td>
							<td>{{ $reportLog->created_at }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
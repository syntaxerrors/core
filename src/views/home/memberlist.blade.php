<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Memberlist</div>
			<div class="list-glow">
				<div class="list-glow-labels">
					<div class="col-md-4">Username</div>
					<div class="col-md-4" style="width: 33%;">Email</div>
					<div class="col-md-4" style="width: 33%;">Last Active</div>
				</div>
				<ul class="list-glow-group">
					@foreach ($users as $user)
						@if ($user->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes')))
							<li class="online">
						@else
							<li>
						@endif
							<div class="list-glow-group-item list-glow-group-item-sm">
								<div class="col-md-4">{{ HTML::link('user/view/'. $user->id, $user->username) }}</div>
								<div class="col-md-4">{{ $user->emailLink }}</div>
								<div class="col-md-4">{{ $user->lastActiveReadable }}</div>
							</div>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>
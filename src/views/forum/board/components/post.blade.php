<li class="{{ $post->classes }}">
	<div class="list-glow-group-item">
		<div class="col-md-7">
			{{ $post->link }}
			<br />
			{{ $post->startedBy }}
		</div>
		<div class="col-md-2">
			{{ $post->repliesBlock }}
		</div>
		<div class="col-md-3">
			{{ $post->lastPostBlock }}
		</div>
	</div>
</li>
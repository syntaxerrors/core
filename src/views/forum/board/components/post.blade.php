<li class="{{ $post->classes }}">
	<div class="post">
		<div class="subject">
			{{ $post->link }}
			<br />
			{{ $post->startedBy }}
		</div>
		<div class="replies">
			{{ $post->repliesBlock }}
		</div>
		<div class="lastPost">
			{{ $post->lastPostBlock }}
		</div>
		<div class="clearfix"></div>
	</div>
</li>
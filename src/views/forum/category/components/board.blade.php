<li class="{{ $board->classes }}">
	<div class="post">
		<div class="subject">
			{{ $board->link }}
		</div>
		<div class="replies">
			{{ $board->repliesBlock }}
		</div>
		<div class="lastPost">
			{{ $board->lastPostBlock }}
		</div>
		<div class="clearfix"></div>
	</div>
</li>
<small>
	<ul class="breadcrumb">
		<li>{{ HTML::link('forum', 'Forums') }}</li>
		<li>{{ HTML::link('forum/category/view/'. $post->board->category->id, $post->board->category->name) }}</li>
		@if ($post->board->parent != null)
			<li>{{ HTML::link('forum/board/view/'. $post->board->parent->id, $post->board->parent->name) }}</li>
		@endif
		<li>{{ HTML::link('forum/board/view/'. $post->board->id, $post->board->name) }}</li>
		<li class="active">
			{{ $post->name }}
			@if ($details->pageCount > 1 || isset($_GET['page']))
				<?php
					if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				?>
				: Page {{ $page }}
			@endif
		</li>
	</ul>
</small>
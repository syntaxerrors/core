<small>
	<ul class="breadcrumb">
		<li>{{ HTML::link('forum', 'Forums') }}</li>
		<li>{{ HTML::link('forum/category/view/'. $board->category->id, $board->category->name) }}</li>
		<li class="active">
			{{ $board->name }}
			@if ($posts->getTotal() > 30)
				<?php
					if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				?>
				: Page {{ $posts->getCurrentPage() }}
			@endif
		</li>
		<li class="pull-right">
			{{ HTML::link('/forum/post/add/'. $board->id, 'Add Post') }}
		</li>
	</ul>
</small>
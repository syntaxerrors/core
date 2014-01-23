<div class="pull-left">
	<div class="btn-group">
		@if ($post->previousPost != null)
			{{ HTML::link('forum/post/view/'. $post->previousPost->id, $post->previousPost->name, array('class' => 'btn btn-xs btn-primary')) }}
		@endif
	</div>
</div>
<div class="pull-right">
	<div class="btn-group">
		@if ($post->nextPost != null)
			{{ HTML::link('forum/post/view/'. $post->nextPost->id, $post->nextPost->name, array('class' => 'btn btn-xs btn-primary')) }}
		@endif
	</div>
</div>
<div class="clearfix"></div>
<br />
 @if (!isset($main))
	@include('forum.category.components.breadcrumbs')
@endif
	<div class="panel panel-default">
	<div class="panel-heading">
		{{ $category->name }}
		<div class="panel-btn">
			<div class="panel-btn-divider"></div>
			{{ HTML::linkIcon('forum/category/view/'. $category->id, 'fa fa-share-square-o', null, array('style' => 'color: #000;')) }}
			@if ($category->type->keyName == 'technical-support')
				<div class="panel-btn-divider"></div>
				<a href="javascript: void(0);"><i class="fa fa-cogs"></i></a>
			@endif
		</div>
	</div>
	<div class="list-glow">
		<ul class="list-glow-group no-header">
			@if (count($category->boards) > 0)
				@foreach ($category->boards as $board)
					@if ($board->parent_id == null)
						@include('forum.category.components.board', array('board' => $board))
					@endif
				@endforeach
			@endif
		</ul>
	</div>
</div>
@if (!isset($main))
	@include('forum.category.components.breadcrumbs')
@endif
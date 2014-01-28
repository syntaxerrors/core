<div class="row">
	<div class="col-md-12">
		@foreach ($categories as $category)
			<?php $boardsWithChildren = array(); ?>
			<div class="panel panel-default">
				<div class="panel-heading">{{ $category->name }}</div>
				<table class="table table-condensed table-striped table-hover" id="category_{{ $category->id }}">
					<thead>
						<tr>
							<th style="width: 2%;">&nbsp;</th>
							<th style="width: 49%;">Name</th>
							<th style="width: 49%;">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($category->boards as $board)
							@if ($board->parent_id != null)
								<?php continue; ?>
							@endif
							<tr id="{{ $board->id }}">
								<td style="cursor: move;"><i class="fa fa-arrows-v" title="Change order"></i></td>
								<td>
									<a href="javascript: void(0);" class="editable" id="name" data-type="text" data-pk="{{ $board->id }}">
										{{ $board->name }}
									</a>
								</td>
								<td>
									<div class="btn-group">
										{{ HTML::link('forum/board/edit/'. $board->id, 'Edit', array('class' => 'btn btn-xs btn-primary')) }}
										{{ HTML::link('forum/admin/delete-board/'. $board->id, 'Delete', array('class' => 'confirm-remove btn btn-xs btn-danger')) }}
									</div>
								</td>
							</tr>
							@if ($board->children->count() > 0)
								<?php $boardsWithChildren[] = $board; ?>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
			@if (count($boardsWithChildren) > 0)
				@foreach ($boardsWithChildren as $board)
					<div class="panel panel-default" style="margin-left: 20px;">
						<div class="panel-heading">{{ $board->name }} child boards</div>
						<table class="table table-inner table-condensed table-striped table-hover" id="board_{{ $board->id }}">
							<thead>
								<tr>
									<th style="width: 2%;">&nbsp;</th>
									<th style="width: 49%;">Name</th>
									<th style="width: 49%;">Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($board->children as $child)
									<tr id="{{ $child->id }}">
										<td style="cursor: move;"><i class="fa fa-arrows-v" title="Change order"></i></td>
										<td>
											<a href="javascript: void(0);" class="editable" id="name" data-type="text" data-pk="{{ $child->id }}">
												{{ $child->name }}
											</a>
										</td>
										<td>
											<div class="btn-group">
												{{ HTML::link('forum/board/edit/'. $child->id, 'Edit', array('class' => 'btn btn-xs btn-primary')) }}
												{{ HTML::link('forum/admin/delete-board/'. $child->id, 'Delete', array('class' => 'confirm-remove btn btn-xs btn-danger')) }}
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endforeach
			@endif
		@endforeach
	</div>
</div>
@section('css')
	{{ HTML::style('/vendor/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}
@endsection
@section('jsInclude')
	{{ HTML::script('vendor/TableDnD/js/jquery.tablednd.js') }}
	{{ HTML::script('vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js') }}
@stop
<script>
	@section('onReadyJs')
		// X-Editable details
		$.fn.editable.defaults.mode        = 'inline';
		$.fn.editable.defaults.url         = '/forum/admin/board-edit';
		$.fn.editable.defaults.showbuttons = false;
		$.fn.editable.defaults.class       = false;
		$('.editable').editable();

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		}

		var categoryIds = {{ $categories->id->toJson() }};
		var boardIds    = {{ $boards->id->toJson() }};

		$.each(categoryIds, function(key, categoryId) {
			$(function() {
				$('#category_'+ categoryId).tableDnD({
					onDragClass: 'info',
					dragHandle: '.fa-arrows-v',
					onDrop: function(table, row) {
						$.post('/forum/admin/move-boards', $.tableDnD.serialize());
					}
				});
			});
		});

		$.each(boardIds, function(key, boardId) {
			$(function() {
				$('#board_'+ boardId).tableDnD({
					onDragClass: 'info',
					dragHandle: '.fa-arrows-v',
					onDrop: function(table, row) {
						$.post('/forum/admin/move-boards', $.tableDnD.serialize());
					}
				});
			});
		});
	@stop
</script>
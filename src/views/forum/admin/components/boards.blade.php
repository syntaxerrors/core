<?php $newChildren = array(); ?>
<div class="panel panel-default" style="margin-left: {{ $width }}px;">
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
				@if ($child->children->count() > 0)
					<?php $newChildren[] = $child; ?>
				@endif
			@endforeach
		</tbody>
	</table>
</div>
@if (count($newChildren) > 0)
	@foreach ($newChildren as $board)
		@include('forum.admin.components.boards', array('width' => $width + 20))
	@endforeach
@endif
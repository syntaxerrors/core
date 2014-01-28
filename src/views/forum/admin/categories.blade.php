<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Forum Categories</div>
			<table class="table table-condensed table-striped table-hover" id="sortCategories">
				<thead>
					<tr>
						<th style="width: 2%;">&nbsp;</th>
						<th style="width: 49%;">Name</th>
						<th style="width: 49%;">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
						<tr id="{{ $category->id }}">
							<td style="cursor: move;"><i class="fa fa-arrows-v" title="Change order"></i></td>
							<td>
								<a href="javascript: void(0);" class="editable" id="name" data-type="text" data-pk="{{ $category->id }}">
									{{ $category->name }}
								</a>
							</td>
							<td>
								<div class="btn-group">
									{{ HTML::link('forum/category/edit/'. $category->id, 'Edit', array('class' => 'btn btn-xs btn-primary')) }}
									{{ HTML::link('forum/admin/delete-category/'. $category->id, 'Delete', array('class' => 'confirm-remove btn btn-xs btn-danger')) }}
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
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
		$.fn.editable.defaults.url         = '/forum/admin/category-edit';
		$.fn.editable.defaults.showbuttons = false;
		$.fn.editable.defaults.class       = false;
		$('.editable').editable();

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		}

		$('#sortCategories').tableDnD({
			onDragClass: 'info',
			dragHandle: '.fa-arrows-v',
			onDrop: function(table, row) {
				$.post('/forum/admin/move-categories', $.tableDnD.serialize());
			}
		});
	@stop
</script>
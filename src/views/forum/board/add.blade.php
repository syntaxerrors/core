{{ bForm::open() }}
	<div class="row">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('/forum', 'Forums') }}</li>
				@if ($category != null)
					<li>{{ HTML::link('forum/category/view/'. $category->id, $category->name) }}</li>
				@endif
				<li>{{ HTML::link('/forum/admin/dashboard', 'Admin') }}</li>
				<li class="active">Add Board</li>
			</ul>
		</small>
		<div class="col-md-offset-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Add new forum board</div>
					<div class="panel-body">
						{{ bForm::select('forum_category_id', $categories, ($category != null ? array($category->id) : null), array(), 'Category') }}
						{{ bForm::text('name', Input::old('name'), array('placeholder' => 'Name'), 'Name') }}
						{{ bForm::select('forum_board_type_id', $types, array(1), array('onChange' => 'isChild(this)'), 'Type') }}
						{{ bForm::select('parent_id', $boards, array(), array('id' => 'child', 'style' => 'display: none;'), 'Parent') }}
						{{ bForm::textarea('description', Input::old('description'), array('placeholder' => 'Description'), 'Description') }}
						{{ bForm::submit('Add Board') }}
					</div>
				</div>
			</div>
		</div>
	</div>
{{ bForm::close() }}
<script type="text/javascript">
	function isChild(object) {
		if ($(object).val() == 2) {
			$('#child').css('display', 'inline');
		} else {
			$('#child').css('display', 'none');
		}
	}
</script>